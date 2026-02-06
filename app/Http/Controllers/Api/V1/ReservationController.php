<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Reservation;
use App\Models\ReservationSetting;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('DEBUG: ReservationController@index hit');
        $query = Reservation::query()->with(['table', 'branch']);

        if ($request->has('email')) {
            $query->where('guest_email', $request->email);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('limit')) {
            $query->limit($request->limit);
        }

        $reservations = $query->latest('reservation_time')->get()->map(function ($r) {
            $r->reservation_time_iso = $r->reservation_time ? $r->reservation_time->toIso8601String() : null;
            // Overwrite reservation_time for the JSON response to satisfy Flutter's DateTime.parse
            $data = $r->toArray();
            $data['reservation_time'] = $r->reservation_time ? $r->reservation_time->toIso8601String() : null;
            return $data;
        });

        \Illuminate\Support\Facades\Log::info('DEBUG: ReservationController@index returning ' . $reservations->count() . ' reservations');
        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }

    public function availability(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'party_size' => 'required|integer|min:1',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $date = $validated['date'];
        $partySize = (int) $validated['party_size'];
        $settings = ReservationSetting::getInstance();
        $carbonDate = Carbon::parse($date);

        // Check business hours
        $dayOfWeek = strtolower($carbonDate->format('l'));
        $hours = $settings->business_hours[$dayOfWeek] ?? null;

        if (!$hours || ($hours['closed'] ?? false)) {
            return response()->json(['data' => [], 'message' => 'Closed on this day'], 200);
        }

        $startTime = Carbon::parse($date . ' ' . $hours['open']);
        $endTime = Carbon::parse($date . ' ' . $hours['close']);
        $interval = 30; // 30 minute slots
        $slots = [];

        $tables = Table::where('capacity', '>=', $partySize)
            ->where('status', 'available')
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->get();

        if ($tables->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No tables with this capacity'], 200);
        }

        $currentTime = $startTime->copy();
        while ($currentTime->lessThan($endTime)) {
            // Check if slot is in the past
            if ($currentTime->isPast()) {
                $currentTime->addMinutes($interval);

                continue;
            }

            $slotEnd = $currentTime->copy()->addMinutes($settings->default_duration_minutes ?? 120);

            // Check if at least one table is available for this slot
            $isAvailable = false;
            foreach ($tables as $table) {
                $overlapping = Reservation::where('table_id', $table->id)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) use ($currentTime, $slotEnd) {
                        $query->whereBetween('reservation_time', [$currentTime, $slotEnd->subSecond()])
                            ->orWhere(function ($q) use ($currentTime) {
                                $q->where('reservation_time', '<=', $currentTime)
                                    ->whereRaw('DATE_ADD(reservation_time, INTERVAL duration_minutes MINUTE) > ?', [$currentTime->toDateTimeString()]);
                            });
                    })
                    ->exists();

                if (!$overlapping) {
                    $isAvailable = true;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = $currentTime->format('H:i');
            }

            $currentTime->addMinutes($interval);
        }

        return response()->json(['data' => $slots]);
    }

    public function store(Request $request)
    {
        $settings = ReservationSetting::getInstance();
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'reservation_time' => 'required|date',
            'party_size' => 'required|integer|min:1',
            'guest_name' => 'required|string',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string',
            'special_requests' => 'nullable|string',
        ]);

        $resTime = Carbon::parse($validated['reservation_time']);
        $partySize = $validated['party_size'];

        // Find an available table
        $table = Table::where('branch_id', $validated['branch_id'])
            ->where('capacity', '>=', $partySize)
            ->where('status', 'available')
            ->whereDoesntHave('reservations', function ($query) use ($resTime, $settings) {
                $endTime = $resTime->copy()->addMinutes($settings->default_duration_minutes ?? 120);
                $query->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($resTime, $endTime) {
                        $q->whereBetween('reservation_time', [$resTime, $endTime->subSecond()])
                            ->orWhere(function ($sq) use ($resTime) {
                                $sq->where('reservation_time', '<=', $resTime)
                                    ->whereRaw('DATE_ADD(reservation_time, INTERVAL duration_minutes MINUTE) > ?', [$resTime->toDateTimeString()]);
                            });
                    });
            })
            ->first();

        if (!$table) {
            return response()->json(['message' => 'No tables available for this time'], 422);
        }

        $reservation = Reservation::create([
            'branch_id' => $validated['branch_id'],
            'table_id' => $table->id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'party_size' => $partySize,
            'reservation_time' => $resTime,
            'duration_minutes' => $settings->default_duration_minutes ?? 120,
            'status' => $settings->auto_confirm_enabled ? 'confirmed' : 'pending',
            'confirmation_code' => Reservation::generateConfirmationCode(),
            'special_requests' => $validated['special_requests'] ?? null,
            'source' => $request->input('source', 'mobile_app'),
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => $reservation,
        ], 201);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,confirmed,arrived,completed,cancelled',
            'table_id' => 'nullable|exists:tables,id',
            'party_size' => 'nullable|integer|min:1',
            'reservation_time' => 'nullable|date',
            'special_requests' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => $reservation->load(['table', 'branch']),
        ]);
    }

    public function show(Reservation $reservation)
    {
        return response()->json(['data' => $reservation->load(['table', 'branch'])]);
    }

    public function branches()
    {
        return response()->json([
            'data' => Branch::where('is_active', true)->get(),
        ]);
    }
}

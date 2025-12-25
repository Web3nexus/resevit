<?php

namespace App\Services;

use App\Models\Table;
use App\Models\Reservation;
use App\Models\ReservationSetting;
use Illuminate\Support\Facades\Http;

class TableOptimizationService
{
    public function optimize(string $date)
    {
        $settings = ReservationSetting::getInstance();
        $apiKey = $settings->openai_api_key;

        if (!$apiKey) {
            throw new \Exception('OpenAI API key is not configured in System Settings.');
        }

        $tables = Table::where('status', 'active')->get(['id', 'name', 'capacity']);
        $reservations = Reservation::whereDate('reservation_time', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get(['id', 'party_size', 'reservation_time', 'duration_minutes']);

        if ($reservations->isEmpty()) {
            return ['message' => 'No reservations found for the selected date.'];
        }

        $prompt = $this->buildPrompt($tables, $reservations, $date);

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert restaurant manager. Optimize table assignments to maximize floor utilization and minimize group splitting. Return ONLY a JSON object mapping reservation_id to table_id.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            throw new \Exception('AI Optimization failed: ' . $response->body());
        }

        return json_decode($response->json('choices.0.message.content'), true);
    }

    protected function buildPrompt($tables, $reservations, $date): string
    {
        $tablesList = $tables->map(fn($t) => "Table {$t->id}: Capacity {$t->capacity}")->implode("\n");
        $reservationsList = $reservations->map(fn($r) => "Res {$r->id}: Size {$r->party_size} at {$r->reservation_time} for {$r->duration_minutes} mins")->implode("\n");

        return "Date: {$date}\n\nTables:\n{$tablesList}\n\nReservations:\n{$reservationsList}\n\nProvide the optimal mapping.";
    }
}

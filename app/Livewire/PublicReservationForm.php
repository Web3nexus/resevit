<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\ReservationSetting;
use Livewire\Component;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class PublicReservationForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $date;
    public $time;
    public $party_size = 2;
    public $special_requests;

    public $success = false;

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required',
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required',
        'party_size' => 'required|integer|min:1|max:20',
        'special_requests' => 'nullable|max:500',
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function submit()
    {
        $this->validate();

        $settings = ReservationSetting::getInstance();

        // Check if within business hours
        $dateTime = Carbon::parse($this->date . ' ' . $this->time);

        if (!$settings->isWithinBusinessHours($dateTime)) {
            $this->addError('time', 'Selected time is outside our business hours.');
            return;
        }

        Reservation::create([
            'customer_name' => $this->name,
            'customer_email' => $this->email,
            'customer_phone' => $this->phone,
            'reservation_datetime' => $dateTime,
            'party_size' => $this->party_size,
            'special_requests' => $this->special_requests,
            'status' => $settings->auto_confirm_enabled ? 'confirmed' : 'pending',
            'source' => 'website',
        ]);

        $this->success = true;

        Notification::make()
            ->success()
            ->title('Reservation Received!')
            ->body('Thank you for choosing us. We will confirm your booking shortly.')
            ->send();

        $this->reset(['name', 'email', 'phone', 'date', 'time', 'party_size', 'special_requests']);
    }

    public function render()
    {
        return view('livewire.public-reservation-form');
    }
}

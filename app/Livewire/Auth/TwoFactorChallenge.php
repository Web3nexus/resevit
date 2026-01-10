<?php

namespace App\Livewire\Auth;

use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code = '';
    public string $error = '';

    public function mount(TwoFactorService $service)
    {
        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        $userId = session('2fa:user:id');
        $userType = session('2fa:user:type');
        $user = $userType::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Send the code automatically on mount
        $service->generateAndSendCode($user);
    }

    public function verify(TwoFactorService $service)
    {
        $this->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa:user:id');
        $userType = session('2fa:user:type');
        $user = $userType::find($userId);

        if (!$user || !$service->verifyCode($user, $this->code)) {
            $this->error = 'Invalid or expired verification code.';
            return;
        }

        // Log the user in
        Auth::login($user, session('2fa:remember', false));

        session()->forget(['2fa:user:id', '2fa:user:type', '2fa:remember']);
        session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function resend(TwoFactorService $service)
    {
        $userId = session('2fa:user:id');
        $userType = session('2fa:user:type');
        $user = $userType::find($userId);

        if ($user) {
            $service->generateAndSendCode($user);
            session()->flash('message', 'A new verification code has been sent to your email.');
        }
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')
            ->layout('layouts.landing'); // Using landing layout which should have the auth-split-layout logic if I check
    }
}

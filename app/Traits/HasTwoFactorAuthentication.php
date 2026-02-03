<?php

namespace App\Traits;

use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Support\Str;

trait HasTwoFactorAuthentication
{
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    public function enableTwoFactorAuthentication(): void
    {
        $google2fa = new Google2FA();
        $this->forceFill([
            'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(
                \Illuminate\Support\Collection::times(8, function () {
                    return Str::random(10) . '-' . Str::random(10);
                })->all()
            )),
        ])->save();
    }

    public function disableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function confirmTwoFactorAuthentication($code): bool
    {
        if (!$this->two_factor_secret) {
            return false;
        }

        $google2fa = new Google2FA();
        $secret = decrypt($this->two_factor_secret);

        if ($google2fa->verifyKey($secret, $code)) {
            $this->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();
            return true;
        }

        return false;
    }

    public function verifyTwoFactorAuthentication($code): bool
    {
        if (!$this->two_factor_secret || !$this->two_factor_confirmed_at) {
            return false;
        }

        $google2fa = new Google2FA();
        $secret = decrypt($this->two_factor_secret);

        return $google2fa->verifyKey($secret, $code);
    }

    public function getTwoFactorQrCodeUrl(): string
    {
        if (!$this->two_factor_secret) {
            return '';
        }

        $google2fa = new Google2FA();
        $appName = config('app.name');
        $secret = decrypt($this->two_factor_secret);

        return $google2fa->getQRCodeUrl(
            $appName,
            $this->email,
            $secret
        );
    }

    public function getTwoFactorRecoveryCodes(): array
    {
        if (!$this->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    public function regenerateTwoFactorRecoveryCodes(): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                \Illuminate\Support\Collection::times(8, function () {
                    return Str::random(10) . '-' . Str::random(10);
                })->all()
            )),
        ])->save();
    }
}

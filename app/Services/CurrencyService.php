<?php

namespace App\Services;

class CurrencyService
{
    protected array $currencies = [
        'USD' => ['symbol' => '$', 'name' => 'US Dollar', 'rate' => 1.0],
        'EUR' => ['symbol' => '€', 'name' => 'Euro', 'rate' => 0.92],
        'GBP' => ['symbol' => '£', 'name' => 'British Pound', 'rate' => 0.79],
        'NGN' => ['symbol' => '₦', 'name' => 'Nigerian Naira', 'rate' => 1500.0],
        'KES' => ['symbol' => 'KSh', 'name' => 'Kenyan Shilling', 'rate' => 130.0],
        'ZAR' => ['symbol' => 'R', 'name' => 'South African Rand', 'rate' => 18.5],
        'AED' => ['symbol' => 'د.إ', 'name' => 'UAE Dirham', 'rate' => 3.67],
        // Add more as needed or integrate with an API
    ];

    public function format(float $amount, ?string $currency = null): string
    {
        $currency ??= $this->getUserCurrency();
        $symbol = $this->currencies[$currency]['symbol'] ?? '$';

        return $symbol . number_format($amount, 2);
    }

    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to)
            return $amount;

        $baseAmount = $amount / ($this->currencies[$from]['rate'] ?? 1.0);
        return $baseAmount * ($this->currencies[$to]['rate'] ?? 1.0);
    }

    public function getUserCurrency(): string
    {
        if (auth()->check() && auth()->user()->currency) {
            return auth()->user()->currency;
        }

        if (tenant() && tenant()->currency) {
            return tenant()->currency;
        }

        return config('app.currency', 'USD');
    }

    public function getSupportedCurrencies(): array
    {
        return collect($this->currencies)->mapWithKeys(fn($data, $code) => [$code => "{$code} ({$data['symbol']})"])->toArray();
    }
}

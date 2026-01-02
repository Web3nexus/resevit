<?php

namespace App\Filament\Influencer\Resources\WithdrawalRequests\Schemas;

use Filament\Schemas\Schema;

class WithdrawalRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Placeholder::make('current_balance')
                    ->label('Current Balance')
                    ->content(function () {
                        $influencer = auth('influencer')->user();
                        $paidEarnings = $influencer->referralEarnings()->where('status', 'paid')->sum('amount');
                        $requestedWithdrawals = $influencer->withdrawalRequests()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
                        return '$' . number_format($paidEarnings - $requestedWithdrawals, 2);
                    }),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->rules([
                        fn() => function (string $attribute, $value, \Closure $fail) {
                            $influencer = auth('influencer')->user();
                            $minAmount = \App\Models\PlatformSetting::current()->promotion_settings['min_withdrawal_amount'] ?? 50;
                            if ($value < $minAmount) {
                                $fail("The minimum withdrawal amount is \${$minAmount}.");
                            }

                            $paidEarnings = $influencer->referralEarnings()->where('status', 'paid')->sum('amount');
                            $requestedWithdrawals = $influencer->withdrawalRequests()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
                            $balance = $paidEarnings - $requestedWithdrawals;

                            if ($value > $balance) {
                                $fail("You cannot withdraw more than your current balance (\${$balance}).");
                            }
                        },
                    ]),
                \Filament\Forms\Components\Placeholder::make('bank_info')
                    ->label('Bank Account')
                    ->content(function () {
                        $influencer = auth('influencer')->user();
                        if (!$influencer->bank_name) {
                            return new \Illuminate\Support\HtmlString('<span class="text-danger-600">Please update your bank details in your profile first.</span>');
                        }
                        return "{$influencer->bank_name} - {$influencer->account_number}";
                    }),
            ]);
    }
}

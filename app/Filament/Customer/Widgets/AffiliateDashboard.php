<?php

namespace App\Filament\Customer\Widgets;

use Filament\Widgets\Widget;

class AffiliateDashboard extends Widget
{
    protected string $view = 'filament.influencer.widgets.affiliate-dashboard';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return [];
        }

        // Payout Data
        $paidEarnings = $customer->referralEarnings()->where('status', 'paid')->sum('amount');
        $requestedWithdrawals = $customer->withdrawalRequests()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
        $availableBalance = $paidEarnings - $requestedWithdrawals;

        $totalReferrals = $customer->referrals()->count();
        $totalPaidOut = $paidEarnings;
        $hasBankSetup = !empty($customer->bank_name);

        $milestones = [
            ['amount' => 10, 'label' => 'Bronze'],
            ['amount' => 50, 'label' => 'Silver'],
            ['amount' => 100, 'label' => 'Platinum'],
        ];

        $maxMilestone = end($milestones)['amount'];
        $progressPercentage = min(100, ($availableBalance / ($maxMilestone ?: 1)) * 100);

        // Recent Referrals Data
        $recentReferrals = $customer->referrals()
            ->latest()
            ->limit(5)
            ->get();

        return [
            'referral_url' => request()->getSchemeAndHttpHost() . '/?ref=' . $customer->referral_code,
            'availableBalance' => $availableBalance,
            'totalPaidOut' => $totalPaidOut,
            'totalReferrals' => $totalReferrals,
            'hasBankSetup' => $hasBankSetup,
            'milestones' => $milestones,
            'progressPercentage' => $progressPercentage,
            'bankUrl' => \App\Filament\Customer\Pages\BankDetails::getUrl(),
            'recentReferrals' => $recentReferrals,
        ];
    }
}

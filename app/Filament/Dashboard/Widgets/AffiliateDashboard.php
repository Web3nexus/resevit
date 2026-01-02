<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\Widget;

class AffiliateDashboard extends Widget
{
    protected string $view = 'filament.influencer.widgets.affiliate-dashboard';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = auth()->user();

        if (!$user) {
            return [];
        }

        // Payout Data
        $paidEarnings = $user->referralEarnings()->where('status', 'paid')->sum('amount');
        $requestedWithdrawals = $user->withdrawalRequests()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
        $availableBalance = $paidEarnings - $requestedWithdrawals;

        $totalReferrals = $user->referrals()->count();
        $totalPaidOut = $paidEarnings;
        $hasBankSetup = !empty($user->bank_name);

        $milestones = [
            ['amount' => 50, 'label' => 'Standard'],
            ['amount' => 200, 'label' => 'Pro'],
            ['amount' => 500, 'label' => 'Gold'],
        ];

        $maxMilestone = end($milestones)['amount'];
        $progressPercentage = min(100, ($availableBalance / ($maxMilestone ?: 1)) * 100);

        // Recent Referrals Data
        $recentReferrals = $user->referrals()
            ->latest()
            ->limit(5)
            ->get();

        return [
            'referral_url' => request()->getSchemeAndHttpHost() . '/?ref=' . $user->referral_code,
            'availableBalance' => $availableBalance,
            'totalPaidOut' => $totalPaidOut,
            'totalReferrals' => $totalReferrals,
            'hasBankSetup' => $hasBankSetup,
            'milestones' => $milestones,
            'progressPercentage' => $progressPercentage,
            'bankUrl' => \App\Filament\Dashboard\Pages\BankDetails::getUrl(),
            'recentReferrals' => $recentReferrals,
        ];
    }
}

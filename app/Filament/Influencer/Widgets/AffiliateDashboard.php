<?php

namespace App\Filament\Influencer\Widgets;

use Filament\Widgets\Widget;

class AffiliateDashboard extends Widget
{
    protected string $view = 'filament.influencer.widgets.affiliate-dashboard';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $influencer = auth('influencer')->user();

        if (!$influencer) {
            return [];
        }

        // Payout Data
        $paidEarnings = $influencer->referralEarnings()->where('status', 'paid')->sum('amount');
        $requestedWithdrawals = $influencer->withdrawalRequests()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
        $availableBalance = $paidEarnings - $requestedWithdrawals;

        $totalReferrals = $influencer->referrals()->count();
        $totalPaidOut = $paidEarnings;
        $hasBankSetup = !empty($influencer->bank_name);

        $milestones = [
            ['amount' => 30, 'label' => 'Min. Withdrawal'],
            ['amount' => 100, 'label' => 'Standard'],
            ['amount' => 200, 'label' => 'Wire Transfer'],
        ];

        $maxMilestone = end($milestones)['amount'];
        $progressPercentage = min(100, ($availableBalance / $maxMilestone) * 100);

        // Recent Referrals Data
        $recentReferrals = $influencer->referrals()
            ->with(['referee'])
            ->latest()
            ->limit(5)
            ->get();

        return [
            'referral_url' => request()->getSchemeAndHttpHost() . '/?ref=' . $influencer->referral_code,
            'availableBalance' => $availableBalance,
            'totalPaidOut' => $totalPaidOut,
            'totalReferrals' => $totalReferrals,
            'hasBankSetup' => $hasBankSetup,
            'milestones' => $milestones,
            'progressPercentage' => $progressPercentage,
            'bankUrl' => \App\Filament\Influencer\Pages\BankDetails::getUrl(),
            'recentReferrals' => $recentReferrals,
        ];
    }
}

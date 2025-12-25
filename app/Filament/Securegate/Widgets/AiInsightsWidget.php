<?php

namespace App\Filament\Securegate\Widgets;

use Filament\Widgets\Widget;

class AiInsightsWidget extends Widget
{
    protected string $view = 'filament.securegate.widgets.ai-insights-widget';

    protected static ?int $sort = 4; // Same sort as InvestorAnalyticsChart to sit beside it

    protected int|string|array $columnSpan = 1;

    public function getViewData(): array
    {
        // Gather key metrics to feed to AI
        $tenantGrowth = \App\Models\Tenant::where('created_at', '>=', now()->subDays(30))->count();
        $totalInvestors = \App\Models\Investor::count();
        $activeTenants = \App\Models\Tenant::count();

        // Prepare prompt for AI
        $prompt = "Analyze these metrics for a SaaS platform: 
        - New Tenants (Last 30 days): {$tenantGrowth}
        - Total Active Tenants: {$activeTenants}
        - Total Investors: {$totalInvestors}
        
        Generate 2 short, strategic, 1-sentence insights. 
        1. growth_opportunity: Suggest a marketing action based on tenant growth.
        2. investor_engagement: Suggest an action for investors.
        
        Format as JSON: { \"growth_opportunity\": \"...\", \"investor_engagement\": \"...\" }";

        $insights = [];

        try {
            // Use the service
            $aiService = app(\App\Services\AI\ContentGeneratorService::class);
            $aiResponse = $aiService->generate($prompt, 'social'); // Using 'social' type for concise style

            // Basic JSON parsing attempt (fallback if AI returns text)
            $jsonStart = strpos($aiResponse, '{');
            $jsonEnd = strrpos($aiResponse, '}');

            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
                $aiData = json_decode($jsonStr, true);
            } else {
                // Fallback if not valid JSON
                $aiData = [
                    'growth_opportunity' => $aiResponse, // Just use the raw text
                    'investor_engagement' => "Review investor portfolios for optimization."
                ];
            }

            if (!empty($aiData['growth_opportunity'])) {
                $insights[] = [
                    'title' => 'AI Growth Opportunity',
                    'message' => $aiData['growth_opportunity'],
                    'type' => 'primary',
                    'icon' => 'heroicon-m-sparkles',
                ];
            }

            if (!empty($aiData['investor_engagement'])) {
                $insights[] = [
                    'title' => 'AI Investor Insight',
                    'message' => $aiData['investor_engagement'],
                    'type' => 'success',
                    'icon' => 'heroicon-m-arrow-trending-up',
                ];
            }

        } catch (\Exception $e) {
            // Fallback to purely simulated logic if API fails or service error
            if ($tenantGrowth > 0) {
                $insights[] = [
                    'title' => 'Growth Opportunity Detected',
                    'message' => "Tenant signups have increased by {$tenantGrowth} in the last 30 days. Consider launching a targeted email campaign to convert trial users in the \"Marketing Tools\" sector.",
                    'type' => 'primary',
                    'icon' => 'heroicon-m-sparkles',
                ];
            } else {
                $insights[] = [
                    'title' => 'Growth Stagnation',
                    'message' => "New tenant signups are flat. Review your landing page conversion rates.",
                    'type' => 'warning',
                    'icon' => 'heroicon-m-exclamation-triangle',
                ];
            }

            if ($totalInvestors > 5) {
                $insights[] = [
                    'title' => 'High Investor Engagement',
                    'message' => "You have {$totalInvestors} active investors. Ensure your upcoming investment opportunities are optimized for mobile display as mobile usage is trending up.",
                    'type' => 'success',
                    'icon' => 'heroicon-m-arrow-trending-up',
                ];
            }
        }

        return [
            'insights' => $insights,
        ];
    }
}

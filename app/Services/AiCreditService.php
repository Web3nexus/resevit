<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\AiCreditTransaction;
use Illuminate\Support\Facades\DB;

class AiCreditService
{
    /**
     * Standard unit: 1 credit = $0.001 (configurable)
     */
    protected const CREDIT_USD_VALUE = 0.001;

    /**
     * Markup factor to ensure profit (example: 5x the raw cost)
     */
    protected const PROFIT_MARKUP = 5.0;

    /**
     * Costs per 1 million tokens in USD
     */
    protected const MODEL_COSTS = [
        'claude-3-haiku' => ['input' => 0.25, 'output' => 1.25],
        'claude-3-sonnet' => ['input' => 3.00, 'output' => 15.00],
        'claude-3-opus' => ['input' => 15.00, 'output' => 75.00],
        'gpt-4o-mini' => ['input' => 0.15, 'output' => 0.60],
        'gpt-4o' => ['input' => 5.00, 'output' => 15.00],
    ];

    /**
     * Deduct credits from a tenant based on usage
     */
    public function recordUsage(Tenant $tenant, string $model, int $inputTokens, int $outputTokens, ?string $description = null): float
    {
        $costs = self::MODEL_COSTS[$model] ?? self::MODEL_COSTS['gpt-4o-mini'];

        $rawCostInput = ($inputTokens / 1000000) * $costs['input'];
        $rawCostOutput = ($outputTokens / 1000000) * $costs['output'];
        $totalRawCost = $rawCostInput + $rawCostOutput;

        // Calculate credits to deduct: (Cost * Markup) / Credit Value
        $creditsToDeduct = ($totalRawCost * self::PROFIT_MARKUP) / self::CREDIT_USD_VALUE;

        // Ensure we don't have partial credits if not needed, but decimal:6 supports it
        $creditsToDeduct = round($creditsToDeduct, 6);

        DB::connection('landlord')->transaction(function () use ($tenant, $creditsToDeduct, $model, $inputTokens, $outputTokens, $totalRawCost, $description) {
            $tenant->decrement('ai_credits', $creditsToDeduct);

            AiCreditTransaction::create([
                'tenant_id' => $tenant->id,
                'amount' => -$creditsToDeduct,
                'type' => 'debit',
                'description' => $description ?? "AI Usage: {$model}",
                'provider' => str_contains($model, 'claude') ? 'anthropic' : 'openai',
                'model' => $model,
                'tokens_input' => $inputTokens,
                'tokens_output' => $outputTokens,
                'actual_cost' => $totalRawCost,
            ]);
        });

        return $creditsToDeduct;
    }

    /**
     * Add credits to a tenant (e.g. from subscription or purchase)
     */
    public function addCredits(Tenant $tenant, float $amount, string $description): void
    {
        DB::connection('landlord')->transaction(function () use ($tenant, $amount, $description) {
            $tenant->increment('ai_credits', $amount);

            AiCreditTransaction::create([
                'tenant_id' => $tenant->id,
                'amount' => $amount,
                'type' => 'credit',
                'description' => $description,
            ]);
        });
    }

    /**
     * Check if tenant has enough credits
     */
    public function hasCredits(Tenant $tenant, float $minimum = 1.0): bool
    {
        return $tenant->ai_credits >= $minimum;
    }
}

<?php

namespace App\Repositories;

/**
 * Interface PricingRuleRepositoryInterface
 * Defines the contract for pricing rule repository operations.
 */
interface PricingRuleRepositoryInterface
{
    /**
     * Get pricing rules for a specific item.
     *
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRulesForItem(int $itemId);
}

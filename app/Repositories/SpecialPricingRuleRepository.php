<?php

namespace App\Repositories;

use App\Models\ItemPricingRule;

/**
 * Class SpecialPricingRuleRepository
 * Implements PricingRuleRepositoryInterface for handling pricing rules.
 */
class SpecialPricingRuleRepository implements PricingRuleRepositoryInterface
{
    /**
     * Get pricing rules for a specific item.
     *
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRulesForItem(int $itemId)
    {
        return ItemPricingRule::where('item_id', $itemId)->get();
    }
}

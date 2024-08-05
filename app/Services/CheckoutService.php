<?php

namespace App\Services;

use App\Repositories\ItemRepositoryInterface;
use App\Repositories\PricingRuleRepositoryInterface;
use Exception;

/**
 * Class CheckoutService
 * Handles the checkout process including scanning items and applying pricing rules.
 */
class CheckoutService implements CheckoutServiceInterface
{
    private $itemRepository;
    private $pricingRuleRepository;
    private $scannedItems = [];
    private $subtotals = [];
    private $appliedRules = [];

    /**
     * CheckoutService constructor.
     *
     * @param ItemRepositoryInterface $itemRepository
     * @param PricingRuleRepositoryInterface $pricingRuleRepository
     */
    public function __construct(
        ItemRepositoryInterface $itemRepository,
        PricingRuleRepositoryInterface $pricingRuleRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->pricingRuleRepository = $pricingRuleRepository;
    }

    /**
     * Scan an item and update the totals.
     *
     * @param string $sku
     * @throws Exception
     */
    public function scan(string $sku)
    {
        try {
            $item = $this->itemRepository->findBySku($sku);

            if (!$item) {
                throw new Exception("Item not found: $sku");
            }

            $this->updateScannedItems($sku, $item);

            $this->calculateSubtotal($item);

        } catch (Exception $e) {
            throw new Exception('Error scanning item: ' . $e->getMessage());
        }
    }

    /**
     * Get the total price.
     *
     * @return int
     */
    public function total()
    {
        return array_sum($this->subtotals);
    }

    /**
     * Get the scanned items.
     *
     * @return array
     */
    public function getScannedItems()
    {
        return $this->scannedItems;
    }

    /**
     * Get the subtotals for each item.
     *
     * @return array
     */
    public function getSubtotals()
    {
        return $this->subtotals;
    }

    /**
     * Get the rules applied to each item.
     *
     * @return array
     */
    public function getAppliedRules()
    {
        return $this->appliedRules;
    }

    /**
     * Update the count of scanned items.
     *
     * @param string $sku
     * @param \App\Models\Item $item
     * @return void
     */
    private function updateScannedItems(string $sku, $item)
    {
        if (!isset($this->scannedItems[$sku])) {
            $this->scannedItems[$sku] = 0;
        }
        $this->scannedItems[$sku]++;
    }

    /**
     * Calculate the subtotal for an item and apply any pricing rules.
     *
     * @param \App\Models\Item $item
     * @return void
     */
    private function calculateSubtotal($item)
    {
        $quantity = $this->scannedItems[$item->sku];

        $pricePerUnit = $item->unit_price;
        $rules = $this->pricingRuleRepository->getRulesForItem($item->id);

        $subtotal = $quantity * $pricePerUnit;

        $appliedRules = [];

        foreach ($rules as $rule) {
            if ($quantity >= $rule->quantity) {
                $ruleQty = floor($quantity / $rule->quantity);
                $remainingQty = $quantity % $rule->quantity;
                $subtotal = ($ruleQty * $rule->price) + ($remainingQty * $pricePerUnit);
                $appliedRules[] = [
                    'quantity' => $rule->quantity,
                    'price' => $rule->price
                ];
                break; // Break after applying the applicable rule
            }
        }

        $this->subtotals[$item->sku] = $subtotal;
        if (!empty($appliedRules)) {
            $this->appliedRules[$item->sku] = $appliedRules;
        }
    }
}

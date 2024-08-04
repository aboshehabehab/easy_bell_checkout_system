<?php

namespace App\Services;

/**
 * Interface CheckoutServiceInterface
 * Defines the contract for checkout service operations.
 */
interface CheckoutServiceInterface
{
    /**
     * Scan an item and update the totals.
     *
     * @param string $sku
     * @return void
     */
    public function scan(string $sku);

    /**
     * Get the total price.
     *
     * @return int
     */
    public function total();

    /**
     * Get the scanned items.
     *
     * @return array
     */
    public function getScannedItems();

    /**
     * Get the subtotals for each item.
     *
     * @return array
     */
    public function getSubtotals();

    /**
     * Get the rules applied to each item.
     *
     * @return array
     */
    public function getAppliedRules();
}

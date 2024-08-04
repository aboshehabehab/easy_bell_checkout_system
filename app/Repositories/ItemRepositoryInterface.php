<?php

namespace App\Repositories;

/**
 * Interface ItemRepositoryInterface
 * Defines the contract for item repository operations.
 */
interface ItemRepositoryInterface
{
    /**
     * Find an item by its SKU.
     *
     * @param string $sku
     * @return \App\Models\Item|null
     */
    public function findBySku(string $sku);

    /**
     * Get all items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();
}

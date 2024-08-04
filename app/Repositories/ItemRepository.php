<?php

namespace App\Repositories;

use App\Models\Item;

/**
 * Class ItemRepository
 * Implements ItemRepositoryInterface for handling item data.
 */
class ItemRepository implements ItemRepositoryInterface
{
    /**
     * Find an item by its SKU.
     *
     * @param string $sku
     * @return \App\Models\Item|null
     */
    public function findBySku(string $sku)
    {
        return Item::where('sku', $sku)->first();
    }

    /**
     * Get all items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Item::all();
    }
}

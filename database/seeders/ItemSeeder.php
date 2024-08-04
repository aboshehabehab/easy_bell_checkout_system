<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemPricingRule;

class ItemSeeder extends Seeder
{
    public function run()
    {

        // Create Items with unit prices
        $items = [
            ['sku' => 'A', 'unit_price' => 50],
            ['sku' => 'B', 'unit_price' => 30],
            ['sku' => 'C', 'unit_price' => 20],
            ['sku' => 'D', 'unit_price' => 15],
        ];

        foreach ($items as $itemData) {
            $item = Item::create($itemData);

            // Create Pricing Rules for each item
            switch ($item->sku) {
                case 'A':
                    ItemPricingRule::create([
                        'item_id' => $item->id,
                        'quantity' => 3,
                        'price' => 130, // 3 for $1.30
                    ]);
                    break;
                case 'B':
                    ItemPricingRule::create([
                        'item_id' => $item->id,
                        'quantity' => 2,
                        'price' => 45, // 2 for $0.45
                    ]);
                    break;
                case 'C':
                    // No special pricing rules for C
                    break;
                case 'D':
                    // No special pricing rules for D
                    break;
            }
        }
    }
}

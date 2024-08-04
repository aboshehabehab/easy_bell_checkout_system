<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $itemRepo = new App\Repositories\ItemRepository();
    // Define items and their pricing
    $items = [
        ['name' => 'A', 'unit_price' => 50, 'special_qty' => 3, 'special_price' => 130],
        ['name' => 'B', 'unit_price' => 30, 'special_qty' => 2, 'special_price' => 45],
        ['name' => 'C', 'unit_price' => 20,],
        ['name' => 'D', 'unit_price' => 15,]
    ];

    foreach ($items as $item) {
        // Create or update items
        $createdItem = $this->itemRepo->create([
            'name' => $item['name']
        ]);
        dd($createdItem);
        // // Create unit pricing rule
        // $this->unitRepo->createUnitPriceRule($createdItem->id, $item['unit_price']);

        // // Create special (bulk) pricing rule if applicable
        // if ($item['bulk_qty']) {
        //     $this->specialRepo->createBulkPriceRule($createdItem->id, $item['special_qty'], $item['special_price']);
        // }
    }
});

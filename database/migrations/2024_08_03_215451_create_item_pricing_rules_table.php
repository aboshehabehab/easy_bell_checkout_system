<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the item_pricing_rules table.
 */
class CreateItemPricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade')->comment('Foreign key referencing items');
            $table->integer('quantity')->comment('Special quantity for the pricing rule');
            $table->integer('price')->comment('Price for the special quantity in cents');
            $table->timestamps();
        });

        // Add an index for quick lookup by item_id
        Schema::table('item_pricing_rules', function (Blueprint $table) {
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_pricing_rules');
    }
}

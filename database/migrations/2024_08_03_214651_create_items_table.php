<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the items table.
 */
class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->integer('unit_price')->comment('Price per single item in cents');
            $table->timestamps();
        });

        // Add an index for quick lookup by SKU
        Schema::table('items', function (Blueprint $table) {
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}

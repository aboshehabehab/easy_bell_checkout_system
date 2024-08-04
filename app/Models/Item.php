<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * Represents an item in the inventory.
 */
class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'unit_price'];

    /**
     * Get the pricing rules associated with the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricingRules()
    {
        return $this->hasMany(ItemPricingRule::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemPricingRule
 * Represents a pricing rule for items.
 */
class ItemPricingRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'quantity', 'price'];

    /**
     * Get the item associated with the pricing rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

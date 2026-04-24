<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'quantity_in_stock',
        'low_stock_alert',
    ];

    protected $casts = [
        'quantity_in_stock' => 'integer',
        'low_stock_alert' => 'integer',
    ];

    /**
     * Get the product this inventory belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if this product is low on stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity_in_stock > 0
            && $this->quantity_in_stock <= $this->low_stock_alert;
    }

    /**
     * Check if this product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity_in_stock <= 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

   protected $fillable = [
        'code',
        'type',
        'value',
        'scope',
        'category_id',
        'product_ids',
        'min_cart_value',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_cart_value' => 'decimal:2',
        'product_ids' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function eligibleFor($product): bool
    {
        if ($this->scope === 'all') {
            return true;
        }

        if ($this->scope === 'category') {
            return (int) $this->category_id === (int) $product->category_id;
        }

        return in_array((int) $product->id, array_map('intval', $this->product_ids ?: []), true);
    }
}

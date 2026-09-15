<?php

namespace App\Services;

use App\Models\Coupon;
use Exception;

class CouponService
{
    public function validate(string $code, float $cartTotal): Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new Exception('Invalid coupon code.');
        }

        if (!$coupon->is_active) {
            throw new Exception('This coupon is no longer active.');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            throw new Exception('This coupon has expired.');
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw new Exception('This coupon has reached its usage limit.');
        }

        if ($coupon->min_cart_value !== null && $cartTotal < $coupon->min_cart_value) {
            throw new Exception("Cart total must be at least {$coupon->min_cart_value} to use this coupon.");
        }

        return $coupon;
    }

    public function applyDiscount(Coupon $coupon, float $cartTotal): float
    {
        if ($coupon->type === 'percentage') {
            $discount = $cartTotal * ($coupon->value / 100);
        } else {
            $discount = $coupon->value;
        }

        // never let discount exceed the cart total (no negative totals)
        $discount = min($discount, $cartTotal);

        return round($cartTotal - $discount, 2);
    }

    public function discountForItems(Coupon $coupon, array $items): array
    {
        $eligibleTotal = 0;

        foreach ($items as $item) {
            if ($coupon->eligibleFor($item['product'])) {
                $eligibleTotal += $item['product']->price * $item['quantity'];
            }
        }

        $discount = $coupon->type === 'percentage'
            ? $eligibleTotal * ($coupon->value / 100)
            : min($coupon->value, $eligibleTotal);

        return [
            'eligible_total' => round($eligibleTotal, 2),
            'discount' => round(min($discount, $eligibleTotal), 2),
        ];
    }
}
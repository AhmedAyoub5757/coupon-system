<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $desk = Category::where('slug', 'desk-rituals')->first();
        $mug = Product::where('slug', 'cloud-mug')->first();
        Coupon::updateOrCreate(['code' => 'SAVE10'], [
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'min_cart_value' => 500,
            'usage_limit' => 100,
            'expires_at' => now()->addMonth(),
            'scope' => 'all',
        ]);

        Coupon::updateOrCreate(['code' => 'FLAT200'], [
            'code' => 'FLAT200',
            'type' => 'fixed',
            'value' => 200,
            'min_cart_value' => 1000,
            'usage_limit' => 50,
            'expires_at' => now()->addWeeks(2),
            'scope' => 'category',
            'category_id' => $desk->id,
        ]);

        Coupon::updateOrCreate(['code' => 'EXPIRED5'], [
            'code' => 'EXPIRED5',
            'type' => 'percentage',
            'value' => 5,
            'expires_at' => now()->subDay(), // already expired, for testing
            'scope' => 'products',
            'product_ids' => [$mug->id],
        ]);
    }
}

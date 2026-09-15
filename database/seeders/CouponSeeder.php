<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'min_cart_value' => 500,
            'usage_limit' => 100,
            'expires_at' => now()->addMonth(),
        ]);

        Coupon::create([
            'code' => 'FLAT200',
            'type' => 'fixed',
            'value' => 200,
            'min_cart_value' => 1000,
            'usage_limit' => 50,
            'expires_at' => now()->addWeeks(2),
        ]);

        Coupon::create([
            'code' => 'EXPIRED5',
            'type' => 'percentage',
            'value' => 5,
            'expires_at' => now()->subDay(), // already expired, for testing
        ]);
    }
}

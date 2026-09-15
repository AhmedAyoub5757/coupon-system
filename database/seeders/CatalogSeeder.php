<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            ['name' => 'Desk rituals', 'slug' => 'desk-rituals', 'products' => [
                ['name' => 'Arc notebook', 'slug' => 'arc-notebook', 'description' => 'A calm place for bright ideas.', 'price' => 24, 'accent' => '#dbe7d6'],
                ['name' => 'Focus timer', 'slug' => 'focus-timer', 'description' => 'Twenty-five minutes, beautifully kept.', 'price' => 48, 'accent' => '#f2d59b'],
            ]],
            ['name' => 'Slow mornings', 'slug' => 'slow-mornings', 'products' => [
                ['name' => 'Cloud mug', 'slug' => 'cloud-mug', 'description' => 'Hand-thrown comfort for two hands.', 'price' => 32, 'accent' => '#e8c8b5'],
                ['name' => 'Linen eye mask', 'slug' => 'linen-eye-mask', 'description' => 'Soft light, softer landing.', 'price' => 19, 'accent' => '#cbdcdd'],
            ]],
            ['name' => 'Good carry', 'slug' => 'good-carry', 'products' => [
                ['name' => 'Daily tote', 'slug' => 'daily-tote', 'description' => 'Room for the things that matter.', 'price' => 68, 'accent' => '#d9d1bb'],
            ]],
        ];

        foreach ($catalog as $group) {
            $category = Category::updateOrCreate(['slug' => $group['slug']], ['name' => $group['name']]);
            foreach ($group['products'] as $product) {
                Product::updateOrCreate(['slug' => $product['slug']], array_merge($product, ['category_id' => $category->id, 'stock' => 20]));
            }
        }
    }
}
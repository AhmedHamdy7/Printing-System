<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Business Cards',
                'description' => 'Professional business cards (500 pieces)',
                'price' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => 'Flyers A4',
                'description' => 'Full color flyers A4 size (100 pieces)',
                'price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'Brochures',
                'description' => 'Tri-fold brochures (50 pieces)',
                'price' => 45.00,
                'is_active' => true,
            ],
            [
                'name' => 'Posters A3',
                'description' => 'Large format posters A3 size',
                'price' => 15.00,
                'is_active' => true,
            ],
            [
                'name' => 'Banners',
                'description' => 'Vinyl banners (per square meter)',
                'price' => 50.00,
                'is_active' => true,
            ],
            [
                'name' => 'Letterheads',
                'description' => 'Company letterheads (100 sheets)',
                'price' => 30.00,
                'is_active' => true,
            ],
            [
                'name' => 'Envelopes',
                'description' => 'Printed envelopes (100 pieces)',
                'price' => 20.00,
                'is_active' => true,
            ],
            [
                'name' => 'Stickers',
                'description' => 'Custom stickers (100 pieces)',
                'price' => 18.00,
                'is_active' => true,
            ],
            [
                'name' => 'Photo Prints 4x6',
                'description' => 'Photo prints 4x6 inches',
                'price' => 2.00,
                'is_active' => true,
            ],
            [
                'name' => 'Calendars',
                'description' => 'Wall calendars for one year',
                'price' => 12.00,
                'is_active' => true,
            ],
            [
                'name' => 'T-Shirt Printing',
                'description' => 'Custom t-shirt printing (per piece)',
                'price' => 40.00,
                'is_active' => true,
            ],
            [
                'name' => 'Mugs',
                'description' => 'Custom printed mugs',
                'price' => 22.00,
                'is_active' => true,
            ],
            [
                'name' => 'ID Cards',
                'description' => 'PVC ID cards with printing',
                'price' => 8.00,
                'is_active' => true,
            ],
            [
                'name' => 'Certificates',
                'description' => 'Printed certificates on premium paper',
                'price' => 10.00,
                'is_active' => true,
            ],
            [
                'name' => 'Book Binding',
                'description' => 'Book binding service (per book)',
                'price' => 15.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

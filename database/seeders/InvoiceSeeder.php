<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@printing.com')->first();
        $employee = User::where('email', 'employee@printing.com')->first();

        $products = Product::all();

        $invoices = [
            [
                'user_id' => $admin->id,
                'customer_name' => 'Ahmed Mohamed',
                'customer_phone' => '01012345678',
                'customer_email' => 'ahmed@example.com',
                'products' => [
                    ['product_id' => $products[0]->id, 'quantity' => 2],
                    ['product_id' => $products[1]->id, 'quantity' => 1],
                ],
                'discount' => 5,
                'notes' => 'Rush order - deliver by tomorrow',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $employee->id,
                'customer_name' => 'Sara Ali',
                'customer_phone' => '01098765432',
                'customer_email' => 'sara@example.com',
                'products' => [
                    ['product_id' => $products[2]->id, 'quantity' => 3],
                    ['product_id' => $products[4]->id, 'quantity' => 1],
                ],
                'discount' => 0,
                'notes' => 'Standard delivery',
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => $admin->id,
                'customer_name' => 'Khaled Hassan',
                'customer_phone' => '01156789012',
                'customer_email' => null,
                'products' => [
                    ['product_id' => $products[5]->id, 'quantity' => 5],
                    ['product_id' => $products[6]->id, 'quantity' => 5],
                ],
                'discount' => 10,
                'notes' => null,
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $employee->id,
                'customer_name' => 'Fatma Ibrahim',
                'customer_phone' => '01234567890',
                'customer_email' => 'fatma@example.com',
                'products' => [
                    ['product_id' => $products[8]->id, 'quantity' => 50],
                    ['product_id' => $products[9]->id, 'quantity' => 10],
                ],
                'discount' => 0,
                'notes' => 'Wedding photos',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $admin->id,
                'customer_name' => 'Omar Mahmoud',
                'customer_phone' => '01087654321',
                'customer_email' => 'omar@example.com',
                'products' => [
                    ['product_id' => $products[10]->id, 'quantity' => 20],
                ],
                'discount' => 50,
                'notes' => 'Corporate order',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => $employee->id,
                'customer_name' => 'Nour Saeed',
                'customer_phone' => '01123456789',
                'customer_email' => null,
                'products' => [
                    ['product_id' => $products[11]->id, 'quantity' => 10],
                    ['product_id' => $products[0]->id, 'quantity' => 1],
                ],
                'discount' => 0,
                'notes' => 'Gift items',
                'created_at' => now()->subHours(12),
            ],
            [
                'user_id' => $admin->id,
                'customer_name' => 'Youssef Ahmed',
                'customer_phone' => '01198765432',
                'customer_email' => 'youssef@example.com',
                'products' => [
                    ['product_id' => $products[12]->id, 'quantity' => 100],
                    ['product_id' => $products[13]->id, 'quantity' => 50],
                ],
                'discount' => 20,
                'notes' => 'Company event',
                'created_at' => now()->subHours(8),
            ],
            [
                'user_id' => $employee->id,
                'customer_name' => 'Mona Tarek',
                'customer_phone' => '01067890123',
                'customer_email' => 'mona@example.com',
                'products' => [
                    ['product_id' => $products[14]->id, 'quantity' => 5],
                ],
                'discount' => 0,
                'notes' => 'Thesis binding',
                'created_at' => now()->subHours(4),
            ],
            [
                'user_id' => $admin->id,
                'customer_name' => 'Hassan Mostafa',
                'customer_phone' => '01156781234',
                'customer_email' => null,
                'products' => [
                    ['product_id' => $products[3]->id, 'quantity' => 10],
                    ['product_id' => $products[7]->id, 'quantity' => 10],
                ],
                'discount' => 5,
                'notes' => 'Event materials',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $employee->id,
                'customer_name' => 'Layla Kamal',
                'customer_phone' => '01045678901',
                'customer_email' => 'layla@example.com',
                'products' => [
                    ['product_id' => $products[1]->id, 'quantity' => 5],
                    ['product_id' => $products[2]->id, 'quantity' => 3],
                    ['product_id' => $products[0]->id, 'quantity' => 1],
                ],
                'discount' => 10,
                'notes' => 'Marketing campaign',
                'created_at' => now()->subHour(),
            ],
        ];

        foreach ($invoices as $invoiceData) {
            $subtotal = 0;
            $items = [];

            foreach ($invoiceData['products'] as $item) {
                $product = Product::find($item['product_id']);
                $total = $product->price * $item['quantity'];
                $subtotal += $total;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $total,
                ];
            }

            $discount = $invoiceData['discount'];
            $total = $subtotal - $discount;

            $invoice = new Invoice([
                'user_id' => $invoiceData['user_id'],
                'customer_name' => $invoiceData['customer_name'],
                'customer_phone' => $invoiceData['customer_phone'],
                'customer_email' => $invoiceData['customer_email'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'notes' => $invoiceData['notes'],
            ]);

            $invoice->created_at = $invoiceData['created_at'];
            $invoice->updated_at = $invoiceData['created_at'];
            $invoice->save();

            $invoice->items()->createMany($items);
        }
    }
}

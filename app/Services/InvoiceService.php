<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function calculateInvoiceTotals(array $products, float $discount = 0): array
    {
        $subtotal = 0;
        $items = [];

        foreach ($products as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = $item['quantity'];
            $total = $product->price * $quantity;
            $subtotal += $total;

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total_price' => $total,
            ];
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount,
            'items' => $items,
        ];
    }

    public function createInvoice(array $data, int $userId): mixed
    {
        return DB::transaction(function () use ($data, $userId) {
            $totals = $this->calculateInvoiceTotals(
                $data['products'],
                $data['discount'] ?? 0
            );

            $invoice = $this->invoiceRepository->create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'notes' => $data['notes'] ?? null,
            ]);

            $invoice->items()->createMany($totals['items']);

            return $invoice;
        });
    }

    public function updateInvoice(int $invoiceId, array $data): mixed
    {
        return DB::transaction(function () use ($invoiceId, $data) {
            $invoice = $this->invoiceRepository->findOrFail($invoiceId);

            $totals = $this->calculateInvoiceTotals(
                $data['products'],
                $data['discount'] ?? 0
            );

            $invoice->update([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'notes' => $data['notes'] ?? null,
            ]);

            $invoice->items()->delete();
            $invoice->items()->createMany($totals['items']);

            return $invoice->fresh('items.product');
        });
    }

    public function canUserAccessInvoice($user, $invoice): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('employee') && $invoice->user_id === $user->id) {
            return true;
        }

        return false;
    }
}

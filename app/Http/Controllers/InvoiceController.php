<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $query = Invoice::with(['user', 'items.product']);

            if (auth()->user()->hasRole('employee')) {
                $query->where('user_id', auth()->id());
            }

            $invoices = QueryBuilder::for($query)
                ->allowedFilters(['invoice_number', 'customer_name'])
                ->allowedSorts(['created_at', 'total'])
                ->latest()
                ->paginate(10);

            return view('invoices.index', compact('invoices'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load invoices: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $products = Product::where('is_active', true)->get();
            return view('invoices.create', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $validated = $request->validated();
                $subtotal = 0;
                $products = [];

                foreach ($validated['products'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $quantity = $item['quantity'];
                    $total = $product->price * $quantity;
                    $subtotal += $total;

                    $products[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $total,
                    ];
                }

                $discount = $validated['discount'] ?? 0;
                $total = $subtotal - $discount;

                $invoice = Invoice::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'customer_email' => $validated['customer_email'] ?? null,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $invoice->items()->createMany($products);
            });

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        try {
            if (auth()->user()->hasRole('employee') && $invoice->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $invoice->load(['user', 'items.product']);
            return view('invoices.show', compact('invoice'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load invoice: ' . $e->getMessage());
        }
    }

    public function edit(Invoice $invoice)
    {
        try {
            if (auth()->user()->hasRole('employee') && $invoice->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $products = Product::where('is_active', true)->get();
            $invoice->load('items.product');
            return view('invoices.edit', compact('invoice', 'products'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($request, $invoice) {
                $validated = $request->validated();
                $subtotal = 0;
                $products = [];

                foreach ($validated['products'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $quantity = $item['quantity'];
                    $total = $product->price * $quantity;
                    $subtotal += $total;

                    $products[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $total,
                    ];
                }

                $discount = $validated['discount'] ?? 0;
                $total = $subtotal - $discount;

                $invoice->update([
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'customer_email' => $validated['customer_email'] ?? null,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $invoice->items()->delete();
                $invoice->items()->createMany($products);
            });

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            if (auth()->user()->hasRole('employee') && $invoice->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $invoice->delete();

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }

    public function pdf(Invoice $invoice)
    {
        try {
            if (auth()->user()->hasRole('employee') && $invoice->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            $invoice->load(['user', 'items.product']);
            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}

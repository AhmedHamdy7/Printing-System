<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $invoice = $this->route('invoice');

        if (auth()->user()->hasRole('admin')) {
            return true;
        }

        return auth()->user()->can('create-invoices') &&
               $invoice->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'At least one product is required',
            'products.*.product_id.required' => 'Product is required',
            'products.*.product_id.exists' => 'Selected product does not exist',
            'products.*.quantity.required' => 'Quantity is required',
            'products.*.quantity.min' => 'Quantity must be at least 1',
        ];
    }
}

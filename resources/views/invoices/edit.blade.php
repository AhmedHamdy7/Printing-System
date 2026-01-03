<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Invoice #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $invoice->customer_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $invoice->customer_phone) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Customer Email</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $invoice->customer_email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Products</h3>

                            <div id="products-container">
                                @foreach($invoice->items as $index => $item)
                                    <div class="product-row grid grid-cols-12 gap-4 mb-4">
                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Product *</label>
                                            <select name="products[{{ $index }}][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                            <input type="number" name="products[{{ $index }}][quantity]" min="1" value="{{ $item->quantity }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="col-span-3 flex items-end">
                                            @if($index > 0)
                                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md remove-product">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-product" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                                Add Another Product
                            </button>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
                                    <input type="number" name="discount" id="discount" step="0.01" value="{{ old('discount', $invoice->discount) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $invoice->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productIndex = {{ count($invoice->items) }};
        const products = @json($products);

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const newRow = document.createElement('div');
            newRow.className = 'product-row grid grid-cols-12 gap-4 mb-4';
            newRow.innerHTML = `
                <div class="col-span-6">
                    <label class="block text-sm font-medium text-gray-700">Product *</label>
                    <select name="products[${productIndex}][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        ${products.map(p => `<option value="${p.id}">${p.name} - $${parseFloat(p.price).toFixed(2)}</option>`).join('')}
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                    <input type="number" name="products[${productIndex}][quantity]" min="1" value="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="col-span-3 flex items-end">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md remove-product">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            productIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-row').remove();
            }
        });
    </script>
</x-app-layout>

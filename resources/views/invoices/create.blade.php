<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Invoice
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('customer_name') border-red-500 @enderror">
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Customer Email</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Products</h3>

                            <div id="products-container">
                                <div class="product-row grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Product *</label>
                                        <select name="products[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select">
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }} - EGP {{ number_format($product->price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                        <input type="number" name="products[0][quantity]" min="1" value="1"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                                    </div>
                                    <div class="col-span-3 flex items-end">
                                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md remove-product" style="display:none;">Remove</button>
                                    </div>
                                </div>
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
                                    <input type="number" name="discount" id="discount" step="0.01" value="{{ old('discount', 0) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        @error('products')
                            <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productIndex = 1;

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const newRow = document.querySelector('.product-row').cloneNode(true);

            newRow.querySelectorAll('select, input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace('[0]', `[${productIndex}]`));
                }
                if (input.tagName === 'INPUT') {
                    input.value = input.type === 'number' ? '1' : '';
                } else {
                    input.selectedIndex = 0;
                }
            });

            newRow.querySelector('.remove-product').style.display = 'block';
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

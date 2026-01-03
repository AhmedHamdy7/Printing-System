<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoice #{{ $invoice->invoice_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    Download PDF
                </a>
                @if(auth()->user()->hasRole('admin') || $invoice->user_id === auth()->id())
                    <a href="{{ route('invoices.edit', $invoice) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Edit
                    </a>
                @endif
                <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Customer Information</h3>
                            <p class="text-sm text-gray-600">Name: <span class="text-gray-900 font-medium">{{ $invoice->customer_name }}</span></p>
                            @if($invoice->customer_phone)
                                <p class="text-sm text-gray-600">Phone: <span class="text-gray-900 font-medium">{{ $invoice->customer_phone }}</span></p>
                            @endif
                            @if($invoice->customer_email)
                                <p class="text-sm text-gray-600">Email: <span class="text-gray-900 font-medium">{{ $invoice->customer_email }}</span></p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Invoice Information</h3>
                            <p class="text-sm text-gray-600">Invoice #: <span class="text-gray-900 font-medium">{{ $invoice->invoice_number }}</span></p>
                            <p class="text-sm text-gray-600">Created By: <span class="text-gray-900 font-medium">{{ $invoice->user->name }}</span></p>
                            <p class="text-sm text-gray-600">Date: <span class="text-gray-900 font-medium">{{ $invoice->created_at->format('Y-m-d H:i') }}</span></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Products</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">EGP {{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">EGP {{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium text-gray-900">EGP {{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            @if($invoice->discount > 0)
                                <div class="flex justify-between py-2 border-b">
                                    <span class="text-gray-600">Discount:</span>
                                    <span class="font-medium text-red-600">-EGP {{ number_format($invoice->discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-2 text-lg font-semibold">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-gray-900">EGP {{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($invoice->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
                            <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

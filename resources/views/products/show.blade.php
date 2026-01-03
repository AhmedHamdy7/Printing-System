<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Product Details
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Edit
                </a>
                <a href="{{ route('products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Product Name</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $product->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Price</h3>
                            <p class="mt-1 text-lg text-gray-900">EGP {{ number_format($product->price, 2) }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($product->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $product->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="mt-1 text-gray-900">{{ $product->description ?? 'No description available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

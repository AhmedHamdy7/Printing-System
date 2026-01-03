<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}!</h3>
                <p class="text-gray-600">Role: {{ auth()->user()->roles->first()->name ?? 'N/A' }}</p>
            </div>

            @php
                $todayInvoices = \App\Models\Invoice::whereDate('created_at', today())->count();
                $todayRevenue = \App\Models\Invoice::whereDate('created_at', today())->sum('total');
                $totalProducts = \App\Models\Product::where('is_active', true)->count();

                if(auth()->user()->hasRole('employee')) {
                    $myInvoices = \App\Models\Invoice::where('user_id', auth()->id())->count();
                    $myRevenue = \App\Models\Invoice::where('user_id', auth()->id())->sum('total');
                } else {
                    $totalUsers = \App\Models\User::count();
                    $totalInvoices = \App\Models\Invoice::count();
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @if(auth()->user()->hasRole('admin'))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $totalInvoices }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">My Invoices</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $myInvoices }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">My Revenue</p>
                                    <p class="text-2xl font-semibold text-gray-900">EGP {{ number_format($myRevenue, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Products</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalProducts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900">EGP {{ number_format($todayRevenue, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('invoices.create') }}" class="block w-full text-left px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                Create New Invoice
                            </a>
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('products.index') }}" class="block w-full text-left px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                                    Manage Products
                                </a>
                                <a href="{{ route('users.index') }}" class="block w-full text-left px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition">
                                    Manage Users
                                </a>
                                <a href="{{ route('reports.index') }}" class="block w-full text-left px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                    View Reports
                                </a>
                            @else
                                <a href="{{ route('invoices.index') }}" class="block w-full text-left px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                                    View My Invoices
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Invoices Today</span>
                                <span class="font-semibold text-gray-900">{{ $todayInvoices }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-600">Revenue Today</span>
                                <span class="font-semibold text-gray-900">EGP {{ number_format($todayRevenue, 2) }}</span>
                            </div>
                            @if($todayInvoices > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Average Invoice</span>
                                    <span class="font-semibold text-gray-900">EGP {{ number_format($todayRevenue / $todayInvoices, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

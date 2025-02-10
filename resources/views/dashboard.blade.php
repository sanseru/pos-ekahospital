<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Products Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <a href="{{ route('products.index') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Produk</h3>
                                <p class="text-gray-600 dark:text-gray-400">Kelola data produk dan stok</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Sales Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <a href="{{ route('sales.index') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penjualan</h3>
                                <p class="text-gray-600 dark:text-gray-400">Buat dan kelola transaksi</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Reports Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <a href="{{ route('reports.index') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Laporan</h3>
                                <p class="text-gray-600 dark:text-gray-400">Lihat dan export laporan</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Profile Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <a href="{{ route('profile.edit') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profil</h3>
                                <p class="text-gray-600 dark:text-gray-400">Pengaturan akun</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase">Penjualan Hari Ini</h4>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($todaySales,2) }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase">Total Produk</h4>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">items</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase">Transaksi Bulan Ini</h4>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $monthlyTransactions }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">transaksi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

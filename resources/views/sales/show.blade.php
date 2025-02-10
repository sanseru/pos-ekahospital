<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ __('Sale Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Invoice: {{ $sale->invoice_number }}
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('sales.invoice', $sale) }}"
                                class="inline-flex items-center px-4 py-2 bg-secondary hover:bg-secondary-dark text-white rounded-md transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                {{ __('Download Invoice') }}
                            </a>
                            <a href="{{ route('sales.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                {{ __('Back to Sales') }}
                            </a>
                        </div>
                    </div>

                    <!-- Sale Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ __('Sale Information') }}</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $sale->customer_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $sale->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Payment Method') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ Str::title($sale->payment_method) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $sale->payment_status === 'paid' ? 'bg-secondary/10 text-secondary-dark dark:text-secondary-light' : 'bg-primary/10 text-primary-dark dark:text-primary-light' }}">
                                            {{ Str::title($sale->payment_status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ __('Amount Summary') }}</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Subtotal') }}</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <dt class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Total') }}</dt>
                                    <dd class="text-base font-semibold text-primary dark:text-primary-light">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Product') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Price') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Quantity') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Subtotal') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-700/50 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($sale->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary dark:text-primary-light">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

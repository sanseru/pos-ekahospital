<div class="overflow-x-auto">
    <!-- Desktop Table (hidden on mobile) -->
    <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200 hidden md:table">
        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Name') }}</th>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Price') }}</th>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Stock') }}</th>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Status') }}</th>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Type') }}</th>
                <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light font-semibold">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($products as $product)
                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->stock }}</td>
                    <td class="px-6 py-4">
                        @if ($product->stock > 10)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                {{ __('In Stock') }}
                            </span>
                        @elseif($product->stock > 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                {{ __('Low Stock') }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                {{ __('Out of Stock') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->type }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'edit-product-{{ $product->id }}')"
                                class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                                {{ __('Edit') }}
                            </button>
                            <button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion-{{ $product->id }}')"
                                class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 transition-colors duration-200">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="bg-white dark:bg-gray-800">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No products found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Mobile Cards (hidden on desktop) -->
    <div class="md:hidden space-y-4">
        @forelse ($products as $product)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->type }}</p>
                    </div>
                    <!-- Status Badge -->
                    <div>
                        @if ($product->stock > 10)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                {{ __('In Stock') }}
                            </span>
                        @elseif($product->stock > 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                {{ __('Low Stock') }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                {{ __('Out of Stock') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">{{ __('Price') }}</span>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">{{ __('Stock') }}</span>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $product->stock }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'edit-product-{{ $product->id }}')"
                        class="flex-1 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                        {{ __('Edit') }}
                    </button>
                    <button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion-{{ $product->id }}')"
                        class="flex-1 px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 transition-colors duration-200">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center text-gray-500 dark:text-gray-400">
                {{ __('No products found') }}
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    @media (max-width: 768px) {
        .products-table {
            margin: -1rem;
            padding: 1rem;
        }
    }
</style>
@endpush

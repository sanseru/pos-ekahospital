<table class="w-full text-sm text-left">
    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
        <tr>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Name') }}</th>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Price') }}</th>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Stock') }}</th>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Status') }}</th>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Type') }}</th>
            <th scope="col" class="px-6 py-4 text-primary-dark dark:text-primary-light">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($products as $product)
            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
                <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ $product->stock }}</td>
                <td class="px-6 py-4">
                    @if ($product->stock > 10)
                        <span
                            class="px-2 py-1 text-xs rounded-full bg-secondary/10 text-secondary-dark dark:text-secondary-light">
                            {{ __('In Stock') }}
                        </span>
                    @elseif($product->stock > 0)
                        <span
                            class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            {{ __('Low Stock') }}
                        </span>
                    @else
                        <span
                            class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary-dark dark:text-primary-light">
                            {{ __('Out of Stock') }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">{{ $product->type }}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <x-secondary-button class="bg-secondary text-white hover:bg-secondary-dark"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'edit-product-{{ $product->id }}')">
                            {{ __('Edit') }}
                        </x-secondary-button>
                        <x-danger-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion-{{ $product->id }}')">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

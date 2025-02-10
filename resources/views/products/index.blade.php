<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products Management') }}
            </h2>
            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-product')" class="bg-primary hover:bg-primary-dark dark:bg-primary-dark dark:hover:bg-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Add New Product') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-text-input
                        type="search"
                        name="search"
                        placeholder="Search products..."
                        value="{{ request('search') }}"
                        class="w-full dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:focus:border-primary" />
                </div>
                <div class="flex gap-4">
                    <select name="filter"
                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary shadow-sm">
                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                        <option value="in-stock" {{ request('filter') == 'in-stock' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                        <option value="low-stock" {{ request('filter') == 'low-stock' ? 'selected' : '' }}>{{ __('Low Stock') }}</option>
                        <option value="out-of-stock" {{ request('filter') == 'out-of-stock' ? 'selected' : '' }}>{{ __('Out of Stock') }}</option>
                    </select>
                    <x-primary-button type="submit" class="bg-primary hover:bg-primary-dark dark:bg-primary-dark dark:hover:bg-primary">
                        {{ __('Search') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Products Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto products-table">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $product->stock > 10 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                                           ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' :
                                           'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex space-x-3">
                                        <button @click="$dispatch('open-modal', 'edit-product-{{$product->id}}')"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Edit
                                        </button>
                                        <button @click="$dispatch('open-modal', 'confirm-product-deletion-{{$product->id}}')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 products-pagination">
                @if($products->hasPages())
                    <div class="dark:bg-gray-800">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <x-modal name="add-product" focusable>
        <form method="POST"
            action="{{ route('products.store') }}"
            class="p-6"
            x-data="{
                submitting: false,
                init() {
                    this.$watch('submitting', value => {
                        if (value) this.$el.submit();
                    })
                }
            }"
            @submit.prevent="submitting = true">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add New Product') }}
            </h2>
            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Product Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        required maxlength="255" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-textarea-input id="description"
                        name="description"
                        class="mt-1 block w-full"
                        rows="3">
                        {{ old('description') }}
                    </x-textarea-input>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" :value="__('Price')" />
                        <x-text-input id="price" name="price" type="number"
                            class="mt-1 block w-full" required min="0" step="0.01" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="stock" :value="__('Initial Stock')" />
                        <x-text-input id="stock" name="stock" type="number"
                            class="mt-1 block w-full" required min="0" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="category" :value="__('Category')" />
                    <x-text-input id="category" name="category" type="text"
                        class="mt-1 block w-full" required maxlength="100" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="type" :value="__('Type')" />
                    <select id="type" name="type"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary rounded-md shadow-sm">
                        <option value="Barang">{{ __('Barang') }}</option>
                        <option value="Jasa">{{ __('Jasa') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="bg-primary hover:bg-primary-dark">
                    <span x-show="!submitting">{{ __('Save Product') }}</span>
                    <span x-show="submitting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Saving...') }}
                    </span>
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Product Modal -->
    @foreach($products as $product)
    <x-modal name="edit-product-{{$product->id}}" focusable>
        <form method="POST" action="{{ route('products.update', $product) }}" class="p-6 bg-white dark:bg-gray-800">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Product') }}
            </h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name{{$product->id}}" :value="__('Product Name')" />
                    <x-text-input id="name{{$product->id}}" name="name" type="text"
                        class="mt-1 block w-full" required maxlength="255"
                        :value="old('name', $product->name)" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description{{$product->id}}" :value="__('Description')" />
                    <x-textarea-input id="description{{$product->id}}"
                        name="description"
                        class="mt-1 block w-full"
                        rows="3">
                        {{ old('description', $product->description) }}
                    </x-textarea-input>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price{{$product->id}}" :value="__('Price')" />
                        <x-text-input id="price{{$product->id}}" name="price" type="number"
                            class="mt-1 block w-full" required min="0" step="0.01"
                            :value="old('price', $product->price)" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="stock{{$product->id}}" :value="__('Stock')" />
                        <x-text-input id="stock{{$product->id}}" name="stock" type="number"
                            class="mt-1 block w-full" required min="0"
                            :value="old('stock', $product->stock)" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="category{{$product->id}}" :value="__('Category')" />
                    <x-text-input id="category{{$product->id}}" name="category" type="text"
                        class="mt-1 block w-full" required maxlength="100"
                        :value="old('category', $product->category)" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="type{{$product->id}}" :value="__('Type')" />
                    <select id="type{{$product->id}}" name="type"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary rounded-md shadow-sm">
                        <option value="Barang" {{ $product->type == 'Barang' ? 'selected' : '' }}>{{ __('Barang') }}</option>
                        <option value="Jasa" {{ $product->type == 'Jasa' ? 'selected' : '' }}>{{ __('Jasa') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button type="button" @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="bg-primary hover:bg-primary-dark">
                    {{ __('Update Product') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Product Confirmation Modal -->
    <x-modal name="confirm-product-deletion-{{$product->id}}" focusable>
        <form method="POST" action="{{ route('products.destroy', $product) }}" class="p-6 bg-white dark:bg-gray-800">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete this product?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once this product is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button type="button" @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button>
                    {{ __('Delete Product') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    @endforeach
</x-app-layout>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('New Sale') }}
                        </h2>
                        <div class="text-xl font-semibold text-primary dark:text-primary-light bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg" id="total-amount">
                            Total: Rp 0
                        </div>
                    </div>

                    <form action="{{ route('sales.store') }}" method="POST" id="sale-form" onsubmit="return validateForm()">
                        @csrf

                        <!-- Items Container -->
                        <div id="items-container" class="space-y-4 mb-6">
                            <!-- Initial Item Row -->
                            <div class="item-row bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg fade-in">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                    <!-- Product Selection -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="product_0" :value="__('Product')" />
                                        <select id="product_0" name="items[0][product_id]"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary shadow-sm"
                                            onchange="updatePrice(0)">
                                            <option value="">{{ __('Select a product') }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->price }}"
                                                    data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Quantity Input with Plus/Minus Buttons -->
                                    <div>
                                        <x-input-label for="quantity_0" :value="__('Quantity')" />
                                        <div class="flex items-center mt-1">
                                            <button type="button" onclick="adjustQuantity(0, -1)"
                                                class="px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-l hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                                -
                                            </button>
                                            <x-text-input id="quantity_0" type="number" name="items[0][quantity]"
                                                class="w-full text-center dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"
                                                min="1" value="1"
                                                onchange="updatePrice(0)"
                                                onkeyup="updatePrice(0)" />
                                            <button type="button" onclick="adjustQuantity(0, 1)"
                                                class="px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-r hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Price Display -->
                                    <div>
                                        <x-input-label :value="__('Price')" />
                                        <div class="mt-2 text-gray-700 dark:text-gray-300" id="price_0">
                                            Rp 0
                                        </div>
                                    </div>

                                    <!-- Subtotal Display -->
                                    <div>
                                        <x-input-label :value="__('Subtotal')" />
                                        <div class="mt-2 font-medium text-primary dark:text-primary-light" id="subtotal_0">
                                            Rp 0
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="flex items-end justify-end">
                                        <button type="button" onclick="removeItem(0)"
                                            class="px-4 py-2 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-md transition">
                                            {{ __('Remove') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Item Button -->
                        <button type="button" onclick="addItem()"
                            class="mb-6 inline-flex items-center px-4 py-2 bg-secondary hover:bg-secondary-dark dark:bg-secondary-dark dark:hover:bg-secondary text-white rounded-md transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Add Item') }}
                        </button>

                        <!-- Footer Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <!-- Payment Method and Customer Name -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="payment_method" :value="__('Payment Method')" />
                                    <select id="payment_method" name="payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary shadow-sm">
                                        <option value="cash">{{ __('Cash') }}</option>
                                        <option value="card">{{ __('Card') }}</option>
                                        <option value="transfer">{{ __('Bank Transfer') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="customer_name" :value="__('Customer Name')" />
                                    <x-text-input id="customer_name" name="customer_name" type="text"
                                        class="mt-1 block w-full" placeholder="{{ __('Guest') }}" />
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('sales.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark dark:bg-primary-dark dark:hover:bg-primary text-white font-semibold text-sm rounded-md transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 0 012 2" />
                                    </svg>
                                    {{ __('Complete Sale') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemCount = 1;
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        // Update Price and Subtotal
        function updatePrice(index) {
            try {
                // Get elements using correct selectors
                const row = document.querySelector(`.item-row:nth-child(${index + 1})`);
                if (!row) {
                    console.error(`Row ${index} not found`);
                    return;
                }

                const productSelect = row.querySelector(`select[id^="product_"]`);
                const quantityInput = row.querySelector(`input[id^="quantity_"]`);
                const priceDisplay = row.querySelector(`[id^="price_"]`);
                const subtotalDisplay = row.querySelector(`[id^="subtotal_"]`);

                if (!productSelect || !quantityInput || !priceDisplay || !subtotalDisplay) {
                    console.error('Required elements not found in row', index);
                    return;
                }

                if (productSelect.value) {
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price) || 0;
                    const stock = parseInt(selectedOption.dataset.stock) || 0;
                    const quantity = parseInt(quantityInput.value) || 1;

                    // Check stock
                    if (quantity > stock) {
                        alert(`Maximum available stock is ${stock}`);
                        quantityInput.value = stock;
                        updatePrice(index);
                        return;
                    }

                    const subtotal = price * quantity;

                    // Update displays with formatted values
                    priceDisplay.textContent = formatter.format(price);
                    subtotalDisplay.textContent = formatter.format(subtotal);
                } else {
                    // Reset displays if no product selected
                    priceDisplay.textContent = formatter.format(0);
                    subtotalDisplay.textContent = formatter.format(0);
                }

                updateTotal();
            } catch (error) {
                console.error('Error in updatePrice:', error);
            }
        }

        // Update Total Amount
        function updateTotal() {
            try {
                let total = 0;
                const subtotalElements = document.querySelectorAll('[id^="subtotal_"]');

                subtotalElements.forEach(element => {
                    // Extract numeric value from formatted currency string
                    const value = element.textContent.replace(/[^\d]/g, '');
                    total += parseInt(value) || 0;
                });

                const totalDisplay = document.getElementById('total-amount');
                if (totalDisplay) {
                    totalDisplay.textContent = `Total: ${formatter.format(total)}`;
                }
            } catch (error) {
                console.error('Error in updateTotal:', error);
            }
        }

        // Adjust Quantity with Plus/Minus Buttons
        function adjustQuantity(index, delta) {
            try {
                const input = document.getElementById(`quantity_${index}`);
                if (!input) return;

                const currentValue = parseInt(input.value) || 1;
                const newValue = Math.max(1, currentValue + delta);
                input.value = newValue;
                updatePrice(index);
            } catch (error) {
                console.error('Error in adjustQuantity:', error);
            }
        }

        // Add New Item Row
        function addItem() {
            try {
                const container = document.getElementById('items-container');
                if (!container) return;

                const template = document.querySelector('.item-row').cloneNode(true);
                const newIndex = container.children.length;

                // Update IDs and names
                template.querySelectorAll('[id], [name]').forEach(element => {
                    if (element.id) {
                        element.id = element.id.replace(/_\d+/, `_${newIndex}`);
                    }
                    if (element.name) {
                        element.name = element.name.replace(/\[\d+\]/, `[${newIndex}]`);
                    }
                });

                // Update event listeners
                const select = template.querySelector('select');
                if (select) {
                    select.value = '';
                    select.onchange = () => updatePrice(newIndex);
                }

                const quantityInput = template.querySelector('input[type="number"]');
                if (quantityInput) {
                    quantityInput.value = '1';
                    quantityInput.onchange = () => updatePrice(newIndex);
                    quantityInput.onkeyup = () => updatePrice(newIndex);
                }

                // Update quantity adjustment buttons
                template.querySelectorAll('button[onclick*="adjustQuantity"]').forEach(button => {
                    const delta = button.textContent.trim() === '+' ? 1 : -1;
                    button.setAttribute('onclick', `adjustQuantity(${newIndex}, ${delta})`);
                });

                // Reset price and subtotal displays
                template.querySelector('[id^="price_"]').textContent = formatter.format(0);
                template.querySelector('[id^="subtotal_"]').textContent = formatter.format(0);

                // Add animation class
                template.classList.add('animate-fade-in');

                container.appendChild(template);
                if (select) select.focus();

                itemCount = newIndex + 1;
            } catch (error) {
                console.error('Error in addItem:', error);
            }
        }

        // Remove Item Row
        function removeItem(button) {
            try {
                const itemRow = button.closest('.item-row');
                const container = document.getElementById('items-container');

                if (!itemRow || !container) return;

                const totalItems = container.querySelectorAll('.item-row').length;

                if (totalItems > 1) {
                    itemRow.remove();
                } else {
                    // Reset the last row instead of removing
                    const select = itemRow.querySelector('select');
                    const quantity = itemRow.querySelector('input[type="number"]');
                    const price = itemRow.querySelector('[id^="price_"]');
                    const subtotal = itemRow.querySelector('[id^="subtotal_"]');

                    if (select) select.value = '';
                    if (quantity) quantity.value = '1';
                    if (price) price.textContent = formatter.format(0);
                    if (subtotal) subtotal.textContent = formatter.format(0);
                }

                updateTotal();
            } catch (error) {
                console.error('Error in removeItem:', error);
            }
        }

        // Add form validation function
        function validateForm() {
            try {
                const items = document.querySelectorAll('.item-row');
                let isValid = false;

                items.forEach(item => {
                    const select = item.querySelector('select');
                    if (select && select.value) {
                        isValid = true;
                    }
                });

                if (!isValid) {
                    alert('Please select at least one product');
                    return false;
                }

                // Disable submit button to prevent double submission
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;
                }

                return true;
            } catch (error) {
                console.error('Error in validateForm:', error);
                return false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Add event listeners to initial row
                const firstSelect = document.querySelector('#product_0');
                const firstQuantity = document.querySelector('#quantity_0');

                if (firstSelect) {
                    firstSelect.addEventListener('change', () => updatePrice(0));
                }

                if (firstQuantity) {
                    firstQuantity.addEventListener('change', () => updatePrice(0));
                    firstQuantity.addEventListener('keyup', () => updatePrice(0));
                }

                // Initial total update
                updateTotal();
            } catch (error) {
                console.error('Error in initialization:', error);
            }
        });

        // Add payment method handling
        document.getElementById('payment_method').addEventListener('change', function() {
            const cashPaymentDiv = document.getElementById('cash-payment-details');
            if (!cashPaymentDiv) return;

            if (this.value === 'cash') {
                cashPaymentDiv.classList.remove('hidden');
            } else {
                cashPaymentDiv.classList.add('hidden');
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .item-row {
            transition: all 0.3s ease;
        }
        .item-row:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .dark .item-row:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dark mode input focus states */
        .dark input:focus,
        .dark select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 1px var(--primary-color);
        }

        /* Dark mode select options */
        .dark select option {
            background-color: #1a1a1a;
            color: #e5e7eb;
        }

        /* Dark mode number input arrows */
        .dark input[type="number"]::-webkit-inner-spin-button,
        .dark input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
            background: #4b5563;
        }
    </style>
    @endpush
</x-app-layout>

/**
 * Price Handler for Product Forms
 *
 * This script handles the display and processing of product prices
 * in the admin forms, ensuring proper number formatting and submission.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Price Handler initialized');

    // Format a number with dots as thousand separators (Indonesian format)
    function formatNumberWithDots(number) {
        if (!number) return '';

        // Convert to string and clean up the number first
        let cleanNumber = number;
        if (typeof cleanNumber === 'string') {
            // Remove all dots and replace commas with dots
            cleanNumber = cleanNumber.replace(/\./g, '');
            cleanNumber = cleanNumber.replace(/,/g, '.');
        }

        // Ensure it's a valid number
        if (isNaN(Number(cleanNumber))) {
            console.error('Invalid number format:', number);
            return '0';
        }

        // Get the integer part only for formatting
        const integerPart = Math.floor(Number(cleanNumber)).toString();

        // Format with dots as thousand separators (Indonesian format)
        return integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Find all display price inputs and fix their attributes
    const priceFields = document.querySelectorAll('[data-field="base_price"]');
    console.log(`Found ${priceFields.length} price fields to initialize`);

    priceFields.forEach(function(displayInput, index) {
        // Fix the input attributes - remove required and add name
        if (displayInput.hasAttribute('required')) {
            displayInput.removeAttribute('required');
            console.log(`Removed required attribute from price field #${index+1}`);
        }

        if (!displayInput.hasAttribute('name')) {
            displayInput.setAttribute('name', 'display_price');
            console.log(`Added name attribute to price field #${index+1}`);
        }

        const realInputId = displayInput.getAttribute('id').replace('display-', 'real-');
        const realInput = document.getElementById(realInputId);

        if (!realInput) {
            console.error(`Could not find real input with ID: ${realInputId}`);
            return;
        }

        // Make the real input required instead
        if (realInput && !realInput.hasAttribute('required')) {
            realInput.setAttribute('required', 'required');
            console.log(`Added required attribute to the real input field #${index+1}`);
        }

        if (realInput && realInput.value) {
            let initialPrice = realInput.value;
            console.log(`Initial price value for field #${index+1}:`, initialPrice);

            // Clean and format the initial price
            if (initialPrice.toString().includes('.')) {
                let parts = initialPrice.toString().split('.');
                parts[0] = parts[0].replace(/\./g, '');
                initialPrice = parts[0];
            } else {
                initialPrice = initialPrice.toString().replace(/\./g, '');
            }

            console.log(`After cleaning, price value: ${initialPrice}`);

            displayInput.value = formatNumberWithDots(initialPrice);
            realInput.value = initialPrice;

            console.log(`Set display value: ${displayInput.value}, real value: ${realInput.value}`);
        }

        // Handle input changes
        displayInput.addEventListener('input', function() {
            let inputValue = this.value.replace(/[^\d]/g, '');

            if (inputValue === '') {
                this.value = '';
                realInput.value = '';
                return;
            }

            realInput.value = inputValue;
            this.value = formatNumberWithDots(inputValue);
        });

        // Add custom validation to ensure the price field has a value
        displayInput.addEventListener('blur', function() {
            if (!this.value || this.value === '0') {
                this.classList.add('is-invalid');

                // Create or update error message
                let errorMsg = this.parentNode.querySelector('.invalid-feedback');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    this.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Harga tidak boleh kosong';
            } else {
                this.classList.remove('is-invalid');

                // Remove error message if exists
                const errorMsg = this.parentNode.querySelector('.invalid-feedback');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    });

    // Form submission handling
    const productForms = document.querySelectorAll('form[action*="products"]');
    console.log(`Found ${productForms.length} product forms`);

    productForms.forEach(function(form, formIndex) {
        console.log(`Setting up form #${formIndex+1}`);

        form.addEventListener('submit', function(e) {
            console.log(`Form #${formIndex+1} is being submitted`);
            const priceFields = this.querySelectorAll('[data-field="base_price"]');
            console.log(`This form has ${priceFields.length} price fields`);

            let hasError = false;

            priceFields.forEach(function(displayInput, fieldIndex) {
                const realInputId = displayInput.getAttribute('id').replace('display-', 'real-');
                const realInput = document.getElementById(realInputId);

                if (!realInput) {
                    console.error(`Could not find real input with ID: ${realInputId}`);
                    return;
                }

                console.log(`Checking field #${fieldIndex+1} - Display value: ${displayInput.value}`);

                // Validate that price is entered
                if (!displayInput.value || displayInput.value === '0') {
                    displayInput.classList.add('is-invalid');

                    // Create or update error message
                    let errorMsg = displayInput.parentNode.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        displayInput.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'Harga tidak boleh kosong';

                    hasError = true;
                    console.error(`Validation error: Price field #${fieldIndex+1} is empty`);
                    e.preventDefault();
                    return;
                }

                if (displayInput.value) {
                    const cleanValue = displayInput.value.replace(/\./g, '');
                    realInput.value = cleanValue;
                    console.log(`Set real input value for field #${fieldIndex+1}:`, realInput.value);
                }
            });

            if (hasError) {
                console.error('Form submission prevented due to validation errors');
                e.preventDefault();
                return;
            }

            // Handle range price sections if they exist
            const basePrice = document.getElementById('base-price');
            if (basePrice && basePrice.value) {
                const cleanBasePrice = basePrice.value.replace(/\./g, '');
                console.log('Range mode - base price:', cleanBasePrice);

                // Create hidden input if it doesn't exist
                let hiddenBasePrice = document.querySelector('input[type="hidden"][name="base_price"]');
                if (!hiddenBasePrice) {
                    hiddenBasePrice = document.createElement('input');
                    hiddenBasePrice.type = 'hidden';
                    hiddenBasePrice.name = 'base_price';
                    form.appendChild(hiddenBasePrice);
                    console.log('Created hidden base_price input');
                }

                hiddenBasePrice.value = cleanBasePrice;
            }

            // Clean size price inputs
            const sizePrices = document.querySelectorAll('.size-price');
            sizePrices.forEach(function(input, index) {
                if (input.value) {
                    const originalValue = input.value;
                    input.value = input.value.replace(/\./g, '');
                    console.log(`Cleaned size price #${index+1} from ${originalValue} to ${input.value}`);
                }
            });

            console.log('Form validation passed, submitting with price:',
                document.querySelector('input[name="base_price"]')?.value);
        });
    });
});

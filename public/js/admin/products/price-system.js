document.addEventListener('DOMContentLoaded', function() {
    const priceTypeSingle = document.getElementById('priceTypeSingle');
    const priceTypeRange = document.getElementById('priceTypeRange');
    const singlePriceSection = document.getElementById('singlePriceSection');
    const rangePriceSection = document.getElementById('rangePriceSection');
    const basePriceInput = document.getElementById('base-price');
    const priceIncreaseInput = document.getElementById('price-increase');
    const sizePriceInputs = document.querySelectorAll('.size-price');

    // Format number to currency
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Parse currency string to number
    function parseCurrency(currencyString) {
        return parseInt(currencyString.replace(/[^\d]/g, '')) || 0;
    }

    // Handle price type change
    function handlePriceTypeChange() {
        if (priceTypeSingle.checked) {
            singlePriceSection.style.display = 'block';
            rangePriceSection.style.display = 'none';
        } else {
            singlePriceSection.style.display = 'none';
            rangePriceSection.style.display = 'block';
        }
    }

    // Calculate size prices based on base price and increase percentage
    function calculateSizePrices() {
        const basePrice = parseCurrency(basePriceInput.value);
        const increasePercentage = parseFloat(priceIncreaseInput.value) || 0;

        if (basePrice > 0 && increasePercentage > 0) {
            const sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
            const baseIndex = sizes.indexOf('M'); // Use M as base size

            sizes.forEach((size, index) => {
                const sizeInput = document.querySelector(`.size-price[data-size="${size}"]`);
                if (sizeInput) {
                    const difference = index - baseIndex;
                    const price = basePrice + (basePrice * (increasePercentage / 100) * difference);
                    sizeInput.value = formatCurrency(Math.round(price));
                }
            });
        }
    }

    // Initialize price inputs with currency formatting
    function initializePriceInputs() {
        const priceInputs = [basePriceInput, ...sizePriceInputs];

        priceInputs.forEach(input => {
            if (input) {
                // Format on blur
                input.addEventListener('blur', function() {
                    const value = parseCurrency(this.value);
                    this.value = formatCurrency(value);
                });

                // Allow only numbers and formatting on input
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^\d]/g, '');
                });
            }
        });
    }

    // Event listeners
    if (priceTypeSingle && priceTypeRange) {
        priceTypeSingle.addEventListener('change', handlePriceTypeChange);
        priceTypeRange.addEventListener('change', handlePriceTypeChange);
    }

    if (basePriceInput && priceIncreaseInput) {
        basePriceInput.addEventListener('input', calculateSizePrices);
        priceIncreaseInput.addEventListener('input', calculateSizePrices);
    }

    // Initialize
    handlePriceTypeChange();
    initializePriceInputs();
});

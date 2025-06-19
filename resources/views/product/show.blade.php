@extends('layouts.landing')

@section('title', $product->name . ' - Urbanova')

@push('css')
    <style>
        .product-detail {
            padding: 2rem 0;
        }

        .product-img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: 400px;
            object-fit: cover;
        }

        .product-thumb {
            cursor: pointer;
            height: 80px;
            width: 100%;
            object-fit: cover;
            transition: all 0.2s;
            border: 2px solid #eee;
            border-radius: 4px;
            opacity: 0.7;
        }

        .product-thumb:hover {
            transform: translateY(-2px);
            opacity: 1;
        }

        .product-thumb.active {
            border-color: #090969;
            opacity: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .product-info {
            padding: 1rem;
        }

        .product-name {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #090969;
            margin-bottom: 1rem;
        }

        .product-price del {
            font-size: 1rem;
            color: #6c757d;
            margin-right: 0.5rem;
        }

        .product-description {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .sizes-container {
            margin-bottom: 1.5rem;
        }

        .size-label {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .size-label:hover {
            border-color: #090969;
        }

        .size-label.active {
            background-color: #090969;
            color: white;
            border-color: #090969;
        }

        .size-label.out-of-stock {
            background-color: #f5f5f5;
            color: #aaa;
            border-color: #ddd;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #090969;
            background: transparent;
            color: #090969;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #090969;
            color: white;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            margin: 0 10px;
        }

        .btn-add-to-cart {
            background-color: #090969;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }

        .btn-add-to-cart:hover {
            background-color: #060644;
        }

        .category-badge {
            display: inline-block;
            background-color: #f5f5f5;
            color: #090969;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .related-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }

        .related-product {
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            text-align: center;
            margin-bottom: 1rem;
        }

        .related-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .related-product img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .related-product-name {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .related-product-price {
            font-weight: 700;
            color: #090969;
        }

        .main-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-container {
            cursor: pointer;
            padding: 2px;
            border-radius: 4px;
            overflow: hidden;
            background-color: #fff;
            height: 85px;
        }

        /* Color selection styles */
        .colors-container {
            margin-bottom: 1.5rem;
        }

        .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .color-option-wrapper {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin-right: 12px;
            margin-bottom: 10px;
        }

        .color-option {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #ddd !important;
            transition: all 0.2s;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .color-name {
            font-size: 12px;
            margin-top: 5px;
            color: #555;
            text-align: center;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.active {
            border-color: #090969 !important;
            box-shadow: 0 0 0 2px rgba(9, 9, 105, 0.3);
        }

        .color-option-wrapper.hidden {
            display: none;
        }

        .color-option:after {
            content: '';
            position: absolute;
            top: -6px;
            right: -6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #090969;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .color-option.active:after {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="container product-detail">
        <div class="row">
            <div class="col-md-5">
                <div class="main-image-container mb-3">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500' }}"
                        alt="{{ $product->name }}" class="product-img" id="main-product-image">
                </div>

                @if (isset($product->additional_images) && count($product->additional_images) > 0)
                    <div class="product-thumbnails">
                        <div class="row">
                            <!-- Show main image as first thumbnail -->
                            <div class="col-3 mb-2">
                                <div class="thumbnail-container">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100' }}"
                                        alt="{{ $product->name }}" class="img-thumbnail product-thumb active"
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500' }}">
                                </div>
                            </div>

                            <!-- Show additional images as thumbnails -->
                            @foreach ($product->additional_images as $additionalImage)
                                <div class="col-3 mb-2">
                                    <div class="thumbnail-container">
                                        <img src="{{ asset('storage/' . $additionalImage) }}" alt="{{ $product->name }}"
                                            class="img-thumbnail product-thumb"
                                            data-image="{{ asset('storage/' . $additionalImage) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="product-info">
                    <span class="category-badge">{{ $product->category->name }}</span>
                    <h1 class="product-name">{{ $product->name }}</h1>
                    <div class="product-price" id="product-price-display">
                        @if ($product->hasActiveDiscount())
                            <del>Rp {{ number_format($product->price, 0, ',', '.') }}</del>
                            Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </div>

                    <div class="product-description">
                        {!! $product->description !!}
                    </div>

                    <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selected-variant-id">

                        @if (count($product->sizes) > 0)
                            <div class="sizes-container">
                                <h5>Select Size</h5>
                                <div class="size-options">
                                    @foreach ($product->sizes()->get() as $sizeObj)
                                        @php
                                            $sizeId = $sizeObj->id;
                                            $sizeName = $sizeObj->name;
                                            $inStock = $product->isSizeInStock($sizeId);
                                            $stockAmount = $product->getStockForSize($sizeId);
                                            $hasSizeVariants =
                                                isset($variantsBySizeAndColor[$sizeName]) &&
                                                count($variantsBySizeAndColor[$sizeName]) > 0;
                                            // Get size-specific price if enabled
                                            $sizePrice =
                                                $product->has_size_prices && isset($product->size_prices[$sizeName])
                                                    ? $product->size_prices[$sizeName]
                                                    : null;
                                        @endphp
                                        <label class="size-label {{ !$hasSizeVariants ? 'out-of-stock' : '' }}"
                                            title="{{ !$hasSizeVariants ? 'Stok habis' : 'Tersedia: ' . $stockAmount }}"
                                            data-size="{{ $sizeName }}" data-price="{{ $sizePrice }}"
                                            data-discounted="{{ $product->hasActiveDiscount() ? ($sizePrice ? $sizePrice - ($sizePrice * $product->discount_percentage) / 100 : null) : null }}">
                                            <input type="radio" name="size" value="{{ $sizeName }}"
                                                style="display:none;" {{ !$hasSizeVariants ? 'disabled' : '' }}>
                                            {{ $sizeName }} {{ !$hasSizeVariants ? '(Habis)' : '' }}
                                        </label>
                                    @endforeach
                                </div>
                                <div id="size-error" class="text-danger" style="display:none;">Please select a size</div>
                            </div>
                        @endif

                        @if (count($product->colors) > 0)
                            <div class="colors-container">
                                <h5>Select Color</h5>
                                <div class="color-options">
                                    @php
                                        $uniqueColors = [];
                                        foreach ($product->variants as $variant) {
                                            if ($variant->color && !isset($uniqueColors[$variant->color->id])) {
                                                $uniqueColors[$variant->color->id] = $variant->color;
                                            }
                                        }
                                    @endphp

                                    @foreach ($uniqueColors as $color)
                                        <div class="color-option-wrapper hidden" data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}">
                                            <div class="color-option"
                                                style="background-color: #{{ $color->code }}; border: 2px solid #ddd;"
                                                title="{{ $color->name }} ({{ $color->code }})"></div>
                                            <span class="color-name">{{ $color->name }}</span>
                                        </div>
                                    @endforeach


                                </div>
                                <div id="color-error" class="text-danger" style="display:none;">Please select a color</div>
                            </div>
                        @endif

                        <div>
                            <h5>Quantity</h5>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" id="decrease-qty">-</button>
                                <input type="text" name="qty" id="qty" value="1" class="quantity-input"
                                    readonly>
                                <button type="button" class="quantity-btn" id="increase-qty">+</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        @if ($relatedProducts && $relatedProducts->count() > 0)
            <div class="row">
                <div class="col-12">
                    <h2 class="related-title">You might also like</h2>
                </div>
                @foreach ($relatedProducts as $related)
                    <div class="col-md-3">
                        <a href="{{ route('product.show', $related->slug) }}"
                            style="text-decoration: none; color: inherit;">
                            <div class="related-product">
                                <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://via.placeholder.com/200' }}"
                                    alt="{{ $related->name }}">
                                <h3 class="related-product-name">{{ $related->name }}</h3>
                                <div class="related-product-price">Rp {{ number_format($related->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Store variants data for JavaScript usage
        const variantsBySizeAndColor = @json($variantsBySizeAndColor);
    </script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Make sure first thumbnail is active
            $('.product-thumb').first().addClass('active');

            // Thumbnail gallery functionality
            $('.product-thumb').click(function() {
                // Update active thumbnail
                $('.product-thumb').removeClass('active');
                $(this).addClass('active');

                // Update main image with animation
                let newImageSrc = $(this).data('image');
                $('#main-product-image').fadeOut(200, function() {
                    $(this).attr('src', newImageSrc).fadeIn(200);
                });
            });

            // Size selection
            $('.size-label').not('.out-of-stock').click(function() {
                $('.size-label').removeClass('active');
                $(this).addClass('active');
                $('#size-error').hide();

                // Get the selected size
                const selectedSize = $(this).data('size');

                // Update color options based on the selected size
                updateColorOptions(selectedSize);

                // Update price display based on size
                if ({{ $product->has_size_prices ? 'true' : 'false' }}) {
                    const sizePrice = $(this).data('price');
                    if (sizePrice) {
                        // Format the price with thousand separators
                        const formattedPrice = formatNumber(sizePrice);

                        if ({{ $product->hasActiveDiscount() ? 'true' : 'false' }}) {
                            const discountedPrice = $(this).data('discounted');
                            const formattedDiscountedPrice = formatNumber(discountedPrice);
                            $('#product-price-display').html(
                                `<del>Rp ${formattedPrice}</del> Rp ${formattedDiscountedPrice}`);
                        } else {
                            $('#product-price-display').html(`Rp ${formattedPrice}`);
                        }
                    }
                }

                // Reset color selection
                $('.color-option').removeClass('active');
                $('input[name="color"]').prop('checked', false);
                $('#selected-variant-id').val('');

                // If the selected size has only variants without colors, select it directly
                const sizeVariants = variantsBySizeAndColor[selectedSize] || [];
                if (sizeVariants.length === 1 && sizeVariants[0].no_color) {
                    $('#selected-variant-id').val(sizeVariants[0].variant_id);
                    // Hide the color selection section as it's not needed
                    $('.colors-container').hide();
                } else if ($('.color-option').length > 0 && !$('.color-option.hidden').length) {
                    // Show the color selection section if there are color options
                    $('.colors-container').show();
                }
            });

            // Color selection
            $(document).on('click', '.color-option-wrapper:not(.hidden)', function() {
                $('.color-option-wrapper').find('.color-option').removeClass('active');
                $(this).find('.color-option').addClass('active');
                $('input[name="color"]').prop('checked', false);
                $('#color-error').hide();

                // Find the matching variant
                const selectedSize = $('.size-label.active').data('size');
                const selectedColorId = $(this).data('color-id');

                if (selectedSize && selectedColorId && variantsBySizeAndColor[selectedSize]) {
                    const selectedVariant = variantsBySizeAndColor[selectedSize].find(variant =>
                        variant.color_id === selectedColorId);

                    if (selectedVariant) {
                        $('#selected-variant-id').val(selectedVariant.variant_id);
                    }
                }
            });

            // Function to show/hide color options based on selected size
            function updateColorOptions(selectedSize) {
                // Hide all color options first
                $('.color-option-wrapper').addClass('hidden');

                // If no size is selected or no variants for this size, don't show any colors
                if (!selectedSize || !variantsBySizeAndColor[selectedSize]) {
                    $('.colors-container').hide();
                    return;
                }

                // Check if this size has only "no color" variants
                const sizeVariants = variantsBySizeAndColor[selectedSize];

                if (sizeVariants.length === 1 && sizeVariants[0].no_color) {
                    $('.colors-container').hide();
                    return;
                }

                // Show the color selection section
                $('.colors-container').show();

                // Show only colors available for the selected size
                const availableColors = variantsBySizeAndColor[selectedSize].filter(variant => !variant.no_color);

                availableColors.forEach(colorData => {
                    const colorOption = $(`.color-option-wrapper[data-color-id="${colorData.color_id}"]`);
                    colorOption.removeClass('hidden');

                    // Force style refresh for the color circle
                    const colorCircle = colorOption.find('.color-option');
                    colorCircle.attr('style', '');
                    setTimeout(() => {
                        colorCircle.attr('style', `background-color: #${colorData.color_code}; border: 2px solid #ddd;`);
                    }, 10);
                });

                // If only one color is available, select it automatically
                if (availableColors.length === 1) {
                    const onlyColorOption = $(`.color-option-wrapper[data-color-id="${availableColors[0].color_id}"]`);
                    onlyColorOption.click();
                }
            }

            // Format number with thousand separators
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Quantity controls
            $('#increase-qty').click(function() {
                let qty = parseInt($('#qty').val());
                $('#qty').val(qty + 1);
            });

            $('#decrease-qty').click(function() {
                let qty = parseInt($('#qty').val());
                if (qty > 1) {
                    $('#qty').val(qty - 1);
                }
            });

            // Add to cart form submission
            $('#add-to-cart-form').submit(function(e) {
                e.preventDefault(); // Prevent normal form submission

                // Check if size is selected (if sizes exist)
                if ($('.size-label').length > 0 && !$('.size-label.active').length) {
                    $('#size-error').show();
                    return false;
                }

                // Get selected size and its variants
                const selectedSize = $('.size-label.active').data('size');
                const sizeVariants = selectedSize ? (variantsBySizeAndColor[selectedSize] || []) : [];

                // Check if color is selected (if colors are required for this size)
                const needsColorSelection = sizeVariants.length > 0 &&
                    !(sizeVariants.length === 1 && sizeVariants[0].no_color);

                if (needsColorSelection && $('.color-option-wrapper').length > 0 && !$(
                        '.color-option.active').length) {
                    $('#color-error').show();
                    return false;
                }

                // Check for out of stock
                const selectedSizeLabel = $('.size-label.active');
                if (selectedSizeLabel.length > 0 && selectedSizeLabel.hasClass('out-of-stock')) {
                    $('#size-error').text('Ukuran ini tidak tersedia stoknya').show();
                    return false;
                }

                // Get form data
                const formData = $(this).serialize();

                // Add to cart via AJAX
                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: "POST",
                    data: formData,
                    success: function(res) {
                        // Update cart count in navbar
                        if (res.cart_count) {
                            updateCartCount(res.cart_count);
                        }

                        // Show success modal
                        $.get('/cart/added-modal', {
                            product_id: {{ $product->id }},
                            size: $('.size-label.active').data('size'),
                            color_id: $('.color-option.active').length ? $(
                                '.color-option.active').closest(
                                '.color-option-wrapper').data('color-id') : null,
                            qty: $('#qty').val()
                        }, function(modalHtml) {
                            $('#cartAddedModal').remove();
                            $('body').append(modalHtml);
                            $('#cartAddedModal').modal('show');
                        });
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Failed to add product to cart');
                    }
                });
            });

            // Function to update cart count in navbar
            function updateCartCount(count) {
                if (count > 0) {
                    if ($('.cart-count').length) {
                        $('.cart-count').text(count);
                    } else {
                        $('.cart-link').append('<span class="cart-count">' + count + '</span>');
                    }
                } else {
                    $('.cart-count').remove();
                }
            }
        });
    </script>
@endpush

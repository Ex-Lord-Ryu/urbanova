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
                                            // Get size-specific price if enabled
                                            $sizePrice =
                                                $product->has_size_prices && isset($product->size_prices[$sizeName])
                                                    ? $product->size_prices[$sizeName]
                                                    : null;
                                        @endphp
                                        <label class="size-label {{ !$inStock ? 'out-of-stock' : '' }}"
                                            title="{{ !$inStock ? 'Stok habis' : 'Tersedia: ' . $stockAmount }}"
                                            data-size="{{ $sizeName }}" data-price="{{ $sizePrice }}"
                                            data-discounted="{{ $product->hasActiveDiscount() ? ($sizePrice ? $sizePrice - ($sizePrice * $product->discount_percentage) / 100 : null) : null }}">
                                            <input type="radio" name="size" value="{{ $sizeName }}"
                                                style="display:none;" {{ !$inStock ? 'disabled' : '' }}>
                                            {{ $sizeName }} {{ !$inStock ? '(Habis)' : '' }}
                                        </label>
                                    @endforeach
                                </div>
                                <div id="size-error" class="text-danger" style="display:none;">Please select a size</div>
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
                        <a href="{{ route('product.show', $related->id) }}" style="text-decoration: none; color: inherit;">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Make sure first thumbnail is active
            $('.product-thumb').first().addClass('active');

            // Thumbnail gallery functionality
            $('.product-thumb').click(function() {
                console.log('Thumbnail clicked');

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
            });

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
                if ($('.size-label').length > 0 && !$('input[name="size"]:checked').val()) {
                    $('#size-error').show();
                    return false;
                }

                // Check for out of stock
                const selectedSize = $('input[name="size"]:checked');
                if (selectedSize.length > 0 && selectedSize.prop('disabled')) {
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
                            size: $('input[name="size"]:checked').val(),
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

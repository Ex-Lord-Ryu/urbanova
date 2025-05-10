@extends('layouts.landing')

@section('title', 'Urbanova - Home')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/landing/landing_page.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #FF6B6B;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(255, 107, 107, 0.3);
        }

        .product-price {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .original-price {
            color: #6c757d;
            font-size: 0.85em;
            text-decoration: line-through;
        }

        .discount-price {
            font-weight: bold;
            color: #FF6B6B;
        }
    </style>
@endpush

@section('content')
    <section class="hero">
        <h1>New Arrivals</h1>
    </section>

    <section class="products">
        @forelse ($featuredProducts as $product)
            <div class="product-card">
                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-container">
                        @if($product->discount_percentage > 0)
                            <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                        @endif
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="product-img">
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <div class="product-price">
                            @if($product->discount_percentage > 0)
                                <div class="original-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="discount-price">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                            @else
                                <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
                <button class="btn-add-to-cart"
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->name }}"
                    data-product-price="{{ $product->discount_percentage > 0 ? $product->discounted_price : $product->price }}"
                    data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                    data-product-sizes='@json($product->sizes()->pluck("name"))'
                    data-has-size-prices="{{ $product->has_size_prices }}"
                    data-size-prices='@json($product->has_size_prices ? $product->size_prices : [])'
                    data-discount-percentage="{{ $product->discount_percentage }}"
                    data-has-active-discount="{{ $product->hasActiveDiscount() ? 'true' : 'false' }}"
                    data-available-sizes='@json(collect($product->sizes)->filter(function($size) use ($product) {
                      $sizeId = \App\Models\Size::where("name", $size)->first()->id ?? 0;
                      return $product->isSizeInStock($sizeId);
                    }))'>Add to Cart</button>
            </div>
        @empty
            <div class="no-products">
                <p>No featured products available at the moment.</p>
            </div>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.btn-add-to-cart').click(function() {
                var productId = $(this).data('product-id');
                var productName = $(this).data('product-name');
                var productPrice = $(this).data('product-price');
                var productImage = $(this).data('product-image');
                var sizes = $(this).data('product-sizes');
                var availableSizes = $(this).data('available-sizes') || [];
                var hasSizePrices = $(this).data('has-size-prices');
                var sizePrices = $(this).data('size-prices') || {};
                var discountPercentage = $(this).data('discount-percentage') || 0;
                var hasActiveDiscount = $(this).data('has-active-discount') === 'true';

                if (!sizes || sizes.length === 0) {
                    Swal.fire('Oops', 'Size tidak tersedia untuk produk ini', 'error');
                    return;
                }

                let sizeOptions = '';
                sizes.forEach(function(size) {
                    const isAvailable = availableSizes.includes(size);
                    // Add size-specific price data to each button
                    const sizePrice = hasSizePrices && sizePrices[size] ? sizePrices[size] : productPrice;
                    const discountedSizePrice = hasActiveDiscount ? sizePrice - (sizePrice * discountPercentage / 100) : sizePrice;

                    sizeOptions += `<button type='button' class='btn ${isAvailable ? 'btn-outline-dark' : 'btn-outline-secondary'} btn-size ${!isAvailable ? 'btn-disabled' : ''}'
                                        data-size='${size}'
                                        data-price='${sizePrice}'
                                        data-discounted='${discountedSizePrice}'
                                        ${!isAvailable ? 'disabled title="Stok habis"' : ''}>
                                        ${size} ${!isAvailable ? '(Habis)' : ''}
                                    </button> `;
                });

                function formatRupiah(num) {
                    return num.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }

                // Default display price
                let displayPrice = hasActiveDiscount ?
                    `<del>Rp ${formatRupiah(parseInt(productPrice, 10))}</del> <span class="ml-2 h5" style="color:#fc544b;font-weight:700;">Rp ${formatRupiah(parseInt(productPrice - (productPrice * discountPercentage / 100), 10))}</span>` :
                    `<span class="ml-2 h5" style="color:#fc544b;font-weight:700;">Rp ${formatRupiah(parseInt(productPrice, 10))}</span>`;

                Swal.fire({
                    title: 'Add to Cart',
                    html: `
                        <div style="background:#f5f7fa;border-radius:10px;padding:20px 10px 10px 10px;max-width:350px;margin:auto;">
                        <div class="text-center mb-2">
                            <img src='${productImage}' alt='' style='width:100px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:10px;'>
                        </div>
                        <div class="h5 font-weight-bold mb-1" style="color:#34395e;">${productName}</div>
                        <div class="mb-2" id="swal-price-display">
                            ${displayPrice}
                        </div>
                        <div class="mb-2">
                            <b>SIZE</b>
                            <br>${sizeOptions}</div>
                        <div class='mt-3 mb-2'>
                            <b>Jumlah</b><br>
                            <div class="d-flex justify-content-center align-items-center" style="gap:12px;">
                            <button type="button" class="btn btn-outline-primary btn-qty-minus" tabindex="-1"
                                style="width:36px;height:36px;border-radius:8px;font-size:22px;line-height:1;padding:0;display:flex;align-items:center;justify-content:center;border:2px solid #090969;color:#090969;background:#fff;">
                                <span style="font-weight:600;">&#8722;</span>
                            </button>
                            <span id="swal-qty-value" style="width:32px;display:inline-block;text-align:center;font-size:20px;font-weight:600;">1</span>
                            <button type="button" class="btn btn-primary btn-qty-plus" tabindex="-1"
                                style="width:36px;height:36px;border-radius:8px;font-size:22px;line-height:1;padding:0;display:flex;align-items:center;justify-content:center;background:#090969;border:2px solid #090969;color:#fff;">
                                <span style="font-weight:600;">&#43;</span>
                            </button>
                            </div>
                        </div>
                        <input type="hidden" id="swal-qty" value="1">
                        <div id='swal-size-error' class='text-danger mt-2' style='display:none;'>Please select SIZE</div>
                        </div>
                    `,
                    customClass: {
                        popup: 'p-0',
                        confirmButton: 'btn font-weight-bold swal-btn-addcart',
                        cancelButton: 'btn font-weight-bold swal-btn-cancel'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Add to Cart',
                    preConfirm: () => {
                        const selectedSize = $('.btn-size.active').data('size');
                        const qty = parseInt($('#swal-qty').val());
                        if (!selectedSize) {
                            $('#swal-size-error').show();
                            return false;
                        }
                        return {
                            size: selectedSize,
                            qty: qty
                        };
                    },
                    didOpen: () => {
                        $('.btn-size').click(function() {
                            $('.btn-size').removeClass('active');
                            $(this).addClass('active');
                            $('#swal-size-error').hide();

                            // Update price display based on selected size
                            if (hasSizePrices) {
                                const sizePrice = $(this).data('price');
                                if (sizePrice) {
                                    const formattedPrice = formatRupiah(sizePrice);

                                    if (hasActiveDiscount) {
                                        const discountedPrice = $(this).data('discounted');
                                        const formattedDiscountedPrice = formatRupiah(discountedPrice);
                                        $('#swal-price-display').html(`<del>Rp ${formattedPrice}</del> <span class="ml-2 h5" style="color:#fc544b;font-weight:700;">Rp ${formattedDiscountedPrice}</span>`);
                                    } else {
                                        $('#swal-price-display').html(`<span class="ml-2 h5" style="color:#fc544b;font-weight:700;">Rp ${formattedPrice}</span>`);
                                    }
                                }
                            }
                        });

                        let qty = 1;
                        $('.btn-qty-minus').click(function() {
                            if (qty > 1) {
                                qty--;
                                $('#swal-qty-value').text(qty);
                                $('#swal-qty').val(qty);
                            }
                        });
                        $('.btn-qty-plus').click(function() {
                            qty++;
                            $('#swal-qty-value').text(qty);
                            $('#swal-qty').val(qty);
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $.ajax({
                            url: '{{ route('cart.add') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                product_id: productId,
                                size: result.value.size,
                                qty: result.value.qty
                            },
                            success: function(res) {
                                // Update cart count in navbar if it's returned
                                if (res.cart_count) {
                                    updateCartCount(res.cart_count);
                                }

                                // Setelah add to cart sukses, ambil HTML modal
                                $.get('/cart/added-modal', {
                                    product_id: productId,
                                    size: result.value.size,
                                    qty: result.value.qty
                                }, function(modalHtml) {
                                    $('#cartAddedModal').remove();
                                    $('body').append(modalHtml);
                                    $('#cartAddedModal').modal('show');
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message ||
                                    'Gagal menambah ke cart', 'error');
                            }
                        });
                    }
                });
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

        $('<style>\
        .swal-btn-addcart {\
          background: #090969 !important;\
          color: #fff !important;\
          border-radius: 8px !important;\
          border: none !important;\
          font-weight: 700 !important;\
          font-size: 16px !important;\
          padding: 10px 28px !important;\
          margin-right: 10px;\
        }\
        .swal-btn-addcart:focus, .swal-btn-addcart:hover {\
          background: #090969 !important;\
          color: #fff !important;\
        }\
        .swal-btn-cancel {\
          background: #fff !important;\
          color: #090969 !important;\
          border: 2px solid #090969 !important;\
          border-radius: 8px !important;\
          font-weight: 700 !important;\
          font-size: 16px !important;\
          padding: 10px 28px !important;\
        }\
        .swal-btn-cancel:focus, .swal-btn-cancel:hover {\
          background: #f5f5f5 !important;\
          color: #34395e !important;\
          border: 2px solid #34395e !important;\
        }\
        </style>').appendTo('head');
    </script>
@endpush

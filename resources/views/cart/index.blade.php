@extends('layouts.landing')

@section('title', 'Urbanova - Cart')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/cart/cart.css') }}">
    <style>
        .cart-page {
            padding: 40px 0;
            background-color: #f8f9fa;
        }
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .cart-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 24px;
        }
        .cart-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .cart-table {
            margin-bottom: 0;
        }
        .cart-table th {
            background-color: #f7f7f7;
            border-top: none;
            padding: 16px 20px;
            color: #6c757d;
            font-weight: 600;
            font-size: 14px;
        }
        .cart-table td {
            vertical-align: middle;
            padding: 20px;
            border-top: 1px solid #f2f2f2;
        }
        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .product-name {
            font-weight: 600;
            color: #34395e;
            margin-left: 15px;
        }
        .product-size {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 30px;
            background-color: #f1f3ff;
            color: #090969;
            font-size: 13px;
            font-weight: 600;
        }
        .product-price {
            font-weight: 600;
            color: #090969;
        }
        .quantity-control {
            display: inline-flex;
            align-items: center;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: transparent;
            padding: 2px;
        }
        .btn-qty-minus {
            width: 32px;
            height: 32px;
            border-radius: 8px !important;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #090969;
            border: 1px solid #e4e6fc;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-qty-plus {
            width: 32px;
            height: 32px;
            border-radius: 8px !important;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #090969;
            color: #fff;
            border: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-qty:hover {
            opacity: 0.85;
        }
        .qty-input {
            width: 45px;
            border: none;
            background: transparent;
            text-align: right;
            font-weight: 600;
            color: #34395e;
            box-shadow: none !important;
            font-size: 16px;
            padding: 0;
        }
        .item-subtotal {
            font-weight: 700;
            color: #090969;
            font-size: 16px;
        }
        .btn-remove {
            border-radius: 8px;
            background-color: #fff;
            color: #fc544b;
            border: 1px solid #fc544b;
            padding: 6px 12px;
            font-size: 13px;
            transition: all 0.2s;
        }
        .btn-remove:hover {
            background-color: #fc544b;
            color: #fff;
        }
        .cart-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 24px;
        }
        .cart-summary-title {
            font-size: 18px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 20px;
            border-bottom: 1px solid #f2f2f2;
            padding-bottom: 15px;
        }
        .cart-total-label {
            font-size: 14px;
            color: #6c757d;
        }
        .cart-total-price {
            font-size: 24px;
            font-weight: 700;
            color: #090969;
            margin-bottom: 20px;
        }
        .btn-checkout {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            background: #090969;
            border: none;
            color: #fff;
            font-weight: 600;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-checkout:hover {
            background: #060645;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9,9,105,0.2);
        }
        .btn-checkout::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            top: -60px;
            left: -100px;
            transition: all 0.3s;
        }
        .btn-checkout:hover::after {
            left: 120%;
        }
        .btn-clear {
            background: #fff;
            color: #6c757d;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s;
        }
        .btn-clear:hover {
            background: #f8f9fa;
            color: #343a40;
        }
        .shipping-badge {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 10px;
            background-color: #d1fae5;
            color: #059669;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-cart-icon {
            font-size: 64px;
            color: #e3e8ef;
            margin-bottom: 16px;
        }
        .empty-cart-text {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 24px;
        }
        .btn-shop {
            background-color: #090969;
            color: #fff;
            border-radius: 8px;
            padding: 10px 24px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-shop:hover {
            background-color: #060645;
            color: #fff;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .cart-table thead {
                display: none;
            }
            .cart-table, .cart-table tbody, .cart-table tr, .cart-table td {
                display: block;
                width: 100%;
            }
            .cart-table tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #f2f2f2;
            }
            .cart-table td {
                position: relative;
                padding: 10px 20px;
                text-align: right;
                border-top: none;
            }
            .cart-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 20px;
                width: 50%;
                font-weight: 600;
                text-align: left;
                color: #6c757d;
            }
            .cart-table .d-flex {
                justify-content: flex-end;
            }
        }
    </style>
@endpush

@section('content')
<main class="cart-page">
    <div class="cart-container">
        <h2 class="cart-title">Shopping Cart</h2>

        @if(count($cart) > 0)
            <div class="shipping-badge">
                <i class="fas fa-shipping-fast mr-2"></i> Hooray! You get Free Shipping discount and will be applied on Checkout page
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-card">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $key => $item)
                                <tr class="cart-item" data-key="{{ $key }}">
                                    <td data-label="Product">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="product-image">
                                            <span class="product-name">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Size">
                                        <span class="product-size">{{ $item['size'] }}</span>
                                    </td>
                                    <td data-label="Price" class="product-price">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td data-label="Quantity">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline-block update-cart-form">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $key }}">
                                            <div class="quantity-control">
                                                <button type="button" class="btn btn-qty-minus">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" class="form-control form-control-sm qty-input">
                                                <button type="button" class="btn btn-qty-plus">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td data-label="Subtotal" class="item-subtotal">
                                        Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                    </td>
                                    <td data-label="Action">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline-block remove-cart-form">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $key }}">
                                            <button type="button" class="btn btn-remove remove-btn">
                                                <i class="fas fa-trash-alt mr-1"></i> Remove
                                            </button>
                                            <span class="remove-loading d-none spinner-border spinner-border-sm text-danger" role="status"></span>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-start mb-4">
                        <form action="{{ route('cart.clear') }}" method="POST" id="clear-cart-form">
                            @csrf
                            <button type="button" class="btn btn-clear" id="clear-cart-btn">
                                <i class="fas fa-trash mr-1"></i> Clear Cart
                            </button>
                            <span id="clear-loading" class="d-none spinner-border spinner-border-sm text-warning ml-2" role="status"></span>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h3 class="cart-summary-title">Order Summary</h3>
                        <div class="mb-2 cart-total-label">Total Items: <span class="font-weight-bold">{{ count($cart) }}</span></div>
                        <div class="cart-total-price" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-checkout">
                                <i class="fas fa-shopping-cart mr-2"></i> Checkout with Discount
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="cart-card empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="mb-3">Your cart is empty</h3>
                <p class="empty-cart-text">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('shop') }}" class="btn-shop">
                    <i class="fas fa-store mr-2"></i> Continue Shopping
                </a>
            </div>
        @endif
    </div>
</main>

@include('components.sweet-alert')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Setup quantity buttons
    $('.btn-qty-minus').click(function() {
        const form = $(this).closest('form');
        const qtyInput = form.find('input[name="qty"]');
        let qty = parseInt(qtyInput.val());
        if (qty > 1) {
            qty--;
            qtyInput.val(qty);
            updateItemQuantity(form);
        }
    });

    $('.btn-qty-plus').click(function() {
        const form = $(this).closest('form');
        const qtyInput = form.find('input[name="qty"]');
        let qty = parseInt(qtyInput.val());
        qty++;
        qtyInput.val(qty);
        updateItemQuantity(form);
    });

    // Handle manual input changes
    $('.qty-input').on('change', function() {
        const form = $(this).closest('form');
        let qty = parseInt($(this).val());

        // Validate minimum quantity
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            $(this).val(1);
        }

        updateItemQuantity(form);
    });

    function updateItemQuantity(form) {
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                const cartItem = form.closest('.cart-item');
                const qty = parseInt(form.find('input[name="qty"]').val());
                const priceText = cartItem.find('.product-price').text().replace('Rp ', '').replace(/\./g, '');
                const price = parseInt(priceText);
                const newSubtotal = qty * price;

                // Update subtotal display
                cartItem.find('.item-subtotal').text('Rp ' + formatNumber(newSubtotal));

                // Update total and cart count
                updateCartTotal();
            },
            error: function(xhr) {
                showError(xhr.responseJSON?.message || 'Failed to update cart');
            }
        });
    }

    // Remove cart item with SweetAlert
    $('.remove-btn').click(function() {
        const form = $(this).closest('form');
        const cartItem = form.closest('.cart-item');
        const removeBtn = form.find('.remove-btn');
        const loading = form.find('.remove-loading');
        const productName = cartItem.find('.product-name').text().trim();

        Swal.fire({
            title: 'Remove Item',
            text: "Are you sure you want to remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#090969',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Yes, remove it!',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancel',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                removeBtn.addClass('d-none');
                loading.removeClass('d-none');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        // Remove the row from the table with animation
                        cartItem.fadeOut(300, function() {
                            $(this).remove();

                            // If cart count is returned from server, use it
                            if (res.cart_count !== undefined) {
                                updateCartCount(res.cart_count);

                                // Update item count in the total section
                                $('.cart-total-label').html('Total Items: <span class="font-weight-bold">' + res.cart_count + '</span>');

                                // If cart is empty, reload the page to show empty cart message
                                if (res.empty) {
                                    Swal.fire({
                                        title: 'Cart is now empty',
                                        text: 'Your cart has been cleared.',
                                        icon: 'info',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                    return;
                                }

                                // Show success message
                                showSuccess('Item removed from cart');
                            } else {
                                // Calculate count manually if not provided
                                updateCartTotal();
                            }

                            // Calculate new total if items remain
                            updateCartTotal();
                        });
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        removeBtn.removeClass('d-none');
                        loading.addClass('d-none');

                        showError(xhr.responseJSON?.message || 'Failed to remove item from cart');
                    }
                });
            }
        });
    });

    // Clear cart with SweetAlert
    $('#clear-cart-btn').click(function() {
        const form = $('#clear-cart-form');
        const clearBtn = $(this);
        const loading = $('#clear-loading');

        Swal.fire({
            title: 'Clear Cart',
            text: "Are you sure you want to remove all items from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#090969',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Yes, clear it!',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancel',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                clearBtn.prop('disabled', true);
                loading.removeClass('d-none');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        // Hide loading indicator
                        clearBtn.prop('disabled', false);
                        loading.addClass('d-none');

                        Swal.fire({
                            title: 'Cart Cleared',
                            text: 'Your cart has been cleared successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Hide loading indicator
                        clearBtn.prop('disabled', false);
                        loading.addClass('d-none');

                        showError(xhr.responseJSON?.message || 'Failed to clear cart');
                    }
                });
            }
        });
    });

    // Function to update cart total and count
    function updateCartTotal() {
        // Calculate new total
        let total = 0;
        $('.cart-item').each(function() {
            const text = $(this).find('.item-subtotal').text().replace('Rp ', '').replace(/\./g, '');
            total += parseInt(text);
        });

        // Update total display
        $('#cart-total').text('Rp ' + formatNumber(total));

        // Update cart count in navbar
        const count = $('.cart-item').length;
        updateCartCount(count);

        // Update item count display
        $('.cart-total-label').html('Total Items: <span class="font-weight-bold">' + count + '</span>');
    }

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

    // Helper function to format numbers with thousands separators
    function formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endpush
@endsection

@extends('layouts.landing')

@section('title', 'Urbanova - Checkout')

@push('css')
    <style>
        .checkout-page {
            padding: 40px 0;
            background-color: #f8f9fa;
        }
        .checkout-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .checkout-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 24px;
        }
        .checkout-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
            padding: 24px;
        }
        .checkout-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 20px;
            border-bottom: 1px solid #f2f2f2;
            padding-bottom: 15px;
        }
        .form-group label {
            font-weight: 600;
            color: #34395e;
            font-size: 14px;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #e4e6fc;
            padding: 0 15px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #090969;
            box-shadow: 0 0 0 0.2rem rgba(9, 9, 105, 0.25);
        }
        textarea.form-control {
            height: auto;
            padding: 15px;
        }
        .payment-method {
            padding: 15px;
            border: 1px solid #e4e6fc;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method:hover {
            border-color: #090969;
            background-color: #f1f3ff;
        }
        .payment-method.active {
            border-color: #090969;
            background-color: #f1f3ff;
        }
        .payment-method-title {
            font-weight: 600;
            color: #34395e;
            margin-bottom: 5px;
        }
        .payment-method-description {
            font-size: 13px;
            color: #6c757d;
        }
        .order-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 24px;
        }
        .order-summary-title {
            font-size: 18px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 20px;
            border-bottom: 1px solid #f2f2f2;
            padding-bottom: 15px;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        .order-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .order-item-details {
            flex-grow: 1;
        }
        .order-item-name {
            font-weight: 600;
            color: #34395e;
            margin-bottom: 5px;
        }
        .order-item-meta {
            font-size: 13px;
            color: #6c757d;
        }
        .order-item-price {
            font-weight: 600;
            color: #090969;
            text-align: right;
            width: 100px;
        }
        .order-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        .order-summary-row:last-child {
            border-bottom: none;
        }
        .order-summary-label {
            font-size: 14px;
            color: #6c757d;
        }
        .order-summary-value {
            font-weight: 600;
            color: #34395e;
        }
        .order-total {
            font-size: 18px;
            font-weight: 700;
            color: #090969;
        }
        .btn-place-order {
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
            margin-top: 20px;
        }
        .btn-place-order:hover {
            background: #060645;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9,9,105,0.2);
        }
        .btn-place-order::after {
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
        .btn-place-order:hover::after {
            left: 120%;
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
        @media (max-width: 768px) {
            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-item-image {
                margin-bottom: 10px;
            }
            .order-item-price {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }
        }
    </style>
@endpush

@section('content')
<main class="checkout-page">
    <div class="checkout-container">
        <h2 class="checkout-title">Checkout</h2>

        <div class="shipping-badge">
            <i class="fas fa-shipping-fast mr-2"></i> Gratis Ongkir untuk pesanan Anda!
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <h3 class="checkout-section-title">Informasi Pengiriman</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="full_name">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $user->name ?? '') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email">Alamat Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">Nomor Telepon *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="postal_code">Kode Pos *</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">Alamat *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="city">Kota *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">Catatan Pesanan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h3 class="checkout-section-title">Metode Pembayaran</h3>

                        <div class="payment-method active" data-method="bank_transfer">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" id="payment_bank_transfer" name="payment_method" value="bank_transfer" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="payment_bank_transfer"></label>
                                </div>
                                <div>
                                    <h5 class="payment-method-title">Transfer Bank</h5>
                                    <p class="payment-method-description">Lakukan pembayaran langsung ke rekening bank kami. Pesanan Anda akan dikirim setelah kami menerima pembayaran.</p>
                                </div>
                            </div>
                        </div>

                        @error('payment_method')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order-summary">
                        <h3 class="order-summary-title">Ringkasan Pesanan</h3>

                        <div class="order-items">
                            @foreach($cart as $key => $item)
                            <div class="order-item">
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="order-item-image">
                                <div class="order-item-details">
                                    <h5 class="order-item-name">{{ $item['name'] }}</h5>
                                    <div class="order-item-meta">
                                        <span class="mr-2">Ukuran: {{ $item['size'] }}</span>
                                        @if($item['color_name'])
                                        <span>Warna: {{ $item['color_name'] }}</span>
                                        @endif
                                    </div>
                                    <div class="order-item-meta mt-1">
                                        <span>{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="order-item-price">
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="order-summary-calculations mt-3">
                            <div class="order-summary-row">
                                <div class="order-summary-label">Subtotal</div>
                                <div class="order-summary-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                            <div class="order-summary-row">
                                <div class="order-summary-label">Pengiriman</div>
                                <div class="order-summary-value text-success">Gratis</div>
                            </div>
                            <div class="order-summary-row">
                                <div class="order-summary-label">Total</div>
                                <div class="order-summary-value order-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-place-order">
                            <i class="fas fa-lock mr-2"></i> Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@include('components.sweet-alert')

@push('scripts')
<script>
$(document).ready(function() {
    // Handle payment method selection
    $('.payment-method').click(function() {
        $('.payment-method').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    // Form validation
    $('#checkout-form').submit(function(e) {
        let isValid = true;

        // Check required fields
        $(this).find('input[required], textarea[required]').each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                title: 'Error Form',
                text: 'Mohon isi semua field yang diperlukan.',
                icon: 'error',
                confirmButtonColor: '#090969'
            });

            // Scroll to first invalid input
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    });
});
</script>
@endpush
@endsection

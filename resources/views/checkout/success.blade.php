@extends('layouts.landing')

@section('title', 'Urbanova - Order Success')

@push('css')
    <style>
        .success-page {
            padding: 60px 0;
            background-color: #f8f9fa;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .success-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .success-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            padding: 40px;
            text-align: center;
        }
        .success-icon {
            width: 90px;
            height: 90px;
            background-color: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .success-icon i {
            font-size: 40px;
            color: #059669;
        }
        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 16px;
        }
        .success-message {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-continue {
            background-color: #090969;
            color: #fff;
            border-radius: 8px;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-continue:hover {
            background-color: #060645;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9,9,105,0.2);
        }
        .btn-view-orders {
            background-color: transparent;
            color: #090969;
            border: 1px solid #090969;
            border-radius: 8px;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
            margin-right: 10px;
            margin-top: 10px;
        }
        .btn-view-orders:hover {
            background-color: #f1f3ff;
            color: #060645;
            border-color: #060645;
        }
        .payment-info {
            background-color: #f1f3ff;
            border-radius: 10px;
            padding: 20px;
            margin: 30px auto;
            max-width: 500px;
            text-align: left;
        }
        .payment-info-title {
            font-size: 18px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .payment-info-title i {
            color: #090969;
            margin-right: 10px;
        }
        .bank-details {
            border-top: 1px solid #e4e6fc;
            padding-top: 15px;
            margin-top: 15px;
        }
        .bank-item {
            margin-bottom: 8px;
            display: flex;
        }
        .bank-label {
            font-weight: 600;
            color: #6c757d;
            width: 120px;
        }
        .bank-value {
            font-weight: 600;
            color: #34395e;
        }
        .order-number {
            background-color: #f1f3ff;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            color: #090969;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
@endpush

@section('content')
<main class="success-page">
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="success-title">Order Placed Successfully!</h2>
            <div class="order-number">
                <i class="fas fa-receipt mr-2"></i> Order #{{ $orderNumber }}
            </div>
            <p class="success-message">
                Thank you for your purchase. Your order has been received and is now being processed.
                You will receive an email confirmation shortly.
            </p>

            <div class="payment-info">
                <div class="payment-info-title">
                    <i class="fas fa-university"></i> Bank Transfer Payment Instructions
                </div>
                <p>Please complete your payment by transferring the total amount to our bank account below:</p>

                <div class="bank-details">
                    <div class="bank-item">
                        <span class="bank-label">Bank Name:</span>
                        <span class="bank-value">Bank Central Asia (BCA)</span>
                    </div>
                    <div class="bank-item">
                        <span class="bank-label">Account Name:</span>
                        <span class="bank-value">PT Urbanova Indonesia</span>
                    </div>
                    <div class="bank-item">
                        <span class="bank-label">Account Number:</span>
                        <span class="bank-value">8720145678</span>
                    </div>
                </div>

                <p class="mt-3 mb-0">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i> After making the payment, please send the payment confirmation to our WhatsApp at +62 812-3456-7890 or email to payment@urbanova.id
                    </small>
                </p>
            </div>

            <div class="mt-4">
                <a href="{{ route('landing.orders') }}" class="btn-view-orders">
                    <i class="fas fa-list-ul mr-2"></i> View My Orders
                </a>
                <a href="{{ route('payment.form', $orderNumber) }}" class="btn-view-orders">
                    <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                </a>
                <a href="{{ route('shop') }}" class="btn-continue">
                    <i class="fas fa-store mr-2"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</main>
@endsection

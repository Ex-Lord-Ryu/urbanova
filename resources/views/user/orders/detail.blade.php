@extends('layouts.landing')

@section('title', 'Detail Pesanan')

@push('css')
    <style>
        /* Main container styling */
        .order-detail-page {
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 70vh;
        }

        .order-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .order-detail-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-detail-title .back-link {
            font-size: 16px;
            color: #090969;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .order-detail-title .back-link i {
            margin-right: 5px;
        }

        /* Card styling */
        .order-detail-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
            border: none;
        }

        .order-detail-header {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: white;
            padding: 20px;
            position: relative;
        }

        .order-detail-body {
            padding: 20px;
        }

        /* Order info styling */
        .order-number {
            font-weight: 700;
            font-size: 20px;
            color: #fff;
            margin-bottom: 5px;
        }

        .order-date {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .order-status {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Info section styling */
        .info-section {
            margin-bottom: 30px;
        }

        .info-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f2f2f2;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .info-value {
            color: #34395e;
            font-size: 16px;
        }

        /* Badge styling */
        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-info {
            background-color: #17a2b8;
            color: #fff;
        }

        .badge-primary {
            background-color: #007bff;
            color: #fff;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        /* Order items table styling */
        .order-items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .order-items-table th {
            background-color: #f7f7f7;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
        }

        .order-items-table td {
            padding: 15px;
            border-top: 1px solid #f2f2f2;
            vertical-align: middle;
        }

        .order-items-table tr:hover {
            background-color: #f9f9f9;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .product-name {
            font-weight: 600;
            color: #34395e;
        }

        .product-meta {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }

        .product-price {
            font-weight: 600;
            color: #090969;
        }

        /* Summary section styling */
        .order-summary {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            color: #6c757d;
            font-weight: 600;
        }

        .summary-value {
            font-weight: 700;
            color: #34395e;
        }

        .total-row {
            font-size: 18px;
        }

        .total-row .summary-value {
            color: #090969;
        }

        /* Bank details section */
        .bank-details {
            background-color: #f1f3ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .bank-details-title {
            font-size: 16px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .bank-details-title i {
            color: #090969;
            margin-right: 10px;
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

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .order-status {
                position: static;
                margin-top: 10px;
                display: block;
            }

            .order-detail-title {
                font-size: 24px;
                flex-direction: column;
                align-items: flex-start;
            }

            .order-detail-title .back-link {
                margin-bottom: 10px;
            }

            .order-items-table thead {
                display: none;
            }

            .order-items-table, .order-items-table tbody, .order-items-table tr, .order-items-table td {
                display: block;
                width: 100%;
                text-align: right;
            }

            .order-items-table tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #f2f2f2;
            }

            .order-items-table td {
                position: relative;
                padding: 10px 15px;
                border-top: none;
            }

            .order-items-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 50%;
                font-weight: 600;
                text-align: left;
                color: #6c757d;
            }

            .order-items-table .d-flex {
                justify-content: flex-end;
            }
        }
    </style>
@endpush

@section('content')
    <main class="order-detail-page">
        <div class="order-detail-container">
            <div class="order-detail-title">
                <a href="{{ route('landing.orders') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                <h2>Detail Pesanan</h2>
            </div>

            <div class="order-detail-card">
                <div class="order-detail-header">
                    <div class="order-number">Pesanan #{{ $order->order_number }}</div>
                    <div class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</div>
                    <div class="order-status">
                        {!! $order->status_badge !!}
                    </div>
                </div>

                <div class="order-detail-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h3 class="info-section-title">Informasi Pengiriman</h3>
                                <div class="info-item">
                                    <div class="info-label">Nama Lengkap</div>
                                    <div class="info-value">{{ $order->full_name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $order->email }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Nomor Telepon</div>
                                    <div class="info-value">{{ $order->phone }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Alamat</div>
                                    <div class="info-value">{{ $order->address }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Kota</div>
                                            <div class="info-value">{{ $order->city }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Kode Pos</div>
                                            <div class="info-value">{{ $order->postal_code }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($order->tracking_number && $order->order_status != 'pending')
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="info-item">
                                            <div class="info-label">Pengiriman</div>
                                            <div class="info-value">
                                                <div><strong>{{ $order->courier_name }}</strong></div>
                                                <div class="mt-1">
                                                    <span class="badge badge-primary">{{ $order->tracking_number }}</span>
                                                    <a href="https://cekresi.com/?noresi={{ $order->tracking_number }}" target="_blank" class="btn btn-sm btn-info ml-2">
                                                        <i class="fas fa-truck"></i> Lacak Pengiriman
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-section">
                                <h3 class="info-section-title">Informasi Pembayaran</h3>
                                <div class="info-item">
                                    <div class="info-label">Metode Pembayaran</div>
                                    <div class="info-value">
                                        @if($order->payment_method == 'bank_transfer')
                                            <i class="fas fa-university mr-2"></i> Transfer Bank
                                        @elseif($order->payment_method == 'cod')
                                            <i class="fas fa-money-bill-wave mr-2"></i> Cash on Delivery
                                        @elseif($order->payment_method == 'credit_card')
                                            <i class="fas fa-credit-card mr-2"></i> Kartu Kredit
                                        @else
                                            {{ $order->payment_method }}
                                        @endif
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Status Pembayaran</div>
                                    <div class="info-value">
                                        {!! $order->payment_badge !!}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Status Pesanan</div>
                                    <div class="info-value">
                                        {!! $order->status_badge !!}
                                    </div>
                                </div>

                                @if($order->notes)
                                <div class="info-item">
                                    <div class="info-label">Catatan</div>
                                    <div class="info-value">{{ $order->notes }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3 class="info-section-title">Item Pesanan</h3>
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th width="60">Gambar</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td data-label="Gambar">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}" class="product-image">
                                    </td>
                                    <td data-label="Produk">
                                        <div class="product-name">{{ $item->product_name }}</div>
                                        <div class="product-meta">
                                            <span class="mr-2">Ukuran: {{ $item->size }}</span>
                                            @if($item->color_name)
                                            <span>
                                                Warna:
                                                <span class="color-swatch mr-1" style="background-color: #{{ $item->color_hex }}; width: 12px; height: 12px; border-radius: 50%; display: inline-block; border: 1px solid #ddd;"></span>
                                                {{ $item->color_name }}
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td data-label="Harga" class="product-price">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td data-label="Jumlah">
                                        {{ $item->quantity }}
                                    </td>
                                    <td data-label="Subtotal" class="text-right product-price">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @if($order->payment_method == 'bank_transfer')
                            <div class="bank-details">
                                <div class="bank-details-title">
                                    <i class="fas fa-university"></i> Informasi Bank
                                </div>
                                <p>Silakan transfer pembayaran ke rekening berikut:</p>

                                <div class="bank-item">
                                    <span class="bank-label">Bank:</span>
                                    <span class="bank-value">Bank Central Asia (BCA)</span>
                                </div>
                                <div class="bank-item">
                                    <span class="bank-label">Atas Nama:</span>
                                    <span class="bank-value">PT Urbanova Indonesia</span>
                                </div>
                                <div class="bank-item">
                                    <span class="bank-label">No. Rekening:</span>
                                    <span class="bank-value">8720145678</span>
                                </div>

                                @if($order->payment_status == 'pending')
                                <div class="mt-3">
                                    <a href="{{ route('payment.form', $order->order_number) }}" class="btn btn-primary">
                                        <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                                    </a>
                                </div>
                                @elseif($order->payment_proof && $order->payment_status == 'verification')
                                <div class="mt-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i> Bukti pembayaran Anda sedang diverifikasi.
                                    </div>
                                    <div class="mt-3">
                                        <p><strong>Bukti Pembayaran:</strong></p>
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 300px; border-radius: 8px; border: 1px solid #eee;">
                                    </div>
                                </div>
                                @endif

                                <p class="mt-3 mb-0">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Setelah melakukan pembayaran, silakan kirim konfirmasi pembayaran ke WhatsApp +62 812-3456-7890 atau email ke payment@urbanova.id
                                    </small>
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <div class="summary-label">Subtotal</div>
                                    <div class="summary-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">Biaya Pengiriman</div>
                                    <div class="summary-value text-success">Gratis</div>
                                </div>
                                <div class="summary-row total-row">
                                    <div class="summary-label">Total</div>
                                    <div class="summary-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

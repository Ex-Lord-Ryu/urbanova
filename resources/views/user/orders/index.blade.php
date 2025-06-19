@extends('layouts.landing')

@section('title', 'Pesanan Saya')

@push('css')
    <style>
        /* Main container styling */
        .orders-page {
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 70vh;
        }

        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .orders-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 24px;
        }

        /* Card styling */
        .orders-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .orders-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .orders-card-header {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: white;
            padding: 15px 20px;
            position: relative;
        }

        .orders-card-body {
            padding: 20px;
        }

        /* Order item styling */
        .order-item {
            border-bottom: 1px solid #f2f2f2;
            padding: 15px 0;
            transition: transform 0.2s;
        }

        .order-item:hover {
            transform: translateX(5px);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-number {
            font-weight: 700;
            font-size: 18px;
            color: #fff;
            margin-bottom: 5px;
        }

        .order-date {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .order-status {
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .order-amount {
            font-weight: 700;
            color: #090969;
            font-size: 18px;
        }

        .order-items-count {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
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

        /* Button styling */
        .btn-view-order {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: #fff;
            border-radius: 50px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s;
            border: none;
            display: inline-block;
            text-decoration: none;
        }

        .btn-view-order:hover {
            background: linear-gradient(135deg, #080851 0%, #13138a 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(9, 9, 105, 0.3);
            color: #fff;
        }

        .btn-view-order i {
            margin-right: 5px;
        }

        /* Empty orders styling */
        .empty-orders {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-orders-icon {
            font-size: 64px;
            color: #e3e8ef;
            margin-bottom: 20px;
        }

        .empty-orders-text {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .btn-shop {
            background-color: #090969;
            color: #fff;
            border-radius: 8px;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-shop:hover {
            background-color: #060645;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9,9,105,0.2);
        }

        /* Pagination styling */
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link {
            color: #090969;
            border-radius: 5px;
            margin: 0 3px;
            border: 1px solid #e4e6fc;
            padding: 8px 16px;
        }

        .page-item.active .page-link {
            background-color: #090969;
            border-color: #090969;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .order-status {
                position: static;
                margin-top: 10px;
                display: block;
            }

            .orders-title {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="orders-page">
        <div class="orders-container">
            <h2 class="orders-title">Pesanan Saya</h2>

            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if(count($orders) > 0)
                @foreach($orders as $order)
                    <div class="orders-card">
                        <div class="orders-card-header">
                            <div class="order-number">Pesanan #{{ $order->order_number }}</div>
                            <div class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</div>
                            <div class="order-status">
                                {!! $order->status_badge !!}
                            </div>
                        </div>
                        <div class="orders-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="order-items-count">
                                        <i class="fas fa-shopping-bag mr-2"></i>
                                        {{ $order->items->count() }} item(s)
                                    </div>
                                    <div class="order-amount">
                                        Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                    @if($order->tracking_number && $order->order_status == 'shipped')
                                    <div class="order-tracking mt-2">
                                        <i class="fas fa-truck mr-2"></i>
                                        <span class="font-weight-bold">{{ $order->courier_name }}:</span>
                                        <span class="badge badge-info">{{ $order->tracking_number }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                                        <div class="mr-3">
                                            <div class="text-muted mb-1">Metode Pembayaran</div>
                                            <div class="font-weight-bold">
                                                @if($order->payment_method == 'bank_transfer')
                                                    <i class="fas fa-university mr-1"></i> Transfer Bank
                                                @elseif($order->payment_method == 'cod')
                                                    <i class="fas fa-money-bill-wave mr-1"></i> Cash on Delivery
                                                @elseif($order->payment_method == 'credit_card')
                                                    <i class="fas fa-credit-card mr-1"></i> Kartu Kredit
                                                @else
                                                    {{ $order->payment_method }}
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-muted mb-1">Status Pembayaran</div>
                                            <div>
                                                {!! $order->payment_badge !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-3">
                                <a href="{{ route('landing.orders.detail', $order->order_number) }}" class="btn-view-order">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="orders-card empty-orders">
                    <div class="empty-orders-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="mb-3">Belum ada pesanan</h3>
                    <p class="empty-orders-text">Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                    <a href="{{ route('shop') }}" class="btn-shop">
                        <i class="fas fa-store mr-2"></i> Belanja Sekarang
                    </a>
                </div>
            @endif
        </div>
    </main>
@endsection

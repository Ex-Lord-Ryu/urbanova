@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/order-payment.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Pesanan #{{ $order->order_number }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manajemen Order</a></div>
                <div class="breadcrumb-item">Detail Pesanan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="back-button mb-3">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-icon btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span class="ml-2">Kembali ke Daftar Pesanan</span>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td width="40%"><strong>Tanggal</strong></td>
                                            <td>: {{ $order->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Pelanggan</strong></td>
                                            <td>: {{ $order->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>: {{ $order->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Telepon</strong></td>
                                            <td>: {{ $order->phone }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td width="40%"><strong>Status Pesanan</strong></td>
                                            <td>: {!! $order->status_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Pembayaran</strong></td>
                                            <td>: {!! $order->payment_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Metode Pembayaran</strong></td>
                                            <td>: {{ $order->payment_method }}</td>
                                        </tr>
                                        @if($order->tracking_number)
                                        <tr>
                                            <td><strong>Kurir</strong></td>
                                            <td>: {{ $order->courier_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor Resi</strong></td>
                                            <td>: {{ $order->tracking_number }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td>: <strong>Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title mt-0">Alamat Pengiriman</div>
                                    <p>{{ $order->address }}, {{ $order->city }}, {{ $order->postal_code }}</p>
                                </div>
                            </div>

                            @if ($order->notes)
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="section-title mt-0">Catatan</div>
                                        <p>{{ $order->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Item Pesanan</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Warna</th>
                                            <th class="text-center">Ukuran</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="product-item">
                                                        @if ($item->image)
                                                            <div class="product-item-image">
                                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                                    width="50" height="50" class="rounded">
                                                            </div>
                                                        @endif
                                                        <div class="product-item-info">
                                                            {{ $item->product_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="color-badge">
                                                        <span class="badge-color"
                                                            style="background-color: #{{ $item->color_hex }}"></span>
                                                        {{ $item->color_name }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="size-badge">
                                                        {{ $item->size }}
                                                    </div>
                                                </td>
                                                <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                            <td class="text-right"><strong>Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Status Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Status Pesanan</label>
                                    <select name="order_status" class="form-control">
                                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="processing"
                                            {{ $order->order_status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                                            Dikirim</option>
                                        <option value="delivered"
                                            {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled"
                                            {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Update Status Pesanan</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Update Status Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Status Pembayaran</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="pending"
                                            {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="verification"
                                            {{ $order->payment_status == 'verification' ? 'selected' : '' }}>Verifikasi
                                        </option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                            Dibayar</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                            Gagal</option>
                                        <option value="refunded"
                                            {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Dikembalikan
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Update Status Pembayaran</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Nomor Resi Pengiriman</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.update-tracking', $order) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Pilih Kurir</label>
                                    <select name="courier_name" class="form-control" required>
                                        <option value="" {{ !$order->courier_name ? 'selected' : '' }}>-- Pilih Kurir --</option>
                                        @php
                                            $availableCouriers = \App\Models\Setting::get('available_couriers', [
                                                ['name' => 'JNE', 'code' => 'jne', 'active' => true],
                                                ['name' => 'J&T Express', 'code' => 'jnt', 'active' => true],
                                                ['name' => 'SiCepat', 'code' => 'sicepat', 'active' => true],
                                                ['name' => 'Pos Indonesia', 'code' => 'pos', 'active' => true],
                                            ]);
                                        @endphp

                                        @foreach($availableCouriers as $courier)
                                            @if(isset($courier['active']) && $courier['active'])
                                                <option value="{{ $courier['name'] }}" {{ $order->courier_name == $courier['name'] ? 'selected' : '' }}>
                                                    {{ $courier['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nomor Resi</label>
                                    <input type="text" name="tracking_number" class="form-control" value="{{ $order->tracking_number }}" placeholder="Masukkan nomor resi pengiriman" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Update Informasi Pengiriman</button>
                            </form>
                        </div>
                    </div>

                    @if ($order->payment_proof)
                        <div class="card">
                            <div class="card-header">
                                <h4>Bukti Pembayaran</h4>
                            </div>
                            <div class="card-body">
                                <div class="payment-proof-container">
                                    <img id="paymentProofImage" src="{{ asset('storage/' . $order->payment_proof) }}"
                                        alt="Bukti Pembayaran" class="payment-proof-image" onclick="openImageModal()">
                                </div>

                                <div class="action-buttons-container">
                                    <a href="{{ route('admin.payments.download', $order) }}"
                                        class="btn btn-info btn-block">
                                        <i class="fas fa-download"></i> Unduh Bukti Pembayaran
                                    </a>

                                    @if ($order->payment_status == 'verification')
                                        <div class="d-flex mt-3">
                                            <a href="{{ route('admin.payments.verify', $order) }}"
                                                class="btn btn-success btn-block mr-2"
                                                onclick="event.preventDefault(); document.getElementById('verify-form-{{ $order->id }}').submit();">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </a>
                                            <form id="verify-form-{{ $order->id }}"
                                                action="{{ route('admin.payments.verify', $order) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>

                                            <a href="{{ route('admin.payments.reject', $order) }}"
                                                class="btn btn-danger btn-block"
                                                onclick="event.preventDefault(); document.getElementById('reject-form-{{ $order->id }}').submit();">
                                                <i class="fas fa-times"></i> Tolak
                                            </a>
                                            <form id="reject-form-{{ $order->id }}"
                                                action="{{ route('admin.payments.reject', $order) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk tampilan gambar fullscreen -->
    @if ($order->payment_proof)
        <div class="modal fade fullscreen-modal" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" id="fullscreenImage">
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        function openImageModal() {
            $('#imageModal').modal('show');
        }

        $(document).ready(function() {
            // Tambahkan tombol close untuk modal
            $('.fullscreen-modal').on('click', function(e) {
                if ($(e.target).hasClass('fullscreen-modal') || $(e.target).hasClass('modal-body')) {
                    $('#imageModal').modal('hide');
                }
            });
        });
    </script>
@endpush

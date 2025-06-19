@extends('layouts.app')

@section('title', 'Detail Pembayaran - Admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/order-payment.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Bukti Pembayaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Verifikasi Pembayaran</a></div>
                <div class="breadcrumb-item">Detail Pembayaran</div>
            </div>
        </div>

        <div class="section-body">
            <div class="back-button mb-3">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-icon btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span class="ml-2">Kembali ke Daftar Pembayaran</span>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pembayaran Pesanan #{{ $order->order_number }}</h4>
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
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td>: <strong>Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Bukti Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="payment-proof-container">
                                <img id="paymentProofImage"
                                    src="{{ asset('storage/' . $order->payment_proof) }}"
                                    alt="Bukti Pembayaran"
                                    class="payment-proof-image"
                                    onclick="openImageModal()">
                            </div>

                            <div class="action-buttons-container">
                                <a href="{{ route('admin.payments.download', $order) }}" class="btn btn-info btn-block">
                                    <i class="fas fa-download"></i> Unduh Bukti Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Aksi Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if ($order->payment_status == 'verification')
                                    <form action="{{ route('admin.payments.verify', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-block mb-3">
                                            <i class="fas fa-check"></i> Verifikasi Pembayaran
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.payments.reject', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-times"></i> Tolak Pembayaran
                                        </button>
                                    </form>
                                @else
                                    @if ($order->payment_status == 'paid')
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> Pembayaran ini sudah diverifikasi.
                                        </div>
                                    @elseif($order->payment_status == 'failed')
                                        <div class="alert alert-danger">
                                            <i class="fas fa-times-circle"></i> Pembayaran ini sudah ditolak.
                                        </div>
                                    @endif

                                    <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Ubah Status Pembayaran</label>
                                            <select name="payment_status" class="form-control">
                                                <option value="verification"
                                                    {{ $order->payment_status == 'verification' ? 'selected' : '' }}>
                                                    Verifikasi</option>
                                                <option value="paid"
                                                    {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Dibayar
                                                </option>
                                                <option value="failed"
                                                    {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Gagal
                                                </option>
                                                <option value="refunded"
                                                    {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                                    Dikembalikan</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-edit"></i> Update Status
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Item Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($order->items as $item)
                                    <li class="list-group-item">
                                        <div class="product-item">
                                            @if ($item->image)
                                                <div class="product-item-image">
                                                    <img src="{{ asset('storage/' . $item->image) }}" width="50"
                                                        height="50" class="rounded">
                                                </div>
                                            @endif
                                            <div class="product-item-info">
                                                <h6 class="product-name">{{ $item->product_name }}</h6>
                                                <div class="small">
                                                    @if ($item->color_name)
                                                        <div class="color-badge">
                                                            <span class="badge-color" style="background-color: #{{ $item->color_hex }}"></span>
                                                            {{ $item->color_name }}
                                                        </div>
                                                    @endif
                                                    @if ($item->size)
                                                        <div class="size-badge">
                                                            {{ $item->size }}
                                                        </div>
                                                    @endif
                                                    <span class="ml-2">{{ $item->quantity }} x Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 text-right">
                                <strong>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk tampilan gambar fullscreen -->
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

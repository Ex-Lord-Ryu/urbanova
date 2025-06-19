@extends('layouts.app')

@section('title', 'Manajemen Order - Admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/order-payment.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Order</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Manajemen Order</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Semua Pesanan</h2>
            <p class="section-lead">
                Kelola semua pesanan pelanggan pada halaman ini.
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Pesanan</h4>
                        </div>
                        <div class="card-body">
                            <div class="filter-area mb-3">
                                <form action="{{ route('admin.orders.index') }}" method="GET" class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Pesanan</label>
                                            <select name="order_status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="pending"
                                                    {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="processing"
                                                    {{ request('order_status') == 'processing' ? 'selected' : '' }}>
                                                    Processing</option>
                                                <option value="shipped"
                                                    {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                                </option>
                                                <option value="delivered"
                                                    {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Selesai
                                                </option>
                                                <option value="cancelled"
                                                    {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>
                                                    Dibatalkan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Pembayaran</label>
                                            <select name="payment_status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="pending"
                                                    {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Menunggu
                                                </option>
                                                <option value="verification"
                                                    {{ request('payment_status') == 'verification' ? 'selected' : '' }}>
                                                    Verifikasi</option>
                                                <option value="paid"
                                                    {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar
                                                </option>
                                                <option value="failed"
                                                    {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal
                                                </option>
                                                <option value="refunded"
                                                    {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                                    Dikembalikan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pencarian</label>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="No. Order, nama atau email" value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No. Order</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Pembayaran</th>
                                            <th>No. Resi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->full_name }}<br><small
                                                        class="text-muted">{{ $order->email }}</small></td>
                                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                                <td>{!! $order->status_badge !!}</td>
                                                <td>{!! $order->payment_badge !!}</td>
                                                <td>
                                                    @if($order->tracking_number)
                                                        <span class="badge badge-primary">{{ $order->tracking_number }}</span>
                                                        <br>
                                                        <small class="text-muted mt-1 d-inline-block">{{ $order->courier_name }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#deleteModal{{ $order->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data pesanan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @foreach ($orders as $order)
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Pesanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus pesanan #{{ $order->order_number }}? Tindakan ini tidak dapat
                        dibatalkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

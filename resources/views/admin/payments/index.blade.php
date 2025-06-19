@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/order-payment.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Verifikasi Pembayaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Verifikasi Pembayaran</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Pembayaran Masuk</h2>
        <p class="section-lead">
            Kelola dan verifikasi bukti pembayaran dari pelanggan pada halaman ini.
        </p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Bukti Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="filter-area mb-3">
                            <form action="{{ route('admin.payments.index') }}" method="GET" class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Status Pembayaran</label>
                                        <select name="payment_status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="verification" {{ request('payment_status') == 'verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Pencarian</label>
                                        <input type="text" name="search" class="form-control" placeholder="No. Order, nama atau email" value="{{ request('search') }}">
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
                                        <th>Status Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->full_name }}<br><small class="text-muted">{{ $order->email }}</small></td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>{!! $order->payment_badge !!}</td>
                                        <td class="action-buttons">
                                            <a href="{{ route('admin.payments.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            @if($order->payment_status == 'verification')
                                            <a href="{{ route('admin.payments.verify', $order) }}" class="btn btn-sm btn-success"
                                               onclick="event.preventDefault(); document.getElementById('verify-payment-{{ $order->id }}').submit();">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </a>
                                            <form id="verify-payment-{{ $order->id }}" action="{{ route('admin.payments.verify', $order) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data pembayaran</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

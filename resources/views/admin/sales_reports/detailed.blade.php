@extends('layouts.app')

@section('title', 'Laporan Detail Penjualan')

@push('css')
    <style>
        /*main-content*/
        .main-content {
            margin-left: 150px;
            width: calc(100% - 150px);
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Detail Penjualan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/sales-reports') }}">Laporan Penjualan</a></div>
                <div class="breadcrumb-item">Detail Penjualan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Periode Laporan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/sales-reports/detailed') }}" method="GET">
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="start_date">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ $start_date->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ $end_date->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Pesanan</h4>
                            <div class="card-header-action">
                                <a href="{{ url('admin/sales-reports/export-detailed') }}?start_date={{ $start_date->format('Y-m-d') }}&end_date={{ $end_date->format('Y-m-d') }}"
                                   class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                                <td>
                                                    @foreach($order->items as $item)
                                                        <div>{{ $item->product_name }}
                                                            @if($item->size)
                                                                ({{ $item->size }})
                                                            @endif
                                                            @if($item->color_name)
                                                                - {{ $item->color_name }}
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($order->items as $item)
                                                        <div>{{ $item->quantity }}</div>
                                                    @endforeach
                                                </td>
                                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $order->order_status == 'delivered' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data pesanan pada periode ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

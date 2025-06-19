@extends('layouts.app')

@section('title', 'Laporan Penjualan')

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
            <h1>Laporan Penjualan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan Penjualan</div>
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
                            <form action="{{ url('admin/sales-reports') }}" method="GET">
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
                            <h4>Ringkasan Penjualan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-primary">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Total Pesanan</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ $summary['total_orders'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-success">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Total Pendapatan</h4>
                                            </div>
                                            <div class="card-body">
                                                Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-warning">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Total Item Terjual</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ $summary['total_items_sold'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Penjualan Harian</h4>
                            <div class="card-header-action">
                                <a href="{{ url('admin/sales-reports/detailed') }}?start_date={{ $start_date->format('Y-m-d') }}&end_date={{ $end_date->format('Y-m-d') }}"
                                    class="btn btn-info mr-2">
                                    <i class="fas fa-list"></i> Laporan Detail
                                </a>
                                <a href="{{ url('admin/sales-reports/export') }}?start_date={{ $start_date->format('Y-m-d') }}&end_date={{ $end_date->format('Y-m-d') }}"
                                    class="btn btn-success" target="_blank">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah Pesanan</th>
                                            <th>Total Pendapatan</th>
                                            <th>Item Terjual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sales as $sale)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($sale['date'])->format('d/m/Y') }}</td>
                                                <td>{{ $sale['total_orders'] }}</td>
                                                <td>Rp {{ number_format($sale['total_revenue'], 0, ',', '.') }}</td>
                                                <td>{{ $sale['total_items_sold'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data penjualan pada periode
                                                    ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

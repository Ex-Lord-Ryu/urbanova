@extends('layouts.app')

@section('title', 'Kelola Diskon')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/discounts.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Kelola Diskon Produk</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Diskon</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Daftar Produk dengan Diskon</h2>
            <p class="section-lead">Kelola semua diskon untuk produk di sini.</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Produk dengan Diskon</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Diskon
                                    </a>
                                    <a href="{{ route('admin.discounts.create-bulk') }}" class="btn btn-info">
                                        <i class="fas fa-tags"></i> Diskon Massal
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th>Diskon</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                            <tr class="product-row">
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                alt="{{ $product->name }}" class="mr-3 rounded"
                                                                style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="fas fa-image text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $product->name }}</strong>
                                                            <small class="d-block text-muted">SKU:
                                                                {{ $product->sku }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>
                                                    <span
                                                        class="discount-badge">{{ $product->discount_percentage }}%</span>
                                                </td>
                                                <td>
                                                    <div class="price-container">
                                                        <div class="original-price">Rp
                                                            {{ number_format($product->price, 0, ',', '.') }}</div>
                                                        <div class="discount-price">Rp
                                                            {{ number_format($product->discounted_price, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($product->hasActiveDiscount())
                                                        <span class="badge badge-success">Aktif</span>
                                                    @elseif($product->discount_start_date && $product->discount_start_date->isFuture())
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($product->discount_end_date && $product->discount_end_date->isPast())
                                                        <span class="badge badge-danger">Berakhir</span>
                                                    @else
                                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="discount-period">
                                                        @if ($product->discount_start_date)
                                                            <small>Mulai:
                                                                {{ $product->discount_start_date->format('d M Y') }}</small>
                                                        @else
                                                            <small>Mulai: Langsung</small>
                                                        @endif
                                                        <br>
                                                        @if ($product->discount_end_date)
                                                            <small>Berakhir:
                                                                {{ $product->discount_end_date->format('d M Y') }}</small>
                                                        @else
                                                            <small>Berakhir: Tidak dibatasi</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="javascript:void(0)"
                                                            onclick="confirmEdit('{{ route('admin.discounts.edit', $product->id) }}')"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form id="delete-form-{{ $product->id }}"
                                                            action="{{ route('admin.discounts.destroy', $product->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="confirmDelete(event)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="empty-state" data-height="400">
                                                        <div class="empty-state-icon">
                                                            <i class="fas fa-tags"></i>
                                                        </div>
                                                        <h2>Belum ada diskon yang ditambahkan</h2>
                                                        <p class="lead">
                                                            Mulai tambahkan diskon untuk produk anda.
                                                        </p>
                                                        <a href="{{ route('admin.discounts.create') }}"
                                                            class="btn btn-primary mt-4">Tambah Diskon</a>
                                                        <a href="{{ route('admin.discounts.create-bulk') }}"
                                                            class="btn btn-info mt-4">Diskon Massal</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="float-right">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.sweet-alert')
@endsection

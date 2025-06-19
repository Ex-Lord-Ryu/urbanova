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

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Sistem hanya mengatur tanggal akhir diskon minimal 1 hari setelah tanggal mulai.
                <br>
                <b>Info:</b> Diskon dengan status "Menunggu" akan aktif secara otomatis pada tanggal mulai yang ditentukan.
            </div>

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
                                            @php
                                                $activeDiscount = $product->activeDiscount();
                                            @endphp
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
                                                    @php
                                                        $discountPercentage = $product->discount_percentage;
                                                        $isFuture = $activeDiscount && $activeDiscount->start_date && $activeDiscount->start_date->isFuture();

                                                        // If no active discount but has future discount, get it
                                                        if (!$activeDiscount && $discountPercentage > 0) {
                                                            $futureDiscount = $product->discounts()
                                                                ->where('percentage', '>', 0)
                                                                ->where('start_date', '>', now())
                                                                ->orderBy('start_date', 'asc')
                                                                ->first();
                                                            $isFuture = $futureDiscount !== null;
                                                        }
                                                    @endphp

                                                    <span class="discount-badge {{ $isFuture ? 'text-warning' : '' }}">
                                                        {{ $discountPercentage }}%
                                                        @if($isFuture)
                                                            <small>(Menunggu)</small>
                                                        @endif
                                                    </span>
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
                                                    @elseif($activeDiscount && $activeDiscount->start_date && $activeDiscount->start_date->isFuture())
                                                        <span class="badge badge-warning">Menunggu (Mulai {{ $activeDiscount->start_date->format('d M Y') }})</span>
                                                    @elseif($activeDiscount && $activeDiscount->end_date && $activeDiscount->end_date->isPast())
                                                        <span class="badge badge-danger">Berakhir</span>
                                                    @else
                                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="discount-period">
                                                        @if ($activeDiscount && $activeDiscount->start_date)
                                                            <small>Mulai:
                                                                {{ $activeDiscount->start_date->format('d M Y') }}</small>
                                                        @else
                                                            <small>Mulai: Langsung</small>
                                                        @endif
                                                        <br>
                                                        @if ($activeDiscount && $activeDiscount->end_date)
                                                            <small>Berakhir:
                                                                {{ $activeDiscount->end_date->format('d M Y') }}</small>
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

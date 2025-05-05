@extends('layouts.app')

@section('title', 'Detail Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
@endpush

@section('content')
<section class="section products-section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Detail Produk</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="products-title mb-0">Informasi Produk</h4>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning" data-toggle="tooltip" title="Edit Produk">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-primary" data-toggle="tooltip" title="Kembali">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 text-center">
                                <div class="mb-4 product-main-image">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
                                    @else
                                    <div class="alert alert-light rounded">Tidak ada gambar utama</div>
                                    @endif
                                </div>

                                @if($product->additional_images && count($product->additional_images) > 0)
                                <div class="row product-gallery">
                                    @foreach($product->additional_images as $image)
                                    <div class="col-4 mb-2">
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="img-thumbnail product-image">
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="col-md-7">
                                <div class="mb-3">
                                    <h5 class="product-details-title">Detail Produk</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-striped product-details-table">
                                            <tr>
                                                <td width="200">Nama Produk</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-color-dot" style="background-color: #{{ substr(md5($product->name), 0, 6) }}"></div>
                                                        <strong>{{ $product->name }}</strong>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kategori</td>
                                                <td>
                                                    <a href="{{ route('categories.show', $product->category_id) }}" class="badge badge-primary">
                                                        {{ $product->category->name }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>SKU</td>
                                                <td><span class="text-muted">{{ $product->sku }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>Harga</td>
                                                <td class="text-primary font-weight-bold">@rupiah($product->price)</td>
                                            </tr>
                                            <tr>
                                                <td>Stok</td>
                                                <td>{{ $product->stock }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($product->is_active)
                                                    <span class="product-status active">Aktif</span>
                                                    @else
                                                    <span class="product-status inactive">Tidak Aktif</span>
                                                    @endif

                                                    @if($product->is_featured)
                                                    <span class="product-status featured">Produk Unggulan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ukuran Tersedia</td>
                                                <td>
                                                    @if(count($product->sizes) > 0)
                                                        @foreach($product->sizes as $size)
                                                            @php
                                                                // Cek apakah $size adalah object atau string
                                                                $sizeObj = is_object($size) ? $size : \App\Models\Size::where('name', $size)->first();
                                                                $sizeName = is_object($size) ? $size->name : $size;
                                                                $sizeId = is_object($size) ? $size->id : ($sizeObj ? $sizeObj->id : null);
                                                            @endphp
                                                            @if($sizeId)
                                                            <a href="{{ route('sizes.edit', $sizeId) }}" class="badge badge-info mr-1 mb-1">{{ $sizeName }}</a>
                                                            @else
                                                            <span class="badge badge-info mr-1 mb-1">{{ $sizeName }}</span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    <span class="text-muted">Tidak ada ukuran yang ditentukan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Warna Tersedia</td>
                                                <td>
                                                    @if(count($product->colors) > 0)
                                                        @foreach($product->colors as $color)
                                                            @php
                                                                // Cek apakah $color adalah object atau string
                                                                $colorObj = is_object($color) ? $color : \App\Models\Color::where('name', $color)->first();
                                                                $hexCode = $colorObj && $colorObj->hex_code ? '#'.$colorObj->hex_code : '#808080';
                                                                $colorName = is_object($color) ? $color->name : $color;
                                                                $colorId = is_object($color) ? $color->id : ($colorObj ? $colorObj->id : null);
                                                            @endphp
                                                            @if($colorId)
                                                            <a href="{{ route('colors.edit', $colorId) }}" class="color-chip mr-1 mb-1">
                                                                <span class="chip-color-dot" style="background-color: {{ $hexCode }}"></span>
                                                                {{ $colorName }}
                                                            </a>
                                                            @else
                                                            <span class="color-chip mr-1 mb-1">
                                                                <span class="chip-color-dot" style="background-color: {{ $hexCode }}"></span>
                                                                {{ $colorName }}
                                                            </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    <span class="text-muted">Tidak ada warna yang ditentukan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Slug</td>
                                                <td><span class="text-muted">{{ $product->slug }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Dibuat</td>
                                                <td>{{ $product->created_at->format('d F Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Terakhir Diperbarui</td>
                                                <td>{{ $product->updated_at->format('d F Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="product-description-title">Deskripsi Produk</h5>
                                <div class="p-3 bg-light rounded product-description">
                                    {!! nl2br(e($product->description)) ?: '<span class="text-muted">Tidak ada deskripsi</span>' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Add fade-in animation to elements
        $('.product-main-image').addClass('fade-in');
        $('.product-gallery .product-image').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.addClass('fade-in');
            }, index * 100);
        });
    });
</script>
@endpush

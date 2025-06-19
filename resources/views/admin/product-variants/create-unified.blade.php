@extends('layouts.app')

@section('title', 'Tambah Varian Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .variant-item, .color-item {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .variant-item:hover, .color-item:hover {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .price-by-size {
            background-color: #e9f7ef;
            border-left: 4px solid #2ecc71;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .price-adjustment {
            background-color: #f0f8ff;
            border-left: 4px solid #3498db;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <section class="section variants-section">
        <div class="page-header-bg">
            <h1 class="variants-title mb-3">
                @if(isset($product))
                    Tambah Varian {{ $product->name }}
                @else
                    Tambah Varian Produk
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Varian</li>
                </ol>
            </nav>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card variant-form-card">
                        <div class="card-header variant-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cubes text-success mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="variants-title mb-0">
                                    @if(isset($product))
                                        Varian untuk Produk: {{ $product->name }}
                                    @else
                                        Form Tambah Varian
                                    @endif
                                </h4>
                            </div>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary back-btn">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs mb-4" id="variantTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ !isset($product) ? 'active' : '' }}" id="multiple-variants-tab" data-toggle="tab" href="#multiple-variants" role="tab">
                                        <i class="fas fa-cubes mr-1"></i> Multiple Varian
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($product) ? 'active' : '' }}" id="size-variant-tab" data-toggle="tab" href="#size-variant" role="tab">
                                        <i class="fas fa-ruler mr-1"></i> Varian Per Ukuran
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="variantTabsContent">
                                <!-- Multiple Variants Tab -->
                                <div class="tab-pane fade {{ !isset($product) ? 'show active' : '' }}" id="multiple-variants" role="tabpanel">
                                    <form action="{{ route('product-variants.store') }}" method="POST" id="multiple-variants-form">
                                        @csrf
                                        <div class="form-section mb-4">
                                            <h5 class="section-title"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Dasar</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="product_id">Produk <span class="text-danger">*</span></label>
                                                        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($products as $prod)
                                                                <option value="{{ $prod->id }}"
                                                                    {{ (old('product_id') == $prod->id || (isset($product) && $product->id == $prod->id)) ? 'selected' : '' }}
                                                                    data-price="{{ $prod->base_price }}">
                                                                    {{ $prod->name }} ({{ $prod->sku ?? 'No SKU' }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('product_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-by-size mt-3 mb-3">
                                                <i class="fas fa-info-circle mr-1"></i> Anda dapat menetapkan harga berbeda untuk setiap ukuran produk, dan
                                                setiap ukuran dapat memiliki beberapa warna dengan stok yang berbeda-beda.
                                            </div>

                                            <!-- Price Adjustment by Percentage -->
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="use_percentage_multiple" name="use_percentage">
                                                <label class="form-check-label" for="use_percentage_multiple">
                                                    Gunakan penyesuaian harga berdasarkan persentase
                                                </label>
                                            </div>

                                            <div id="percentage-adjustment-multiple" style="display: none;" class="mb-4">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="base_price_multiple">Harga Dasar</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input type="number" step="0.01" id="base_price_multiple" class="form-control" value="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="percentage_multiple">Persentase Kenaikan (%)</label>
                                                            <input type="number" step="0.01" id="percentage_multiple" class="form-control" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-primary btn-block" id="apply-percentage-multiple">
                                                                Terapkan ke Semua Varian
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price-adjustment">
                                                    <i class="fas fa-calculator mr-1"></i> Penyesuaian harga: <span id="price-calculation-multiple">Rp 0</span>
                                                </div>
                                            </div>

                                            <!-- Stock Bulk Adjustment -->
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="use_stock_adjustment_multiple" name="use_stock_adjustment">
                                                <label class="form-check-label" for="use_stock_adjustment_multiple">
                                                    Gunakan penyesuaian stok secara massal
                                                </label>
                                            </div>

                                            <div id="stock-adjustment-multiple" style="display: none;" class="mb-4">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="base_stock_multiple">Jumlah Stok</label>
                                                            <input type="number" min="0" id="base_stock_multiple" class="form-control" value="0">
                                                            <small class="text-muted">Jumlah stok yang akan diterapkan ke semua varian</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-primary btn-block" id="apply-stock-multiple">
                                                                Terapkan ke Semua Varian
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-section mb-4">
                                            <h5 class="section-title d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-list text-warning mr-2"></i>Daftar Varian</span>
                                                <button type="button" class="btn btn-sm btn-success" id="add-variant">
                                                    <i class="fas fa-plus"></i> Tambah Varian Baru
                                                </button>
                                            </h5>

                                            <div id="variants-container">
                                                <div class="variant-item">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Ukuran <span class="text-danger">*</span></label>
                                                                <select name="variants[0][size_id]" class="form-control size-select">
                                                                    <option value="">Pilih Ukuran</option>
                                                                    @foreach ($sizes as $size)
                                                                        <option value="{{ $size->id }}">
                                                                            {{ $size->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Warna</label>
                                                                <select name="variants[0][color_id]" class="form-control color-select">
                                                                    <option value="">Tidak Ada</option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}"
                                                                            data-color="{{ $color->hex_code }}">
                                                                            {{ $color->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Harga <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">Rp</span>
                                                                    </div>
                                                                    <input type="number" step="0.01" name="variants[0][price]" class="form-control variant-price" value="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Stok <span class="text-danger">*</span></label>
                                                                <input type="number" min="0" name="variants[0][stock]" class="form-control" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-center">
                                                            <button type="button" class="btn btn-danger remove-variant mt-4" disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-save mr-1"></i> Simpan Semua Varian
                                            </button>
                                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg ml-2">
                                                <i class="fas fa-times mr-1"></i> Batal
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                <!-- Size Variant Tab -->
                                <div class="tab-pane fade {{ isset($product) ? 'show active' : '' }}" id="size-variant" role="tabpanel">
                                    <form action="{{ isset($product) ? route('product-variants.store-size-variant', $product->id) : '#' }}"
                                          method="POST" id="size-variant-form" {{ !isset($product) ? 'disabled' : '' }}>
                                        @csrf
                                        <div class="form-section mb-4">
                                            <h5 class="section-title"><i class="fas fa-ruler-vertical text-primary mr-2"></i>Informasi Ukuran</h5>

                                            @if(!isset($product))
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="product_id_size">Produk <span class="text-danger">*</span></label>
                                                        <select name="product_id_size" id="product_id_size" class="form-control @error('product_id_size') is-invalid @enderror" required>
                                                            <option value="">Pilih Produk Terlebih Dahulu</option>
                                                            @foreach ($products as $prod)
                                                                <option value="{{ $prod->id }}" data-price="{{ $prod->base_price }}">
                                                                    {{ $prod->name }} ({{ $prod->sku ?? 'No SKU' }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-danger" id="product-select-warning">
                                                            Pilih produk terlebih dahulu untuk mengaktifkan form
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="button" id="load-size-variant" class="btn btn-primary btn-block mt-2">
                                                            <i class="fas fa-arrow-right mr-1"></i> Lanjutkan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mb-4">
                                            @endif

                                            <div id="size-variant-content" class="{{ !isset($product) ? 'opacity-50' : '' }}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="size_id">Ukuran <span class="text-danger">*</span></label>
                                                            <select name="size_id" id="size_id" class="form-control @error('size_id') is-invalid @enderror" required {{ !isset($product) ? 'disabled' : '' }}>
                                                                <option value="">Pilih Ukuran</option>
                                                                @foreach ($sizes as $size)
                                                                    <option value="{{ $size->id }}" {{ (old('size_id') == $size->id || (isset($selectedSize) && $selectedSize->id == $size->id)) ? 'selected' : '' }}>
                                                                        {{ $size->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('size_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="price">Harga untuk Ukuran Ini <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                                                    value="{{ old('price', $product->base_price ?? 0) }}" required {{ !isset($product) ? 'disabled' : '' }}>
                                                            </div>
                                                            <small class="text-muted">Harga khusus untuk ukuran ini</small>
                                                            @error('price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="price-by-size mt-2 mb-4">
                                                    <i class="fas fa-info-circle mr-1"></i> Anda dapat menetapkan harga berbeda untuk setiap ukuran produk, dan
                                                    Anda dapat menyesuaikan harga dan stok secara massal.
                                                </div>

                                                <!-- Price Adjustment by Percentage -->
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="use_percentage_size" name="use_percentage" {{ !isset($product) ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="use_percentage_size">
                                                        Gunakan penyesuaian harga berdasarkan persentase
                                                    </label>
                                                </div>

                                                <div id="percentage-adjustment-size" style="display: none;" class="mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="base_price_size">Harga Dasar</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">Rp</span>
                                                                    </div>
                                                                    <input type="number" step="0.01" id="base_price_size" class="form-control"
                                                                        value="{{ $product->base_price ?? 0 }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="percentage_size">Persentase Kenaikan (%)</label>
                                                                <input type="number" step="0.01" id="percentage_size" class="form-control" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-primary btn-block" id="apply-percentage-size">
                                                                    Terapkan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price-adjustment">
                                                        <i class="fas fa-calculator mr-1"></i> Penyesuaian harga: <span id="price-calculation-size">Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>

                                                <!-- Stock Bulk Adjustment for Size Variant -->
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="use_stock_adjustment_size" name="use_stock_adjustment" {{ !isset($product) ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="use_stock_adjustment_size">
                                                        Gunakan penyesuaian stok secara massal
                                                    </label>
                                                </div>

                                                <div id="stock-adjustment-size" style="display: none;" class="mb-4">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label for="base_stock_size">Jumlah Stok</label>
                                                                <input type="number" min="0" id="base_stock_size" class="form-control" value="0">
                                                                <small class="text-muted">Jumlah stok yang akan diterapkan ke semua warna</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="button" class="btn btn-primary btn-block" id="apply-stock-size">
                                                                    Terapkan ke Semua Warna
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-section mb-4" id="no-colors-section">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" id="has_no_colors" name="has_no_colors" {{ !isset($product) ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="has_no_colors">
                                                            Produk ini tidak memiliki varian warna
                                                        </label>
                                                    </div>

                                                    <div id="no-colors-stock" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="stock">Stok untuk ukuran ini <span class="text-danger">*</span></label>
                                                            <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror"
                                                                value="{{ old('stock', 0) }}" {{ !isset($product) ? 'disabled' : '' }}>
                                                            @error('stock')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-section mb-4" id="colors-section">
                                                    <h5 class="section-title">
                                                        <i class="fas fa-palette text-warning mr-2"></i>Warna dan Stok
                                                        <button type="button" class="btn btn-sm btn-primary ml-2" id="add-color" {{ !isset($product) ? 'disabled' : '' }}>
                                                            <i class="fas fa-plus"></i> Tambah Warna
                                                        </button>
                                                    </h5>

                                                    <div id="colors-container">
                                                        <div class="color-item">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Warna <span class="text-danger">*</span></label>
                                                                        <select name="colors[0][color_id]" class="form-control color-select" {{ !isset($product) ? 'disabled' : '' }}>
                                                                            <option value="">Pilih Warna</option>
                                                                            @foreach ($colors as $color)
                                                                                <option value="{{ $color->id }}"
                                                                                    data-color="{{ $color->hex_code }}">
                                                                                    {{ $color->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label>Stok <span class="text-danger">*</span></label>
                                                                        <input type="number" min="0" name="colors[0][stock]" class="form-control" value="0" {{ !isset($product) ? 'disabled' : '' }}>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1 d-flex align-items-center">
                                                                    <button type="button" class="btn btn-danger remove-color mt-4" disabled>
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-success btn-lg" {{ !isset($product) ? 'disabled' : '' }}>
                                                        <i class="fas fa-save mr-1"></i> Simpan Varian
                                                    </button>
                                                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg ml-2">
                                                        <i class="fas fa-times mr-1"></i> Batal
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        let variantCount = 1;
        let colorCount = 1;

        // Toggle between tabs
        $('#variantTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Load size variant form when product is selected in tab 2
        $('#load-size-variant').click(function() {
            const productId = $('#product_id_size').val();
            if (productId) {
                window.location.href = `/admin/products/${productId}/size-variants/create`;
            } else {
                alert('Silakan pilih produk terlebih dahulu');
            }
        });

        // Add variant for multiple variants tab
        $('#add-variant').click(function() {
            const productPrice = $('#product_id option:selected').data('price') || 0;

            const newVariant = `
                <div class="variant-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ukuran <span class="text-danger">*</span></label>
                                <select name="variants[${variantCount}][size_id]" class="form-control size-select">
                                    <option value="">Pilih Ukuran</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Warna</label>
                                <select name="variants[${variantCount}][color_id]" class="form-control color-select">
                                    <option value="">Tidak Ada</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}"
                                            data-color="{{ $color->hex_code }}">
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" step="0.01" name="variants[${variantCount}][price]" class="form-control variant-price" value="${productPrice}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Stok <span class="text-danger">*</span></label>
                                <input type="number" min="0" name="variants[${variantCount}][stock]" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-variant mt-4">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $('#variants-container').append(newVariant);
            variantCount++;

            // Enable all remove buttons when there's more than one variant
            if (variantCount > 1) {
                $('.remove-variant').prop('disabled', false);
            }

            // Initialize color selects for the new variant
            initColorSelects();
        });

        // Add color for size variant tab
        $('#add-color').click(function() {
            const newColor = `
                <div class="color-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Warna <span class="text-danger">*</span></label>
                                <select name="colors[${colorCount}][color_id]" class="form-control color-select">
                                    <option value="">Pilih Warna</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}"
                                            data-color="{{ $color->hex_code }}">
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Stok <span class="text-danger">*</span></label>
                                <input type="number" min="0" name="colors[${colorCount}][stock]" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-color mt-4">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $('#colors-container').append(newColor);
            colorCount++;

            // Enable all remove buttons when there's more than one color
            if (colorCount > 1) {
                $('.remove-color').prop('disabled', false);
            }

            // Initialize the color select change event for the new color
            initColorSelects();
        });

        // Remove variant
        $(document).on('click', '.remove-variant', function() {
            $(this).closest('.variant-item').remove();

            // If only one variant remains, disable its remove button
            if ($('.variant-item').length === 1) {
                $('.remove-variant').prop('disabled', true);
            }
        });

        // Remove color
        $(document).on('click', '.remove-color', function() {
            $(this).closest('.color-item').remove();

            // If only one color remains, disable its remove button
            if ($('.color-item').length === 1) {
                $('.remove-color').prop('disabled', true);
            }
        });

        // Toggle between no colors and colors
        $('#has_no_colors').change(function() {
            if ($(this).is(':checked')) {
                $('#colors-section').hide();
                $('#no-colors-stock').show();
            } else {
                $('#colors-section').show();
                $('#no-colors-stock').hide();
            }
        });

        // Toggle percentage price adjustment for multiple variants
        $('#use_percentage_multiple').change(function() {
            if ($(this).is(':checked')) {
                $('#percentage-adjustment-multiple').show();
                const productPrice = $('#product_id option:selected').data('price') || 0;
                $('#base_price_multiple').val(productPrice);
                updatePriceCalculationMultiple();
            } else {
                $('#percentage-adjustment-multiple').hide();
            }
        });

        // Toggle stock adjustment for multiple variants
        $('#use_stock_adjustment_multiple').change(function() {
            if ($(this).is(':checked')) {
                $('#stock-adjustment-multiple').show();
            } else {
                $('#stock-adjustment-multiple').hide();
            }
        });

        // Toggle percentage price adjustment for size variant
        $('#use_percentage_size').change(function() {
            if ($(this).is(':checked')) {
                $('#percentage-adjustment-size').show();
            } else {
                $('#percentage-adjustment-size').hide();
            }
        });

        // Toggle stock adjustment for size variant
        $('#use_stock_adjustment_size').change(function() {
            if ($(this).is(':checked')) {
                $('#stock-adjustment-size').show();
            } else {
                $('#stock-adjustment-size').hide();
            }
        });

        // Apply percentage to all variant prices in multiple variants tab
        $('#apply-percentage-multiple').click(function() {
            const basePrice = parseFloat($('#base_price_multiple').val()) || 0;
            const percentage = parseFloat($('#percentage_multiple').val()) || 0;

            if (basePrice > 0) {
                const increase = basePrice * (percentage / 100);
                const newPrice = basePrice + increase;

                // Update all price fields
                $('.variant-price').val(newPrice.toFixed(2));

                // Update display calculation
                updatePriceCalculationMultiple();
            }
        });

        // Apply stock to all variants in multiple variants tab
        $('#apply-stock-multiple').click(function() {
            const stockValue = parseInt($('#base_stock_multiple').val()) || 0;

            // Update all stock input fields in the variants tab
            $('#variants-container').find('input[name$="[stock]"]').val(stockValue);
        });

        // Apply percentage in size variant tab
        $('#apply-percentage-size').click(function() {
            const basePrice = parseFloat($('#base_price_size').val()) || 0;
            const percentage = parseFloat($('#percentage_size').val()) || 0;

            if (basePrice > 0) {
                const increase = basePrice * (percentage / 100);
                const newPrice = basePrice + increase;

                // Display calculation
                $('#price-calculation-size').text('Rp ' + formatNumber(newPrice));

                // Update price field
                $('#price').val(newPrice.toFixed(2));
            }
        });

        // Apply stock to all colors in size variant tab
        $('#apply-stock-size').click(function() {
            const stockValue = parseInt($('#base_stock_size').val()) || 0;

            // Update all stock input fields in the colors container
            $('#colors-container').find('input[name$="[stock]"]').val(stockValue);

            // Also update the no-colors stock field if it exists
            $('#stock').val(stockValue);
        });

        function updatePriceCalculationMultiple() {
            const basePrice = parseFloat($('#base_price_multiple').val()) || 0;
            const percentage = parseFloat($('#percentage_multiple').val()) || 0;
            const increase = basePrice * (percentage / 100);
            const newPrice = basePrice + increase;

            $('#price-calculation-multiple').text('Rp ' + formatNumber(newPrice) +
                ' (Rp ' + formatNumber(basePrice) + ' + ' + percentage + '% = Rp ' +
                formatNumber(increase) + ')');
        }

        // Update calculation when base price or percentage changes
        $('#base_price_multiple, #percentage_multiple').on('input', function() {
            updatePriceCalculationMultiple();
        });

        $('#base_price_size, #percentage_size').on('input', function() {
            const basePrice = parseFloat($('#base_price_size').val()) || 0;
            const percentage = parseFloat($('#percentage_size').val()) || 0;
            const increase = basePrice * (percentage / 100);
            const newPrice = basePrice + increase;

            $('#price-calculation-size').text('Rp ' + formatNumber(newPrice) +
                ' (Rp ' + formatNumber(basePrice) + ' + ' + percentage + '% = Rp ' +
                formatNumber(increase) + ')');
        });

        // Add color indicator for color select
        function initColorSelects() {
            $('.color-select').change(function() {
                const selectedOption = $(this).find('option:selected');
                const hexCode = selectedOption.data('color');

                if (hexCode) {
                    $(this).css('background-color', '#' + hexCode);
                    $(this).css('color', isLightColor(hexCode) ? '#000' : '#fff');
                } else {
                    $(this).css('background-color', '');
                    $(this).css('color', '');
                }
            });
        }

        // Initialize color selects
        initColorSelects();

        // Set product price when product is selected in multiple variants tab
        $('#product_id').change(function() {
            const productPrice = $(this).find('option:selected').data('price') || 0;
            $('.variant-price').val(productPrice);
            $('#base_price_multiple').val(productPrice);
            updatePriceCalculationMultiple();

            // Enable/disable create size variants button
            const productId = $(this).val();
            $('#create-size-variants').prop('disabled', !productId);

            if (productId) {
                // Set redirect URL for the create size variants button
                $('#create-size-variants').off('click').on('click', function() {
                    window.location.href = `/admin/products/${productId}/size-variants/create`;
                });
            }
        });

        // Helper function to determine if a color is light
        function isLightColor(hex) {
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);
            const brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            return brightness > 128;
        }

        // Helper function to format numbers
        function formatNumber(number) {
            return number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>
@endpush

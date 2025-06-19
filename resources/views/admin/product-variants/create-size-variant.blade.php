@extends('layouts.app')

@section('title', 'Tambah Varian Produk untuk Ukuran')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .color-item {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
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
            <h1 class="variants-title mb-3">Tambah Varian {{ $product->name }} untuk Ukuran</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Varian Ukuran</li>
                </ol>
            </nav>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card variant-form-card">
                        <div class="card-header variant-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ruler text-primary mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="variants-title mb-0">Form Tambah Varian untuk Produk: {{ $product->name }}</h4>
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

                            <form action="{{ route('product-variants.store-size-variant', $product->id) }}" method="POST">
                                @csrf
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-ruler-vertical text-primary mr-2"></i>Informasi Ukuran</h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size_id">Ukuran <span class="text-danger">*</span></label>
                                                <select name="size_id" id="size_id" class="form-control @error('size_id') is-invalid @enderror" required>
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
                                                        value="{{ old('price', $product->base_price ?? 0) }}" required>
                                                </div>
                                                <small class="text-muted">Harga khusus untuk ukuran ini</small>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="price-by-size mt-2 mb-4">
                                        <i class="fas fa-info-circle mr-1"></i> Anda dapat menetapkan harga berbeda untuk setiap ukuran produk.
                                    </div>

                                    <!-- Price Adjustment by Percentage -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_percentage" name="use_percentage">
                                        <label class="form-check-label" for="use_percentage">
                                            Gunakan penyesuaian harga berdasarkan persentase
                                        </label>
                                    </div>

                                    <div id="percentage-adjustment" style="display: none;" class="mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="base_price">Harga Dasar</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="number" step="0.01" id="base_price" class="form-control"
                                                            value="{{ $product->base_price ?? 0 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="percentage">Persentase Kenaikan (%)</label>
                                                    <input type="number" step="0.01" id="percentage" class="form-control" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block" id="apply-percentage">
                                                        Terapkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-adjustment">
                                            <i class="fas fa-calculator mr-1"></i> Penyesuaian harga: <span id="price-calculation">Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4" id="no-colors-section">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="has_no_colors" name="has_no_colors">
                                        <label class="form-check-label" for="has_no_colors">
                                            Produk ini tidak memiliki varian warna
                                        </label>
                                    </div>

                                    <div id="no-colors-stock" style="display: none;">
                                        <div class="form-group">
                                            <label for="stock">Stok untuk ukuran ini <span class="text-danger">*</span></label>
                                            <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror"
                                                value="{{ old('stock', 0) }}">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4" id="colors-section">
                                    <h5 class="section-title">
                                        <i class="fas fa-palette text-warning mr-2"></i>Warna dan Stok
                                        <button type="button" class="btn btn-sm btn-primary ml-2" id="add-color">
                                            <i class="fas fa-plus"></i> Tambah Warna
                                        </button>
                                    </h5>

                                    <div id="colors-container">
                                        <div class="color-item">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Warna <span class="text-danger">*</span></label>
                                                        <select name="colors[0][color_id]" class="form-control color-select">
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
                                                        <input type="number" min="0" name="colors[0][stock]" class="form-control" value="0">
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
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save mr-1"></i> Simpan Varian
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg ml-2">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </a>
                                </div>
                            </form>
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
        let colorCount = 1;

        // Add color option
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

        // Toggle percentage price adjustment
        $('#use_percentage').change(function() {
            if ($(this).is(':checked')) {
                $('#percentage-adjustment').show();
            } else {
                $('#percentage-adjustment').hide();
            }
        });

        // Apply percentage
        $('#apply-percentage').click(function() {
            const basePrice = parseFloat($('#base_price').val()) || 0;
            const percentage = parseFloat($('#percentage').val()) || 0;

            if (basePrice > 0) {
                const increase = basePrice * (percentage / 100);
                const newPrice = basePrice + increase;

                // Display calculation
                $('#price-calculation').text('Rp ' + formatNumber(newPrice));

                // Update price field
                $('#price').val(newPrice.toFixed(2));
            }
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

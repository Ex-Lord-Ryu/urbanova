@extends('layouts.app')

@section('title', 'Tambah Varian Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .variant-item {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .variant-item:hover {
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
            <h1 class="variants-title mb-3">Tambah Varian Produk</h1>
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
                                <h4 class="variants-title mb-0">Form Tambah Varian</h4>
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

                            <form action="{{ route('product-variants.store') }}" method="POST">
                                @csrf
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Dasar</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_id">Produk <span class="text-danger">*</span></label>
                                                <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}
                                                            data-price="{{ $product->base_price }}">
                                                            {{ $product->name }} ({{ $product->sku }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('product_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-primary" id="create-size-variants" disabled>
                                                    <i class="fas fa-plus-circle mr-1"></i> Buat Varian Per Ukuran
                                                </button>
                                                <small class="d-block text-muted mt-2">
                                                    <i class="fas fa-info-circle"></i> Gunakan opsi ini untuk membuat varian dengan banyak warna per ukuran
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="price-by-size mt-3 mb-3">
                                        <i class="fas fa-info-circle mr-1"></i> Anda dapat menetapkan harga berbeda untuk setiap ukuran produk, dan
                                        Anda dapat menyesuaikan harga dan stok secara massal.
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
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="base_price">Harga Dasar</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="number" step="0.01" id="base_price" class="form-control" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="percentage">Persentase Kenaikan (%)</label>
                                                    <input type="number" step="0.01" id="percentage" class="form-control" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block" id="apply-percentage">
                                                        Terapkan ke Semua Varian
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-adjustment">
                                            <i class="fas fa-calculator mr-1"></i> Penyesuaian harga: <span id="price-calculation">Rp 0</span>
                                        </div>
                                    </div>

                                    <!-- Stock Bulk Adjustment -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_stock_adjustment" name="use_stock_adjustment">
                                        <label class="form-check-label" for="use_stock_adjustment">
                                            Gunakan penyesuaian stok secara massal
                                        </label>
                                    </div>

                                    <div id="stock-adjustment" style="display: none;" class="mb-4">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="base_stock">Jumlah Stok</label>
                                                    <input type="number" min="0" id="base_stock" class="form-control" value="0">
                                                    <small class="text-muted">Jumlah stok yang akan diterapkan ke semua varian</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block" id="apply-stock">
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

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="has_no_colors" name="has_no_colors">
                                        <label class="form-check-label" for="has_no_colors">
                                            Produk ini tidak memiliki varian warna
                                        </label>
                                    </div>

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
                                                <div class="col-md-3 color-selector-container">
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

        // Add variant
        $('#add-variant').click(function() {
            const productPrice = $('#product_id option:selected').data('price') || 0;
            const noColors = $('#has_no_colors').is(':checked');
            const colorSelectorClass = noColors ? 'color-selector-container d-none' : 'color-selector-container';

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
                        <div class="col-md-3 ${colorSelectorClass}">
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

            // Initialize the color select change event for the new variant
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

        // Toggle percentage price adjustment
        $('#use_percentage').change(function() {
            if ($(this).is(':checked')) {
                $('#percentage-adjustment').show();
                const productPrice = $('#product_id option:selected').data('price') || 0;
                $('#base_price').val(productPrice);
                updatePriceCalculation();
            } else {
                $('#percentage-adjustment').hide();
            }
        });

        // Toggle stock adjustment
        $('#use_stock_adjustment').change(function() {
            if ($(this).is(':checked')) {
                $('#stock-adjustment').show();
            } else {
                $('#stock-adjustment').hide();
            }
        });

        // Toggle color selectors visibility
        $('#has_no_colors').change(function() {
            if ($(this).is(':checked')) {
                $('.color-selector-container').hide();
                // Clear all color selections
                $('.color-select').val('');
            } else {
                $('.color-selector-container').show();
            }
        });

        // Apply percentage to all variant prices
        $('#apply-percentage').click(function() {
            const basePrice = parseFloat($('#base_price').val()) || 0;
            const percentage = parseFloat($('#percentage').val()) || 0;

            if (basePrice > 0) {
                const increase = basePrice * (percentage / 100);
                const newPrice = basePrice + increase;

                // Update all price fields
                $('.variant-price').val(newPrice.toFixed(2));

                // Update display calculation
                updatePriceCalculation();
            }
        });

        // Apply stock to all variants
        $('#apply-stock').click(function() {
            const stockValue = parseInt($('#base_stock').val()) || 0;

            // Update all stock input fields
            $('#variants-container').find('input[name$="[stock]"]').val(stockValue);
        });

        function updatePriceCalculation() {
            const basePrice = parseFloat($('#base_price').val()) || 0;
            const percentage = parseFloat($('#percentage').val()) || 0;
            const increase = basePrice * (percentage / 100);
            const newPrice = basePrice + increase;

            $('#price-calculation').text('Rp ' + formatNumber(newPrice) +
                ' (Rp ' + formatNumber(basePrice) + ' + ' + percentage + '% = Rp ' +
                formatNumber(increase) + ')');
        }

        // Update calculation when base price or percentage changes
        $('#base_price, #percentage').on('input', function() {
            updatePriceCalculation();
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

        // Set product price when product is selected
        $('#product_id').change(function() {
            const productPrice = $(this).find('option:selected').data('price') || 0;
            $('.variant-price').val(productPrice);
            $('#base_price').val(productPrice);
            updatePriceCalculation();

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

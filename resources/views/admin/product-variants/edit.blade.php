@extends('layouts.app')

@section('title', 'Edit Varian Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .form-section {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 25px;
            background-color: #f9f9f9;
        }
        .form-section:hover {
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
            <h1 class="variants-title mb-3">Edit Varian Produk</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Varian</li>
                </ol>
            </nav>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card variant-form-card">
                        <div class="card-header variant-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cubes text-warning mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="variants-title mb-0">Form Edit Varian</h4>
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

                            <form action="{{ route('product-variants.update', $productVariant) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Dasar</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_id">Produk <span class="text-danger">*</span></label>
                                                <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ old('product_id', $productVariant->product_id) == $product->id ? 'selected' : '' }}
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
                                            <div class="form-group">
                                                <label for="price">Harga <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="number" step="0.01" name="price" id="price"
                                                        class="form-control @error('price') is-invalid @enderror"
                                                        value="{{ old('price', $productVariant->price) }}" required>
                                                </div>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="price-by-size mt-3 mb-3">
                                        <i class="fas fa-info-circle mr-1"></i> Anda dapat menetapkan harga berbeda untuk setiap ukuran produk, dan
                                        Anda dapat menyesuaikan harga secara massal.
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
                                                        <input type="number" step="0.01" id="base_price" class="form-control" value="{{ $productVariant->product->base_price ?? 0 }}">
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
                                                        Terapkan Penyesuaian
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-adjustment">
                                            <i class="fas fa-calculator mr-1"></i> Penyesuaian harga: <span id="price-calculation">Rp 0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-palette text-warning mr-2"></i>Ukuran & Warna</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size_id">Ukuran</label>
                                                <select name="size_id" id="size_id" class="form-control @error('size_id') is-invalid @enderror">
                                                    <option value="">Tidak Ada</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ old('size_id', $productVariant->size_id) == $size->id ? 'selected' : '' }}>
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
                                                <label for="color_id">Warna</label>
                                                <select name="color_id" id="color_id" class="form-control @error('color_id') is-invalid @enderror">
                                                    <option value="">Tidak Ada</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}"
                                                            {{ old('color_id', $productVariant->color_id) == $color->id ? 'selected' : '' }}
                                                            data-color="{{ $color->hex_code }}">
                                                            {{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('color_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check mb-3 mt-3">
                                        <input class="form-check-input" type="checkbox" id="has_no_colors" name="has_no_colors" {{ !$productVariant->color_id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_no_colors">
                                            Produk ini tidak memiliki varian warna
                                        </label>
                                    </div>
                                </div>

                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-boxes text-success mr-2"></i>Inventaris</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stok <span class="text-danger">*</span></label>
                                                <input type="number" min="0" name="stock" id="stock"
                                                    class="form-control @error('stock') is-invalid @enderror"
                                                    value="{{ old('stock', $productVariant->stock) }}" required>
                                                @error('stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save mr-1"></i> Perbarui Varian Produk
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
        // Add color indicator for color select
        function updateColorSelect() {
            const selectedOption = $('#color_id').find('option:selected');
            const hexCode = selectedOption.data('color');

            if (hexCode) {
                $('#color_id').css('background-color', '#' + hexCode);
                $('#color_id').css('color', isLightColor(hexCode) ? '#000' : '#fff');
            } else {
                $('#color_id').css('background-color', '');
                $('#color_id').css('color', '');
            }
        }

        // Run on page load
        updateColorSelect();

        // Run on change
        $('#color_id').change(updateColorSelect);

        // Toggle color selector visibility
        $('#has_no_colors').change(function() {
            if ($(this).is(':checked')) {
                $('#color_id').val('').parent().parent().hide();
            } else {
                $('#color_id').parent().parent().show();
            }
        });

        // Run on page load for "no colors" checkbox
        if ($('#has_no_colors').is(':checked')) {
            $('#color_id').parent().parent().hide();
        }

        // Toggle percentage price adjustment
        $('#use_percentage').change(function() {
            if ($(this).is(':checked')) {
                $('#percentage-adjustment').show();
                const basePrice = $('#product_id option:selected').data('price') || 0;
                $('#base_price').val(basePrice);
                updatePriceCalculation();
            } else {
                $('#percentage-adjustment').hide();
            }
        });

        // Apply percentage to price
        $('#apply-percentage').click(function() {
            const basePrice = parseFloat($('#base_price').val()) || 0;
            const percentage = parseFloat($('#percentage').val()) || 0;

            if (basePrice > 0) {
                const increase = basePrice * (percentage / 100);
                const newPrice = basePrice + increase;

                // Update price field
                $('#price').val(newPrice.toFixed(2));

                // Update display calculation
                updatePriceCalculation();
            }
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

        // Add animation to form sections
        $('.form-section').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css('opacity', '0').css('transform', 'translateY(20px)');
                setTimeout(function() {
                    $this.css('transition', 'all 0.3s ease')
                         .css('opacity', '1')
                         .css('transform', 'translateY(0)');
                }, 50);
            }, index * 100);
        });
    });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Edit Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <style>
        .note-editor .note-toolbar {
            background-color: #f8f9fa;
        }
        .template-content {
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: #fdfdfd;
        }
        .template-selector-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .selected-templates {
            margin-bottom: 15px;
        }
        .selected-template-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            margin-bottom: 5px;
            background-color: #f4f6f9;
            border-radius: 4px;
            border-left: 3px solid #6777ef;
        }
        .selected-template-name {
            font-weight: 500;
        }
        .selected-template-remove {
            cursor: pointer;
            color: #cdd3d8;
            transition: all 0.2s;
        }
        .selected-template-remove:hover {
            color: #fc544b;
        }
        #templateSelectorModal {
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .template-item {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .template-item:hover {
            border-color: #6777ef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .template-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .template-item-name {
            font-weight: 600;
        }
        .template-item-category {
            font-size: 11px;
            background-color: #6777ef;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .template-item-content {
            font-size: 12px;
            color: #6c757d;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        #templatePreview {
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 15px;
            background-color: white;
        }
        .preview-content {
            min-height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #f1f1f1;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
<section class="section products-section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Edit Produk</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit {{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex align-items-center">
                        <i class="fas fa-edit text-primary mr-2" style="font-size: 1.5rem;"></i>
                        <h4 class="products-title mb-0">Form Edit Produk</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="sku">SKU</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" readonly>
                                        <small class="form-text text-muted">Biarkan kosong untuk generate otomatis (KAT-PRD-tglbl-XXX)</small>
                                        @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Harga <span class="text-danger">*</span></label>
                                        <div class="price-system">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="price_type" id="priceTypeSingle" value="single" {{ !$product->has_size_prices ? 'checked' : '' }}>
                                                <label class="form-check-label" for="priceTypeSingle">
                                                    Harga Satuan
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="price_type" id="priceTypeRange" value="range" {{ $product->has_size_prices ? 'checked' : '' }}>
                                                <label class="form-check-label" for="priceTypeRange">
                                                    Harga dengan Range
                                                </label>
                                            </div>

                                            <div id="singlePriceSection" style="{{ $product->has_size_prices ? 'display: none;' : '' }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control @error('price') is-invalid @enderror" id="display-price" placeholder="0" required>
                                                    <input type="hidden" name="price" id="real-price" value="{{ old('price', $product->price) }}">
                                                </div>
                                                <small class="text-muted">Contoh: 1.000.000</small>
                                            </div>

                                            <div id="rangePriceSection" style="{{ !$product->has_size_prices ? 'display: none;' : '' }}">
                                                <div class="form-group">
                                                    <label>Harga Dasar</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control" id="base-price" placeholder="0" value="{{ old('base_price', $product->base_price) }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Persentase Kenaikan per Size</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="price-increase" placeholder="0" min="0" max="100" value="{{ old('price_increase', $product->price_increase) }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="sizePricesSection" class="mt-3">
                                                <h6>Harga per Size</h6>
                                                <div class="size-prices">
                                                    @php
                                                        $sizePrices = $product->size_prices ?? [];
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>XS</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="XS" name="size_prices[XS]" placeholder="0" value="{{ old('size_prices.XS', $sizePrices['XS'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>S</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="S" name="size_prices[S]" placeholder="0" value="{{ old('size_prices.S', $sizePrices['S'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>M</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="M" name="size_prices[M]" placeholder="0" value="{{ old('size_prices.M', $sizePrices['M'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>L</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="L" name="size_prices[L]" placeholder="0" value="{{ old('size_prices.L', $sizePrices['L'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>XL</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="XL" name="size_prices[XL]" placeholder="0" value="{{ old('size_prices.XL', $sizePrices['XL'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>XXL</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control size-price" data-size="XXL" name="size_prices[XXL]" placeholder="0" value="{{ old('size_prices.XXL', $sizePrices['XXL'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="stock">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                        <div class="text-warning mt-1" id="stock-warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle"></i> Jika anda menggunakan ukuran, nilai stok ini akan digunakan untuk semua ukuran yang tidak memiliki stok spesifik.
                                        </div>
                                        @error('stock')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <div class="template-selector">
                                            <div class="template-selector-header">
                                                <h6 class="template-selector-title">Template Deskripsi</h6>
                                                <button type="button" class="btn btn-sm btn-primary" id="showTemplateSelector">
                                                    <i class="fas fa-plus-circle"></i> Tambah Template
                                                </button>
                                            </div>

                                            <div id="templateSelectorModal" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control" id="templateCategory">
                                                        <option value="">Semua Kategori</option>
                                                    </select>
                                                </div>

                                                <div class="template-list" id="templateList">
                                                    <div class="text-center py-3">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <p class="mt-2">Memuat template...</p>
                                                    </div>
                                                </div>

                                                <div class="template-preview" id="templatePreview" style="display: none;">
                                                    <h6>Pratinjau Template</h6>
                                                    <div class="preview-content" id="previewContent"></div>
                                                    <div class="mt-2 text-right">
                                                        <button type="button" class="btn btn-sm btn-secondary" id="closePreview">Kembali</button>
                                                        <button type="button" class="btn btn-sm btn-primary" id="useTemplate">Gunakan Template</button>
                                                    </div>
                                                </div>

                                                <div class="mt-2 text-right">
                                                    <button type="button" class="btn btn-sm btn-secondary" id="closeTemplateSelector">Tutup</button>
                                                </div>
                                            </div>

                                            <div class="selected-templates" id="selectedTemplates">
                                                @php
                                                    $selectedTemplateIds = $product->descriptionTemplates()->pluck('description_templates.id')->toArray();
                                                @endphp

                                                @foreach($product->descriptionTemplates as $template)
                                                <div class="selected-template-item" data-id="{{ $template->id }}">
                                                    <span class="selected-template-name">{{ $template->name }}</span>
                                                    <input type="hidden" name="description_templates[]" value="{{ $template->id }}">
                                                    <span class="selected-template-remove"><i class="fas fa-times"></i></span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <small class="form-text text-muted">Tambahan deskripsi manual (opsional).</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ukuran Tersedia</label>
                                        <div class="form-group">
                                            <div class="selectgroup selectgroup-pills size-options">
                                                @php
                                                    $selectedSizeIds = $product->sizes()->pluck('sizes.id')->toArray();
                                                @endphp
                                                @foreach($sizes as $size)
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="selectgroup-input size-checkbox"
                                                        {{ in_array($size->id, old('sizes', $selectedSizeIds) ?: []) ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ $size->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>

                                            <div class="mt-3 mb-3">
                                                <h6 class="text-primary">Stok per Ukuran</h6>
                                                <p class="text-muted small">Jika tidak diisi, akan menggunakan stok global</p>
                                                <div class="size-stock-container">
                                                    @foreach($sizes as $size)
                                                    @php
                                                        $sizeStock = $product->sizeStocks()->where('size_id', $size->id)->first();
                                                        $stockValue = $sizeStock ? $sizeStock->stock : 0;
                                                    @endphp
                                                    <div class="form-group size-stock-item" data-size-id="{{ $size->id }}" style="{{ in_array($size->id, $selectedSizeIds) ? '' : 'display: none;' }}">
                                                        <label for="size_stock_{{ $size->id }}">Stok untuk {{ $size->name }}</label>
                                                        <input type="number" class="form-control" id="size_stock_{{ $size->id }}" name="size_stock_{{ $size->id }}" value="{{ old('size_stock_' . $size->id, $stockValue) }}" min="0">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <p class="text-muted mb-2 mt-3">Tambahkan ukuran baru:</p>
                                            <div class="size-input-group">
                                                <input type="text" id="new-size" class="form-control" placeholder="Nama ukuran baru">
                                                <button type="button" id="add-size-btn" class="add-size-btn">
                                                    <i class="fas fa-plus mr-1"></i> Tambah
                                                </button>
                                            </div>
                                            <div id="size-chips" class="size-chips">
                                                @if(is_array(old('sizes')))
                                                    @foreach(old('sizes') as $sizeValue)
                                                        @if(!is_numeric($sizeValue))
                                                        <div class="size-chip">
                                                            {{ $sizeValue }}
                                                            <input type="hidden" name="sizes[]" value="{{ $sizeValue }}">
                                                            <span class="chip-remove"><i class="fas fa-times"></i></span>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Warna Tersedia</label>
                                        <div class="color-options">
                                            <!-- Database Colors -->
                                            <p class="text-muted mb-2">Pilih warna tersedia:</p>
                                            <div class="selectgroup selectgroup-pills color-options">
                                                @php
                                                    $selectedColorIds = $product->colors()->pluck('colors.id')->toArray();
                                                @endphp
                                                @foreach($colors as $color)
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="selectgroup-input color-checkbox"
                                                        {{ in_array($color->id, old('colors', $selectedColorIds) ?: []) ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">
                                                        <span class="color-dot" style="background-color: #{{ $color->hex_code }}"></span>
                                                        {{ $color->name }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>

                                            <!-- Custom Colors -->
                                            <p class="text-muted mb-2 mt-3">Tambahkan warna kustom:</p>
                                            <div class="color-input-group">
                                                <input type="text" id="new-color" class="form-control" placeholder="Nama warna baru">
                                                <input type="color" id="color-picker" class="form-control color-picker" value="#6777ef">
                                                <button type="button" id="add-color-btn" class="add-color-btn">
                                                    <i class="fas fa-plus mr-1"></i> Tambah
                                                </button>
                                            </div>

                                            <!-- Color Chips - Will show custom colors -->
                                            <div id="color-chips" class="color-chips">
                                                @if(is_array(old('colors')))
                                                    @foreach(old('colors') as $colorValue)
                                                        @if(!is_numeric($colorValue))
                                                        <div class="color-chip">
                                                            <span class="chip-color-dot" style="background-color: {{ \App\Helpers\ColorHelper::getColorHex($colorValue) }}"></span>
                                                            {{ $colorValue }}
                                                            <input type="hidden" name="colors[]" value="{{ $colorValue }}">
                                                            <input type="hidden" name="color_hex_codes[]" value="{{ substr(\App\Helpers\ColorHelper::getColorHex($colorValue), 1) }}">
                                                            <span class="chip-remove"><i class="fas fa-times"></i></span>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Gambar Utama</label>
                                        @if($product->image)
                                        <div class="mb-2 current-image">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail product-image" style="max-height: 200px;">
                                        </div>
                                        @endif
                                        <div class="file-input-container">
                                            <div class="file-input-field" id="image-filename">{{ $product->image ? 'Ganti Gambar' : 'Pilih Gambar' }}</div>
                                            <button type="button" class="file-input-btn">Browse</button>
                                            <input type="file" class="file-input" id="image" name="image" accept="image/*">
                                        </div>
                                            @error('image')
                                        <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        <div id="image-preview" class="file-preview mt-2"></div>
                                        <small class="form-text text-muted">Format: JPG, PNG, JPEG, GIF. Maks: 2MB</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="additional_images">Gambar Tambahan</label>
                                        @if($product->additional_images && count($product->additional_images) > 0)
                                        <div class="mb-2 row additional-images">
                                            @foreach($product->additional_images as $image)
                                            <div class="col-4 mb-2">
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="img-thumbnail product-image" style="max-height: 100px;">
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <div class="file-input-container">
                                            <div class="file-input-field" id="additional-images-filename">{{ $product->additional_images ? 'Ganti Gambar Tambahan' : 'Pilih Beberapa Gambar' }}</div>
                                            <button type="button" class="file-input-btn">Browse</button>
                                            <input type="file" class="file-input" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                        </div>
                                            @error('additional_images')
                                        <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        <div id="additional-images-preview" class="file-preview mt-2"></div>
                                        <small class="form-text text-muted">Format: JPG, PNG, JPEG, GIF. Maks: 2MB per file. Maksimal 5 file. Mengunggah foto baru akan mengganti semua foto tambahan yang ada.</small>
                                    </div>

                                    <div class="form-group form-switch-container">
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="is_featured" class="custom-switch-input" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Produk Unggulan</span>
                                        </label>
                                    </div>

                                    <div class="form-group form-switch-container">
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="is_active" class="custom-switch-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Aktif</span>
                                        </label>
                                </div>
                            </div>

                            <div class="form-group form-actions">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('js/admin/products/price-system.js') }}"></script>
<script>
    $(document).ready(function() {
        // Size stock management
        $('.size-checkbox').on('change', function() {
            const sizeId = $(this).val();
            const isChecked = $(this).is(':checked');

            // Show/hide the stock input for this size
            if (isChecked) {
                $(`.size-stock-item[data-size-id="${sizeId}"]`).show();
                // Populate with global stock value if it's zero
                const currentSizeStock = $(`#size_stock_${sizeId}`).val();
                if (currentSizeStock === '0' || currentSizeStock === '') {
                    $(`#size_stock_${sizeId}`).val($('#stock').val());
                }
                // Show the warning message
                $('#stock-warning').show();
            } else {
                $(`.size-stock-item[data-size-id="${sizeId}"]`).hide();
                // Hide warning if no sizes are checked
                if ($('.size-checkbox:checked').length === 0) {
                    $('#stock-warning').hide();
                }
            }
        });

        // Initialize size stock visibility
        $('.size-checkbox').each(function() {
            if ($(this).is(':checked')) {
                const sizeId = $(this).val();
                $(`.size-stock-item[data-size-id="${sizeId}"]`).show();
                // Show warning if any size is checked
                $('#stock-warning').show();
            }
        });

        // When global stock changes, update all empty size stocks
        $('#stock').on('change', function() {
            const globalStock = $(this).val();
            $('.size-checkbox:checked').each(function() {
                const sizeId = $(this).val();
                const sizeStockField = $(`#size_stock_${sizeId}`);
                // Only update if the current value is 0
                if (sizeStockField.val() === '0' || sizeStockField.val() === '') {
                    sizeStockField.val(globalStock);
                }
            });
        });

        // Initialize Summernote WYSIWYG editor
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Tulis deskripsi produk di sini...',
            callbacks: {
                onImageUpload: function(files) {
                    alert('Unggah gambar tidak diizinkan. Gunakan URL gambar jika diperlukan.');
                }
            }
        });

        // Price formatting
        function formatNumberWithDots(number) {
            if (!number) return '';

            // Convert to string and clean up the number first
            let cleanNumber = number;
            if (typeof cleanNumber === 'string') {
                // Remove all dots and replace commas with dots
                cleanNumber = cleanNumber.replace(/\./g, '');
                cleanNumber = cleanNumber.replace(/,/g, '.');
            }

            // Ensure it's a valid number
            if (isNaN(Number(cleanNumber))) {
                console.error('Invalid number format:', number);
                return '0';
            }

            // Get the integer part only for formatting
            const integerPart = Math.floor(Number(cleanNumber)).toString();

            // Format with dots as thousand separators (Indonesian format)
            return integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Initialize price display
        const initialPrice = $('#real-price').val();
        console.log('Original price from database:', initialPrice);

        if (initialPrice) {
            // Handle decimal point properly
            let cleanInitialPrice;

            // Check if price has a decimal part
            if (initialPrice.toString().includes('.')) {
                // Split by decimal point
                let parts = initialPrice.toString().split('.');
                // Clean only the integer part (remove thousand separators)
                parts[0] = parts[0].replace(/\./g, '');
                // Reconstruct the number with just integer part (drop the decimals)
                cleanInitialPrice = parts[0];
            } else {
                // No decimal point, just remove any thousand separators
                cleanInitialPrice = initialPrice.toString().replace(/\./g, '');
            }

            console.log('Cleaned initial price:', cleanInitialPrice);

            // Now format the clean price
            const initialDisplayPrice = formatNumberWithDots(cleanInitialPrice);
            console.log('Formatted display price:', initialDisplayPrice);

            $('#display-price').val(initialDisplayPrice);
            // Update the real price field with the clean value
            $('#real-price').val(cleanInitialPrice);
        }

        // Handle price input changes
        $('#display-price').on('input', function(e) {
            // Ambil nilai input saat ini dan hapus semua karakter kecuali angka
            let inputValue = $(this).val().replace(/[^\d]/g, '');

            // Jika kosong, tampilkan kosong
            if (inputValue === '') {
                $(this).val('');
                $('#real-price').val('');
                return;
            }

            // Simpan nilai bersih ke hidden field
            $('#real-price').val(inputValue);

            // Format ulang dan tampilkan (tanpa padding angka 0 di depan)
            const formattedValue = formatNumberWithDots(inputValue);
            $(this).val(formattedValue);
        });

        // Tambahkan validasi form sebelum submit untuk memastikan stok ukuran terisi
        $('form').on('submit', function(e) {
            // Validasi harga...
            const displayValue = $('#display-price').val();
            const cleanValue = displayValue.replace(/\./g, '');
            $('#real-price').val(cleanValue);

            // Handle price system values
            const priceType = $('input[name="price_type"]:checked').val();
            if (priceType === 'range') {
                // Format and set base price
                const basePrice = $('#base-price').val().replace(/\./g, '');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'base_price',
                    value: basePrice
                }).appendTo('form');

                // Set price increase
                const priceIncrease = $('#price-increase').val();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'price_increase',
                    value: priceIncrease
                }).appendTo('form');

                // Format size prices
                $('.size-price').each(function() {
                    const input = $(this);
                    const value = input.val();
                    if (value) {
                        input.val(value.replace(/\./g, ''));
                    }
                });
            }

            // Validasi stok ukuran
            let hasSizes = $('.size-checkbox:checked').length > 0;
            if (hasSizes) {
                let hasEmptyStock = false;
                $('.size-checkbox:checked').each(function() {
                    const sizeId = $(this).val();
                    const stockField = $(`#size_stock_${sizeId}`);
                    if (stockField.val() === '' || stockField.val() === '0') {
                        hasEmptyStock = true;
                        stockField.val($('#stock').val()); // Isi dengan stok global
                    }
                });
            }

            console.log('Nilai harga yang dikirim:', cleanValue);
        });

        // Custom file input
        $(".file-input").on("change", function() {
            var fileField = $(this).siblings('.file-input-field');
            var fileName = "";

            if ($(this).attr('multiple') && this.files.length > 1) {
                fileName = this.files.length + " file dipilih";
            } else if (this.files.length > 0) {
                fileName = this.files[0].name;
            } else {
                fileName = $(this).attr('id') === 'image' ? 'Pilih Gambar' : 'Pilih Beberapa Gambar';
            }

            if (this.files.length > 0) {
                fileField.addClass('has-file');
            } else {
                fileField.removeClass('has-file');
            }

            fileField.text(fileName);

            // Generate preview
            var preview = $(this).attr('id') === 'image' ? $('#image-preview') : $('#additional-images-preview');
            preview.empty();

            if (this.files) {
                for (var i = 0; i < this.files.length; i++) {
                    if (i >= 5 && $(this).attr('multiple')) break; // Max 5 files for additional images

                    const file = this.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = $('<div class="file-preview-item"><img src="' + e.target.result + '" alt="Preview"><div class="file-preview-remove"><i class="fas fa-times"></i></div></div>');
                        preview.append(img);
                    }

                    reader.readAsDataURL(file);
                }
            }
        });

        // Also trigger file input on button click (for better UX)
        $(".file-input-btn").on("click", function(e) {
            e.preventDefault();
            $(this).siblings('.file-input').click();
        });

        // Remove preview on click
        $(document).on('click', '.file-preview-remove', function() {
            $(this).parent('.file-preview-item').remove();
            // Reset file input
            var formGroup = $(this).closest('.form-group');
            var fileInput = formGroup.find('.file-input');
            var fileField = formGroup.find('.file-input-field');

            fileInput.val('');

            var defaultText = '';
            if (fileInput.attr('id') === 'image') {
                defaultText = '{{ $product->image ? "Ganti Gambar" : "Pilih Gambar" }}';
            } else {
                defaultText = '{{ $product->additional_images ? "Ganti Gambar Tambahan" : "Pilih Beberapa Gambar" }}';
            }

            fileField.text(defaultText).removeClass('has-file');

            // If no more preview items, clear preview area
            if (formGroup.find('.file-preview-item').length === 0) {
                formGroup.find('.file-preview').empty();
            }
        });

        // Add animation to form elements
        $('input:not(.file-input), select, textarea').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css('opacity', '0');
                setTimeout(function() {
                    $this.css('transition', 'all 0.3s ease')
                         .css('opacity', '1');
                }, 50);
            }, index * 30);
        });

        // Add animation to images
        $('.product-image').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.addClass('fade-in');
            }, index * 100);
        });

        // Color management
        $('#add-color-btn').on('click', function() {
            addNewColor();
        });

        $('#new-color').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                addNewColor();
            }
        });

        // Size management
        $('#add-size-btn').on('click', function() {
            addNewSize();
        });

        $('#new-size').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                addNewSize();
            }
        });

        // Remove color/size chip when X is clicked
        $(document).on('click', '.chip-remove', function() {
            $(this).closest('.color-chip, .size-chip').remove();
        });

        // Function to add a new color
        function addNewColor() {
            const colorInput = $('#new-color');
            const colorName = colorInput.val().trim();
            const colorHex = $('#color-picker').val().replace('#', '');

            if (colorName !== '') {
                // Check if color already exists
                let exists = false;

                // Check in database colors
                $('.color-checkbox').each(function() {
                    const colorText = $(this).closest('.selectgroup-item').find('.selectgroup-button').text().trim();
                    if (colorText === colorName) {
                        exists = true;
                        return false; // Break loop
                    }
                });

                // Check in custom chips
                if (!exists) {
                    $('.color-chip').each(function() {
                        const chipText = $(this).text().trim().replace('×', '').trim();
                        if (chipText === colorName) {
                            exists = true;
                            return false; // Break loop
                        }
                    });
                }

                if (exists) {
                    alert('Warna tersebut sudah dipilih!');
                    return;
                }

                // Create a color chip with the new color
                const colorChip = `
                    <div class="color-chip">
                        <span class="chip-color-dot" style="background-color: #${colorHex}"></span>
                        ${colorName}
                        <input type="hidden" name="colors[]" value="${colorName}">
                        <input type="hidden" name="color_hex_codes[]" value="${colorHex}">
                        <span class="chip-remove"><i class="fas fa-times"></i></span>
                    </div>
                `;

                $('#color-chips').append(colorChip);
                colorInput.val('');

                // Save color to database via AJAX and update dropdown
                $.ajax({
                    url: '{{ route("colors.ajax.store") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: colorName,
                        hex_code: colorHex,
                        is_active: 1
                    },
                    success: function(response) {
                        if (response.success) {
                            // Add new color to selectbox
                            const newColorOption = `
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="colors[]" value="${response.color.id}" class="selectgroup-input color-checkbox" checked>
                                    <span class="selectgroup-button">
                                        <span class="color-dot" style="background-color: #${colorHex}"></span>
                                        ${colorName}
                                    </span>
                                </label>
                            `;
                            $('.color-options .selectgroup-pills').append(newColorOption);

                            // Remove the custom chip since we're now using the database color
                            $('.color-chip').each(function() {
                                const chipText = $(this).text().trim().replace('×', '').trim();
                                if (chipText === colorName) {
                                    $(this).remove();
                                }
                            });
                        }
                    }
                });
            }
        }

        // Function to add a new size
        function addNewSize() {
            const sizeInput = $('#new-size');
            const sizeName = sizeInput.val().trim();

            if (sizeName !== '') {
                // Check if size already exists
                let exists = false;

                // Check in database sizes
                $('.selectgroup-input[name="sizes[]"]').each(function() {
                    const sizeText = $(this).closest('.selectgroup-item').find('.selectgroup-button').text().trim();
                    if (sizeText === sizeName) {
                        exists = true;
                        return false; // Break loop
                    }
                });

                // Check in custom chips
                if (!exists) {
                    $('.size-chip').each(function() {
                        const chipText = $(this).text().trim().replace('×', '').trim();
                        if (chipText === sizeName) {
                            exists = true;
                            return false; // Break loop
                        }
                    });
                }

                if (exists) {
                    alert('Ukuran tersebut sudah dipilih!');
                    return;
                }

                // Create a size chip
                const sizeChip = `
                    <div class="size-chip">
                        ${sizeName}
                        <input type="hidden" name="sizes[]" value="${sizeName}">
                        <span class="chip-remove"><i class="fas fa-times"></i></span>
                    </div>
                `;

                $('#size-chips').append(sizeChip);
                sizeInput.val('');

                // Save size to database via AJAX and update dropdown
                $.ajax({
                    url: '{{ route("sizes.ajax.store") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: sizeName,
                        is_active: 1
                    },
                    success: function(response) {
                        if (response.success) {
                            // Add new size to selectbox
                            const newSizeOption = `
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="sizes[]" value="${response.size.id}" class="selectgroup-input" checked>
                                    <span class="selectgroup-button">${sizeName}</span>
                                </label>
                            `;
                            $('.size-options.selectgroup-pills').append(newSizeOption);

                            // Remove the custom chip since we're now using the database size
                            $('.size-chip').each(function() {
                                const chipText = $(this).text().trim().replace('×', '').trim();
                                if (chipText === sizeName) {
                                    $(this).remove();
                                }
                            });
                        }
                    }
                });
            }
        }

        // Template selector functionality
        let templates = [];
        let selectedTemplate = null;

        // Load template categories
        $.ajax({
            url: '{{ route("description-templates.json") }}',
            type: 'GET',
            success: function(data) {
                templates = data;

                // Extract unique categories
                const categories = [...new Set(templates.map(t => t.category).filter(Boolean))];

                // Populate category dropdown
                categories.forEach(category => {
                    $('#templateCategory').append(`<option value="${category}">${category}</option>`);
                });

                // Render templates
                renderTemplates();
            },
            error: function() {
                $('#templateList').html('<div class="alert alert-danger">Gagal memuat template. Silakan coba lagi.</div>');
            }
        });

        function renderTemplates(category = '') {
            let filteredTemplates = templates;
            if (category) {
                filteredTemplates = templates.filter(t => t.category === category);
            }

            if (filteredTemplates.length === 0) {
                $('#templateList').html('<div class="alert alert-info">Tidak ada template yang tersedia.</div>');
                return;
            }

            let html = '';
            filteredTemplates.forEach(template => {
                // Check if already selected
                const isSelected = $('#selectedTemplates').find(`[data-id="${template.id}"]`).length > 0;
                if (!isSelected) {
                    html += `
                        <div class="template-item" data-id="${template.id}">
                            <div class="template-item-header">
                                <span class="template-item-name">${template.name}</span>
                                ${template.category ? `<span class="template-item-category">${template.category}</span>` : ''}
                            </div>
                            <div class="template-item-content">${template.content.substring(0, 100)}${template.content.length > 100 ? '...' : ''}</div>
                        </div>
                    `;
                }
            });

            $('#templateList').html(html);

            // Attach click event to template items
            $('.template-item').on('click', function() {
                const templateId = $(this).data('id');
                selectedTemplate = templates.find(t => t.id === templateId);

                // Show preview
                $('#templateList').hide();
                $('#templatePreview').show();

                // Replace placeholder variables with product values
                let previewContent = selectedTemplate.content;
                previewContent = previewContent.replace(/\[product_name\]/g, '{{ $product->name }}');
                previewContent = previewContent.replace(/\[category\]/g, '{{ $product->category->name }}');
                previewContent = previewContent.replace(/\[price\]/g, 'Rp ' + formatNumberWithDots({{ $product->price }}));

                $('#previewContent').html(previewContent);
            });
        }

        // Filter templates by category
        $('#templateCategory').on('change', function() {
            renderTemplates($(this).val());
        });

        // Close preview
        $('#closePreview').on('click', function() {
            $('#templatePreview').hide();
            $('#templateList').show();
            selectedTemplate = null;
        });

        // Use template
        $('#useTemplate').on('click', function() {
            if (selectedTemplate) {
                // Add to selected templates
                const templateHtml = `
                    <div class="selected-template-item" data-id="${selectedTemplate.id}">
                        <span class="selected-template-name">${selectedTemplate.name}</span>
                        <input type="hidden" name="description_templates[]" value="${selectedTemplate.id}">
                        <span class="selected-template-remove"><i class="fas fa-times"></i></span>
                    </div>
                `;

                $('#selectedTemplates').append(templateHtml);

                // Insert content to editor
                let templateContent = selectedTemplate.content;
                templateContent = templateContent.replace(/\[product_name\]/g, '{{ $product->name }}');
                templateContent = templateContent.replace(/\[category\]/g, '{{ $product->category->name }}');
                templateContent = templateContent.replace(/\[price\]/g, 'Rp ' + formatNumberWithDots({{ $product->price }}));

                // Bersihkan tag kosong di awal/akhir
                templateContent = cleanEditorContent(templateContent);

                // Append to existing content in editor
                let currentContent = $('.summernote').summernote('code');
                currentContent = cleanEditorContent(currentContent);
                if (currentContent) {
                    currentContent += '<br>' + templateContent;
                } else {
                    currentContent = templateContent;
                }
                $('.summernote').summernote('code', currentContent);

                // Close preview
                $('#templatePreview').hide();
                $('#templateList').show();

                // Re-render templates to exclude the selected one
                renderTemplates($('#templateCategory').val());

                // Attach to product via AJAX for immediate effect
                $.ajax({
                    url: '{{ route("description-templates.attach") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: {{ $product->id }},
                        template_id: selectedTemplate.id
                    }
                });

                selectedTemplate = null;
            }
        });

        // Remove selected template
        $(document).on('click', '.selected-template-remove', function() {
            const templateItem = $(this).closest('.selected-template-item');
            const templateId = templateItem.data('id');

            // Remove from DOM
            templateItem.remove();

            // Re-render templates to include the removed one
            renderTemplates($('#templateCategory').val());

            // Detach from product via AJAX for immediate effect
            $.ajax({
                url: '{{ route("description-templates.detach") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: {{ $product->id }},
                    template_id: templateId
                }
            });
        });

        // Show/hide template selector
        $('#showTemplateSelector').on('click', function() {
            $('#templateSelectorModal').show();
        });

        $('#closeTemplateSelector').on('click', function() {
            $('#templateSelectorModal').hide();
            $('#templatePreview').hide();
            $('#templateList').show();
            selectedTemplate = null;
        });

        function cleanEditorContent(html) {
            // Hapus <br>, <p><br></p>, <p></p> di awal dan akhir
            html = html.replace(/^(<br\s*\/?>|<p><br\s*\/?><\/p>|<p>\s*<\/p>)+/gi, '');
            html = html.replace(/(<br\s*\/?>|<p><br\s*\/?><\/p>|<p>\s*<\/p>)+$/gi, '');
            return html.trim();
        }
    });
</script>
@endpush

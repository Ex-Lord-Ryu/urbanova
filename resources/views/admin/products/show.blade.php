@extends('layouts.app')

@section('title', 'Detail Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .clean-html p:empty {
            display: none;
        }

        .clean-html br:first-child {
            display: none;
        }

        .clean-html br+br {
            display: none;
        }

        .clean-html ul {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .clean-html li {
            margin-bottom: 0.25rem;
        }

        .action-buttons .btn {
            width: 100%;
            border-radius: 4px;
            width: auto;
            height: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            transition: all 0.2s ease;
            opacity: 0.85;
            padding: 5px 10px;
        }

        /* Variant styles */
        .variant-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .variant-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .variant-header {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .variant-body {
            padding: 15px;
        }

        .color-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
            border: 1px solid rgba(0,0,0,0.1);
            vertical-align: middle;
        }

        .color-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .size-badge {
            background-color: #f1f1f1;
            color: #666;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            display: inline-block;
            margin: 2px;
        }

        .variants-detail-toggle {
            color: #007bff;
            font-size: 0.85rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .variants-detail-toggle:hover {
            color: #0056b3;
            text-decoration: none;
        }

        .variants-detail-toggle i {
            transition: transform 0.2s;
        }

        .variants-detail-toggle.active i {
            transform: rotate(180deg);
        }

        .stock-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .stock-badge.in-stock {
            background-color: #d4edda;
            color: #155724;
        }

        .stock-badge.low-stock {
            background-color: #fff3cd;
            color: #856404;
        }

        .stock-badge.out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }

        .variant-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .variant-price {
            font-weight: 600;
            color: #28a745;
        }
    </style>
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
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning"
                                    data-toggle="tooltip" title="Edit Produk">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                @if (Route::has('product-variants.index'))
                                    <a href="{{ route('product-variants.index', ['product_id' => $product->id]) }}"
                                        class="btn btn-info" data-toggle="tooltip" title="Kelola Varian">
                                        <i class="fas fa-cubes"></i> Kelola Varian
                                    </a>
                                @endif
                                <a href="{{ route('products.index') }}" class="btn btn-primary" data-toggle="tooltip"
                                    title="Kembali">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 text-center">
                                    <div class="mb-4 product-main-image">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="img-fluid rounded shadow-sm">
                                        @else
                                            <div class="alert alert-light rounded">Tidak ada gambar utama</div>
                                        @endif
                                    </div>

                                    @if ($product->additional_images && count($product->additional_images) > 0)
                                        <div class="row product-gallery">
                                            @foreach ($product->additional_images as $image)
                                                <div class="col-4 mb-2">
                                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                                        class="img-thumbnail product-image">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light">Tidak ada gambar tambahan</div>
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
                                                            <div class="product-color-dot"
                                                                style="background-color: #{{ substr(md5($product->name), 0, 6) }}">
                                                            </div>
                                                            <strong>{{ $product->name }}</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Kategori</td>
                                                    <td>
                                                        <a href="{{ route('categories.show', $product->category_id) }}"
                                                            class="badge badge-primary">
                                                            {{ $product->category->name }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>SKU</td>
                                                    <td><span class="text-muted">{{ $product->sku }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        @if ($product->is_active)
                                                            <span class="product-status active">Aktif</span>
                                                        @else
                                                            <span class="product-status inactive">Tidak Aktif</span>
                                                        @endif

                                                        @if ($product->is_featured)
                                                            <span class="product-status featured">Produk Unggulan</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Slug</td>
                                                    <td><span class="text-muted">{{ $product->slug }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Template Deskripsi</td>
                                                    <td>
                                                        @if ($product->descriptionTemplates && $product->descriptionTemplates->count() > 0)
                                                            @foreach ($product->descriptionTemplates as $template)
                                                                <span
                                                                    class="badge badge-light mr-1 mb-1">{{ $template->name }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">Tidak menggunakan template</span>
                                                        @endif
                                                    </td>
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

                                    <div class="mt-3">
                                        <h5 class="mb-3">Varian Produk</h5>
                                        @if($product->variants->count() > 0)
                                            <div class="variant-card">
                                                <div class="variant-header">
                                                    <div>
                                                        <i class="fas fa-cubes text-success mr-2"></i>
                                                        <strong>Ringkasan Varian</strong>
                                                    </div>
                                                    <a href="{{ route('product-variants.create') }}?product_id={{ $product->id }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-plus-circle"></i> Tambah Varian
                                                    </a>
                                                </div>
                                                <div class="variant-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <strong>Ukuran Tersedia:</strong>
                                                            </div>
                                                            <div>
                                                                @foreach($product->variants->pluck('size.name')->unique() as $size)
                                                                    <span class="size-badge">{{ $size }}</span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <strong>Warna Tersedia:</strong>
                                                            </div>
                                                            <div>
                                                                @foreach($product->variants->pluck('color')->unique() as $color)
                                                                    @if($color)
                                                                    <div class="d-inline-block mr-2 mb-1">
                                                                        <span class="color-badge" style="background-color: {{ $color->hex_code }}"></span>
                                                                        <span class="color-name">{{ $color->name }}</span>
                                                                    </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <strong>Rentang Harga:</strong>
                                                            </div>
                                                            <div class="variant-price">
                                                                @php
                                                                    $minPrice = $product->variants->min('price') ?? 0;
                                                                    $maxPrice = $product->variants->max('price') ?? 0;
                                                                @endphp

                                                                @if($minPrice == $maxPrice)
                                                                    @rupiah($minPrice)
                                                                @else
                                                                    @rupiah($minPrice) - @rupiah($maxPrice)
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <strong>Total Stok:</strong>
                                                            </div>
                                                            @php
                                                                $totalStock = $product->variants->sum('stock');
                                                            @endphp

                                                            @if($totalStock > 10)
                                                                <span class="stock-badge in-stock">{{ $totalStock }}</span>
                                                            @elseif($totalStock > 0)
                                                                <span class="stock-badge low-stock">{{ $totalStock }}</span>
                                                            @else
                                                                <span class="stock-badge out-of-stock">{{ $totalStock }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 text-center">
                                                        <a class="variants-detail-toggle" id="toggleVariantDetails">
                                                            <i class="fas fa-chevron-down mr-1"></i>
                                                            Lihat Detail Varian ({{ $product->variants->count() }})
                                                        </a>
                                                    </div>

                                                    <div id="variant-details" class="mt-3" style="display: none;">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped variant-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Ukuran</th>
                                                                        <th>Warna</th>
                                                                        <th>Harga</th>
                                                                        <th>Stok</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($product->variants as $variant)
                                                                    <tr>
                                                                        <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                                                        <td>
                                                                            @if($variant->color)
                                                                                <span class="color-badge" style="background-color: {{ $variant->color->hex_code }}"></span>
                                                                                <span class="color-name">{{ $variant->color->name }}</span>
                                                                            @else
                                                                                <span class="text-muted">N/A</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="variant-price">@rupiah($variant->price)</td>
                                                                        <td>
                                                                            @if($variant->stock > 10)
                                                                                <span class="stock-badge in-stock">{{ $variant->stock }}</span>
                                                                            @elseif($variant->stock > 0)
                                                                                <span class="stock-badge low-stock">{{ $variant->stock }}</span>
                                                                            @else
                                                                                <span class="stock-badge out-of-stock">{{ $variant->stock }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <div class="action-buttons">
                                                                                <a href="{{ route('product-variants.edit', $variant->id) }}?from_product=1" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                                                                    <i class="fas fa-pen"></i>
                                                                                </a>
                                                                                <form action="{{ route('product-variants.destroy', $variant->id) }}" method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <input type="hidden" name="from_product" value="1">
                                                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus varian ini?')">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-1"></i> Produk ini belum memiliki varian.
                                                <a href="{{ route('product-variants.create') }}?product_id={{ $product->id }}" class="alert-link">
                                                    Klik di sini untuk menambahkan varian.
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="product-description-title">Deskripsi Produk</h5>
                                    <div class="p-3 bg-light rounded product-description clean-html">
                                        @if ($product->description)
                                            {!! $product->description !!}
                                        @elseif($product->full_description)
                                            {!! $product->full_description !!}
                                        @else
                                            <span class="text-muted">Tidak ada deskripsi</span>
                                        @endif
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

            // Variant details toggle
            $('#toggleVariantDetails').on('click', function() {
                const $this = $(this);
                const $details = $('#variant-details');

                if ($details.is(':visible')) {
                    $details.slideUp(300);
                    $this.removeClass('active');
                    $this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    $this.html('<i class="fas fa-chevron-down mr-1"></i> Lihat Detail Varian ({{ $product->variants->count() }})');
                } else {
                    $details.slideDown(300);
                    $this.addClass('active');
                    $this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    $this.html('<i class="fas fa-chevron-up mr-1"></i> Sembunyikan Detail Varian');

                    // Add animation to rows
                    $details.find('tbody tr').each(function(index) {
                        const $row = $(this);
                        setTimeout(function() {
                            $row.css('opacity', '0').css('transform', 'translateY(10px)');
                            setTimeout(function() {
                                $row.css('transition', 'all 0.2s ease')
                                     .css('opacity', '1')
                                     .css('transform', 'translateY(0)');
                            }, 50);
                        }, index * 50);
                    });
                }
            });

            // Card animation on load
            $('.variant-card').css('opacity', '0').css('transform', 'translateY(20px)');
            setTimeout(function() {
                $('.variant-card').css('transition', 'all 0.5s ease')
                         .css('opacity', '1')
                         .css('transform', 'translateY(0)');
            }, 300);
        });
    </script>
@endpush

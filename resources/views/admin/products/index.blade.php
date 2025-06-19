@extends('layouts.app')

@section('title', 'Daftar Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .variants-detail-container {
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        }

        .variants-summary .badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .variants-detail-toggle {
            color: #007bff;
            font-size: 0.85rem;
            text-decoration: none;
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

        .variants-detail-row {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        /* Sortable table headers */
        .variants-table th {
            cursor: pointer;
            position: relative;
            padding-right: 20px;
            user-select: none;
        }

        .variants-table th:after {
            content: "\f0dc";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 8px;
            color: #ccc;
            font-size: 0.8em;
        }

        .variants-table th.sort-asc:after {
            content: "\f0de";
            color: #007bff;
        }

        .variants-table th.sort-desc:after {
            content: "\f0dd";
            color: #007bff;
        }
    </style>
@endpush

@section('content')
    <section class="section products-section">
        <div class="page-header-bg">
            <h1 class="products-title mb-3">Manajemen Produk</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
        </div>

        <!-- Stats Cards -->
        <div class="products-stats">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h3>{{ $products->total() }}</h3>
                <p>Total Produk</p>
            </div>
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>{{ $products->where('is_active', true)->count() }}</h3>
                <p>Produk Aktif</p>
            </div>
            <div class="stat-card inactive">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3>{{ $products->where('is_active', false)->count() }}</h3>
                <p>Produk Non-Aktif</p>
            </div>
            <div class="stat-card variant">
                <div class="stat-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <h3>{{ $productVariants->total() }}</h3>
                <p>Total Varian</p>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <!-- Products Table Card -->
                    <div class="card products-card mb-4">
                        <div class="card-header products-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tshirt text-primary mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="products-title mb-0">Daftar Produk</h4>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="search-wrapper mr-3">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control" id="searchProduct" placeholder="Cari produk...">
                                </div>
                                <a href="{{ route('products.create') }}" class="btn btn-primary add-product-btn">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Produk
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert success-alert alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <div class="table-responsive">
                                @if($products->count() > 0)
                                <table class="table table-striped products-table" id="table-products">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Gambar</th>
                                            <th>Nama</th>
                                            <th>SKU</th>
                                            <th>Kategori</th>
                                            <th>Rentang Harga</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                        @php
                                            $minPrice = $product->variants->min('price') ?? 0;
                                            $maxPrice = $product->variants->max('price') ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + $products->firstItem() }}</td>
                                            <td>
                                                @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" class="product-image">
                                                @else
                                                <span class="badge badge-light">Tidak ada gambar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $product->name }}</strong>

                                                    @if(count($product->colors) > 0)
                                                    <div class="mt-1">
                                                        @foreach($product->colors->take(3) as $color)
                                                        @php
                                                            // Cek apakah $color adalah object atau string
                                                            $colorObj = is_object($color) ? $color : \App\Models\Color::where('name', $color)->first();
                                                            $hexCode = $colorObj && $colorObj->hex_code ? $colorObj->hex_code : '#808080';
                                                            $colorName = is_object($color) ? $color->name : $color;
                                                        @endphp
                                                        <span class="product-color-dot" style="background-color: {{ $hexCode }}" title="{{ $colorName }}"></span>
                                                        @endforeach

                                                        @if(count($product->colors) > 3)
                                                        <small class="text-muted ml-1">+{{ count($product->colors) - 3 }}</small>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td><span class="text-muted">{{ $product->sku }}</span></td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>
                                                @if($minPrice == $maxPrice)
                                                    @rupiah($minPrice)
                                                @else
                                                    @rupiah($minPrice) - @rupiah($maxPrice)
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->is_active)
                                                <span class="product-status active">Aktif</span>
                                                @else
                                                <span class="product-status inactive">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info" data-toggle="tooltip" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning" data-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="empty-state">
                                    <img src="{{ asset('images/empty-state.svg') }}" alt="Tidak ada data" onerror="this.src='https://cdn-icons-png.flaticon.com/512/7486/7486754.png'; this.onerror='';">
                                    <h3 class="mt-3">Belum Ada Produk</h3>
                                    <p class="text-muted">Mulai tambahkan produk baru untuk toko Anda</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle mr-1"></i> Tambah Produk Pertama
                                    </a>
                                </div>
                                @endif
                            </div>

                            @if($products->count() > 0)
                            <div class="pagination-container mt-4 d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="badge badge-light p-2">
                                        <i class="fas fa-list-ul mr-1"></i>
                                        Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                                    </span>
                                </div>
                                <div class="pagination-links">
                                    {{ $products->links() }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Variants Table Card -->
                    <div class="card variants-card">
                        <div class="card-header variants-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cubes text-success mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="variants-title mb-0">Daftar Varian Produk</h4>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="search-wrapper mr-3">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control" id="searchVariant" placeholder="Cari varian...">
                                </div>
                                <a href="{{ route('product-variants.create') }}" class="btn btn-success add-variant-btn">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Varian
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @if($productVariants->count() > 0)
                                <table class="table table-striped variants-table" id="table-variants">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Produk</th>
                                            <th>Varian</th>
                                            <th>Harga</th>
                                            <th>Stok Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedVariants = $productVariants->groupBy('product_id');
                                            $counter = $productVariants->firstItem() ?? 1;
                                        @endphp

                                        @foreach ($groupedVariants as $productId => $variants)
                                        @php
                                            $product = $variants->first()->product;
                                            $totalStock = $variants->sum('stock');
                                            $minPrice = $variants->min('price');
                                            $maxPrice = $variants->max('price');
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td>{{ $product->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="variants-summary">
                                                    <div class="mb-1">
                                                        <strong>Ukuran:</strong>
                                                        @foreach($variants->pluck('size.name')->unique() as $size)
                                                            <span class="badge badge-secondary mr-1">{{ $size }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div>
                                                        <strong>Warna:</strong>
                                                        @foreach($variants->pluck('color')->unique() as $color)
                                                            @if ($color)
                                                            <span class="color-badge mr-1" style="background-color: {{ $color->hex_code }}">
                                                                {{ $color->name }}
                                                            </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <a href="#" class="variants-detail-toggle mt-2 d-inline-block" data-product="{{ $product->id }}">
                                                        <i class="fas fa-list-ul"></i> Lihat {{ $variants->count() }} varian
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                @if($minPrice == $maxPrice)
                                                    @rupiah($minPrice)
                                                @else
                                                    @rupiah($minPrice) - @rupiah($maxPrice)
                                                @endif
                                            </td>
                                            <td>
                                                @if($totalStock > 10)
                                                <span class="stock-badge in-stock">{{ $totalStock }}</span>
                                                @elseif($totalStock > 0)
                                                <span class="stock-badge low-stock">{{ $totalStock }}</span>
                                                @else
                                                <span class="stock-badge out-of-stock">{{ $totalStock }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info" data-toggle="tooltip" title="Detail Produk">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('product-variants.create') }}?product_id={{ $product->id }}" class="btn btn-success" data-toggle="tooltip" title="Tambah Varian">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Detail varian tersembunyi -->
                                        <tr class="variants-detail-row" id="variants-detail-{{ $product->id }}" style="display: none;">
                                            <td colspan="6">
                                                <div class="variants-detail-container p-3">
                                                    <h5 class="mb-3">Detail Varian {{ $product->name }}</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
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
                                                                @foreach($variants as $variant)
                                                                <tr>
                                                                    <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                                                    <td>
                                                                        @if ($variant->color)
                                                                        <span class="color-badge" style="background-color: {{ $variant->color->hex_code }}">
                                                                            {{ $variant->color->name }}
                                                                        </span>
                                                                        @else
                                                                        <span class="text-muted">N/A</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>@rupiah($variant->price)</td>
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
                                                                            <a href="{{ route('product-variants.edit', $variant->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                                                                <i class="fas fa-pen"></i>
                                                                            </a>
                                                                            <form action="{{ route('product-variants.destroy', $variant->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="empty-state">
                                    <img src="{{ asset('images/empty-state.svg') }}" alt="Tidak ada data" onerror="this.src='https://cdn-icons-png.flaticon.com/512/7486/7486754.png'; this.onerror='';">
                                    <h3 class="mt-3">Belum Ada Varian Produk</h3>
                                    <p class="text-muted">Tambahkan varian untuk produk Anda</p>
                                    <a href="{{ route('product-variants.create') }}" class="btn btn-success mt-3">
                                        <i class="fas fa-plus-circle mr-1"></i> Tambah Varian Pertama
                                    </a>
                                </div>
                                @endif
                            </div>

                            @if($productVariants->count() > 0)
                            <div class="pagination-container mt-4 d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="badge badge-light p-2">
                                        <i class="fas fa-list-ul mr-1"></i>
                                        Menampilkan {{ $productVariants->firstItem() ?? 0 }} - {{ $productVariants->lastItem() ?? 0 }} dari {{ $productVariants->total() }} varian
                                    </span>
                                </div>
                                <div class="pagination-links">
                                    {{ $productVariants->links() }}
                                </div>
                            </div>
                            @endif
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
        // Initialize DataTable for products
        $('#table-products').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "responsive": true,
            "searching": false
        });

        // DISABLE DataTables for variants table - causing issues with nested rows
        // Instead, use our custom search functionality
        /*
        $('#table-variants').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "responsive": true,
            "searching": false,
            "rowGroup": {
                "dataSrc": 1 // Group by product column (index 1)
            },
            // Exclude detail rows from DataTable processing
            "rowCallback": function(row, data, index) {
                if($(row).hasClass('variants-detail-row')) {
                    $(row).attr('data-dt-row', index);
                    return false; // Don't process this row
                }
                return row;
            }
        });
        */

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Custom sorting for variant table
        $('.variants-table th').on('click', function() {
            var table = $(this).parents('table');
            var rows = table.find('tbody tr:not(.variants-detail-row)').toArray();
            var index = $(this).index();
            var direction = $(this).hasClass('sort-asc') ? -1 : 1;

            // Toggle sort direction
            $(this).siblings().removeClass('sort-asc sort-desc');
            if ($(this).hasClass('sort-asc')) {
                $(this).removeClass('sort-asc').addClass('sort-desc');
            } else {
                $(this).removeClass('sort-desc').addClass('sort-asc');
            }

            // Sort rows
            rows.sort(function(a, b) {
                var aValue = $(a).find('td').eq(index).text().trim();
                var bValue = $(b).find('td').eq(index).text().trim();

                // Handle numeric values
                if (!isNaN(aValue) && !isNaN(bValue)) {
                    return direction * (parseFloat(aValue) - parseFloat(bValue));
                }

                // Handle text values
                return direction * aValue.localeCompare(bValue);
            });

            // Reattach rows to table
            $.each(rows, function(index, row) {
                table.find('tbody').append(row);

                // If this row has an expanded detail, attach it after this row
                var productId = $(row).find('.variants-detail-toggle').data('product');
                if (productId) {
                    var detailRow = $('#variants-detail-' + productId);
                    if (detailRow.length) {
                        $(row).after(detailRow);
                    }
                }
            });
        });

        // Search functionality for products
        $("#searchProduct").on("keyup", function() {
            var value = $(this).val().toLowerCase();

            // Toggle visibility
            $("#table-products tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });

            // Add animation to matched items
            if (value.length > 0) {
                setTimeout(function() {
                    $("#table-products tbody tr:visible").addClass('animate__animated animate__pulse');
                }, 300);
            } else {
                $("#table-products tbody tr").removeClass('animate__animated animate__pulse');
            }
        });

        // Search functionality for variants
        $("#searchVariant").on("keyup", function() {
            var value = $(this).val().toLowerCase();

            // Toggle visibility - only search in parent rows, not detail rows
            $("#table-variants tbody tr:not(.variants-detail-row)").filter(function() {
                var shouldShow = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(shouldShow);

                // If detail row is expanded, toggle it as well
                var rowId = $(this).find('.variants-detail-toggle').data('product');
                if (rowId && $('#variants-detail-' + rowId).is(':visible')) {
                    $('#variants-detail-' + rowId).toggle(shouldShow);
                }

                return true;
            });

            // Add animation to matched items
            if (value.length > 0) {
                setTimeout(function() {
                    $("#table-variants tbody tr:visible:not(.variants-detail-row)").addClass('animate__animated animate__pulse');
                }, 300);
            } else {
                $("#table-variants tbody tr:not(.variants-detail-row)").removeClass('animate__animated animate__pulse');
            }
        });

        // Fade out alerts after 5 seconds
        setTimeout(function() {
            $('.success-alert').fadeOut('slow');
        }, 5000);

        // Add animation to product rows on page load
        $("#table-products tbody tr").each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css('opacity', '0').css('transform', 'translateX(20px)');
                setTimeout(function() {
                    $this.css('transition', 'all 0.3s ease')
                         .css('opacity', '1')
                         .css('transform', 'translateX(0)');
                }, 50);
            }, index * 50);
        });

        // Add animation to variant rows on page load
        $("#table-variants tbody tr:not(.variants-detail-row)").each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css('opacity', '0').css('transform', 'translateX(20px)');
                setTimeout(function() {
                    $this.css('transition', 'all 0.3s ease')
                         .css('opacity', '1')
                         .css('transform', 'translateX(0)');
                }, 50);
            }, (index * 50) + 500); // Add delay to start after products animation
        });

        // Toggle detail row
        $('.variants-detail-toggle').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('product');
            const detailRow = $('#variants-detail-' + productId);

            // Close any previously opened rows
            $('.variants-detail-row').not(detailRow).hide();
            $('.variants-detail-toggle').not(this).removeClass('active');

            // Toggle current row
            if (detailRow.is(':visible')) {
                detailRow.hide();
                $(this).removeClass('active');
            } else {
                detailRow.show();
                $(this).addClass('active');

                // Add animation to the detail row
                detailRow.find('tr').each(function(index) {
                    const $this = $(this);
                    setTimeout(function() {
                        $this.css('opacity', '0').css('transform', 'translateY(10px)');
                        setTimeout(function() {
                            $this.css('transition', 'all 0.2s ease')
                                 .css('opacity', '1')
                                 .css('transform', 'translateY(0)');
                        }, 50);
                    }, index * 30);
                });
            }
        });
    });
</script>
@endpush

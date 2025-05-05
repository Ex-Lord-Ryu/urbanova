@extends('layouts.app')

@section('title', 'Daftar Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
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
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tshirt text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="products-title mb-0">Daftar Produk Pakaian</h4>
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
                                        <th>Kategori</th>
                                        <th>SKU</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $index => $product)
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
                                                    @foreach(array_slice($product->colors, 0, 3) as $color)
                                                    @php
                                                        // Cek apakah $color adalah object atau string
                                                        $colorObj = is_object($color) ? $color : \App\Models\Color::where('name', $color)->first();
                                                        $hexCode = $colorObj && $colorObj->hex_code ? '#'.$colorObj->hex_code : '#808080';
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
                                        <td>{{ $product->category->name }}</td>
                                        <td><span class="text-muted">{{ $product->sku }}</span></td>
                                        <td>@rupiah($product->price)</td>
                                        <td>{{ $product->stock }}</td>
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
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#table-products').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "responsive": true,
            "searching": false
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Search functionality with highlight
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

        // Fade out alerts after 5 seconds
        setTimeout(function() {
            $('.success-alert').fadeOut('slow');
        }, 5000);

        // Add animation to rows on page load
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
    });
</script>
@endpush

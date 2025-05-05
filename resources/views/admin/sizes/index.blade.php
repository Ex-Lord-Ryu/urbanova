@extends('layouts.app')

@section('title', 'Daftar Ukuran')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
@endpush

@section('content')
<section class="section products-section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Manajemen Ukuran</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ukuran</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-ruler text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="products-title mb-0">Daftar Ukuran Produk</h4>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="search-wrapper mr-3">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control" id="searchSize" placeholder="Cari ukuran...">
                            </div>
                            <a href="{{ route('sizes.create') }}" class="btn btn-primary add-product-btn">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Ukuran
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
                            @if($sizes->count() > 0)
                            <table class="table table-striped products-table" id="table-sizes">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Produk Terkait</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizes as $index => $size)
                                    <tr>
                                        <td class="text-center">{{ $index + $sizes->firstItem() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-light mr-2 p-2">{{ $size->name }}</span>
                                                <strong>{{ $size->name }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="text-muted">{{ $size->slug }}</span></td>
                                        <td>
                                            @if($size->is_active)
                                            <span class="product-status active">Aktif</span>
                                            @else
                                            <span class="product-status inactive">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light">{{ $size->products->count() }} Produk</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-warning" data-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus ukuran ini? Ini akan mempengaruhi produk terkait.')">
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
                                <h3 class="mt-3">Belum Ada Ukuran</h3>
                                <p class="text-muted">Mulai tambahkan ukuran baru untuk produk Anda</p>
                                <a href="{{ route('sizes.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Ukuran Pertama
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($sizes->count() > 0)
                        <div class="pagination-container mt-4 d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <span class="badge badge-light p-2">
                                    <i class="fas fa-list-ul mr-1"></i>
                                    Menampilkan {{ $sizes->firstItem() ?? 0 }} - {{ $sizes->lastItem() ?? 0 }} dari {{ $sizes->total() }} ukuran
                                </span>
                            </div>
                            <div class="pagination-links">
                                {{ $sizes->links() }}
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
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Search functionality
        $("#searchSize").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table-sizes tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });

            // Add animation to matched items
            if (value.length > 0) {
                setTimeout(function() {
                    $("#table-sizes tbody tr:visible").addClass('animate__animated animate__pulse');
                }, 300);
            } else {
                $("#table-sizes tbody tr").removeClass('animate__animated animate__pulse');
            }
        });

        // Fade out alerts after 5 seconds
        setTimeout(function() {
            $('.success-alert').fadeOut('slow');
        }, 5000);

        // Add animation to rows on page load
        $("#table-sizes tbody tr").each(function(index) {
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

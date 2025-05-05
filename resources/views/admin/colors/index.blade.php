@extends('layouts.app')

@section('title', 'Daftar Warna')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
@endpush

@section('content')
<section class="section products-section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Manajemen Warna</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Warna</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-palette text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="products-title mb-0">Daftar Warna Produk</h4>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="search-wrapper mr-3">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control" id="searchColor" placeholder="Cari warna...">
                            </div>
                            <a href="{{ route('colors.create') }}" class="btn btn-primary add-product-btn">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Warna
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
                            @if($colors->count() > 0)
                            <table class="table table-striped products-table" id="table-colors">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Warna</th>
                                        <th>Nama</th>
                                        <th>Kode Hex</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Produk Terkait</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($colors as $index => $color)
                                    <tr>
                                        <td class="text-center">{{ $index + $colors->firstItem() }}</td>
                                        <td>
                                            <div class="chip-color-dot" style="width: 25px; height: 25px; border-radius: 4px; background-color: #{{ $color->hex_code ?? '808080' }}"></div>
                                        </td>
                                        <td><strong>{{ $color->name }}</strong></td>
                                        <td><code>{{ $color->hex_code ?? 'N/A' }}</code></td>
                                        <td><span class="text-muted">{{ $color->slug }}</span></td>
                                        <td>
                                            @if($color->is_active)
                                            <span class="product-status active">Aktif</span>
                                            @else
                                            <span class="product-status inactive">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light">{{ $color->products->count() }} Produk</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning" data-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('colors.destroy', $color->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus warna ini? Ini akan mempengaruhi produk terkait.')">
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
                                <h3 class="mt-3">Belum Ada Warna</h3>
                                <p class="text-muted">Mulai tambahkan warna baru untuk produk Anda</p>
                                <a href="{{ route('colors.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Warna Pertama
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($colors->count() > 0)
                        <div class="pagination-container mt-4 d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <span class="badge badge-light p-2">
                                    <i class="fas fa-list-ul mr-1"></i>
                                    Menampilkan {{ $colors->firstItem() ?? 0 }} - {{ $colors->lastItem() ?? 0 }} dari {{ $colors->total() }} warna
                                </span>
                            </div>
                            <div class="pagination-links">
                                {{ $colors->links() }}
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
        $("#searchColor").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table-colors tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });

            // Add animation to matched items
            if (value.length > 0) {
                setTimeout(function() {
                    $("#table-colors tbody tr:visible").addClass('animate__animated animate__pulse');
                }, 300);
            } else {
                $("#table-colors tbody tr").removeClass('animate__animated animate__pulse');
            }
        });

        // Fade out alerts after 5 seconds
        setTimeout(function() {
            $('.success-alert').fadeOut('slow');
        }, 5000);

        // Add animation to rows on page load
        $("#table-colors tbody tr").each(function(index) {
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

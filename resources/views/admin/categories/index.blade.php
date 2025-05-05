@extends('layouts.app')

@section('title', 'Daftar Kategori')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/categories.css') }}">
@endpush

@section('content')
    <section class="section categories-section">
        <div class="page-header-bg">
            <h1 class="categories-title mb-3">Manajemen Kategori</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                </ol>
            </nav>
        </div>

        <!-- Stats Cards -->
        <div class="categories-stats">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>{{ $categories->total() }}</h3>
                <p>Total Kategori</p>
            </div>
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>{{ $categories->where('is_active', true)->count() }}</h3>
                <p>Kategori Aktif</p>
            </div>
            <div class="stat-card inactive">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3>{{ $categories->where('is_active', false)->count() }}</h3>
                <p>Kategori Non-Aktif</p>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card categories-card">
                        <div class="card-header categories-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder-open text-primary mr-2" style="font-size: 1.5rem;"></i>
                                <h4 class="categories-title mb-0">Daftar Kategori Produk</h4>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="search-wrapper mr-3">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control" id="searchCategory" placeholder="Cari kategori...">
                                </div>
                                <a href="{{ route('categories.create') }}" class="btn btn-primary add-category-btn">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert success-alert alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                @if($categories->count() > 0)
                                <table class="table table-striped categories-table" id="table-categories">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Nama</th>
                                            <th>Slug</th>
                                            <th>Gambar</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td class="text-center">{{ $index + $categories->firstItem() }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="category-color-dot" style="background-color: #{{ substr(md5($category->name), 0, 6) }}"></div>
                                                        <strong>{{ $category->name }}</strong>
                                                    </div>
                                                </td>
                                                <td><span class="text-muted">{{ $category->slug }}</span></td>
                                                <td>
                                                    @if ($category->image)
                                                        <img src="{{ asset('storage/' . $category->image) }}"
                                                            alt="{{ $category->name }}" width="50" class="category-image">
                                                    @else
                                                        <span class="badge badge-light">Tidak ada gambar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($category->is_active)
                                                        <span class="category-status active">Aktif</span>
                                                    @else
                                                        <span class="category-status inactive">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a href="{{ route('categories.show', $category->id) }}"
                                                            class="btn btn-info" data-toggle="tooltip" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" onclick="confirmEdit('{{ route('categories.edit', $category->id) }}')"
                                                            class="btn btn-warning" data-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('categories.destroy', $category->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"
                                                                data-toggle="tooltip" title="Hapus"
                                                                onclick="confirmDelete(event)">
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
                                    <h3 class="mt-3">Belum Ada Kategori</h3>
                                    <p class="text-muted">Mulai tambahkan kategori baru untuk produk Anda</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori Pertama
                                    </a>
                                </div>
                                @endif
                            </div>

                            @if($categories->count() > 0)
                            <div class="pagination-container mt-4 d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="badge badge-light p-2">
                                        <i class="fas fa-list-ul mr-1"></i>
                                        Menampilkan {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} kategori
                                    </span>
                                </div>
                                <div class="pagination-links">
                                    {{ $categories->links() }}
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
    <!-- Initialize script for categories -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#table-categories').DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "responsive": true,
                "searching": false
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Search functionality with highlight
            $("#searchCategory").on("keyup", function() {
                var value = $(this).val().toLowerCase();

                // Toggle visibility
                $("#table-categories tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });

                // Add animation to matched items
                if (value.length > 0) {
                    setTimeout(function() {
                        $("#table-categories tbody tr:visible").addClass('animate__animated animate__pulse');
                    }, 300);
                } else {
                    $("#table-categories tbody tr").removeClass('animate__animated animate__pulse');
                }
            });

            // Fade out alerts after 5 seconds
            setTimeout(function() {
                $('.success-alert').fadeOut('slow');
            }, 5000);

            // Add animation to rows on page load
            $("#table-categories tbody tr").each(function(index) {
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

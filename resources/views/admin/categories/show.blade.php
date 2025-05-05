@extends('layouts.app')

@section('title', 'Detail Kategori')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/categories.css') }}">
@endpush

@section('content')
<section class="section categories-section">
    <div class="page-header-bg">
        <h1 class="categories-title mb-3">Detail Kategori</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></div>
                <div class="breadcrumb-item">{{ $category->name }}</div>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card categories-card category-detail-card">
                    <div class="card-header categories-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-info mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="categories-title mb-0">Informasi Kategori</h4>
                        </div>
                        <div class="action-buttons">
                            <a href="#" onclick="confirmEdit('{{ route('categories.edit', $category->id) }}')" class="btn btn-warning">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="category-image-detail text-center mb-4">
                                    @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded shadow category-detail-image">
                                    @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p>Tidak ada gambar</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="category-info">
                                    <div class="category-info-header">
                                        <span class="category-status {{ $category->is_active ? 'active' : 'inactive' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                        <h3 class="mt-2">{{ $category->name }}</h3>
                                        <div class="category-meta">
                                            <span class="badge badge-light p-2 mr-2">
                                                <i class="fas fa-calendar mr-1"></i> Dibuat: {{ $category->created_at->format('d M Y') }}
                                            </span>
                                            <span class="badge badge-light p-2">
                                                <i class="fas fa-clock mr-1"></i> Diperbarui: {{ $category->updated_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="category-info-body mt-4">
                                        <div class="category-detail-item">
                                            <div class="detail-label">Slug:</div>
                                            <div class="detail-value">
                                                <code>{{ $category->slug }}</code>
                                            </div>
                                        </div>

                                        <div class="category-detail-item">
                                            <div class="detail-label">Deskripsi:</div>
                                            <div class="detail-value">
                                                {{ $category->description ?? 'Tidak ada deskripsi' }}
                                            </div>
                                        </div>

                                        <div class="category-detail-item">
                                            <div class="detail-label">Jumlah Produk:</div>
                                            <div class="detail-value">
                                                <span class="badge badge-primary">{{ $category->products->count() }} Produk</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card categories-card">
                    <div class="card-header categories-header d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-boxes text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="categories-title mb-0">Produk dalam Kategori Ini</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped categories-table" id="products-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $index => $product)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" class="category-image">
                                            @else
                                            <span class="badge badge-light">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($product->stock > 10)
                                                <span class="badge badge-success">{{ $product->stock }}</span>
                                            @elseif($product->stock > 0)
                                                <span class="badge badge-warning">{{ $product->stock }}</span>
                                            @else
                                                <span class="badge badge-danger">Habis</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="category-status active">Aktif</span>
                                            @else
                                                <span class="category-status inactive">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info" data-toggle="tooltip" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <img src="{{ asset('images/empty-products.svg') }}" alt="Tidak ada produk" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4076/4076432.png'; this.onerror='';">
                            <h3 class="mt-3">Belum Ada Produk</h3>
                            <p class="text-muted">Belum ada produk yang terdaftar dalam kategori ini</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Produk Baru
                            </a>
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
        $('#products-table').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "responsive": true,
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Card animation on load
        $('.categories-card').css('opacity', '0');
        $('.categories-card').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css({
                    'transition': 'all 0.5s ease',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, 200 * index);
        });
    });
</script>
@endpush

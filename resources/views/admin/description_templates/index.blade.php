@extends('layouts.app')

@section('title', 'Template Deskripsi Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .template-filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .template-search {
            min-width: 250px;
        }

        .template-search .form-control {
            border-radius: 50px;
            padding-left: 35px;
            height: 38px;
            border-color: #e4e6fc;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .template-search .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        #categoryFilter {
            border-radius: 50px;
            height: 38px;
            min-width: 180px;
            border-color: #e4e6fc;
            background-color: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-left: 15px;
            padding-right: 35px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        #categoryFilter:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .template-count .badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            background-color: #f4f4f4;
            color: #6c757d;
            border-radius: 20px;
            font-weight: 500;
        }

        .template-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 15px;
            height: 100%;
            transition: all 0.3s;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .template-card:hover {
            border-color: #6777ef;
            box-shadow: 0 5px 15px rgba(0,0,0,0.07);
            transform: translateY(-3px);
        }
    </style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Template Deskripsi Produk</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Template Deskripsi</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Kelola Template Deskripsi Produk</h2>
        <p class="section-lead">
            Buat template deskripsi yang dapat digunakan berulang kali untuk produk-produk Anda.
        </p>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Template Deskripsi</h4>
                        <div class="card-header-action">
                            <a href="{{ route('description-templates.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Template Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <div class="template-filter-bar">
                            <div class="d-flex align-items-center">
                                <div class="template-search mr-3 position-relative">
                                    <input type="text" class="form-control pl-5" id="searchTemplate" placeholder="Cari template...">
                                    <i class="fas fa-search position-absolute template-search-icon"></i>
                                </div>
                                <div class="form-group mb-0">
                                    <select class="form-control custom-select" id="categoryFilter">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="template-count">
                                <span class="badge badge-light">Total: {{ $templates->total() }} template</span>
                            </div>
                        </div>

                        <div class="row" id="template-container">
                            @forelse($templates as $template)
                            <div class="col-md-6 template-item-wrapper" data-category="{{ $template->category }}" data-name="{{ $template->name }}">
                                <div class="template-card">
                                    <div class="template-header">
                                        <h5 class="template-title">{{ $template->name }}</h5>
                                        @if($template->category)
                                        <span class="template-category">{{ $template->category }}</span>
                                        @endif
                                    </div>
                                    <div class="template-content">
                                        {!! $template->content !!}
                                    </div>
                                    <div class="template-footer">
                                        <div class="template-meta">
                                            <i class="far fa-calendar-alt"></i> {{ $template->created_at->format('d M Y') }}
                                            <span class="ml-3">
                                                <i class="fas fa-circle {{ $template->is_active ? 'text-success' : 'text-danger' }}"></i>
                                                {{ $template->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                        <div class="template-actions">
                                            <a href="{{ route('description-templates.show', $template->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('description-templates.edit', $template->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('description-templates.destroy', $template->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')" data-toggle="tooltip" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="empty-state" data-height="400">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h2>Belum ada template deskripsi</h2>
                                    <p class="lead">
                                        Buat template deskripsi untuk mempercepat penulisan deskripsi produk Anda.
                                    </p>
                                    <a href="{{ route('description-templates.create') }}" class="btn btn-primary mt-4">
                                        <i class="fas fa-plus"></i> Buat Template Pertama
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            {{ $templates->links() }}
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

        // Search functionality
        $("#searchTemplate").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            filterTemplates();
        });

        // Category filter
        $("#categoryFilter").on("change", function() {
            filterTemplates();
        });

        function filterTemplates() {
            var search = $("#searchTemplate").val().toLowerCase();
            var category = $("#categoryFilter").val();

            $(".template-item-wrapper").each(function() {
                var name = $(this).data('name').toLowerCase();
                var templateCategory = $(this).data('category');

                var matchesSearch = name.indexOf(search) > -1;
                var matchesCategory = category === '' || templateCategory === category;

                $(this).toggle(matchesSearch && matchesCategory);
            });

            // Show empty state if no results
            if ($(".template-item-wrapper:visible").length === 0) {
                if ($("#no-results").length === 0) {
                    $("#template-container").append(
                        '<div id="no-results" class="col-12 text-center py-4">' +
                        '<i class="fas fa-search fa-3x text-muted mb-3"></i>' +
                        '<h4 class="text-muted">Tidak ada template yang sesuai dengan filter</h4>' +
                        '</div>'
                    );
                }
            } else {
                $("#no-results").remove();
            }
        }
    });
</script>
@endpush

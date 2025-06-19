@extends('layouts.app')

@section('title', 'Edit Ukuran')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Edit Ukuran</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sizes.index') }}">Ukuran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Ukuran</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex align-items-center">
                        <i class="fas fa-ruler text-primary mr-2" style="font-size: 1.5rem;"></i>
                        <h4 class="products-title mb-0">Form Edit Ukuran</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <form action="{{ route('sizes.update', $size->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Ukuran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $size->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <small class="text-muted">Contoh: XL, 42, Large, dst.</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $size->slug) }}">
                                        @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <small class="text-muted">Biarkan kosong untuk generate otomatis dari nama</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="is_active" value="1" class="selectgroup-input" {{ old('is_active', $size->is_active) == '1' ? 'checked' : '' }}>
                                                <span class="selectgroup-button">Aktif</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="is_active" value="0" class="selectgroup-input" {{ old('is_active', $size->is_active) == '0' ? 'checked' : '' }}>
                                                <span class="selectgroup-button">Tidak Aktif</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Pratinjau Ukuran</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-light p-2 mr-3" style="font-size: 14px;" id="size_preview">{{ $size->name }}</span>
                                                <div>
                                                    <h5 id="size_name_preview">{{ $size->name }}</h5>
                                                    <code id="size_slug_preview">{{ $size->slug }}</code>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('sizes.index') }}" class="btn btn-light ml-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
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
        // Automatic slug generation
        $('#name').on('keyup', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '_');
            $('#slug').val(slug);

            // Update preview
            updatePreview();
        });

        $('#slug').on('input', function() {
            updatePreview();
        });

        function updatePreview() {
            const name = $('#name').val();
            const slug = $('#slug').val();

            $('#size_preview').text(name);
            $('#size_name_preview').text(name);
            $('#size_slug_preview').text(slug);
        }

        // No product usage information since table doesn't exist yet
    });
</script>
@endpush

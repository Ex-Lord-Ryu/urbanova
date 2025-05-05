@extends('layouts.app')

@section('title', 'Tambah Kategori')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/categories.css') }}">
@endpush

@section('content')
<section class="section categories-section">
    <div class="page-header-bg">
        <h1 class="categories-title mb-3">Tambah Kategori</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></div>
                <div class="breadcrumb-item">Tambah Kategori</div>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card categories-card">
                    <div class="card-header categories-header d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-plus-circle text-primary mr-2" style="font-size: 1.5rem;"></i>
                            <h4 class="categories-title mb-0">Form Tambah Kategori</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="createCategoryForm">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama kategori" required>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text text-muted">Nama kategori akan otomatis diubah menjadi slug.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Masukkan deskripsi kategori">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text text-muted">Deskripsi singkat tentang kategori (opsional).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image" class="col-sm-3 col-form-label">Gambar Kategori</label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image">Pilih Gambar</label>
                                        @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, JPEG, GIF. Maks: 2MB</small>
                                    <div class="mt-3" id="image-preview-container" style="display: none;">
                                        <img id="image-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="is_active" class="custom-switch-input" value="1" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Aktif</span>
                                    </label>
                                    <small class="form-text text-muted">Kategori aktif akan ditampilkan di aplikasi.</small>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" onclick="confirmUpdate(event)">
                                    <i class="fas fa-save mr-1"></i> Simpan Kategori
                                </button>
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
        // Custom file input
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            // Image preview
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                    $('#image-preview-container').show();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Form animation on load
        $('.card-body').css('opacity', '0');
        setTimeout(function() {
            $('.card-body').css({
                'transition': 'all 0.5s ease',
                'opacity': '1'
            });
        }, 200);
    });
</script>
@endpush

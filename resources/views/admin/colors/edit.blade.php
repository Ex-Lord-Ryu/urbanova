@extends('layouts.app')

@section('title', 'Edit Warna')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Edit Warna</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('colors.index') }}">Warna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Warna</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex align-items-center">
                        <i class="fas fa-palette text-primary mr-2" style="font-size: 1.5rem;"></i>
                        <h4 class="products-title mb-0">Form Edit Warna</h4>
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

                        <form action="{{ route('colors.update', $color->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Warna <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $color->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hex_code">Kode Hex (contoh: #FF5733)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">#</span>
                                            </div>
                                            <input type="text" class="form-control @error('hex_code') is-invalid @enderror" id="hex_code" name="hex_code" value="{{ old('hex_code', $color->hex_code) }}" placeholder="FF5733" maxlength="6">
                                            <div class="input-group-append">
                                                <span class="input-group-text p-0">
                                                    <input type="color" id="color_picker" value="{{ old('hex_code', '#' . ($color->hex_code ?? 'FFFFFF')) }}" class="border-0" style="width: 40px; height: 38px;">
                                                </span>
                                            </div>
                                            @error('hex_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Pilih warna atau masukkan kode hex (tanpa #)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $color->slug) }}">
                                        @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <small class="text-muted">Biarkan kosong untuk generate otomatis dari nama</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="is_active" value="1" class="selectgroup-input" {{ old('is_active', $color->is_active) == '1' ? 'checked' : '' }}>
                                                <span class="selectgroup-button">Aktif</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="is_active" value="0" class="selectgroup-input" {{ old('is_active', $color->is_active) == '0' ? 'checked' : '' }}>
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
                                            <h6 class="mb-0">Pratinjau Warna</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div id="color_preview" style="width: 50px; height: 50px; border-radius: 5px; border: 1px solid #ddd; margin-right: 15px; background-color: #{{ $color->hex_code ?? 'FFFFFF' }};"></div>
                                                <div>
                                                    <h5 id="color_name_preview">{{ $color->name }}</h5>
                                                    <code id="color_code_preview">#{{ strtoupper($color->hex_code ?? 'FFFFFF') }}</code>
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
                                <a href="{{ route('colors.index') }}" class="btn btn-light ml-2">
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

            // Update preview with the full name
            updateColorPreview();
        });

        // Color picker functionality
        $('#color_picker').on('input', function() {
            const hexValue = $(this).val().substring(1); // Remove # symbol
            $('#hex_code').val(hexValue);
            updateColorPreview();
        });

        $('#hex_code').on('input', function() {
            let hexValue = $(this).val();
            if (hexValue) {
                if (hexValue.startsWith('#')) {
                    hexValue = hexValue.substring(1);
                    $(this).val(hexValue);
                }
                $('#color_picker').val('#' + hexValue);
                updateColorPreview();
            }
        });

        $('#name').on('input', function() {
            updateColorPreview();
        });

        function updateColorPreview() {
            const name = $('#name').val() || 'Nama Warna';
            let hexCode = $('#hex_code').val() || 'FFFFFF';

            // Ensure hex code is valid
            if (!/^[0-9A-F]{6}$/i.test(hexCode)) {
                hexCode = 'FFFFFF';
            }

            $('#color_name_preview').text(name);
            $('#color_code_preview').text('#' + hexCode.toUpperCase());
            $('#color_preview').css('background-color', '#' + hexCode);
        }

        // Initialize preview on load
        updateColorPreview();

        // No product usage information since table doesn't exist yet
    });
</script>
@endpush

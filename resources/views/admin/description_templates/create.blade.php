@extends('layouts.app')

@section('title', 'Tambah Template Deskripsi')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <style>
        .template-variables {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .var-btn {
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 12px;
            padding: 4px 8px;
        }

        .note-editor .note-toolbar {
            background-color: #f8f9fa;
        }

        .preview-content {
            min-height: 150px;
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: #fdfdfd;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Template Deskripsi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('description-templates.index') }}">Template Deskripsi</a>
                </div>
                <div class="breadcrumb-item">Tambah Template</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Buat Template Deskripsi Baru</h2>
            <p class="section-lead">
                Template ini dapat digunakan untuk mempercepat pembuatan deskripsi pada produk.
            </p>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Template Deskripsi</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('description-templates.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nama Template <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: Deskripsi Kaos Polos, Deskripsi Jeans,
                                        dsb.</small>
                                </div>

                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <div class="input-group">
                                        <select class="form-control @error('category') is-invalid @enderror" id="category"
                                            name="category">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category }}"
                                                    {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="addNewCategory">
                                                <i class="fas fa-plus"></i> Kategori Baru
                                            </button>
                                        </div>
                                    </div>
                                    @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">Kategori membantu mengelompokkan template
                                        (opsional).</small>
                                </div>

                                <div class="form-group" id="newCategoryGroup" style="display: none;">
                                    <label for="newCategory">Kategori Baru</label>
                                    <input type="text" class="form-control" id="newCategory"
                                        placeholder="Masukkan nama kategori baru">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary"
                                            id="saveNewCategory">Simpan</button>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            id="cancelNewCategory">Batal</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="content">Konten Template <span class="text-danger">*</span></label>
                                    <div class="template-variables mb-2">
                                        <span class="mr-1">Variabel:</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary var-btn"
                                            data-var="[product_name]">Nama Produk</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary var-btn"
                                            data-var="[category]">Kategori</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary var-btn"
                                            data-var="[price]">Harga</button>
                                    </div>
                                    <textarea class="form-control summernote @error('content') is-invalid @enderror" id="content" name="content" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Gunakan tombol variabel untuk menyisipkan placeholder yang akan otomatis diganti
                                        dengan nilai dari produk.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Aktif</label>
                                    </div>
                                    <small class="form-text text-muted">Template yang tidak aktif tidak akan muncul di
                                        daftar pilihan.</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Simpan Template
                                    </button>
                                    <a href="{{ route('description-templates.index') }}"
                                        class="btn btn-secondary btn-lg ml-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pratinjau Template</h4>
                        </div>
                        <div class="card-body">
                            <div class="preview-content" id="previewContent">
                                <em>Pratinjau akan muncul di sini saat Anda mengetik...</em>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Bantuan Template</h4>
                        </div>
                        <div class="card-body">
                            <p>Beberapa tips untuk membuat template deskripsi yang efektif:</p>
                            <ul class="pl-3">
                                <li>Gunakan paragraf yang terpisah untuk memudahkan pembacaan</li>
                                <li>Sertakan informasi seperti bahan, ukuran, atau keunggulan produk</li>
                                <li>Gunakan variabel <code>[product_name]</code> untuk nama produk</li>
                                <li>Gunakan variabel <code>[category]</code> untuk kategori produk</li>
                                <li>Gunakan variabel <code>[price]</code> untuk harga produk</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                placeholder: 'Tulis deskripsi template di sini...',
                callbacks: {
                    onImageUpload: function(files) {
                        alert('Unggah gambar tidak diizinkan. Gunakan URL gambar jika diperlukan.');
                    }
                }
            });

            // Insert variable buttons
            $('.var-btn').on('click', function() {
                var varText = $(this).data('var');
                var summernote = $('.summernote');
                summernote.summernote('editor.insertText', varText);
            });

            // Preview functionality
            function updatePreview() {
                var content = $('.summernote').summernote('code');
                if (content.trim() === '') {
                    $('#previewContent').html('<em>Pratinjau akan muncul di sini saat Anda mengetik...</em>');
                } else {
                    // Replace placeholder variables with sample values
                    content = content.replace(/\[product_name\]/g, '<strong>Nama Produk</strong>');
                    content = content.replace(/\[category\]/g, '<strong>Kategori</strong>');
                    content = content.replace(/\[price\]/g, '<strong>Rp 299.000</strong>');

                    $('#previewContent').html(content);
                }
            }

            // Watch for content changes
            $('.summernote').on('summernote.change', function() {
                updatePreview();
            });

            // Trigger initial preview
            updatePreview();

            // New category functionality
            $('#addNewCategory').on('click', function() {
                $('#newCategoryGroup').show();
                $('#newCategory').focus();
            });

            $('#cancelNewCategory').on('click', function() {
                $('#newCategoryGroup').hide();
                $('#newCategory').val('');
            });

            $('#saveNewCategory').on('click', function() {
                var newCategory = $('#newCategory').val().trim();
                if (newCategory !== '') {
                    // Check if already exists
                    var exists = false;
                    $('#category option').each(function() {
                        if ($(this).val() === newCategory) {
                            exists = true;
                            return false;
                        }
                    });

                    if (!exists) {
                        // Add new option and select it
                        $('#category').append(new Option(newCategory, newCategory, false, true));
                        $('#newCategoryGroup').hide();
                        $('#newCategory').val('');
                    } else {
                        // Select existing category
                        $('#category').val(newCategory);
                        $('#newCategoryGroup').hide();
                        $('#newCategory').val('');
                    }
                }
            });

            // Save on Enter key in new category input
            $('#newCategory').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#saveNewCategory').click();
                }
            });
        });
    </script>
@endpush

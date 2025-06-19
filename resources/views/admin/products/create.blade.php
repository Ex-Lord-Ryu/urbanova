@extends('layouts.app')

@section('title', 'Tambah Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <style>
        .note-editor .note-toolbar {
            background-color: #f8f9fa;
        }
        .template-content {
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: #fdfdfd;
        }
        .template-selector-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .selected-templates {
            margin-bottom: 15px;
        }
        .selected-template-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            margin-bottom: 5px;
            background-color: #f4f6f9;
            border-radius: 4px;
            border-left: 3px solid #6777ef;
        }
        .selected-template-name {
            font-weight: 500;
        }
        .selected-template-remove {
            cursor: pointer;
            color: #cdd3d8;
            transition: all 0.2s;
        }
        .selected-template-remove:hover {
            color: #fc544b;
        }
        #templateSelectorModal {
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            max-height: 300px;
            overflow-y: auto;
        }
        .template-list-container {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 5px;
        }
        .template-preview-container {
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 15px;
            background-color: white;
            display: flex;
            flex-direction: column;
        }
        .template-item {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .template-item:hover {
            border-color: #6777ef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .template-item.active {
            border-color: #6777ef;
            border-left: 3px solid #6777ef;
            background-color: #f8f9fc;
        }
        .template-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .template-item-name {
            font-weight: 600;
        }
        .template-item-category {
            font-size: 11px;
            background-color: #6777ef;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .template-item-content {
            font-size: 12px;
            color: #6c757d;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .preview-content {
            min-height: 200px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 10px;
            padding: 15px;
            border: 1px solid #f1f1f1;
            border-radius: 4px;
            background-color: #fdfdfd;
            flex: 1;
        }
    </style>
@endpush

@section('content')
<section class="section products-section">
    <div class="page-header-bg">
        <h1 class="products-title mb-3">Tambah Produk Baru</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
            </ol>
        </nav>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card products-card">
                    <div class="card-header products-header d-flex align-items-center">
                        <i class="fas fa-plus-circle text-primary mr-2" style="font-size: 1.5rem;"></i>
                        <h4 class="products-title mb-0">Form Tambah Produk</h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                                        </h5>

                                    <div class="form-group">
                                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="sku">SKU</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" readonly>
                                        <small class="form-text text-muted">Biarkan kosong untuk generate otomatis (KAT-PRD-tglbl-XXX)</small>
                                        @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                            </div>

                                    <div class="form-section mt-4">
                                        <h5 class="section-title">
                                            <i class="fas fa-file-alt mr-2"></i>Deskripsi
                                        </h5>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Deskripsi Produk</label>
                                                    <div class="template-selector-header">
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" id="showTemplateSelector">
                                                                <i class="fas fa-puzzle-piece mr-1"></i> Tambah Template
                                                            </button>
                                                </div>
                                            </div>

                                                    <div id="templateSelectorModal" style="display: none;">
                                                        <div class="mb-3">
                                                        <div class="input-group">
                                                                <input type="text" class="form-control" id="templateSearch" placeholder="Cari template...">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-secondary" type="button" id="closeTemplateSelector">
                                                                        <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                                    </div>
                                                </div>

                                                        <div class="template-list-container">
                                                            @foreach(\App\Models\DescriptionTemplate::where('is_active', true)->get() as $template)
                                                            <div class="template-item" data-id="{{ $template->id }}">
                                                                <div class="template-item-header">
                                                                    <div class="template-item-name">{{ $template->name }}</div>
                                                                    @if($template->category)
                                                                    <div class="template-item-category">{{ $template->category }}</div>
                                                                    @endif
                                                    </div>
                                                                <div class="template-item-content">{{ Str::limit(strip_tags($template->content), 100) }}</div>
                                                        </div>
                                                    @endforeach
                                            </div>
                                        </div>

                                                    <div class="selected-templates" id="selectedTemplates"></div>

                                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-md-5">
                                    <div class="form-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-image mr-2"></i>Gambar Produk
                                        </h5>

                            <div class="form-group">
                                <label for="image">Gambar Utama</label>
                                <div class="file-input-container">
                                    <div class="file-input-field" id="image-filename">Pilih Gambar</div>
                                    <button type="button" class="file-input-btn">Browse</button>
                                    <input type="file" class="file-input" id="image" name="image" accept="image/*">
                                </div>
                                @error('image')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div id="image-preview" class="file-preview mt-2"></div>
                                <small class="form-text text-muted">Format: JPG, PNG, JPEG, GIF. Maks: 2MB</small>
                            </div>

                            <div class="form-group">
                                <label for="additional_images">Gambar Tambahan</label>
                                <div class="file-input-container">
                                    <div class="file-input-field" id="additional-images-filename">Pilih Beberapa Gambar</div>
                                    <button type="button" class="file-input-btn">Browse</button>
                                    <input type="file" class="file-input" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                </div>
                                @error('additional_images')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div id="additional-images-preview" class="file-preview mt-2"></div>
                                <small class="form-text text-muted">Format: JPG, PNG, JPEG, GIF. Maks: 2MB per file. Maksimal 5 file.</small>
                                        </div>
                            </div>

                                    <div class="form-section mt-4">
                                        <h5 class="section-title">
                                            <i class="fas fa-cog mr-2"></i>Pengaturan
                                        </h5>

                            <div class="form-group form-switch-container">
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_featured" class="custom-switch-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Produk Unggulan</span>
                                </label>
                            </div>

                            <div class="form-group form-switch-container">
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_active" class="custom-switch-input" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Aktif</span>
                                </label>
                                        </div>
                                    </div>

                                    <div class="form-section mt-4">
                                        <h5 class="section-title">
                                            <i class="fas fa-eye mr-2"></i>Preview Template
                                        </h5>
                                        <div id="templatePreview" class="template-preview-container">
                                            <div class="preview-content">
                                                <p class="text-muted text-center">Pilih template untuk melihat preview</p>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary btn-block" id="insertTemplate" disabled>
                                                <i class="fas fa-plus mr-1"></i> Masukkan Template
                                            </button>
                                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Produk
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg ml-2">
                            <i class="fas fa-times mr-1"></i> Batal
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Summernote WYSIWYG editor
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Tulis deskripsi produk di sini...',
            callbacks: {
                onImageUpload: function(files) {
                    alert('Unggah gambar tidak diizinkan. Gunakan URL gambar jika diperlukan.');
                }
            }
        });

        // Template content definitions
        const templateContents = {};

        // Load template contents via AJAX
        function loadTemplateContents() {
            $.ajax({
                url: "{{ route('description-templates.json') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    // Store all templates in our object
                    data.forEach(template => {
                        templateContents[template.id] = template.content;
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error loading templates:", error);
                }
            });
        }

        // Call the function to load templates
        loadTemplateContents();

        // Add form submit handler to check for templates before submission
        $('form').on('submit', function(e) {
            console.log('Form submitted with templates:', $('.selected-template-item').length);
            console.log('Template IDs:', $('input[name="description_templates[]"]').map(function() {
                return $(this).val();
            }).get());

            // Make sure we have at least one hidden input for templates array even if empty
            if ($('input[name="description_templates[]"]').length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'description_templates[]',
                    value: ''
                }).appendTo(this);
            }

            // Remove any previous dummy stock/price inputs that might have been added
            $('input[name="stock"]').remove();
            $('input[name="price"]').remove();

            return true;
        });

        // Templates Functionality
        $('#showTemplateSelector').on('click', function() {
            $('#templateSelectorModal').slideDown(300);
        });

        $('#closeTemplateSelector').on('click', function() {
            $('#templateSelectorModal').slideUp(300);
        });

        // Handle template item click
        $('.template-item').on('click', function() {
            $('.template-item').removeClass('active');
            $(this).addClass('active');

            const templateId = $(this).data('id');
            const templateContent = templateContents[templateId];

            $('#templatePreview .preview-content').html(templateContent);
            $('#insertTemplate').prop('disabled', false);
        });

        // Filter templates when searching
        $('#templateSearch').on('input', function() {
            const searchText = $(this).val().toLowerCase();
            $('.template-item').each(function() {
                const name = $(this).find('.template-item-name').text().toLowerCase();
                const category = $(this).find('.template-item-category').text().toLowerCase();
                const content = $(this).find('.template-item-content').text().toLowerCase();

                if (name.includes(searchText) || category.includes(searchText) || content.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Insert template into editor
        $('#insertTemplate').on('click', function() {
            const $activeTemplate = $('.template-item.active');
            if ($activeTemplate.length) {
                const templateId = $activeTemplate.data('id');
                const templateName = $activeTemplate.find('.template-item-name').text();
                const templateContent = templateContents[templateId];

                if (!templateContent) {
                    console.error('Template content not loaded yet');
                    alert('Konten template belum dimuat. Silakan coba lagi.');
                    return;
                }

                // Insert into Summernote
                const currentContent = $('.summernote').summernote('code');
                $('.summernote').summernote('code', currentContent + templateContent);

                // Add to selected templates list
                const templateItem = `
                    <div class="selected-template-item" data-id="${templateId}">
                        <div class="selected-template-name">${templateName}</div>
                        <input type="hidden" name="description_templates[]" value="${templateId}">
                        <div class="selected-template-remove"><i class="fas fa-times"></i></div>
                    </div>
                `;
                $('#selectedTemplates').append(templateItem);

                // Hide template selector
                $('#templateSelectorModal').slideUp(300);
                $('#insertTemplate').prop('disabled', true);
                $('.template-item').removeClass('active');
                $('#templatePreview .preview-content').html('<p class="text-muted text-center">Pilih template untuk melihat preview</p>');
            }
        });

        // Remove selected template
        $(document).on('click', '.selected-template-remove', function() {
            $(this).parent('.selected-template-item').fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Custom file input
        $(".file-input").on("change", function() {
            var fileField = $(this).siblings('.file-input-field');
            var fileName = "";

            if ($(this).attr('multiple') && this.files.length > 1) {
                fileName = this.files.length + " file dipilih";
            } else if (this.files.length > 0) {
                fileName = this.files[0].name;
            } else {
                fileName = $(this).attr('id') === 'image' ? 'Pilih Gambar' : 'Pilih Beberapa Gambar';
            }

            if (this.files.length > 0) {
                fileField.addClass('has-file');
            } else {
                fileField.removeClass('has-file');
            }

            fileField.text(fileName);

            // Generate preview
            var preview = $(this).attr('id') === 'image' ? $('#image-preview') : $('#additional-images-preview');
            preview.empty();

            if (this.files) {
                for (var i = 0; i < this.files.length; i++) {
                    if (i >= 5 && $(this).attr('multiple')) break; // Max 5 files for additional images

                    const file = this.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = $('<div class="file-preview-item"><img src="' + e.target.result + '" alt="Preview"><div class="file-preview-remove"><i class="fas fa-times"></i></div></div>');
                        preview.append(img);
                    }

                    reader.readAsDataURL(file);
                }
            }
        });

        // Trigger file input on button click
        $(".file-input-btn").on("click", function(e) {
            e.preventDefault();
            $(this).siblings('.file-input').click();
        });

        // Remove preview on click
        $(document).on('click', '.file-preview-remove', function() {
            $(this).parent('.file-preview-item').remove();
            // Reset file input
            var formGroup = $(this).closest('.form-group');
            var fileInput = formGroup.find('.file-input');
            var fileField = formGroup.find('.file-input-field');

            fileInput.val('');

            var defaultText = fileInput.attr('id') === 'image' ? 'Pilih Gambar' : 'Pilih Beberapa Gambar';
            fileField.text(defaultText).removeClass('has-file');

            // If no more preview items, clear preview area
            if (formGroup.find('.file-preview-item').length === 0) {
                formGroup.find('.file-preview').empty();
            }
        });

        // Add animation to form elements
        $('input:not(.file-input), select, textarea').each(function(index) {
            const $this = $(this);
            setTimeout(function() {
                $this.css('opacity', '0');
                setTimeout(function() {
                    $this.css('transition', 'all 0.3s ease')
                         .css('opacity', '1');
                }, 50);
            }, index * 50);
        });
    });
</script>
@endpush

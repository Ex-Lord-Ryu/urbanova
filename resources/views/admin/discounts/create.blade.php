@extends('layouts.app')

@section('title', 'Tambah Diskon')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/discounts.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Diskon</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Diskon</a></div>
                <div class="breadcrumb-item">Tambah Diskon</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Tambah Diskon Baru</h2>
            <p class="section-lead">Tambahkan diskon untuk satu atau beberapa produk sekaligus.</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Tambah Diskon</h4>
                        </div>
                        <div class="card-body">
                            {{-- Sweet alert will handle error messages --}}

                            <form action="{{ route('admin.discounts.store') }}" method="POST" id="discountForm">
                                @csrf

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pilih Produk <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2 @error('products') is-invalid @enderror"
                                            multiple name="products[]" id="products" required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ in_array($product->id, old('products', [])) ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('products')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Pilih satu atau beberapa produk yang akan diberi
                                            diskon</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Preview
                                        Produk</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div id="selected-products-preview">
                                            <div class="text-center text-muted py-4">
                                                <i class="fas fa-tag fa-2x mb-2"></i>
                                                <p>Belum ada produk yang dipilih</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Persentase Diskon
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('discount_percentage') is-invalid @enderror"
                                                name="discount_percentage" step="0.01" min="0.01" max="100"
                                                value="{{ old('discount_percentage') }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        @error('discount_percentage')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Masukkan persentase diskon (0.01-100)</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Periode
                                        Diskon</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Tanggal Mulai</label>
                                                <input type="text"
                                                    class="form-control datepicker @error('discount_start_date') is-invalid @enderror"
                                                    name="discount_start_date" value="{{ old('discount_start_date') }}">
                                                @error('discount_start_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">Kosongkan jika diskon mulai berlaku
                                                    sekarang</small>
                                                <small class="form-text text-info"><i class="fas fa-info-circle"></i> Tanggal mulai akan diterapkan sesuai yang Anda pilih.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tanggal Berakhir</label>
                                                <input type="text"
                                                    class="form-control datepicker @error('discount_end_date') is-invalid @enderror"
                                                    name="discount_end_date" value="{{ old('discount_end_date') }}">
                                                @error('discount_end_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">Kosongkan jika diskon tidak memiliki
                                                    batas waktu</small>
                                                <small class="form-text text-info"><i class="fas fa-info-circle"></i> Tanggal akhir akan otomatis diatur 1 hari setelah tanggal mulai.</small>
                                                <small class="form-text text-warning"><i class="fas fa-info-circle"></i> Tanggal akhir akan otomatis disesuaikan jika lebih awal dari tanggal mulai.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">Simpan Diskon</button>
                                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Include Sweet Alert Component --}}
    @include('components.sweet-alert')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Set initial dates
            const today = moment().format('YYYY-MM-DD');
            const tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');

            console.log('Initializing date fields', {
                'today': today,
                'tomorrow': tomorrow
            });

            // Initialize start date picker
            $('input[name="discount_start_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Get current start date value or use today
            const currentStartDate = $('input[name="discount_start_date"]').val() || today;
            const dayAfterStart = moment(currentStartDate).add(1, 'days').format('YYYY-MM-DD');

            console.log('Setting end date based on start date', {
                'currentStartDate': currentStartDate,
                'dayAfterStart': dayAfterStart
            });

            // Initialize end date picker with a default of exactly 1 day after start date
            $('input[name="discount_end_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                drops: 'up',
                startDate: dayAfterStart, // Set default to 1 day after start date
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Set initial value for end date (if empty)
            if (!$('input[name="discount_end_date"]').val()) {
                $('input[name="discount_end_date"]').val(dayAfterStart);
            }

            // Set form submission handler
            $('#discountForm').on('submit', function(e) {
                e.preventDefault();

                const products = $('#products').val();
                const discountPercentage = $('input[name="discount_percentage"]').val();
                const startDate = $('input[name="discount_start_date"]').val();
                const endDate = $('input[name="discount_end_date"]').val();

                console.log('Form submission data', {
                    'products': products,
                    'discountPercentage': discountPercentage,
                    'startDate': startDate,
                    'endDate': endDate
                });

                if (!products || products.length === 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Silakan pilih minimal satu produk',
                        icon: 'error'
                    });
                    return false;
                }

                if (!discountPercentage || discountPercentage <= 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Persentase diskon harus lebih dari 0',
                        icon: 'error'
                    });
                    return false;
                }

                // Form is valid, proceed with submission
                this.submit();
            });

            // Auto-set end date when start date is selected
            $('input[name="discount_start_date"]').on('apply.daterangepicker', function(ev, picker) {
                const selectedDate = picker.startDate.format('YYYY-MM-DD');
                const today = moment().format('YYYY-MM-DD');

                // Set end date to one day after start date
                const nextDay = moment(selectedDate).add(1, 'days').format('YYYY-MM-DD');

                // Update the end date input and reinitialize its daterangepicker
                const $endDateInput = $('input[name="discount_end_date"]');
                $endDateInput.val(nextDay);

                // Destroy and reinitialize the end date picker to reflect the new value
                $endDateInput.data('daterangepicker').remove();
                $endDateInput.daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoApply: true,
                    drops: 'up',
                    startDate: nextDay,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });

                // Check if end date needs to be updated
                const endDate = $endDateInput.val();
                if (endDate && endDate <= selectedDate) {
                    Swal.fire({
                        title: 'Perhatian',
                        text: 'Tanggal akhir akan otomatis disesuaikan karena tanggal mulai telah diubah.',
                        icon: 'info',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });

            // Warning for end date selection
            $('input[name="discount_end_date"]').on('apply.daterangepicker', function(ev, picker) {
                const selectedEndDate = picker.startDate.format('YYYY-MM-DD');
                const startDate = $('input[name="discount_start_date"]').val();

                if (startDate && selectedEndDate <= startDate) {
                    Swal.fire({
                        title: 'Perhatian',
                        text: 'Tanggal akhir akan otomatis disesuaikan karena tidak bisa lebih awal dari tanggal mulai.',
                        icon: 'info',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });

            // Handle product selection change and show preview
            $('#products').on('change', function() {
                const selectedProductIds = $(this).val();
                updateProductPreview(selectedProductIds);
            });

            // Initialize product preview on page load
            const initialSelectedProducts = $('#products').val();
            if (initialSelectedProducts && initialSelectedProducts.length > 0) {
                updateProductPreview(initialSelectedProducts);
            }

            // Function to update product preview
            function updateProductPreview(productIds) {
                const previewContainer = $('#selected-products-preview');

                if (!productIds || productIds.length === 0) {
                    previewContainer.html(`
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tag fa-2x mb-2"></i>
                        <p>Belum ada produk yang dipilih</p>
                    </div>
                `);
                    return;
                }

                // Show loading
                previewContainer.html(`
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat preview produk...</p>
                </div>
            `);

                // Fetch product details via AJAX
                $.ajax({
                    url: "{{ route('products.index') }}",
                    method: 'GET',
                    data: {
                        product_ids: productIds,
                        format: 'json'
                    },
                    success: function(response) {
                        let previewHtml = '';

                        // If we have products data
                        if (response.products && response.products.length > 0) {
                            response.products.forEach(function(product) {
                                let imageUrl = product.image ?
                                    "{{ asset('storage') }}/" + product.image :
                                    "{{ asset('images/product-placeholder.jpg') }}";

                                previewHtml += `
                                <div class="product-item">
                                    <img src="${imageUrl}" alt="${product.name}" class="rounded">
                                    <div class="product-item-content">
                                        <div class="product-item-title">${product.name}</div>
                                        <div class="product-item-meta">SKU: ${product.sku} | Kategori: ${product.category.name}</div>
                                    </div>
                                    <div class="product-item-price">Rp ${formatNumber(product.price)}</div>
                                </div>
                            `;
                            });
                        } else {
                            // Simplified preview for when we don't have full product data
                            // just show product names from select options
                            $('#products option:selected').each(function() {
                                const productName = $(this).text();

                                previewHtml += `
                                <div class="product-item">
                                    <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box text-secondary"></i>
                                    </div>
                                    <div class="product-item-content">
                                        <div class="product-item-title">${productName}</div>
                                    </div>
                                </div>
                            `;
                            });
                        }

                        previewContainer.html(previewHtml);
                    },
                    error: function() {
                        // On error, show simplified preview
                        let previewHtml = '';

                        $('#products option:selected').each(function() {
                            const productName = $(this).text();

                            previewHtml += `
                            <div class="product-item">
                                <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-box text-secondary"></i>
                                </div>
                                <div class="product-item-content">
                                    <div class="product-item-title">${productName}</div>
                                </div>
                            </div>
                        `;
                        });

                        previewContainer.html(previewHtml);
                    }
                });
            }

            // Helper function to format numbers
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endpush

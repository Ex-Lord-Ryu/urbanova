@extends('layouts.app')

@section('title', 'Tambah Diskon Massal')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/discounts.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Diskon Massal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Diskon</a></div>
                <div class="breadcrumb-item">Tambah Diskon Massal</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Diskon Massal Berdasarkan Kategori</h2>
            <p class="section-lead">Terapkan diskon ke semua produk dalam kategori tertentu.</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Diskon Massal</h4>
                        </div>
                        <div class="card-body">
                            {{-- Sweet alert will handle error messages --}}

                            <form action="{{ route('admin.discounts.store-bulk') }}" method="POST" id="bulkDiscountForm">
                                @csrf

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Kategori <span class="text-danger">*</span></label>
                                            <div class="category-list">
                                                @foreach ($categories as $category)
                                                    <div class="category-card @if (old('category_id') == $category->id) selected @endif"
                                                        data-id="{{ $category->id }}">
                                                        <div class="category-title">{{ $category->name }}</div>
                                                        <div class="category-count">{{ $category->products_count ?? 0 }}
                                                            produk</div>
                                                        <input type="radio" name="category_id" value="{{ $category->id }}"
                                                            class="d-none category-radio"
                                                            {{ old('category_id') == $category->id ? 'checked' : '' }}>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('category_id')
                                                <div class="text-danger small mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Persentase Diskon <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('discount_percentage') is-invalid @enderror"
                                                    name="discount_percentage" step="0.01" min="0.01" max="100"
                                                    value="{{ old('discount_percentage', 10) }}" required
                                                    id="discount-percentage">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            @error('discount_percentage')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">Masukkan persentase diskon
                                                (0.01-100)</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Periode Diskon</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Tanggal Mulai</label>
                                                    <input type="text"
                                                        class="form-control datepicker @error('discount_start_date') is-invalid @enderror"
                                                        name="discount_start_date" value="{{ old('discount_start_date') }}"
                                                        id="start-date">
                                                    @error('discount_start_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <small class="form-text text-muted">Kosongkan jika diskon mulai berlaku
                                                        sekarang</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Tanggal Berakhir</label>
                                                    <input type="text"
                                                        class="form-control datepicker @error('discount_end_date') is-invalid @enderror"
                                                        name="discount_end_date" value="{{ old('discount_end_date') }}"
                                                        id="end-date">
                                                    @error('discount_end_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <small class="form-text text-muted">Kosongkan jika diskon tidak memiliki
                                                        batas waktu</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="discount-summary">
                                            <div class="discount-title">Ringkasan Diskon</div>
                                            <div class="discount-detail">
                                                <span>Kategori:</span>
                                                <span class="discount-value" id="summary-category">-</span>
                                            </div>
                                            <div class="discount-detail">
                                                <span>Persentase Diskon:</span>
                                                <span class="discount-value" id="summary-percentage">-</span>
                                            </div>
                                            <div class="discount-detail">
                                                <span>Tanggal Mulai:</span>
                                                <span class="discount-value" id="summary-start-date">-</span>
                                            </div>
                                            <div class="discount-detail">
                                                <span>Tanggal Berakhir:</span>
                                                <span class="discount-value" id="summary-end-date">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Perhatian: Tindakan ini akan menerapkan
                                        diskon ke semua produk dalam kategori yang dipilih.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="confirmUpdate(event)">Terapkan
                                        Diskon Massal</button>
                                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Batal</a>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Datepicker with drops option set to "up"
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                drops: 'up', // This makes the calendar open above the input
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Handle category selection
            $('.category-card').on('click', function() {
                $('.category-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('.category-radio').prop('checked', true);

                // Update summary
                updateSummary();
            });

            // Initialize selected category
            if ($('.category-radio:checked').length > 0) {
                const selectedId = $('.category-radio:checked').val();
                $(`.category-card[data-id="${selectedId}"]`).addClass('selected');
            }

            // Handle changes to form inputs to update summary
            $('#discount-percentage, #start-date, #end-date').on('change', function() {
                updateSummary();
            });

            // Initial summary update
            updateSummary();

            // Function to update discount summary
            function updateSummary() {
                const selectedCategory = $('.category-card.selected');
                const discountPercentage = $('#discount-percentage').val() || '-';
                const startDate = $('#start-date').val() || 'Langsung';
                const endDate = $('#end-date').val() || 'Tidak dibatasi';

                if (selectedCategory.length > 0) {
                    $('#summary-category').text(selectedCategory.find('.category-title').text());
                } else {
                    $('#summary-category').text('-');
                }

                $('#summary-percentage').text(discountPercentage + '%');
                $('#summary-start-date').text(startDate);
                $('#summary-end-date').text(endDate);
            }
        });
    </script>
@endpush

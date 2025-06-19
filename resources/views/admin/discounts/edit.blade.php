@extends('layouts.app')

@section('title', 'Edit Diskon')

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{ asset('css/admin/discounts/discounts.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Diskon</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Diskon</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Edit Diskon Produk</h2>
        <p class="section-lead">Ubah diskon untuk produk "{{ $product->name }}".</p>

        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Edit Diskon</h4>
                    </div>
                    <div class="card-body">
                        {{-- Sweet alert will handle error messages --}}

                        <form action="{{ route('admin.discounts.update', $product->id) }}" method="POST" id="editDiscountForm">
                            @csrf
                            @method('PUT')

                            @php
                                $activeDiscount = $product->activeDiscount();
                                $discountPercentage = $activeDiscount ? $activeDiscount->percentage : 0;
                                $startDate = $activeDiscount && $activeDiscount->start_date ? $activeDiscount->start_date->format('Y-m-d') : null;
                                $endDate = $activeDiscount && $activeDiscount->end_date ? $activeDiscount->end_date->format('Y-m-d') : null;
                            @endphp

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Persentase Diskon <span class="text-danger">*</span></label>
                                <div class="col-sm-12 col-md-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" name="discount_percentage" step="0.01" min="0" max="100" value="{{ old('discount_percentage', $discountPercentage) }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan 0 untuk menghapus diskon</small>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Periode Diskon</label>
                                <div class="col-sm-12 col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Tanggal Mulai</label>
                                            <input type="text" class="form-control datepicker @error('discount_start_date') is-invalid @enderror" name="discount_start_date" value="{{ old('discount_start_date', $startDate) }}">
                                            @error('discount_start_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">Kosongkan jika diskon mulai berlaku sekarang</small>
                                            <small class="form-text text-info"><i class="fas fa-info-circle"></i> Tanggal mulai akan diterapkan sesuai yang Anda pilih.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tanggal Berakhir</label>
                                            <input type="text" class="form-control datepicker @error('discount_end_date') is-invalid @enderror" name="discount_end_date" value="{{ old('discount_end_date', $endDate) }}">
                                            @error('discount_end_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">Kosongkan jika diskon tidak memiliki batas waktu</small>
                                            <small class="form-text text-info"><i class="fas fa-info-circle"></i> Tanggal akhir akan otomatis diatur 1 hari setelah tanggal mulai.</small>
                                            <small class="form-text text-warning"><i class="fas fa-info-circle"></i> Tanggal akhir akan otomatis disesuaikan jika lebih awal dari tanggal mulai.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status Diskon</label>
                                <div class="col-sm-12 col-md-9">
                                    @if($product->hasActiveDiscount())
                                        <div class="badge badge-success mb-2">Diskon Aktif</div>
                                    @elseif($activeDiscount && $activeDiscount->start_date && $activeDiscount->start_date->isFuture())
                                        <div class="badge badge-warning mb-2">Menunggu Dimulai</div>
                                    @elseif($activeDiscount && $activeDiscount->end_date && $activeDiscount->end_date->isPast())
                                        <div class="badge badge-danger mb-2">Diskon Berakhir</div>
                                    @else
                                        <div class="badge badge-secondary mb-2">Tidak Aktif</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-9">
                                    <button type="button" class="btn btn-primary" onclick="confirmUpdate(event)">Simpan Perubahan</button>
                                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Produk</h4>
                    </div>
                    <div class="card-body">
                        <div class="product-card">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-secondary fa-3x"></i>
                                </div>
                            @endif

                            <div class="product-info">
                                <div class="product-title">{{ $product->name }}</div>
                                <div class="product-category">{{ $product->category->name }}</div>
                                <div class="product-sku">SKU: {{ $product->sku }}</div>
                            </div>

                            @if($product->discount_percentage > 0)
                                <div class="discount-badge">{{ $product->discount_percentage }}% OFF</div>
                            @endif

                            <div class="price-container">
                                <div class="original-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="discount-price">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6>Preview Diskon</h6>
                            <div id="discount-preview"></div>
                        </div>
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
        // Set initial dates
        const today = moment().format('YYYY-MM-DD');
        const tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');

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

        // Initialize end date picker with a date one day after start date
        const startDate = $('input[name="discount_start_date"]').val() || today;
        const nextDayAfterStart = moment(startDate).add(1, 'days').format('YYYY-MM-DD');

        $('input[name="discount_end_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            drops: 'up',
            startDate: nextDayAfterStart,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        // Set initial value for end date if empty
        if (!$('input[name="discount_end_date"]').val()) {
            $('input[name="discount_end_date"]').val(nextDayAfterStart);
        }

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
        });

        // Update discount preview
        function updateDiscountPreview() {
            const originalPrice = {{ $product->price }};
            const discountPercentage = parseFloat($('input[name="discount_percentage"]').val()) || 0;

            if (discountPercentage <= 0) {
                $('#discount-preview').html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Diskon tidak aktif (0%)
                    </div>
                `);
                return;
            }

            const discountAmount = (originalPrice * discountPercentage) / 100;
            const discountedPrice = originalPrice - discountAmount;

            const formatter = new Intl.NumberFormat('id-ID');

            $('#discount-preview').html(`
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td>Harga Asli</td>
                            <td>Rp ${formatter.format(originalPrice)}</td>
                        </tr>
                        <tr>
                            <td>Diskon (${discountPercentage}%)</td>
                            <td>- Rp ${formatter.format(discountAmount)}</td>
                        </tr>
                        <tr class="bg-success text-white">
                            <td>Harga Setelah Diskon</td>
                            <td>Rp ${formatter.format(discountedPrice)}</td>
                        </tr>
                    </table>
                </div>
            `);
        }

        // Initial preview
        updateDiscountPreview();

        // Update preview on discount change
        $('input[name="discount_percentage"]').on('input', function() {
            updateDiscountPreview();
        });
    });
</script>
@endpush

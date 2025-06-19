@extends('layouts.app')

@section('title', 'Edit Profile')

@push('css')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr Theme - Material Blue -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<!-- Flatpickr Month Select Plugin -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
    .flatpickr-calendar {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: none;
        animation: fpFadeInDown 300ms ease-out;
        margin-top: 5px;
    }
    .flatpickr-day.selected {
        background: #6777ef;
        border-color: #6777ef;
    }
    .flatpickr-day:hover {
        background: rgba(103, 119, 239, 0.2);
    }
    .flatpickr-day.today {
        border-color: #6777ef;
    }
    .flatpickr-months {
        padding-top: 5px;
    }
    .flatpickr-months .flatpickr-month {
        height: 50px;
    }
    .date-input-container {
        position: relative;
    }
    .date-input-container .form-control:focus {
        box-shadow: none;
        border-color: #6777ef;
    }
    .date-input-container .input-group-text {
        background-color: #6777ef;
        color: white;
        border: 1px solid #6777ef;
    }
    .date-clear-btn {
        position: absolute;
        right: 45px;
        top: 9px;
        z-index: 3;
        color: #ccc;
        cursor: pointer;
        display: none;
    }
    .date-clear-btn:hover {
        color: #dc3545;
    }
    /* Gaya untuk navigasi tahun */
    .numInputWrapper {
        width: auto !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        padding: 5px;
        border-radius: 4px;
    }
    /* Gaya untuk dropdown tahun */
    .flatpickr-current-month .numInput {
        width: 80px;
    }
    .flatpickr-current-month .arrowUp,
    .flatpickr-current-month .arrowDown {
        opacity: 1;
    }
    /* Gaya untuk mode tahun */
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #6777ef;
    }
    /* Styling untuk dropdown tahun kustom */
    .custom-year-dropdown {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 15px;
        margin-left: 10px;
        cursor: pointer;
        display: inline-block;
        background-color: white;
        width: 90px;
    }
    .flatpickr-current-month .numInputWrapper {
        display: none;
    }
    .year-select-container {
        display: inline-block;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Profile') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel" placeholder="Contoh: 08123456789">
                                </div>
                                @error('phone')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="birth_date" class="col-md-4 col-form-label text-md-right">{{ __('Birth Date') }}</label>

                            <div class="col-md-6">
                                <div class="date-input-container">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input id="birth_date" type="text" class="form-control datepicker @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" placeholder="Pilih Tanggal Lahir" readonly>
                                        <div class="date-clear-btn" id="clearDate"><i class="fas fa-times-circle"></i></div>
                                    </div>
                                </div>
                                <div class="mt-2 small text-muted date-display" id="dateDisplay"></div>
                                @error('birth_date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Profile') }}
                                </button>
                                <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary ml-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    $(document).ready(function() {
        // Generate tahun untuk dropdown (100 tahun terakhir)
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 100;
        const endYear = currentYear;

        // Buat array tahun
        let years = [];
        for (let i = endYear; i >= startYear; i--) {
            years.push(i);
        }

        // Inisialisasi flatpickr dengan konfigurasi untuk Indonesia
        const fpInstance = flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            locale: "id",
            maxDate: "today", // Tidak bisa memilih tanggal di masa depan
            disableMobile: "true", // Nonaktifkan mobile native picker
            showMonths: 1,
            allowInput: false,
            static: true,
            animate: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Update human-readable date display
                if (selectedDates.length > 0) {
                    let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    let readableDate = selectedDates[0].toLocaleDateString('id-ID', options);
                    $("#dateDisplay").text(readableDate);

                    // Show clear button when date is selected
                    $(".date-clear-btn").show();

                    // Update dropdown tahun saat tanggal berubah
                    if ($("#yearDropdown").length) {
                        $("#yearDropdown").val(selectedDates[0].getFullYear());
                    }
                }
            },
            onReady: function(selectedDates, dateStr, instance) {
                // Tambahkan dropdown tahun kustom setelah calendar siap
                setTimeout(function() {
                    // Pendekatan yang lebih reliable untuk menemukan container
                    const calendarContainer = document.querySelector(".flatpickr-calendar");
                    if (!calendarContainer) return;

                    // Cari current month element
                    const monthElement = calendarContainer.querySelector(".flatpickr-current-month");
                    if (!monthElement) return;

                    // Cek apakah dropdown tahun sudah ada
                    if (!document.getElementById('yearDropdown')) {
                        // Buat container untuk dropdown tahun
                        const yearContainer = document.createElement('div');
                        yearContainer.className = 'year-select-container';

                        // Buat dropdown tahun
                        const yearDropdown = document.createElement('select');
                        yearDropdown.className = 'custom-year-dropdown';
                        yearDropdown.id = 'yearDropdown';

                        // Tambahkan opsi untuk setiap tahun
                        years.forEach(year => {
                            const option = document.createElement('option');
                            option.value = year;
                            option.text = year;
                            yearDropdown.appendChild(option);
                        });

                        // Event listener untuk perubahan tahun
                        yearDropdown.addEventListener('change', function() {
                            // Set tahun baru
                            instance.currentYear = parseInt(this.value);
                            // Perbarui tampilan calendar
                            instance.redraw();
                        });

                        // Set nilai awal dropdown tahun
                        const initialYear = selectedDates.length ? selectedDates[0].getFullYear() : instance.currentYear;
                        yearDropdown.value = initialYear;

                        // Tambahkan dropdown ke dalam container
                        yearContainer.appendChild(yearDropdown);

                        // Tambahkan ke DOM
                        monthElement.appendChild(yearContainer);
                    }
                }, 200); // Menambah waktu tunggu untuk memastikan DOM sudah terbentuk
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                // Update dropdown tahun saat bulan berubah
                if ($("#yearDropdown").length) {
                    $("#yearDropdown").val(instance.currentYear);
                }
            },
            onYearChange: function(selectedDates, dateStr, instance) {
                // Update dropdown tahun saat tahun berubah
                if ($("#yearDropdown").length) {
                    $("#yearDropdown").val(instance.currentYear);
                }
            },
            // Menambahkan event open untuk memastikan dropdown tahun dibuat setiap kali calendar dibuka
            onOpen: function(selectedDates, dateStr, instance) {
                // Tambahkan dropdown tahun kustom
                setTimeout(function() {
                    // Pendekatan yang lebih reliable untuk menemukan container
                    const calendarContainer = document.querySelector(".flatpickr-calendar");
                    if (!calendarContainer) return;

                    // Cari current month element
                    const monthElement = calendarContainer.querySelector(".flatpickr-current-month");
                    if (!monthElement) return;

                    // Cek apakah dropdown tahun sudah ada
                    if (!document.getElementById('yearDropdown')) {
                        // Buat container untuk dropdown tahun
                        const yearContainer = document.createElement('div');
                        yearContainer.className = 'year-select-container';

                        // Buat dropdown tahun
                        const yearDropdown = document.createElement('select');
                        yearDropdown.className = 'custom-year-dropdown';
                        yearDropdown.id = 'yearDropdown';

                        // Tambahkan opsi untuk setiap tahun
                        years.forEach(year => {
                            const option = document.createElement('option');
                            option.value = year;
                            option.text = year;
                            yearDropdown.appendChild(option);
                        });

                        // Event listener untuk perubahan tahun
                        yearDropdown.addEventListener('change', function() {
                            // Set tahun baru
                            instance.currentYear = parseInt(this.value);
                            // Perbarui tampilan calendar
                            instance.redraw();
                        });

                        // Set nilai awal dropdown tahun
                        const initialYear = selectedDates.length ? selectedDates[0].getFullYear() : instance.currentYear;
                        yearDropdown.value = initialYear;

                        // Tambahkan dropdown ke dalam container
                        yearContainer.appendChild(yearDropdown);

                        // Tambahkan ke DOM
                        monthElement.appendChild(yearContainer);
                    }
                }, 100);
            }
        });

        // Clear date button functionality
        $("#clearDate").on("click", function() {
            fpInstance.clear();
            $("#dateDisplay").text("");
            $(this).hide();
        });

        // Show clear button if date is already set
        if ($("#birth_date").val()) {
            $(".date-clear-btn").show();
            let initialDate = new Date($("#birth_date").val());
            if (!isNaN(initialDate.getTime())) {
                let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                let readableDate = initialDate.toLocaleDateString('id-ID', options);
                $("#dateDisplay").text(readableDate);
            }
        }

        // Validasi nomor telepon - hanya menerima angka
        $('#phone').on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });
    });
</script>
@endpush

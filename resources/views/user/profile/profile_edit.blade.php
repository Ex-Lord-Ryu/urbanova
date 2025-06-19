@extends('layouts.landing')

@section('title', 'Edit Profile')

@push('css')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr Theme - Material Blue -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Flatpickr Month Select Plugin -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <style>
        /* Modern card styling with animation */
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 40px;
            margin-bottom: 40px;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
        }

        /* Stylish header with gradient */
        .card-header {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: white;
            padding: 18px 25px;
            font-weight: 600;
            font-size: 20px;
            position: relative;
            overflow: hidden;
            border-bottom: none;
        }

        /* Decorative background elements */
        .card-header:before {
            content: "";
            position: absolute;
            top: -15px;
            right: -15px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            z-index: 0;
        }

        .card-header:after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            z-index: 0;
        }

        .card-body {
            padding: 35px 30px;
            background-color: #fff;
        }

        /* Button styles with gradients */
        .btn-primary {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 25px;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(9, 9, 105, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #080851 0%, #13138a 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(9, 9, 105, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(9, 9, 105, 0.2);
        }

        .btn-outline-secondary {
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 25px;
            transition: all 0.3s;
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.2);
            background-color: #f8f9fa;
        }

        .btn-outline-secondary:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(108, 117, 125, 0.1);
        }

        /* Form controls with enhanced styling */
        .form-control {
            border-radius: 10px;
            padding: 12px 18px;
            border: 1px solid #e4e6fc;
            transition: all 0.3s;
            background-color: #f8faff;
            color: #333;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #090969;
            box-shadow: 0 0 0 0.2rem rgba(9, 9, 105, 0.15);
            background-color: #fff;
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #aab;
            font-style: italic;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #090969;
            color: white;
            border: 1px solid #090969;
            width: 45px;
            display: flex;
            justify-content: center;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23090969' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) center;
            padding-right: 35px;
        }

        label {
            font-weight: 600;
            color: #34395e;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .col-form-label {
            font-weight: 600;
            color: #34395e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.4s;
        }

        /* Flatpickr calendar styling */
        .flatpickr-calendar {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: none;
            animation: fpFadeInDown 300ms ease-out;
            margin-top: 5px;
        }

        .flatpickr-day.selected {
            background: #090969;
            border-color: #090969;
        }

        .flatpickr-day:hover {
            background: rgba(9, 9, 105, 0.2);
        }

        .flatpickr-day.today {
            border-color: #090969;
        }

        .flatpickr-months {
            padding-top: 5px;
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
        }

        .flatpickr-months .flatpickr-month {
            height: 50px;
            color: white;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            font-weight: 600;
            color: white;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: white;
            fill: white;
        }

        .date-input-container {
            position: relative;
        }

        .date-input-container .form-control:focus {
            box-shadow: none;
            border-color: #090969;
        }

        .date-input-container .input-group-text {
            background-color: #090969;
            color: white;
            border: 1px solid #090969;
        }

        .date-clear-btn {
            position: absolute;
            right: 45px;
            top: 12px;
            z-index: 3;
            color: #ccc;
            cursor: pointer;
            display: none;
        }

        .date-clear-btn:hover {
            color: #dc3545;
        }

        /* Date display styling */
        .date-display {
            color: #555;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-style: italic;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .card-body {
                padding: 25px 20px;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .col-form-label {
                margin-bottom: 0.5rem;
            }
        }

        /* Dropdown year styling */
        .custom-year-dropdown {
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 15px;
            margin-left: 10px;
            cursor: pointer;
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            width: 90px;
            font-weight: 600;
            text-align: center;
        }

        .custom-year-dropdown:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
        }

        .flatpickr-current-month .numInputWrapper {
            display: none;
        }

        .year-select-container {
            display: inline-block;
            vertical-align: middle;
        }

        /* Form section dividers */
        .form-divider {
            position: relative;
            height: 1px;
            background: linear-gradient(to right, rgba(0,0,0,0.03), rgba(0,0,0,0.10), rgba(0,0,0,0.03));
            margin: 2.5rem 0;
        }

        .form-divider:after {
            content: "Personal Information";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 0 15px;
            color: #090969;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 animate-fade-in">
                <div class="card">
                    <div class="card-header">{{ __('Edit Profile') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('landing.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row mb-4 animate-fade-in delay-1">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4 animate-fade-in delay-2">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email', $user->email) }}" required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-divider"></div>

                            <div class="form-group row mb-4 animate-fade-in delay-3">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input id="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone', $user->phone) }}" autocomplete="tel"
                                            placeholder="Contoh: 08123456789">
                                    </div>
                                    @error('phone')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4 animate-fade-in delay-3">
                                <label for="gender"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select id="gender" class="form-control @error('gender') is-invalid @enderror"
                                            name="gender">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="male"
                                                {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    @error('gender')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4 animate-fade-in delay-4">
                                <label for="birth_date"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Birth Date') }}</label>

                                <div class="col-md-6">
                                    <div class="date-input-container">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input id="birth_date" type="text"
                                                class="form-control datepicker @error('birth_date') is-invalid @enderror"
                                                name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                                                placeholder="Pilih Tanggal Lahir" readonly>
                                            <div class="date-clear-btn" id="clearDate"><i class="fas fa-times-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 small text-muted date-display" id="dateDisplay"></div>
                                    @error('birth_date')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-5 animate-fade-in delay-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> {{ __('Update Profile') }}
                                    </button>
                                    <a href="{{ route('landing.profile') }}" class="btn btn-outline-secondary ml-2">
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
                        let options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
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
                        const monthElement = calendarContainer.querySelector(
                            ".flatpickr-current-month");
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
                            const initialYear = selectedDates.length ? selectedDates[0]
                                .getFullYear() : instance.currentYear;
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
                        const monthElement = calendarContainer.querySelector(
                            ".flatpickr-current-month");
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
                            const initialYear = selectedDates.length ? selectedDates[0]
                                .getFullYear() : instance.currentYear;
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
                    let options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    let readableDate = initialDate.toLocaleDateString('id-ID', options);
                    $("#dateDisplay").text(readableDate);
                }
            }

            // Validasi nomor telepon - hanya menerima angka
            $('#phone').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });

            // Add smooth fade-in animation to form
            setTimeout(function() {
                $('.delay-1, .delay-2, .delay-3, .delay-4').addClass('animate-fade-in');
            }, 100);
        });
    </script>
@endpush

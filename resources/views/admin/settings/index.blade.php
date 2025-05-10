@extends('layouts.app')

@section('title', 'Pengaturan')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <style>
        .main-content {
            margin-left: 150px;
            width: calc(100% - 150px);
            transition: all 0.3s ease;
        }

        .sidebar-mini .main-content {
            margin-left: 20px;
            width: calc(100% - 20px);
        }

        .nav-pills .nav-item .nav-link.active {
            box-shadow: 0 2px 6px #acb5f6;
            color: #fff;
            background-color: #6777ef;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Pengaturan</h1>
            <div>
                <a href="{{ route('settings.initialize') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-sync-alt"></i> Inisialisasi Pengaturan
                </a>
                <form action="{{ route('settings.clear-cache') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-trash-alt"></i> Hapus Cache
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kategori Pengaturan</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column" id="settingsTabs" role="tablist">
                                @php
                                    $firstGroup = true;
                                    $groups = isset($settings) ? array_keys($settings->toArray()) : ['display'];
                                @endphp

                                @foreach($groups as $group)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $firstGroup ? 'active' : '' }}"
                                           id="{{ $group }}-tab"
                                           data-toggle="pill"
                                           href="#{{ $group }}"
                                           role="tab"
                                           aria-controls="{{ $group }}"
                                           aria-selected="{{ $firstGroup ? 'true' : 'false' }}">
                                            {{ ucfirst($group) }}
                                        </a>
                                    </li>
                                    @php $firstGroup = false; @endphp
                                @endforeach

                                @if(!isset($settings) || $settings->isEmpty())
                                    <li class="nav-item">
                                        <a class="nav-link active"
                                           id="display-tab"
                                           data-toggle="pill"
                                           href="#display"
                                           role="tab"
                                           aria-controls="display"
                                           aria-selected="true">
                                            Tampilan
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9">
                    <div class="tab-content" id="settingsTabContent">
                        @php $firstGroup = true; @endphp

                        @if(isset($settings) && $settings->isNotEmpty())
                            @foreach($settings as $group => $groupSettings)
                                <div class="tab-pane fade {{ $firstGroup ? 'show active' : '' }}"
                                     id="{{ $group }}"
                                     role="tabpanel"
                                     aria-labelledby="{{ $group }}-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Pengaturan {{ ucfirst($group) }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('settings.update') }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                @foreach($groupSettings as $setting)
                                                    <div class="form-group">
                                                        @if($setting->type === 'boolean')
                                                            <label class="form-label">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox"
                                                                           name="{{ $setting->key }}"
                                                                           id="{{ $setting->key }}"
                                                                           class="custom-control-input toggle-setting"
                                                                           data-key="{{ $setting->key }}"
                                                                           value="1"
                                                                           data-auto-submit="false"
                                                                           {{ $setting->value ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="{{ $setting->key }}">
                                                                        {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                                                    </label>
                                                                </div>
                                                                @if($setting->description)
                                                                    <small class="form-text text-muted">
                                                                        {{ $setting->description }}
                                                                    </small>
                                                                @endif
                                                            </label>
                                                        @elseif($setting->type === 'text')
                                                            <label for="{{ $setting->key }}">
                                                                {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                                            </label>
                                                            <input type="text"
                                                                   name="{{ $setting->key }}"
                                                                   id="{{ $setting->key }}"
                                                                   class="form-control"
                                                                   value="{{ $setting->value }}">
                                                            @if($setting->description)
                                                                <small class="form-text text-muted">
                                                                    {{ $setting->description }}
                                                                </small>
                                                            @endif
                                                        @elseif($setting->type === 'textarea')
                                                            <label for="{{ $setting->key }}">
                                                                {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                                            </label>
                                                            <textarea name="{{ $setting->key }}"
                                                                      id="{{ $setting->key }}"
                                                                      class="form-control"
                                                                      rows="3">{{ $setting->value }}</textarea>
                                                            @if($setting->description)
                                                                <small class="form-text text-muted">
                                                                    {{ $setting->description }}
                                                                </small>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <!-- Only show submit button if there are non-boolean settings -->
                                                @php
                                                    $hasNonBooleanSettings = $groupSettings->where('type', '!=', 'boolean')->count() > 0;
                                                @endphp

                                                @if($hasNonBooleanSettings)
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @php $firstGroup = false; @endphp
                            @endforeach
                        @else
                            <div class="tab-pane fade show active" id="display" role="tabpanel" aria-labelledby="display-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Pengaturan Tampilan</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('settings.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <label class="form-label">
                                                    <div class="custom-control custom-switch">
                                                            <input type="checkbox"
                                                                   name="show_featured_badge"
                                                                   id="show_featured_badge"
                                                                   class="custom-control-input toggle-setting"
                                                                   data-key="show_featured_badge"
                                                                   value="1"
                                                                   data-auto-submit="false"
                                                            {{ \App\Models\Setting::get('show_featured_badge', true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="show_featured_badge">
                                                            Tampilkan Badge "Unggulan" pada Produk di Halaman Shop
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Aktifkan opsi ini untuk menampilkan badge "Unggulan" pada produk unggulan di
                                                        halaman shop.
                                                    </small>
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script>
        // Fallback notification system in case iziToast isn't loaded
        function showNotification(type, title, message) {
            if (typeof iziToast !== 'undefined') {
                // Use iziToast if available
                return iziToast[type]({
                    title: title,
                    message: message,
                    position: 'topRight',
                    timeout: 5000
                });
            } else {
                // Fallback to alert
                console.warn('iziToast not loaded, using alert fallback');
                alert(title + ': ' + message);
                return null;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Directly handle toggle clicks, bypassing checkbox behavior
            const toggleSwitches = document.querySelectorAll('.toggle-setting');

            toggleSwitches.forEach(toggle => {
                const toggleContainer = toggle.closest('.custom-control');

                // Track the real state separately from the visual state
                let realState = toggle.checked;

                toggle.addEventListener('click', function(e) {
                    // Prevent default checkbox behavior
                    e.preventDefault();
                    e.stopPropagation();

                    // Get the key and current state
                    const key = this.dataset.key;

                    // Toggle visual state first (optimistic UI update)
                    realState = !realState;
                    this.checked = realState;

                    // Disable the switch during processing
                    this.disabled = true;
                    toggleContainer.style.opacity = '0.5';

                    // Show processing indicator
                    const loadingToast = showNotification('info', 'Sedang Diproses', 'Menyimpan pengaturan...');

                    // Make API call
                    fetch(`{{ url('settings/toggle') }}/${key}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                            value: realState ? 1 : 0
                        })
                    })
                    .then(response => {
                        // First check if we got a response at all
                        if (!response) {
                            throw new Error('No response from server');
                        }

                        // Even if status is 500, try to parse JSON
                        return response.json().catch(e => {
                            // If JSON parsing fails, throw a more descriptive error
                            throw new Error(`Error ${response.status}: ${response.statusText || 'Server error'}`);
                        }).then(data => {
                            // If parsing succeeded but status isn't ok, include any error message from server
                            if (!response.ok) {
                                const errorMessage = data && data.message
                                    ? data.message
                                    : `Server error (${response.status})`;
                                throw new Error(errorMessage);
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        // Close the loading toast if it exists
                        if (loadingToast && typeof iziToast !== 'undefined') {
                            iziToast.hide({}, loadingToast);
                        }

                        if (data.success) {
                            // Re-enable the switch
                            toggle.disabled = false;
                            toggleContainer.style.opacity = '1';

                            // Set the real state based on the response, in case the server has a different value
                            realState = !!data.value;
                            toggle.checked = realState;

                            showNotification('success', 'Berhasil!',
                                realState ? 'Badge "Unggulan" diaktifkan' : 'Badge "Unggulan" dinonaktifkan'
                            );

                            // Force badge URL parameter update on links to shop
                            const shopLinks = document.querySelectorAll('a[href*="/shop"]');
                            const timestamp = new Date().getTime();
                            shopLinks.forEach(link => {
                                const url = new URL(link.href, window.location.origin);
                                url.searchParams.set('_t', timestamp);
                                link.href = url.toString();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        // Close the loading toast if it exists
                        if (loadingToast && typeof iziToast !== 'undefined') {
                            iziToast.hide({}, loadingToast);
                        }

                        // Detailed error logging
                        console.error('Toggle Error:', error);
                        console.error('Error Details:', {
                            key: key,
                            value: realState,
                            endpoint: `{{ url('settings/toggle') }}/${key}`,
                            errorMessage: error.message
                        });

                        // Revert the real state and visual state
                        realState = !realState;
                        toggle.checked = realState;

                        // Re-enable the switch
                        toggle.disabled = false;
                        toggleContainer.style.opacity = '1';

                        showNotification('error', 'Error!',
                            error.message || 'Terjadi kesalahan saat memperbarui pengaturan'
                        );
                    });
                });

                // Prevent the regular change event from firing
                toggle.addEventListener('change', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
            });

            // Prevent form submissions from checkboxes
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Only allow submit if triggered by a submit button
                    if (!e.submitter || e.submitter.type !== 'submit') {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        });
    </script>
@endpush

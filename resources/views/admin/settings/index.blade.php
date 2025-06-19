@extends('layouts.app')

@section('title', 'Pengaturan')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
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

        .json-form-group .form-control {
            margin-bottom: 10px;
        }

        .json-item {
            border: 1px solid #e4e6fc;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .json-item .btn-sm {
            padding: .25rem .5rem;
            font-size: .75rem;
        }

        .add-json-item-btn {
            margin-top: 10px;
        }

        .json-key-value-pair {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        /* Editor styling */
        .note-editor.note-frame {
            border-color: #e4e6fc;
        }

        .note-editor .note-toolbar {
            background-color: #f8f9fa;
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

                                <!-- About Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="about-content-tab"
                                       data-toggle="pill"
                                       href="#about-content"
                                       role="tab"
                                       aria-controls="about-content"
                                       aria-selected="false">
                                        Edit Halaman About
                                    </a>
                                </li>

                                <!-- FAQ Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="faq-content-tab"
                                       data-toggle="pill"
                                       href="#faq-content"
                                       role="tab"
                                       aria-controls="faq-content"
                                       aria-selected="false">
                                        Edit Halaman FAQ
                                    </a>
                                </li>

                                <!-- Terms Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="terms-content-tab"
                                       data-toggle="pill"
                                       href="#terms-content"
                                       role="tab"
                                       aria-controls="terms-content"
                                       aria-selected="false">
                                        Edit Syarat & Ketentuan
                                    </a>
                                </li>

                                <!-- Privacy Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="privacy-content-tab"
                                       data-toggle="pill"
                                       href="#privacy-content"
                                       role="tab"
                                       aria-controls="privacy-content"
                                       aria-selected="false">
                                        Edit Kebijakan Privasi
                                    </a>
                                </li>

                                <!-- Returns Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="returns-content-tab"
                                       data-toggle="pill"
                                       href="#returns-content"
                                       role="tab"
                                       aria-controls="returns-content"
                                       aria-selected="false">
                                        Edit Kebijakan Pengembalian
                                    </a>
                                </li>

                                <!-- Shipping Content Tab -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="shipping-content-tab"
                                       data-toggle="pill"
                                       href="#shipping-content"
                                       role="tab"
                                       aria-controls="shipping-content"
                                       aria-selected="false">
                                        Edit Informasi Pengiriman
                                    </a>
                                </li>
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
                                                        @elseif($setting->type === 'json')
                                                            <label for="{{ $setting->key }}">
                                                                {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                                            </label>
                                                            @php
                                                                $jsonData = $setting->value;
                                                                if (is_string($jsonData)) {
                                                                    $jsonData = json_decode($jsonData, true) ?? [];
                                                                }
                                                            @endphp

                                                            <div class="json-form-group" id="{{ $setting->key }}_container">
                                                                <input type="hidden" name="{{ $setting->key }}" id="{{ $setting->key }}_input" value="{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}">

                                                                @if(strpos($setting->key, 'social_links') !== false)
                                                                    <!-- Social links with icon and URL format -->
                                                                    <div id="{{ $setting->key }}_items">
                                                                        @foreach($jsonData as $index => $item)
                                                                            <div class="json-item social-item" data-index="{{ $index }}">
                                                                                <div class="json-key-value-pair">
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Icon Class</label>
                                                                                        <input type="text" class="form-control icon-input" value="{{ $item['icon'] ?? '' }}" placeholder="fab fa-facebook-f">
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <label>URL</label>
                                                                                        <div class="url-selection-container">
                                                                                            <select class="form-control url-selection mb-2">
                                                                                                <option value="custom">-- Pilih URL atau masukkan kustom --</option>
                                                                                                <option value="/" {{ ($item['url'] ?? '') == '/' ? 'selected' : '' }}>Home</option>
                                                                                                <option value="/about" {{ ($item['url'] ?? '') == '/about' ? 'selected' : '' }}>About</option>
                                                                                                <option value="/contact" {{ ($item['url'] ?? '') == '/contact' ? 'selected' : '' }}>Contact</option>
                                                                                                <option value="/shop" {{ ($item['url'] ?? '') == '/shop' ? 'selected' : '' }}>Shop</option>
                                                                                                <option value="/faq" {{ ($item['url'] ?? '') == '/faq' ? 'selected' : '' }}>FAQ</option>
                                                                                                <option value="/terms" {{ ($item['url'] ?? '') == '/terms' ? 'selected' : '' }}>Syarat & Ketentuan</option>
                                                                                                <option value="/privacy" {{ ($item['url'] ?? '') == '/privacy' ? 'selected' : '' }}>Kebijakan Privasi</option>
                                                                                                <option value="/returns" {{ ($item['url'] ?? '') == '/returns' ? 'selected' : '' }}>Kebijakan Pengembalian</option>
                                                                                                <option value="/shipping" {{ ($item['url'] ?? '') == '/shipping' ? 'selected' : '' }}>Informasi Pengiriman</option>
                                                                                                <option value="whatsapp" {{ strpos(($item['url'] ?? ''), 'https://wa.me/') !== false ? 'selected' : '' }}>WhatsApp CS</option>
                                                                                                <option value="custom" {{ !in_array(($item['url'] ?? ''), ['/', '/about', '/contact', '/shop', '/faq', '/terms', '/privacy', '/returns', '/shipping']) && strpos(($item['url'] ?? ''), 'https://wa.me/') === false ? 'selected' : '' }}>URL Kustom</option>
                                                                                            </select>

                                                                                            <!-- WhatsApp CS Selector (akan muncul jika WhatsApp CS dipilih) -->
                                                                                            <select class="form-control whatsapp-cs-selection mb-2" {{ strpos(($item['url'] ?? ''), 'https://wa.me/') !== false ? '' : 'style="display:none;"' }}>
                                                                                                <option value="">-- Pilih WhatsApp CS --</option>
                                                                                                @php
                                                                                                    $waContacts = \App\Models\Setting::get('footer_whatsapp_contacts', []);
                                                                                                    if (is_string($waContacts)) {
                                                                                                        $waContacts = json_decode($waContacts, true) ?? [];
                                                                                                    }
                                                                                                @endphp

                                                                                                @foreach($waContacts as $waIndex => $contact)
                                                                                                    @php
                                                                                                        $waNumber = $contact['number'] ?? '';
                                                                                                        $waMessage = $contact['message'] ?? 'Hi, I need assistance';
                                                                                                        $waName = $contact['name'] ?? 'Customer Service';
                                                                                                        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage);
                                                                                                        $isSelected = ($item['url'] ?? '') == $waUrl;
                                                                                                    @endphp
                                                                                                    <option value="{{ $waUrl }}" {{ $isSelected ? 'selected' : '' }}>{{ $waName }}</option>
                                                                                                @endforeach
                                                                                            </select>

                                                                                            <input type="text" class="form-control url-input" value="{{ strpos(($item['url'] ?? ''), 'https://wa.me/') === false ? ($item['url'] ?? '') : '' }}" placeholder="https://..." {{ (in_array(($item['url'] ?? ''), ['/', '/about', '/contact', '/shop', '/faq', '/terms', '/privacy', '/returns', '/shipping']) || strpos(($item['url'] ?? ''), 'https://wa.me/') !== false) ? 'style="display:none;"' : '' }}>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="align-self-end">
                                                                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary btn-sm add-json-item-btn" data-target="{{ $setting->key }}" data-type="social">
                                                                        <i class="fas fa-plus"></i> Tambah Media Sosial
                                                                    </button>
                                                                @elseif(strpos($setting->key, 'whatsapp_contacts') !== false)
                                                                    <!-- WhatsApp contacts with name, number and message -->
                                                                    <div id="{{ $setting->key }}_items">
                                                                        @foreach($jsonData as $index => $item)
                                                                            <div class="json-item whatsapp-item" data-index="{{ $index }}">
                                                                                <div class="json-key-value-pair">
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Nama CS</label>
                                                                                        <input type="text" class="form-control name-input" value="{{ $item['name'] ?? '' }}" placeholder="Layanan Umum">
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Nomor WhatsApp</label>
                                                                                        <input type="text" class="form-control number-input" value="{{ $item['number'] ?? '' }}" placeholder="628123456789">
                                                                                    </div>
                                                                                    <div class="align-self-end">
                                                                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Pesan Default</label>
                                                                                    <input type="text" class="form-control message-input" value="{{ $item['message'] ?? '' }}" placeholder="Halo, saya butuh bantuan...">
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary btn-sm add-json-item-btn" data-target="{{ $setting->key }}" data-type="whatsapp">
                                                                        <i class="fas fa-plus"></i> Tambah Kontak WhatsApp
                                                                    </button>
                                                                @elseif(strpos($setting->key, 'couriers') !== false)
                                                                    <!-- Courier options with name, code and active status -->
                                                                    <div id="{{ $setting->key }}_items">
                                                                        @foreach($jsonData as $index => $item)
                                                                            <div class="json-item courier-item" data-index="{{ $index }}">
                                                                                <div class="json-key-value-pair">
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Nama Kurir</label>
                                                                                        <input type="text" class="form-control name-input" value="{{ $item['name'] ?? '' }}" placeholder="JNE">
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Kode Kurir</label>
                                                                                        <input type="text" class="form-control code-input" value="{{ $item['code'] ?? '' }}" placeholder="jne">
                                                                                    </div>
                                                                                    <div class="align-self-end">
                                                                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input active-input" id="{{ $setting->key }}_{{ $index }}_active" {{ isset($item['active']) && $item['active'] ? 'checked' : '' }}>
                                                                                        <label class="custom-control-label" for="{{ $setting->key }}_{{ $index }}_active">Aktif</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary btn-sm add-json-item-btn" data-target="{{ $setting->key }}" data-type="courier">
                                                                        <i class="fas fa-plus"></i> Tambah Kurir
                                                                    </button>
                                                                @else
                                                                    <!-- Regular links with text and URL format -->
                                                                    <div id="{{ $setting->key }}_items">
                                                                        @foreach($jsonData as $index => $item)
                                                                            <div class="json-item link-item" data-index="{{ $index }}">
                                                                                <div class="json-key-value-pair">
                                                                                    <div class="flex-grow-1">
                                                                                        <label>Text</label>
                                                                                        <input type="text" class="form-control text-input" value="{{ $item['text'] ?? '' }}" placeholder="Link text">
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <label>URL</label>
                                                                                        <div class="url-selection-container">
                                                                                            <select class="form-control url-selection mb-2">
                                                                                                <option value="custom">-- Pilih URL atau masukkan kustom --</option>
                                                                                                <option value="/" {{ ($item['url'] ?? '') == '/' ? 'selected' : '' }}>Home</option>
                                                                                                <option value="/about" {{ ($item['url'] ?? '') == '/about' ? 'selected' : '' }}>About</option>
                                                                                                <option value="/contact" {{ ($item['url'] ?? '') == '/contact' ? 'selected' : '' }}>Contact</option>
                                                                                                <option value="/shop" {{ ($item['url'] ?? '') == '/shop' ? 'selected' : '' }}>Shop</option>
                                                                                                <option value="/faq" {{ ($item['url'] ?? '') == '/faq' ? 'selected' : '' }}>FAQ</option>
                                                                                                <option value="/terms" {{ ($item['url'] ?? '') == '/terms' ? 'selected' : '' }}>Syarat & Ketentuan</option>
                                                                                                <option value="/privacy" {{ ($item['url'] ?? '') == '/privacy' ? 'selected' : '' }}>Kebijakan Privasi</option>
                                                                                                <option value="/returns" {{ ($item['url'] ?? '') == '/returns' ? 'selected' : '' }}>Kebijakan Pengembalian</option>
                                                                                                <option value="/shipping" {{ ($item['url'] ?? '') == '/shipping' ? 'selected' : '' }}>Informasi Pengiriman</option>
                                                                                                <option value="whatsapp" {{ strpos(($item['url'] ?? ''), 'https://wa.me/') !== false ? 'selected' : '' }}>WhatsApp CS</option>
                                                                                                <option value="custom" {{ !in_array(($item['url'] ?? ''), ['/', '/about', '/contact', '/shop', '/faq', '/terms', '/privacy', '/returns', '/shipping']) && strpos(($item['url'] ?? ''), 'https://wa.me/') === false ? 'selected' : '' }}>URL Kustom</option>
                                                                                            </select>

                                                                                            <!-- WhatsApp CS Selector (akan muncul jika WhatsApp CS dipilih) -->
                                                                                            <select class="form-control whatsapp-cs-selection mb-2" {{ strpos(($item['url'] ?? ''), 'https://wa.me/') !== false ? '' : 'style="display:none;"' }}>
                                                                                                <option value="">-- Pilih WhatsApp CS --</option>
                                                                                                @php
                                                                                                    $waContacts = \App\Models\Setting::get('footer_whatsapp_contacts', []);
                                                                                                    if (is_string($waContacts)) {
                                                                                                        $waContacts = json_decode($waContacts, true) ?? [];
                                                                                                    }
                                                                                                @endphp

                                                                                                @foreach($waContacts as $waIndex => $contact)
                                                                                                    @php
                                                                                                        $waNumber = $contact['number'] ?? '';
                                                                                                        $waMessage = $contact['message'] ?? 'Hi, I need assistance';
                                                                                                        $waName = $contact['name'] ?? 'Customer Service';
                                                                                                        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage);
                                                                                                        $isSelected = ($item['url'] ?? '') == $waUrl;
                                                                                                    @endphp
                                                                                                    <option value="{{ $waUrl }}" {{ $isSelected ? 'selected' : '' }}>{{ $waName }}</option>
                                                                                                @endforeach
                                                                                            </select>

                                                                                            <input type="text" class="form-control url-input" value="{{ strpos(($item['url'] ?? ''), 'https://wa.me/') === false ? ($item['url'] ?? '') : '' }}" placeholder="https://..." {{ (in_array(($item['url'] ?? ''), ['/', '/about', '/contact', '/shop', '/faq', '/terms', '/privacy', '/returns', '/shipping']) || strpos(($item['url'] ?? ''), 'https://wa.me/') !== false) ? 'style="display:none;"' : '' }}>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="align-self-end">
                                                                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="btn btn-primary btn-sm add-json-item-btn" data-target="{{ $setting->key }}" data-type="link">
                                                                        <i class="fas fa-plus"></i> Tambah Link
                                                                    </button>
                                                                @endif

                                                                @if($setting->description)
                                                                    <small class="form-text text-muted mt-2">
                                                                        {{ $setting->description }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                                </div>
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

                        <!-- About Content Tab -->
                        <div class="tab-pane fade" id="about-content" role="tabpanel" aria-labelledby="about-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman About</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-about') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="about_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="about_title"
                                                   id="about_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('about_title', 'Our Story') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman About Us.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="about_content">Konten Halaman</label>
                                            <textarea name="about_content"
                                                      id="about_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('about_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman About Us. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Content Tab -->
                        <div class="tab-pane fade" id="faq-content" role="tabpanel" aria-labelledby="faq-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman FAQ</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-page-content') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="page_type" value="faq">
                                        <div class="form-group">
                                            <label for="faq_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="page_title"
                                                   id="faq_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('faq_title', 'Frequently Asked Questions') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman FAQ.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="faq_content">Konten Halaman</label>
                                            <textarea name="page_content"
                                                      id="faq_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('faq_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman FAQ. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('faq') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Content Tab -->
                        <div class="tab-pane fade" id="terms-content" role="tabpanel" aria-labelledby="terms-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman Syarat & Ketentuan</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-page-content') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="page_type" value="terms">
                                        <div class="form-group">
                                            <label for="terms_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="page_title"
                                                   id="terms_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('terms_title', 'Terms & Conditions') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman Syarat & Ketentuan.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="terms_content">Konten Halaman</label>
                                            <textarea name="page_content"
                                                      id="terms_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('terms_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman Syarat & Ketentuan. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('terms') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Content Tab -->
                        <div class="tab-pane fade" id="privacy-content" role="tabpanel" aria-labelledby="privacy-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman Kebijakan Privasi</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-page-content') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="page_type" value="privacy">
                                        <div class="form-group">
                                            <label for="privacy_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="page_title"
                                                   id="privacy_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('privacy_title', 'Privacy Policy') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman Kebijakan Privasi.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="privacy_content">Konten Halaman</label>
                                            <textarea name="page_content"
                                                      id="privacy_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('privacy_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman Kebijakan Privasi. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('privacy') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Returns Content Tab -->
                        <div class="tab-pane fade" id="returns-content" role="tabpanel" aria-labelledby="returns-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman Kebijakan Pengembalian</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-page-content') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="page_type" value="returns">
                                        <div class="form-group">
                                            <label for="returns_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="page_title"
                                                   id="returns_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('returns_title', 'Returns Policy') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman Kebijakan Pengembalian.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="returns_content">Konten Halaman</label>
                                            <textarea name="page_content"
                                                      id="returns_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('returns_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman Kebijakan Pengembalian. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('returns') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Content Tab -->
                        <div class="tab-pane fade" id="shipping-content" role="tabpanel" aria-labelledby="shipping-content-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Halaman Informasi Pengiriman</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.update-page-content') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="page_type" value="shipping">
                                        <div class="form-group">
                                            <label for="shipping_title">Judul Halaman</label>
                                            <input type="text"
                                                   name="page_title"
                                                   id="shipping_title"
                                                   class="form-control"
                                                   value="{{ \App\Models\Setting::get('shipping_title', 'Shipping Information') }}"
                                                   required>
                                            <small class="form-text text-muted">
                                                Judul utama yang ditampilkan pada halaman Informasi Pengiriman.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="shipping_content">Konten Halaman</label>
                                            <textarea name="page_content"
                                                      id="shipping_content"
                                                      class="form-control summernote"
                                                      required>{{ \App\Models\Setting::get('shipping_content', '') }}</textarea>
                                            <small class="form-text text-muted">
                                                Konten utama yang ditampilkan pada halaman Informasi Pengiriman. Gunakan editor untuk memformat teks.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            <a href="{{ route('shipping') }}" target="_blank" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Halaman
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        // Ini bisa digunakan untuk upload gambar jika dibutuhkan
                        // Untuk saat ini kita tidak mengimplementasikan upload gambar
                    }
                }
            });
        });

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
            // Handle JSON item editing
            function updateJsonInput(containerId) {
                const container = document.getElementById(containerId);
                const itemsContainer = document.getElementById(containerId + '_items');
                const jsonInput = document.getElementById(containerId + '_input');

                if (!itemsContainer || !jsonInput) return;

                const items = Array.from(itemsContainer.querySelectorAll('.json-item'));
                let jsonData = [];

                items.forEach((item) => {
                    if (item.classList.contains('social-item')) {
                        const icon = item.querySelector('.icon-input').value;
                        const url = item.querySelector('.url-input').value;
                        jsonData.push({
                            icon: icon,
                            url: url
                        });
                    } else if (item.classList.contains('whatsapp-item')) {
                        const name = item.querySelector('.name-input').value;
                        const number = item.querySelector('.number-input').value;
                        const message = item.querySelector('.message-input').value;
                        jsonData.push({
                            name: name,
                            number: number,
                            message: message
                        });
                    } else if (item.classList.contains('courier-item')) {
                        const name = item.querySelector('.name-input').value;
                        const code = item.querySelector('.code-input').value;
                        const active = item.querySelector('.active-input').checked;
                        jsonData.push({
                            name: name,
                            code: code,
                            active: active
                        });
                    } else {
                        const text = item.querySelector('.text-input').value;
                        const urlContainer = item.querySelector('.url-selection-container');
                        const urlSelect = urlContainer.querySelector('.url-selection');
                        const waSelect = urlContainer.querySelector('.whatsapp-cs-selection');
                        const urlInput = urlContainer.querySelector('.url-input');
                        let url = '';

                        // Get URL from the appropriate source based on selection
                        if (urlSelect) {
                            const selectedType = urlSelect.value;

                            if (selectedType === 'whatsapp' && waSelect && waSelect.value) {
                                url = waSelect.value; // WhatsApp CS URL
                            } else if (selectedType === 'custom' && urlInput) {
                                url = urlInput.value; // Custom URL
                            } else if (selectedType !== 'custom' && selectedType !== 'whatsapp') {
                                url = selectedType; // Static URL
                            }
                        } else if (urlInput) {
                            url = urlInput.value; // Fallback
                        }

                        jsonData.push({
                            text: text,
                            url: url
                        });
                    }
                });

                jsonInput.value = JSON.stringify(jsonData);
            }

            // Add new JSON item
            document.querySelectorAll('.add-json-item-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const itemType = this.getAttribute('data-type');
                    const itemsContainer = document.getElementById(targetId + '_items');

                    if (!itemsContainer) return;

                    const newIndex = itemsContainer.querySelectorAll('.json-item').length;
                    const newItem = document.createElement('div');
                    newItem.className = `json-item ${itemType}-item`;
                    newItem.setAttribute('data-index', newIndex);

                    if (itemType === 'social') {
                        newItem.innerHTML = `
                            <div class="json-key-value-pair">
                                <div class="flex-grow-1">
                                    <label>Icon Class</label>
                                    <input type="text" class="form-control icon-input" value="fab fa-link" placeholder="fab fa-facebook-f">
                                </div>
                                <div class="flex-grow-1">
                                    <label>URL</label>
                                    <input type="text" class="form-control url-input" value="#" placeholder="https://...">
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                    } else if (itemType === 'whatsapp') {
                        newItem.innerHTML = `
                            <div class="json-key-value-pair">
                                <div class="flex-grow-1">
                                    <label>Nama CS</label>
                                    <input type="text" class="form-control name-input" value="Customer Service" placeholder="Layanan Umum">
                                </div>
                                <div class="flex-grow-1">
                                    <label>Nomor WhatsApp</label>
                                    <input type="text" class="form-control number-input" value="628123456789" placeholder="628123456789">
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pesan Default</label>
                                <input type="text" class="form-control message-input" value="Halo, saya butuh bantuan" placeholder="Halo, saya butuh bantuan...">
                            </div>
                        `;
                    } else if (itemType === 'courier') {
                        newItem.innerHTML = `
                            <div class="json-key-value-pair">
                                <div class="flex-grow-1">
                                    <label>Nama Kurir</label>
                                    <input type="text" class="form-control name-input" value="JNE" placeholder="JNE">
                                </div>
                                <div class="flex-grow-1">
                                    <label>Kode Kurir</label>
                                    <input type="text" class="form-control code-input" value="jne" placeholder="jne">
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input active-input" id="${targetId}_${newIndex}_active" checked>
                                    <label class="custom-control-label" for="${targetId}_${newIndex}_active">Aktif</label>
                                </div>
                            </div>
                        `;
                    } else {
                        newItem.innerHTML = `
                            <div class="json-key-value-pair">
                                <div class="flex-grow-1">
                                    <label>Text</label>
                                    <input type="text" class="form-control text-input" value="New Link" placeholder="Link text">
                                </div>
                                <div class="flex-grow-1">
                                    <label>URL</label>
                                    <div class="url-selection-container">
                                        <select class="form-control url-selection mb-2">
                                            <option value="custom">-- Pilih URL atau masukkan kustom --</option>
                                            <option value="/">Home</option>
                                            <option value="/about">About</option>
                                            <option value="/contact">Contact</option>
                                            <option value="/shop">Shop</option>
                                            <option value="/faq">FAQ</option>
                                            <option value="/terms">Syarat & Ketentuan</option>
                                            <option value="/privacy">Kebijakan Privasi</option>
                                            <option value="/returns">Kebijakan Pengembalian</option>
                                            <option value="/shipping">Informasi Pengiriman</option>
                                            <option value="whatsapp">WhatsApp CS</option>
                                            <option value="custom">URL Kustom</option>
                                        </select>

                                        <!-- WhatsApp CS Selector -->
                                        <select class="form-control whatsapp-cs-selection mb-2" style="display:none;">
                                            <option value="">-- Pilih WhatsApp CS --</option>
                                            @php
                                                $waContacts = \App\Models\Setting::get('footer_whatsapp_contacts', []);
                                                if (is_string($waContacts)) {
                                                    $waContacts = json_decode($waContacts, true) ?? [];
                                                }
                                            @endphp

                                            @foreach($waContacts as $contact)
                                                @php
                                                    $waNumber = $contact['number'] ?? '';
                                                    $waMessage = $contact['message'] ?? 'Hi, I need assistance';
                                                    $waName = $contact['name'] ?? 'Customer Service';
                                                    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage);
                                                @endphp
                                                <option value="{{ $waUrl }}">{{ $waName }}</option>
                                            @endforeach
                                        </select>

                                        <input type="text" class="form-control url-input" value="#" placeholder="https://..." style="display:none;">
                                    </div>
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn mt-4"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                    }

                    itemsContainer.appendChild(newItem);

                    // Add event listener for input changes
                    newItem.querySelectorAll('input').forEach((input) => {
                        input.addEventListener('input', () => updateJsonInput(targetId));
                    });

                    // Add event listener for remove button
                    newItem.querySelector('.remove-item-btn').addEventListener('click', function() {
                        newItem.remove();
                        updateJsonInput(targetId);
                    });

                    updateJsonInput(targetId);
                });
            });

            // Handle existing JSON items
            document.querySelectorAll('.json-form-group').forEach((group) => {
                const containerId = group.id;
                const key = containerId.replace('_container', '');

                // Add event listeners to all inputs
                group.querySelectorAll('input').forEach((input) => {
                    input.addEventListener('input', () => updateJsonInput(key));
                });

                // Add event listeners to remove buttons
                group.querySelectorAll('.remove-item-btn').forEach((btn) => {
                    btn.addEventListener('click', function() {
                        this.closest('.json-item').remove();
                        updateJsonInput(key);
                    });
                });
            });

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
                    fetch(`{{ url('admin/settings/toggle') }}/${key}`, {
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
                            endpoint: `{{ url('admin/settings/toggle') }}/${key}`,
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
                    // Update all JSON inputs before submission
                    document.querySelectorAll('.json-form-group').forEach((group) => {
                        const containerId = group.id;
                        const key = containerId.replace('_container', '');
                        updateJsonInput(key);
                    });

                    // Only allow submit if triggered by a submit button
                    if (!e.submitter || e.submitter.type !== 'submit') {
                        e.preventDefault();
                        return false;
                    }
                });
            });

            // Handle URL dropdown selection
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('url-selection')) {
                    const container = e.target.closest('.url-selection-container');
                    const urlInput = container.querySelector('.url-input');
                    const waSelector = container.querySelector('.whatsapp-cs-selection');
                    const selectedValue = e.target.value;

                    // Sembunyikan semua input dulu
                    if (urlInput) urlInput.style.display = 'none';
                    if (waSelector) waSelector.style.display = 'none';

                    // Tampilkan input yang sesuai
                    if (selectedValue === 'custom') {
                        if (urlInput) {
                            urlInput.style.display = 'block';
                            urlInput.focus();
                        }
                    } else if (selectedValue === 'whatsapp') {
                        if (waSelector) {
                            waSelector.style.display = 'block';
                            waSelector.focus();

                            // Jika belum ada WhatsApp CS yang dipilih, pilih yang pertama
                            if (!waSelector.value && waSelector.options.length > 1) {
                                waSelector.selectedIndex = 1; // Pilih opsi pertama setelah placeholder

                                // Trigger change event pada WhatsApp selector
                                const event = new Event('change');
                                waSelector.dispatchEvent(event);
                            }
                        }
                    } else {
                        // Untuk opsi URL tetap, langsung set nilai input
                        if (urlInput) {
                            urlInput.value = selectedValue;
                        }
                    }

                    // Update the JSON input value
                    const item = e.target.closest('.json-item');
                    if (item) {
                        const containerId = item.closest('[id$="_items"]').id.replace('_items', '');
                        updateJsonInput(containerId);
                    }
                }

                // Handle WhatsApp CS selector changes
                if (e.target && e.target.classList.contains('whatsapp-cs-selection')) {
                    const container = e.target.closest('.url-selection-container');
                    const urlInput = container.querySelector('.url-input');
                    const selectedWaUrl = e.target.value;

                    // Set URL input value to the selected WhatsApp URL
                    if (urlInput) {
                        urlInput.value = selectedWaUrl;
                    }

                    // Update the JSON input value
                    const item = e.target.closest('.json-item');
                    if (item) {
                        const containerId = item.closest('[id$="_items"]').id.replace('_items', '');
                        updateJsonInput(containerId);
                    }
                }
            });

            // Initialize the URL dropdowns
            document.querySelectorAll('.url-selection').forEach(function(dropdown) {
                const container = dropdown.closest('.url-selection-container');
                const urlInput = container.querySelector('.url-input');
                const waSelector = container.querySelector('.whatsapp-cs-selection');
                const selectedValue = dropdown.value;

                // Sembunyikan semua input dulu
                if (urlInput) urlInput.style.display = 'none';
                if (waSelector) waSelector.style.display = 'none';

                // Tampilkan input yang sesuai
                if (selectedValue === 'custom') {
                    if (urlInput) urlInput.style.display = 'block';
                } else if (selectedValue === 'whatsapp') {
                    if (waSelector) waSelector.style.display = 'block';
                }
            });
        });
    </script>
@endpush

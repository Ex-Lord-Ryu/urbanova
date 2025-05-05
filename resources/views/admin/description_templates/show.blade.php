@extends('layouts.app')

@section('title', 'Detail Template Deskripsi')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/products/products.css') }}">
    <style>
        .template-content {
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            background-color: #fdfdfd;
            margin-bottom: 20px;
        }
        .template-meta-item {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px solid #f8f9fa;
            padding-bottom: 10px;
        }
        .template-meta-item:last-child {
            border-bottom: none;
        }
        .template-meta-label {
            font-weight: 600;
            width: 120px;
            color: #6c757d;
        }
        .template-meta-value {
            flex: 1;
        }
        .template-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .template-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }
        .template-category {
            display: inline-block;
            background-color: #6777ef;
            color: white;
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 15px;
            margin-left: 10px;
        }
    </style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Template Deskripsi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('description-templates.index') }}">Template Deskripsi</a></div>
            <div class="breadcrumb-item">Detail Template</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="template-header">
                            <div>
                                <h4 class="template-title">{{ $descriptionTemplate->name }}
                                @if($descriptionTemplate->category)
                                <span class="template-category">{{ $descriptionTemplate->category }}</span>
                                @endif
                                </h4>
                            </div>
                            <div>
                                <span class="badge {{ $descriptionTemplate->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $descriptionTemplate->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="template-meta">
                            <div class="template-meta-item">
                                <div class="template-meta-label">Tanggal Dibuat</div>
                                <div class="template-meta-value">{{ $descriptionTemplate->created_at->format('d F Y H:i') }}</div>
                            </div>
                            <div class="template-meta-item">
                                <div class="template-meta-label">Terakhir Diupdate</div>
                                <div class="template-meta-value">{{ $descriptionTemplate->updated_at->format('d F Y H:i') }}</div>
                            </div>
                            <div class="template-meta-item">
                                <div class="template-meta-label">Konten Template</div>
                                <div class="template-meta-value">
                                    <div class="template-content">{!! $descriptionTemplate->content !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="template-actions mt-4">
                            <a href="{{ route('description-templates.edit', $descriptionTemplate->id) }}" class="btn btn-warning">
                                <i class="fas fa-pen"></i> Edit Template
                            </a>
                            <a href="{{ route('description-templates.index') }}" class="btn btn-primary ml-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                            </a>
                            <form action="{{ route('description-templates.destroy', $descriptionTemplate->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger ml-2" onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                                    <i class="fas fa-trash"></i> Hapus Template
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

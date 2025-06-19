@extends('layouts.app')

@section('title', 'Profil Saya')

@push('css')
<style>
    .profile-card {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .profile-header {
        background-color: #6777ef;
        color: white;
        padding: 20px;
        text-align: center;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: white;
        color: #6777ef;
        font-size: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .profile-info {
        padding: 20px;
    }
    .info-item {
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .info-value {
        font-size: 16px;
    }
    .actions-bar {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #eee;
    }
    .profile-badge {
        background-color: #e1e1fc;
        color: #6777ef;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-block;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>{{ $user->name }}</h3>
                        <div class="profile-badge">{{ ucfirst($user->role) }}</div>
                    </div>

                    <div class="profile-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Nomor HP</div>
                                    <div class="info-value">
                                        @if ($user->phone)
                                            {{ $user->phone }}
                                        @else
                                            <span class="text-muted">Belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Jenis Kelamin</div>
                                    <div class="info-value">
                                        @if ($user->gender == 'male')
                                            Laki-laki
                                        @elseif ($user->gender == 'female')
                                            Perempuan
                                        @else
                                            <span class="text-muted">Belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Tanggal Lahir</div>
                                    <div class="info-value">
                                        @if ($user->birth_date)
                                            {{ \Carbon\Carbon::parse($user->birth_date)->format('d F Y') }}
                                        @else
                                            <span class="text-muted">Belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Terdaftar Sejak</div>
                                    <div class="info-value">{{ $user->created_at->format('d F Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diperbarui</div>
                                    <div class="info-value">{{ $user->updated_at->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actions-bar text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-user-edit mr-1"></i> Edit Profil
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.landing')

@section('title', 'Profil Saya')

@push('css')
    <style>
        /* Modern card styling */
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 40px;
            margin-bottom: 40px;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Stylish header with gradient */
        .profile-header {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }

        /* Decorative background elements */
        .profile-header:before {
            content: "";
            position: absolute;
            top: -15px;
            right: -15px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            z-index: 0;
        }

        .profile-header:after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            z-index: 0;
        }

        /* Animated avatar */
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: white;
            color: #090969;
            font-size: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 5px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .profile-avatar i {
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Improved content area */
        .profile-info {
            padding: 30px;
            background-color: #fff;
        }

        .info-item {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            transition: transform 0.2s;
        }

        .info-item:hover {
            transform: translateX(5px);
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 17px;
            color: #343a40;
        }

        .text-muted {
            font-style: italic;
        }

        /* Modern action bar */
        .actions-bar {
            padding: 20px;
            background: linear-gradient(to right, #f8f9fa, #f1f3f5);
            border-top: 1px solid #eee;
            text-align: center;
        }

        /* Fancy badge */
        .profile-badge {
            background: linear-gradient(135deg, #e1e1fc 0%, #d4d4ff 100%);
            color: #090969;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(9, 9, 105, 0.15);
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .profile-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(9, 9, 105, 0.2);
        }

        /* Modern button styling */
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

        .btn-primary i {
            margin-right: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .profile-info {
                padding: 20px 15px;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
        }

        /* Nice animation for the page load */
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
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success animate-fade-in" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card profile-card animate-fade-in">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 class="mb-2">{{ $user->name }}</h2>
                    </div>

                    <div class="profile-info">
                        <div class="row animate-fade-in delay-1">
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
                        <div class="row animate-fade-in delay-2">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Jenis Kelamin</div>
                                    <div class="info-value">
                                        @if ($user->gender == 'male')
                                            <i class="fas fa-mars text-primary mr-2"></i> Laki-laki
                                        @elseif ($user->gender == 'female')
                                            <i class="fas fa-venus text-danger mr-2"></i> Perempuan
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
                                            <i class="fas fa-birthday-cake text-info mr-2"></i>
                                            {{ \Carbon\Carbon::parse($user->birth_date)->format('d F Y') }}
                                        @else
                                            <span class="text-muted">Belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row animate-fade-in delay-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Terdaftar Sejak</div>
                                    <div class="info-value">
                                        <i class="fas fa-calendar-check text-success mr-2"></i>
                                        {{ $user->created_at->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diperbarui</div>
                                    <div class="info-value">
                                        <i class="fas fa-sync text-secondary mr-2"></i>
                                        {{ $user->updated_at->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actions-bar">
                        <a href="{{ route('landing.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

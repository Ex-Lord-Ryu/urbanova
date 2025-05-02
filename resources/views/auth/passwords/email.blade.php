@extends('layouts.app')

@section('title', 'Reset Password - Urbanova')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            {{ __('Reset Password') }}
        </div>

        <div class="auth-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="auth-btn">
                    {{ __('Send Password Reset Link') }}
                </button>
            </form>

            <div class="auth-footer">
                <div>
                    <a class="auth-link" href="{{ route('login') }}">
                        {{ __('Back to Login') }}
                    </a>
                </div>

                <div class="mt-4">
                    <a href="/" class="home-link">
                        <i class="fas fa-home"></i> {{ __('Back to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

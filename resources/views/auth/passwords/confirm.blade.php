@extends('layouts.app')

@section('title', 'Confirm Password - Urbanova')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            {{ __('Confirm Password') }}
        </div>

        <div class="auth-body">
            <p class="mb-4">{{ __('Please confirm your password before continuing.') }}</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="auth-btn">
                    {{ __('Confirm Password') }}
                </button>
            </form>

            <div class="auth-footer">
                @if (Route::has('password.request'))
                    <div>
                        <a class="auth-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif

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

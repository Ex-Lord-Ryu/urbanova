@extends('layouts.landing')

@section('title', 'Urbanova - About Us')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/landing/about.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .about-hero {
            background-color: #f8f9fa;
            padding: 80px 0;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.03) 0%, rgba(0,0,0,0) 100%);
            z-index: 1;
        }

        .about-hero-content {
            position: relative;
            z-index: 2;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
            padding: 0 20px;
        }

        .about-hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
            position: relative;
            display: inline-block;
        }

        .about-hero h1::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #6777ef;
        }

        .about-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px 80px;
        }

        .about-text {
            line-height: 1.9;
            color: #555;
            font-size: 1.05rem;
        }

        .about-text p {
            margin-bottom: 24px;
        }

        .about-text strong {
            color: #333;
        }

        .about-text h2 {
            margin: 40px 0 20px;
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            position: relative;
            padding-bottom: 12px;
        }

        .about-text h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: #6777ef;
        }

        .about-text h3 {
            margin: 30px 0 15px;
            font-size: 1.4rem;
            font-weight: 600;
            color: #444;
        }

        .about-text ul, .about-text ol {
            margin-bottom: 24px;
            padding-left: 20px;
        }

        .about-text li {
            margin-bottom: 8px;
        }

        .about-text img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .about-text blockquote {
            border-left: 4px solid #6777ef;
            padding: 15px 20px;
            background-color: #f8f9fa;
            margin: 20px 0;
            font-style: italic;
            color: #555;
        }

        .about-text table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .about-text table th, .about-text table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .about-text table th {
            background-color: #f5f5f5;
        }

        /* Separator styling */
        .separator {
            height: 1px;
            background: linear-gradient(to right, rgba(0,0,0,0), rgba(0,0,0,0.1), rgba(0,0,0,0));
            margin: 40px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .about-hero {
                padding: 60px 0;
                margin-bottom: 40px;
            }

            .about-hero h1 {
                font-size: 2.2rem;
            }

            .about-content {
                padding: 0 15px 60px;
            }

            .about-text {
                font-size: 1rem;
            }

            .about-text h2 {
                font-size: 1.6rem;
            }

            .about-text h3 {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@section('content')
    <section class="about-hero">
        <div class="about-hero-content">
            <h1>{{ $aboutTitle }}</h1>
        </div>
    </section>

    <section class="about-content">
        <div class="about-text">
            {!! $aboutContent !!}
        </div>
    </section>

    @include('components.footer')
@endsection

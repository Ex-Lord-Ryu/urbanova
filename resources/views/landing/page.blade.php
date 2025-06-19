@extends('layouts.landing')

@section('title', 'Urbanova - ' . $pageTitle)

@push('css')
    <style>
        .page-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 20px 80px;
        }

        .page-hero {
            background-color: #f8f9fa;
            padding: 80px 0;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.03) 0%, rgba(0,0,0,0) 100%);
            z-index: 1;
        }

        .page-hero-content {
            position: relative;
            z-index: 2;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
            padding: 0 20px;
        }

        .page-hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
            position: relative;
            display: inline-block;
        }

        .page-hero h1::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #6777ef;
        }

        .page-text {
            line-height: 1.9;
            color: #555;
            font-size: 1.05rem;
        }

        .page-text p {
            margin-bottom: 24px;
        }

        .page-text strong {
            color: #333;
        }

        .page-text h2 {
            margin: 40px 0 20px;
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            position: relative;
            padding-bottom: 12px;
        }

        .page-text h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: #6777ef;
        }

        .page-text h3 {
            margin: 30px 0 15px;
            font-size: 1.4rem;
            font-weight: 600;
            color: #444;
        }

        .page-text ul, .page-text ol {
            margin-bottom: 24px;
            padding-left: 20px;
        }

        .page-text li {
            margin-bottom: 8px;
        }

        .page-text img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .page-text blockquote {
            border-left: 4px solid #6777ef;
            padding: 15px 20px;
            background-color: #f8f9fa;
            margin: 20px 0;
            font-style: italic;
            color: #555;
        }

        .page-text table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .page-text table th, .page-text table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .page-text table th {
            background-color: #f5f5f5;
        }

        /* Styles specific to FAQ page */
        .faq-list .faq-item {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 30px;
        }

        .faq-list .faq-question {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .faq-list .faq-answer {
            color: #555;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .page-hero {
                padding: 60px 0;
                margin-bottom: 40px;
            }

            .page-hero h1 {
                font-size: 2.2rem;
            }

            .page-content {
                padding: 0 15px 60px;
            }

            .page-text {
                font-size: 1rem;
            }

            .page-text h2 {
                font-size: 1.6rem;
            }

            .page-text h3 {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@section('content')
    <section class="page-hero">
        <div class="page-hero-content">
            <h1>{{ $pageTitle }}</h1>
        </div>
    </section>

    <section class="page-content">
        <div class="page-text">
            {!! $pageContent !!}
        </div>
    </section>

    @include('components.footer')
@endsection

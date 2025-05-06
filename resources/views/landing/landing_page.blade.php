@extends('layouts.landing')

@section('title', 'Urbanova - Home')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/landing/landing_page.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <section class="hero">
        <h1>New Arrivals</h1>
    </section>

    <section class="products">
        @forelse ($featuredProducts as $product)
            <div class="product-card">
                <div class="product-img-container">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="product-img">
                </div>
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <button>Add to Cart</button>
                </div>
            </div>
        @empty
            <div class="no-products">
                <p>No featured products available at the moment.</p>
            </div>
        @endforelse
    </section>
@endsection

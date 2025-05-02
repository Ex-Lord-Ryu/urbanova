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
        <div class="product-card">
            <img src="https://via.placeholder.com/300" alt="Product 1" class="product-img">
            <div class="product-info">
                <h3>Oversized Boxy</h3>
                <div class="price">$29.99</div>
                <button>Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <img src="https://via.placeholder.com/300" alt="Product 2" class="product-img">
            <div class="product-info">
                <h3>Body Fit</h3>
                <div class="price">$39.99</div>
                <button>Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <img src="https://via.placeholder.com/300" alt="Product 3" class="product-img">
            <div class="product-info">
                <h3>Product Title 3</h3>
                <div class="price">$19.99</div>
                <button>Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <img src="https://via.placeholder.com/300" alt="Product 4" class="product-img">
            <div class="product-info">
                <h3>Product Title 4</h3>
                <div class="price">$49.99</div>
                <button>Add to Cart</button>
            </div>
        </div>
    </section>
@endsection

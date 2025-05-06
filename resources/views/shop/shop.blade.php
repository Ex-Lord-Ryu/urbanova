@extends('layouts.landing')

@section('title', 'Urbanova - Shop')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/shop/shop.css') }}">
@endpush

@section('content')
  <!-- Main Shop Section -->
  <main class="shop-page">
    <div class="shop-container">

      <!-- Sidebar: Filters -->
      <aside class="filters">
        <h3>Filter by</h3>
        <div class="filter-group">
          <h4>Category</h4>
          <ul>
            @foreach($categories as $category)
              <li><label><input type="checkbox" name="category[]" value="{{ $category->id }}"> {{ $category->name }}</label></li>
            @endforeach
          </ul>
        </div>
        <div class="filter-group">
          <h4>Price</h4>
          <ul>
            @forelse($priceRanges as $index => $range)
              <li><label><input type="radio" name="price" value="{{ $index }}"> {{ $range['label'] }}</label></li>
            @empty
              <li>No price ranges available</li>
            @endforelse
          </ul>
        </div>
        <button class="apply-filters">Apply Filters</button>
      </aside>

      <!-- Products Grid -->
      <section class="products shop-grid">
        @forelse($products as $product)
          <div class="product-card {{ $product->is_featured && $showFeaturedBadge ? 'featured' : '' }}">
            @if($product->is_featured && $showFeaturedBadge)
              <div class="featured-badge">Unggulan</div>
            @endif
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
            <p>No products available at this time.</p>
          </div>
        @endforelse
      </section>

      <!-- Pagination -->
      @if($products->hasPages())
        <div class="pagination">
          {{ $products->links() }}
        </div>
      @endif
    </div>
  </main>
@endsection

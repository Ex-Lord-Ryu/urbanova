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
        <form action="{{ route('shop') }}" method="GET" id="filterForm">
          <h3>Filter by</h3>
          <div class="filter-group">
            <h4>Category</h4>
            <ul>
              @foreach($categories as $category)
                <li>
                  <label>
                    <input type="checkbox"
                           name="category[]"
                           value="{{ $category->id }}"
                           {{ in_array($category->id, is_array($selectedCategories) ? $selectedCategories : []) ? 'checked' : '' }}>
                    {{ $category->name }}
                  </label>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="filter-group">
            <h4>Price</h4>
            <ul>
              @forelse($priceRanges as $index => $range)
                <li>
                  <label>
                    <input type="radio"
                           name="price"
                           value="{{ $index }}"
                           {{ (string)$selectedPrice === (string)$index ? 'checked' : '' }}>
                    {{ $range['label'] }}
                  </label>
                </li>
              @empty
                <li>No price ranges available</li>
              @endforelse
            </ul>
          </div>
          <button type="submit" class="apply-filters">APPLY FILTERS</button>
        </form>
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

@push('scripts')
<script>
  // Automatically submit form when checkbox or radio changes
  document.addEventListener('DOMContentLoaded', function() {
    // For demo purposes, we're not using this auto-submit
    // as it might be confusing for users to have the page refresh immediately
    // Uncomment the code below if you want filters to apply automatically
    /*
    const filterInputs = document.querySelectorAll('#filterForm input[type="checkbox"], #filterForm input[type="radio"]');
    filterInputs.forEach(input => {
      input.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
      });
    });
    */
  });
</script>
@endpush

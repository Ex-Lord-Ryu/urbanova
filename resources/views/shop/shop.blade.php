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
            <li><label><input type="checkbox"> Clothing</label></li>
            <li><label><input type="checkbox"> Electronics</label></li>
            <li><label><input type="checkbox"> Accessories</label></li>
          </ul>
        </div>
        <div class="filter-group">
          <h4>Price</h4>
          <ul>
            <li><label><input type="radio" name="price"> Under $25</label></li>
            <li><label><input type="radio" name="price"> $25–50</label></li>
            <li><label><input type="radio" name="price"> $50–100</label></li>
          </ul>
        </div>
        <button class="apply-filters">Apply Filters</button>
      </aside>

      <!-- Products Grid -->
      <section class="products shop-grid">
        <!-- Example product card; duplicate as needed -->
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img" style="background-image: url('https://via.placeholder.com/300');"></div>
          <div class="product-info">
            <h3>Urban Tee</h3>
            <div class="price">$29.99</div>
            <button>Add to Cart</button>
          </div>
        </div>
      </section>
    </div>
  </main>
@endsection

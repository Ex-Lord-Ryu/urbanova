<style>
/* Style khusus untuk cart-added-modal, menggunakan selector spesifik agar tidak bentrok dengan global CSS */
#cartAddedModal .modal-header {
  background-color: #090969 !important;
  color: #fff !important;
}

#cartAddedModal .modal-body {
  background-color: #f5f7fa !important;
  padding: 25px !important;
}

#cartAddedModal .btn-outline-primary {
  color: #090969 !important;
  border-color: #090969 !important;
  background-color: transparent !important;
  font-weight: 600 !important;
}

#cartAddedModal .btn-outline-primary:hover,
#cartAddedModal .btn-outline-primary:focus {
  color: #fff !important;
  background-color: #090969 !important;
}

#cartAddedModal .btn-danger {
  background-color: #090969 !important;
  border-color: #090969 !important;
  color: #fff !important;
  font-weight: 600 !important;
}

#cartAddedModal .btn-danger:hover,
#cartAddedModal .btn-danger:focus {
  background-color: #060644 !important;
  border-color: #060644 !important;
}

#cartAddedModal .text-danger {
  color: #090969 !important;
}

#cartAddedModal .badge-secondary {
  background-color: #e3eaef !important;
  color: #090969 !important;
}

#cartAddedModal .card {
  transition: transform 0.2s;
  cursor: pointer;
}

#cartAddedModal .card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}

#cartAddedModal .product-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
}

#cartAddedModal .product-card-link:hover {
  text-decoration: none;
}
</style>

<div class="modal fade" id="cartAddedModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white align-items-center">
        <h5 class="modal-title font-weight-bold">Product Added to Cart</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
        <div class="d-flex align-items-center mb-4">
          <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100' }}" alt="{{ $product->name }}" width="90" class="rounded shadow-sm mr-4" style="background:#fff;">
          <div class="flex-grow-1">
            <div class="h5 mb-1 font-weight-bold">{{ $product->name }}
              @if(isset($size) && $size)
                <span class="badge badge-secondary ml-2">{{ $size }}</span>
              @endif
              @if(isset($color) && $color)
                <span class="badge badge-secondary ml-1 d-inline-flex align-items-center">
                  <span class="color-dot" style="display:inline-block;width:12px;height:12px;border-radius:50%;background-color:#{{ $color->hex_code }};margin-right:4px;"></span>
                  {{ $color->name }}
                </span>
              @endif
            </div>
            <div class="mb-1">
              <span class="text-muted small"><del>Rp {{ number_format($product->price + 20000, 0, ',', '.') }}</del></span>
              <span class="ml-2 h5 text-danger">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            <div class="text-muted">{{ $qty }} item{{ $qty > 1 ? 's' : '' }}</div>
          </div>
          <div class="ml-auto d-flex flex-column align-items-end">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary mb-2 px-4">View Cart</a>
            <a href="{{ route('cart.index') }}" class="btn btn-danger px-4">Buy It Now</a>
          </div>
        </div>
        <hr>
        <div>
          <h6 class="font-weight-bold mb-3">Recently Ordered</h6>
          <div class="d-flex flex-nowrap overflow-auto" style="gap: 20px;">
            @foreach($recent as $item)
              <a href="{{ route('product.show', $item->slug) }}" class="product-card-link">
                <div class="card border-0 shadow-sm text-center p-2" style="min-width:130px;max-width:130px;background:#fff;">
                  <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/80' }}" alt="{{ $item->name }}" width="80" class="rounded mb-2 mx-auto d-block" style="background:#f8f9fa;">
                  <div class="font-weight-600 small mb-1" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->name }}</div>
                  <div class="text-muted small"><del>Rp {{ number_format($item->price + 20000, 0, ',', '.') }}</del></div>
                  <div class="text-danger font-weight-bold small">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

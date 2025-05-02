<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Page - Urbanova</title>
  <link rel="stylesheet" href="cart.css" />
</head>
<body>

  <header>
    <div class="nav-container">
      <div class="logo">URBANOVA</div>
      <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
      <nav>
        <ul class="nav-links">
          <li><a href="home.php">Home</a></li>
          <li><a href="shop.php">Shop</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="cart.php">Cart 🛒</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="product-page">
    <div class="product-container">
      <div class="product-images">
        <img src="shoe-main.jpg" alt="Reebok Zig Kinetica 3" class="main-image" />
        <div class="thumbnail-row">
          <img src="thumb1.jpg" alt="" />
        </div>
      </div>
      <div class="product-details">
        <h2 class="brand">URBANOVA</h2>
        <h1 class="product-title">Oversized Boxy</h1>
        <div class="rating">★★★★☆ <span>(42 reviews)</span></div>
        <div class="price">$199.00</div>

        <div class="product-option">
          <label>Size:</label>
          <div class="size-options">
            <button>M</button>
            <button class="active">L</button>
            <button>XL</button>
          </div>
        </div>

        <button class="add-to-cart">🛒 Add to cart</button>
      </div>
    </div>
  </main>

  <script>
    const toggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    toggle.addEventListener('click', () => {
      navLinks.classList.toggle('show');
    });
  </script>

</body>
</html>

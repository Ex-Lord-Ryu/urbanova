<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urbanova - Home</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
  <div class="nav-container">
    <div class="logo">URBANOVA</div>
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

<script>
  const toggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');

  toggle.addEventListener('click', () => {
    navLinks.classList.toggle('show');
  });
</script>


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

  <footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>About</h4>
        <ul>
          <li><a href="#">Our Story</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h4>Customer Service</h4>
        <ul>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Returns</a></li>
          <li><a href="#">Shipping Info</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h4>Follow Us</h4>
        <div class="socials">
          <a href="#">F</a>
          <a href="#">T</a>
          <a href="#">I</a>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
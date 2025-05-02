<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urbanova - Shop</title>
  <link rel="stylesheet" href="shop.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Header -->
  <header>
    <div class="nav-container">
      <div class="logo">URBANOVA</div>
      <nav>
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="shop.php" class="active">Shop</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="cart.php">Cart 🛒</a></li>
        </ul>
      </nav>
    </div>
  </header>

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

  <!-- Footer -->
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
<header>
    <div class="nav-container">
        <div class="logo">URBANOVA</div>
        <button class="nav-toggle">☰</button>
        <nav>
            <ul class="nav-links">
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ url('/shop') }}" class="{{ request()->is('shop') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
                </li>
                <li>
                    <a href="{{ url('/cart') }}" class="{{ request()->is('cart') ? 'active' : '' }} cart-link">
                        Cart 🛒
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

                @guest
                    <li><a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Login</a></li>
                    <li><a href="{{ route('register') }}"
                            class="{{ request()->is('register') ? 'active' : '' }}">Register</a></li>
                @else
                    @if(auth()->user()->role == 'admin')
                        <li>
                            <a href="{{ route('home') }}"
                                class="{{ request()->is('home') ? 'active' : '' }}">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('landing.profile') }}"
                                class="{{ request()->is('user/profile/profile*') ? 'active' : '' }}">Profil</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('landing.profile') }}"
                                class="{{ request()->is('landing/profile*') ? 'active' : '' }}">Profil</a>
                        </li>
                        <li>
                            <a href="{{ route('landing.orders') }}"
                                class="{{ request()->is('user/orders*') ? 'active' : '' }}">Pesanan Saya</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endif
                @endguest
            </ul>
        </nav>
    </div>
</header>

<style>
    /* Header */
    header {
        background: #fff;
        padding: 1rem 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
    }

    .nav-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 600;
        color: #090969;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 1.5rem;
    }

    nav li a {
        padding: 0.5rem;
        transition: background 0.2s, transform 0.2s;
        border-radius: 0.3rem;
        text-decoration: none;
        color: #090969;
        font-weight: normal;
    }

    nav li a:hover,
    nav li a.active {
        background: #090969;
        color: #fff;
        transform: scale(1.05);
        font-weight: normal;
    }

    .nav-toggle {
        display: none;
        font-size: 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: #090969;
    }

    /* Cart count styles */
    .cart-link {
        position: relative;
    }

    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #ff0000;
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Responsive for Mobile */
    @media (max-width: 768px) {
        .nav-container {
            flex-wrap: wrap;
        }

        .nav-toggle {
            display: block;
        }

        nav {
            width: 100%;
        }

        .nav-links {
            display: none;
            flex-direction: column;
            width: 100%;
            margin-top: 1rem;
        }

        .nav-links.show {
            display: flex;
        }

        .nav-links li {
            padding: 0.5rem 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        if (toggle) {
            toggle.addEventListener('click', () => {
                navLinks.classList.toggle('show');
            });
        }
    });
</script>

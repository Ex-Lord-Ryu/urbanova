@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">URBANOVA</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">UBNV</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>
                <li class="menu-header">Beranda</li>
                <li class="{{ Request::is('landing_page') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home"></i><span>Beranda</span></a>
                </li>
                @if (Auth::user()->role == 'admin')
                    <li class="menu-header">Hak Akses</li>
                    <li class="{{ Request::is('hakakses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('hakakses') }}"><i class="fas fa-user-shield"></i> <span>Hak
                                Akses</span></a>
                    </li>

                    <li class="menu-header">Manajemen Produk</li>
                    <li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/categories') }}"><i class="fas fa-list"></i> <span>Kategori
                                Produk</span></a>
                    </li>
                    <li class="{{ Request::is('admin/products*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/products') }}"><i class="fas fa-tshirt"></i> <span>Produk
                                Pakaian</span></a>
                    </li>
                    <li class="{{ Request::is('admin/colors*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/colors') }}"><i class="fas fa-palette"></i> <span>Warna
                                Produk</span></a>
                    </li>
                    <li class="{{ Request::is('admin/sizes*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/sizes') }}"><i class="fas fa-ruler"></i> <span>Ukuran
                                Produk</span></a>
                    </li>
                    <li class="{{ Request::is('admin/description-templates*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/description-templates') }}"><i class="fas fa-file-alt"></i>
                            <span>Template Deskripsi</span></a>
                    </li>
                    <li class="{{ Request::is('admin/discounts*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/discounts') }}"><i class="fas fa-tags"></i>
                            <span>Manajemen Diskon</span></a>
                    </li>

                    <li class="menu-header">Manajemen Pesanan</li>
                    <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/orders') }}"><i class="fas fa-shopping-cart"></i> <span>Semua Pesanan</span></a>
                    </li>
                    <li class="{{ Request::is('admin/payments*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/payments') }}"><i class="fas fa-money-bill"></i> <span>Verifikasi Pembayaran</span></a>
                    </li>
                    <li class="{{ Request::is('admin/sales-reports*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/sales-reports') }}"><i class="fas fa-chart-bar"></i> <span>Laporan Penjualan</span></a>
                    </li>

                    <li class="menu-header">Pengaturan</li>
                    <li class="{{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/settings') }}"><i class="fas fa-cog"></i> <span>Pengaturan
                                Tampilan</span></a>
                    </li>
                @endif
                <!-- profile ganti password -->
                <li class="menu-header">Profile</li>
                <li class="{{ Request::is('profile') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile') }}"><i class="fas fa-user"></i> <span>My Profile</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endauth

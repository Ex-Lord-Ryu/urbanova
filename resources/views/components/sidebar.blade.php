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
                @if (Auth::user()->role == 'admin')
                    <li class="menu-header">Hak Akses</li>
                    <li class="{{ Request::is('hakakses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('hakakses') }}"><i class="fas fa-user-shield"></i> <span>Hak
                                Akses</span></a>
                    </li>

                    <li class="menu-header">Manajemen Produk</li>
                    <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('categories') }}"><i class="fas fa-list"></i> <span>Kategori Produk</span></a>
                    </li>
                    <li class="{{ Request::is('products*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('products') }}"><i class="fas fa-tshirt"></i> <span>Produk Pakaian</span></a>
                    </li>
                    <li class="{{ Request::is('colors*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('colors') }}"><i class="fas fa-palette"></i> <span>Warna Produk</span></a>
                    </li>
                    <li class="{{ Request::is('sizes*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('sizes') }}"><i class="fas fa-ruler"></i> <span>Ukuran Produk</span></a>
                    </li>
                    <li class="{{ Request::is('description-templates*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('description-templates') }}"><i class="fas fa-file-alt"></i> <span>Template Deskripsi</span></a>
                    </li>
                @endif
                <!-- profile ganti password -->
                <li class="menu-header">Profile</li>
                <li class="{{ Request::is('profile/edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile/edit') }}"><i class="far fa-user"></i>
                        <span>Profile</span></a>
                </li>
                <li class="{{ Request::is('profile/change-password') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile/change-password') }}"><i class="fas fa-key"></i> <span>Ganti
                            Password</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endauth

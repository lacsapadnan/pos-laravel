<aside class="navbar navbar-vertical navbar-expand-lg">
    <div class="container-fluid">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->
        <!-- BEGIN NAVBAR LOGO -->
        <div class="navbar-brand navbar-brand-autodark">
            <a href="#" aria-label="Tabler">
                Laravel POS
            </a>
        </div>
        <!-- END NAVBAR LOGO -->
        <div class="navbar-collapse collapse" id="sidebar-menu" style="">
            <!-- BEGIN NAVBAR MENU -->
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Dashboard </span>
                    </a>
                </li>
                @can('stock.view')
                <li class="nav-item {{ request()->is('stok*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('stock.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M3 3h18v18H3z"></path>
                                <path d="M9 9h6v6H9z"></path>
                                <path d="M3 9h6"></path>
                                <path d="M15 9h6"></path>
                                <path d="M9 3v6"></path>
                                <path d="M9 15v6"></path>
                                <path d="M15 3v6"></path>
                                <path d="M15 15v6"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title"> Manajemen Stok </span>
                    </a>
                </li>
                @endcan
                <li
                    class="nav-item dropdown {{ request()->is('role*') || request()->is('kategori*') || request()->is('supplier*') || request()->is('konsumen*') || request()->is('satuan*') || request()->is('produk*') || request()->is('user*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                        data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-1">
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                                <path d="M12 12l8 -4.5"></path>
                                <path d="M12 12l0 9"></path>
                                <path d="M12 12l-8 -4.5"></path>
                                <path d="M16 5.25l-8 4.5"></path>
                            </svg></span>
                        <span class="nav-link-title"> Master Data </span>
                    </a>
                    <div
                        class="dropdown-menu {{ request()->is('role*') || request()->is('kategori*') || request()->is('supplier*') || request()->is('konsumen*') || request()->is('satuan*') || request()->is('produk*') || request()->is('user*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @can('category.view')
                                <a class="dropdown-item {{ request()->is('kategori*') ? 'active' : '' }}"
                                    href="{{ route('category.index') }}"> Kategori </a>
                                @endcan
                                @can('product.view')
                                <a class="dropdown-item {{ request()->is('produk*') ? 'active' : '' }}"
                                    href="{{ route('product.index') }}"> Produk </a>
                                @endcan
                                @can('user.view')
                                <a class="dropdown-item {{ request()->is('user*') ? 'active' : '' }}"
                                    href="{{ route('user.index') }}"> User </a>
                                @endcan
                                @can('customer.view')
                                <a class="dropdown-item {{ request()->is('konsumen*') ? 'active' : '' }}"
                                    href="{{ route('customer.index') }}"> Konsumen </a>
                                @endcan
                                @can('unit.view')
                                <a class="dropdown-item {{ request()->is('satuan*') ? 'active' : '' }}"
                                    href="{{ route('unit.index') }}"> Satuan </a>
                                @endcan
                                @can('supplier.view')
                                <a class="dropdown-item {{ request()->is('supplier*') ? 'active' : '' }}"
                                    href="{{ route('supplier.index') }}"> Supplier </a>
                                @endcan
                                @can('role.view')
                                <a class="dropdown-item {{ request()->is('role*') ? 'active' : '' }}"
                                    href="{{ route('role.index') }}"> Hak Akses </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- END NAVBAR MENU -->
        </div>
    </div>
</aside>
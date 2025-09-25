@php
$cartItems = session('cart', []);
$cartCount = collect($cartItems)->sum('quantity');
@endphp

<style>
    .sidebar-divider {
        border: 0;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        margin: 10px 0;
    }

    .badge-cart {
        font-size: 10px;
        padding: 3px 6px;
        position: absolute;
        top: 8px;
        left: 20px;
    }

    .nav-icon-wrapper {
        position: relative;
        display: inline-block;
        width: 24px;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ff5722;
        /* warna oranye */
        color: #fff;
        font-size: 11px;
        font-weight: bold;
        border-radius: 50%;
        padding: 3px 6px;
        line-height: 1;
        min-width: 20px;
        text-align: center;
        box-shadow: 0 0 0 2px #fff;
        /* biar ada border putih di luar badge */
    }

    .nav-icon-wrapper {
        position: relative;
        display: inline-block;
    }
</style>

<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <!-- Brand logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}" style="margin-top: 10px;">
            <span style="font-size: 26px; font-weight: bold; color: white;">Boson-POS</span>
        </a>

        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column" id="sideNavbar">
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('dashboard') }}">
                    <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            @role('masteradmin')
            <hr class="sidebar-divider">
            <li class="nav-item mt-2">
                <div class="navbar-heading">User Management</div>
            </li>
            <li class="nav-item">
                <a style="font-size: 15px" class="nav-link has-arrow" href="#" data-bs-toggle="collapse"
                    data-bs-target="#navMasterData" id="toggleMasterData">
                    <i data-feather="database" class="nav-icon icon-xs me-2"></i> Master Data
                </a>
                <div id="navMasterData" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('role.index') }}" id="roleLink">
                                <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Role
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}" id="dataUserLink">
                                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Data User
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endrole

            <!-- product list -->
            <hr class="sidebar-divider">
            <li class="nav-item mt-2">
                <div class="navbar-heading">Product</div>
            </li>

            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('category.index') }}">
                    <i data-feather="tag" class="nav-icon icon-xs me-2"></i> Category
                </a>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('products.management') }}">
                    <i data-feather="archive" class="nav-icon icon-xs me-2"></i> Management Product
                </a>
            </li>

            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('products.index') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Catalog
                </a>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('historytransaction.index') }}">
                    <i data-feather="clock" class="nav-icon icon-xs me-2"></i> History Transaction
                </a>
            </li>

            <li class="nav-item position-relative">
                @php
                $cartCount = auth()->check()
                ? optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0
                : 0;
                @endphp

                <a href="{{ route('cart.index') }}" class="nav-link d-flex align-items-center">
                    <div class="nav-icon-wrapper">
                        <i class="bi bi-cart" style="font-size: 18px;"></i>
                        @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </div>
                    <span class="ms-2">Cart</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item mt-2">
                <div class="navbar-heading">Reports</div>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('sales.index') }}">
                    <i data-feather="bar-chart-2" class="nav-icon icon-xs me-2"></i> Sales
                </a>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item mt-2">
                <div class="navbar-heading">Gifts & Rewards</div>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('admintoko.index') }}">
                    <i data-feather="gift" class="nav-icon icon-xs me-2"></i> Redeem Rewards
                </a>
            </li>
        </ul>
    </div>
</nav>
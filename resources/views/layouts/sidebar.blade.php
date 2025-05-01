<style>
    .sidebar-divider {
        border: 0;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        /* warna garis */
        margin: 10px 0;
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
                <div class="navbar-heading">Data Management</div>
            </li>
            <li class="nav-item">
                <a style="font-size: 15px" class="nav-link has-arrow" href="#" data-bs-toggle="collapse"
                    data-bs-target="#navMasterData" id="toggleMasterData">
                    <i data-feather="database" class="nav-icon icon-xs me-2"></i> Master Data
                </a>
                <div id="navMasterData" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}" id="dataUserLink">
                                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Data User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="roleLink">
                                <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Role
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endrole

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
                <a style="font-size: 16px" class="nav-link" href="{{ route('catalog.index') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Catalog
                </a>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('historytransaction.index') }}">
                    <i data-feather="clock" class="nav-icon icon-xs me-2"></i> History Transaction
                </a>
            </li>
            <li class="nav-item">
                <a style="font-size: 16px" class="nav-link" href="{{ route('cashier.index') }}">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> Cashier
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
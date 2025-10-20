{{-- resources/views/layouts/partials/sidebar.blade.php --}}
@php
$cartCount = auth()->check()
? optional(optional(auth()->user()->cart)->items)->sum('quantity') ?? 0
: 0;

$isMasterActive = request()->routeIs(['role.*','users.*']);

$mgmtRoutes = [
'products.management',
'products.create',
'products.edit',
'products.store',
'products.update',
'products.destroy',
];
$isMgmtProductActive = request()->routeIs($mgmtRoutes) || request()->routeIs('products.management*');

$isCatalogActive = request()->routeIs(['products.index','products.show']) && !$isMgmtProductActive;
@endphp

<style>
  .sidebar-divider {
    border: 0;
    height: 1px;
    background-color: rgba(255, 255, 255, .08);
    margin: 10px 0
  }

  .nav-icon-wrapper {
    position: relative;
    display: inline-block;
    width: 24px
  }

  .cart-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff5722;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    border-radius: 50%;
    padding: 3px 6px;
    line-height: 1;
    min-width: 20px;
    text-align: center;
    box-shadow: 0 0 0 2px #fff
  }

  .navbar-vertical .nav-link {
    border-radius: 0 !important;
    position: relative;
    transition: background .15s, box-shadow .15s
  }

  .navbar-vertical .nav-link.active {
    background: rgba(255, 255, 255, .04);
    box-shadow: 0 1px 4px rgba(0, 0, 0, .12)
  }

  .navbar-vertical .nav-link:hover {
    background: rgba(255, 255, 255, .03)
  }
</style>

<nav class="navbar-vertical navbar">
  <div class="nav-scroller">
    <a class="navbar-brand" href="{{ route('dashboard') }}" style="margin-top:10px;">
      <span style="font-size:26px;font-weight:bold;color:white;">Boson-POS</span>
    </a>

    <ul class="navbar-nav flex-column" id="sideNavbar">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
          href="{{ route('dashboard') }}" style="font-size:16px">
          <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
        </a>
      </li>

      @role('masteradmin')
      <hr class="sidebar-divider">
      <li class="nav-item mt-2">
        <div class="navbar-heading">User Management</div>
      </li>

      <li class="nav-item">
        <a class="nav-link has-arrow {{ $isMasterActive ? 'active' : '' }}"
          href="#" data-bs-toggle="collapse" data-bs-target="#navMasterData"
          aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}" style="font-size:15px">
          <i data-feather="database" class="nav-icon icon-xs me-2"></i> Master Data
        </a>

        <div id="navMasterData" class="collapse {{ $isMasterActive ? 'show' : '' }}" data-bs-parent="#sideNavbar">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}"
                href="{{ route('role.index') }}">
                <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Role
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                href="{{ route('users.index') }}">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Data User
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
        <a class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
          href="{{ route('category.index') }}" style="font-size:16px">
          <i data-feather="tag" class="nav-icon icon-xs me-2"></i> Category
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ $isMgmtProductActive ? 'active' : '' }}"
          href="{{ route('products.management') }}" style="font-size:16px">
          <i data-feather="archive" class="nav-icon icon-xs me-2"></i> Management Product
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ $isCatalogActive ? 'active' : '' }}"
          href="{{ route('products.index') }}" style="font-size:16px">
          <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Catalog
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('historytransaction.*') ? 'active' : '' }}"
          href="{{ route('historytransaction.index') }}" style="font-size:16px">
          <i data-feather="clock" class="nav-icon icon-xs me-2"></i> History Transaction
        </a>
      </li>

      <li class="nav-item position-relative">
        <a class="nav-link d-flex align-items-center {{ request()->routeIs('cart.*') ? 'active' : '' }}"
          href="{{ route('cart.index') }}" style="font-size:16px">
          <div class="nav-icon-wrapper">
            <i class="bi bi-cart" style="font-size:18px;"></i>
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
        <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
          href="{{ route('sales.index') }}" style="font-size:16px">
          <i data-feather="bar-chart-2" class="nav-icon icon-xs me-2"></i> Sales
        </a>
      </li>

      <hr class="sidebar-divider">
      <li class="nav-item mt-2">
        <div class="navbar-heading">Gifts & Rewards</div>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admintoko.*') ? 'active' : '' }}"
          href="{{ route('admintoko.index') }}" style="font-size:16px">
          <i data-feather="gift" class="nav-icon icon-xs me-2"></i> Redeem Rewards
        </a>
      </li>
    </ul>
  </div>
</nav>
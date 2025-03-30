<!-- Sidebar -->
<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <!-- Brand logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <span style="font-size: 24px; font-weight: bold; color: white;">Boson-POS</span>
        </a>

        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column" id="sideNavbar">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            @role('masteradmin')
            <li class="nav-item">
                <div class="navbar-heading">Data Management</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#" data-bs-toggle="collapse"
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
            <li class="nav-item">
                <div class="navbar-heading">Gifts & Rewards</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow" href="{{ route('admintoko.index') }}">
                    <i data-feather="gift" class="nav-icon icon-xs me-2"></i> Redeem Rewards
                </a>
            </li>
        </ul>
    </div>
</nav>
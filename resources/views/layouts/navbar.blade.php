<div>
    <nav class="navbar-classic navbar navbar-expand-lg">
        <a id="nav-toggle" href="#"><i data-feather="menu" class="nav-icon me-2 icon-xs"></i></a>
        <!--Navbar nav -->
        <ul class="navbar-nav navbar-right-wrap ms-auto d-flex nav-top-wrap">
            <li class="dropdown stopevent">
                <a class="btn btn-light btn-icon rounded-circle indicator
                                indicator-primary text-muted"
                    href="#" role="button" id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="icon-xs" data-feather="bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="dropdownNotification">
                    <div>
                        <div
                            class="border-bottom px-3 pt-2 pb-3 d-flex
                                            justify-content-between align-items-center">
                            <p class="mb-0 text-dark fw-medium fs-4">Notifications</p>
                        </div>
                        <!-- List group -->
                        <ul class="list-group list-group-flush notification-list-scroll">
                            <!-- List group item -->
                            <li class="list-group-item">
                                <a href="#" class="text-muted">
                                    <h5 class=" mb-1">Sina Ray</h5>
                                    <p class="mb-0">
                                        Sed aliquam augue sit amet mauris volutpat hendrerit sed nunc eu
                                        diam.
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <div class="border-top px-3 py-2 text-center">
                            <a href="#" class="text-inherit fw-semi-bold">
                                View all Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <!-- List -->
            <li class="dropdown ms-2">
                <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md avatar-indicators avatar-online">
                        <img
                            alt="avatar"
                            src="{{ asset('assets/images/avatar/avatar-1.jpg') }}"
                            class="rounded-circle" />
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <div class="px-4 pb-0 pt-2">
                        <div class="lh-1">
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="dropdown-divider mt-3 mb-2"></div>
                    </div>

                    <ul class="list-unstyled">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i> Edit Profile
                            </a>
                        </li>

                        {{-- <li>
                         <a class="dropdown-item" href="#">
                             <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>Account Settings
                         </a>
                     </li> --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item bg-transparent border-0 w-100 text-start">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>
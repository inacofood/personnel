<aside class="left-sidebar">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/hcmapps.png') }}" alt="Logo" style="width:240px;">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <!-- Home Section -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <span><i class="ti ti-home"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                    {{-- <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a href="{{ route('dashboard') }}" class="sidebar-link" target="_blank">
                                <span><i class="ti ti-clock"></i></span>
                                <span class="hide-menu">Presensi</span>
                            </a>
                        </li>
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a class="sidebar-link" target="_blank">
                                <span><i class="ti ti-file-invoice"></i></span>
                                <span class="hide-menu">Monitoring Invoice</span>
                            </a>
                        </li>
                    </ul> --}}
                </li>

                <!-- Payroll Section -->
                @if ($roles_user->contains(3)||$roles_user->contains(4))
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Payroll</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('presensi.index') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-calendar"></i></span>
                        <span class="hide-menu">Presensi</span>
                    </a>
                </li>
                @endif

                <!-- L & D Section -->
                @if ($roles_user->contains(2)||$roles_user->contains(4))
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">L & D</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('emodule') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-book"></i></span>
                        <span class="hide-menu">Emodule</span>
                    </a>
                </li>
                @endif

                <!-- GA Section -->
                @if ($roles_user->contains(5)||$roles_user->contains(4))
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">GA</span>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('indexvisitor') }}" class="sidebar-link" target="_blank" target="_blank">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Visitor</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-car"></i></span>
                        <span class="hide-menu">Vehicle</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a href="{{ route('kendaraanasset') }}" class="sidebar-link" target="_blank" target="_blank">
                                <span><i class="ti ti-package"></i></span>
                                <span class="hide-menu">Kendaraan Asset</span>
                            </a>
                        </li>
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a href="{{ route('kendaraansewa') }}" class="sidebar-link" target="_blank" target="_blank">
                                <span><i class="ti ti-key"></i></span>
                                <span class="hide-menu">Kendaraan Sewa</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('indexworkorder') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-clipboard"></i></span>
                        <span class="hide-menu">Work Order</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('home') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-wallet"></i></span>
                        <span class="hide-menu">Petty Cash</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-archive"></i></span>
                        <span class="hide-menu">Management ATK</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('indextemuanga') }}" aria-expanded="false" target="_blank">
                        <span><i class="fas fa-hard-hat"></i></span>
                        <span class="hide-menu">Temuan GA</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-settings"></i></span>
                        <span class="hide-menu">Monitoring PR</span>
                    </a>
                </li>
                @endif

                <!-- Other Section -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Other</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('invoice') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-file"></i></span>
                        <span class="hide-menu">Monitoring Invoice</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-key"></i></span>
                        <span class="hide-menu">Reservation</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a href="{{ route('meetingroom') }}" class="sidebar-link" target="_blank" target="_blank">
                                <span><i class="fas fa-warehouse"></i></span>
                                <span class="hide-menu">Meeting Room</span>
                            </a>
                        </li>
                        <li class="sidebar-item" style="padding-left: 30px;">
                            <a href="{{ route('vehicle') }}" class="sidebar-link" target="_blank" target="_blank">
                                <span><i class="ti ti-car"></i></span>
                                <span class="hide-menu">Vehicle</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Permission Section -->
                @if ($roles_user->contains(4))
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Permission</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('users') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-user"></i></span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('role') }}" aria-expanded="false" target="_blank">
                        <span><i class="ti ti-lock"></i></span>
                        <span class="hide-menu">Role</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('usersrole') }}" aria-expanded="false" target="_blank">
                        <span><i class="fas fa-users"></i></span>
                        <span class="hide-menu">User Role</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

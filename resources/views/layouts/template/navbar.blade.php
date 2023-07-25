<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="text-end">
                <span class="fw-semibold d-block">{{ Auth::user()->full_name }}</span>
                <small class="text-muted">{{ roleDetail()->unit }}</small>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../../assets/img/avatars/user-blank.png" alt class="h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="../../assets/img/avatars/user-blank.png" alt class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->full_name }}</span>
                                    <small class="text-muted">{{ roleDetail()->unit }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('setting-profile') }}">
                        <i class="ti ti-settings me-2 ti-md"></i>
                        <span class="align-middle">Pengaturan Profil</span>
                      </a>
                    </li>
                    <li class="d-none">
                        <a class="dropdown-item">
                            <i class="ficon me-2" data-feather="moon"></i>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item style-switcher-toggle hide-arrow">
                            <i class="ti ti-md me-2"></i>
                            <span class="align-middle"> Ubah Mode</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="mx-4 ms-3">
                <span class="border-start"></span> 
                &nbsp;
            </li>
            <li>
                <form action="{{ route('custom-logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">
                        <span class="align-middle">LOG OUT</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box d-flex align-items-center justify-content-center">
                <a href="{{ route('dashboard.index') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
                    </span>
                </a>

                <a href="{{ route('dashboard.index') }}" class="logo logo-light">
                    <span class="logo-sm" style="padding:0px 8px !important;">
                        <img src="{{ URL::asset('/images/CHR.svg') }}" alt="" height="22" width="55px">
                    </span>
                    <span class="logo-lg" style='padding:0px 8px !important;'>
                        <img src="{{ URL::asset('/images/CHR.svg') }}" alt="">
                    </span>
                </a>
            </div>

            <button class="navbar-toggler" id="vertical-menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
             @auth
            <form class="app-search d-lg-block p-0 pt-3 mb-0">
                <div class="position-relative search-input-design">
                    <div class="form-control" id="global-search">
                        <!-- Option to load data asynchronously -->
                    </div>
                </div>
            </form>
            @endauth
        </div>

        <div class="d-flex">
            @auth
            <!-- Display options for authenticated users -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus plusicon"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="javascript:void(0);"
                    @auth
                    @if (Auth::check() && optional(Auth::user())->contactData)
                        onclick="createTransaction({{ json_encode(Auth::user()->contactData) }});"
                    @endif
                    @endauth>
                        <i class="fas fa-plus plusicon"></i> <span>New Transaction</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">{{ ucfirst(Auth::user()->name) }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bx bx-user font-size-16 align-middle me-1"></i> <span>@lang('Profile')</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                        <span>@lang('Logout')</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            @endauth

            @guest
            <!-- Display login and register options for guests -->
            <div class="d-inline-block">
                <a href="{{ route('login') }}" class="btn header-item waves-effect">Login</a>
            </div>
            <div class="d-inline-block">
                <a href="{{ route('register') }}" class="btn header-item waves-effect">Register</a>
            </div>
            @endguest
        </div>
    </div>
</header>

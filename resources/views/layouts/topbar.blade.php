
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
                        {{-- <img src="{{ URL::asset('build/images/logo-light.svg') }}" alt="" height="22"> --}}
                        <img src="{{ URL::asset('/images/CHR.svg') }}" alt="" height="22" width="55px">

                    </span>
                    <span class="logo-lg" style='padding:0px 8px !important;'>
                        {{-- <img src="{{ URL::asset('build/images/logo-light.png') }}" alt=""> --}}
                        <img src="{{ URL::asset('/images/CHR.svg') }}" alt="">

                    </span>
                </a>
            </div>

            <button class="navbar-toggler" id="vertical-menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-fw fa-bars"></i>
            </button>

                <!-- App Search-->
          <form class="app-search d-lg-block p-0 pt-3 mb-0">
            <div class="position-relative search-input-design">
                <div class="form-control " id="global-search">
                    <!-- Option to load data asynchronously -->
                </div>
            </div>
        </form>
           
        </div>
      

        <div class="d-flex ">
                <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus plusicon"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="javascript:void();"
                        onclick="createTransaction({{Auth::user()->contactData}});"><i class="fas fa-plus plusicon"></i> <span
                            key="t-logout">New Transaction</span></a>
                </div>
            </div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1"
                        key="t-henry">{{ ucfirst(Auth::user()->name) }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                        class="bx bx-user font-size-16 align-middle me-1"></i> <span
                        key="t-profile">@lang('Profile')</span></a>
                    {{-- <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i>
                        <span key="t-my-wallet">@lang('My_Wallet')</span></a> --}}
                   {{-- <a class="dropdown-item d-block" href="{{ route('user.profile') }}" data-bs-toggle="modal"
                        data-bs-target=".change-password"><span class="badge bg-success float-end">11</span><i
                            class="bx bx-wrench font-size-16 align-middle me-1"></i> <span
                            key="t-settings">@lang('Profile Settings')</span></a> --}}
                    {{-- <a class="dropdown-item" href="#"><i
                            class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span
                            key="t-lock-screen">@lang('Lock_screen')</span></a> --}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                            key="t-logout">@lang('Logout')</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!--  Change-Password example -->
<div class="modal fade change-password" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="change-password">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="data_id">
                    <div class="mb-3">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input id="current-password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            name="current_password" autocomplete="current_password"
                            placeholder="Enter Current Password" value="{{ old('current_password') }}">
                        <div class="text-danger" id="current_passwordError" data-ajax-feedback="current_password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="newpassword">New Password <span class="text-danger">*</span></label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            autocomplete="new_password" placeholder="Enter New Password">
                        <div class="text-danger" id="passwordError" data-ajax-feedback="password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="userpassword">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" autocomplete="new_password"
                            placeholder="Enter New Confirm password">
                        <div class="text-danger" id="password_confirmError" data-ajax-feedback="password-confirm">
                        </div>
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light UpdatePassword"
                            data-id="{{ Auth::user()->id }}" type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



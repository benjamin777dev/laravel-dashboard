@extends('layouts.master-without-nav')

@section('title')
    @lang('Register')
@endsection

@section('css')
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link href="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('body')

    <body class="auth-body-bg">
    @endsection

    @section('content')

        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">

                    <div class="col-xl-9">
                        <div class="auth-full-bg pt-lg-5 p-4">
                            <div class="w-100">
                                <div class="bg-overlay"></div>
                                <div class="d-flex h-100 flex-column">

                                    <div class="p-4 mt-auto">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-7">
                                                <div class="text-center">
                                                    <h4 class="mb-3"><i class="bx bxs-quote-alt-left text-primary h1 align-middle me-3"></i><span class="text-primary">Zoho</span> Agent Frontend</h4>
                                                    <div dir="ltr">
                                                        <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                            <div class="item">
                                                                <div class="py-3">
                                                                    <p class="font-size-16 mb-4">
                                                                        "Easy to use, simple frontend, which is task and note focused to allow fast access to your pipeline."
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3">
                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5">
                                        <a href="index" class="d-block auth-logo">
                                            <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="18"
                                                class="auth-logo-dark">
                                            <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="18"
                                                class="auth-logo-light">
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        <div>
                                            <h5 class="text-primary">Register account</h5>
                                            <!-- <p class="text-muted">Get your free zPortal account now.</p> -->
                                        </div>

                                        <div class="mt-4">
                                            <!-- if $userData has value then show form otherwise show SignUp with Zoho button -->
                                            @if($userData)
                                                <form method="POST" class="form-horizontal" action="{{ route('register') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="useremail"
                                                        value="{{ $userData['email'] }}" name="email" placeholder="Enter email" autofocus required readonly>
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ $userData['first_name'] . ' ' . $userData['last_name'] }}" id="name" name="name" autofocus required
                                                            placeholder="Enter name">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="userpassword" class="form-label">Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" name="password"
                                                            placeholder="Enter password" autofocus required>
                                                            @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="confirmpassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="confirmpassword"
                                                        name="password_confirmation" placeholder="Enter Confirm password" autofocus required>
                                                        @error('password_confirmation')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <!-- <div class="mb-3">
                                                        <label for="userdob">Date of Birth <span class="text-danger">*</span></label>
                                                        <div class="input-group" id="datepicker1">
                                                            <input type="text" class="form-control @error('dob') is-invalid @enderror" placeholder="dd-mm-yyyy"
                                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-date-end-date="0d" value="{{ old('dob') }}"
                                                                data-provide="datepicker" name="dob" autofocus required>
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            @error('dob')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div> -->

                                                    <!-- <div class="mb-3">
                                                        <label for="avatar">Profile Picture <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="inputGroupFile02" name="avatar" autofocus required>
                                                            <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                                        </div>
                                                        @error('avatar')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div> -->

                                                    <div class="mt-4 d-grid">
                                                        <button class="btn btn-primary waves-effect waves-light"
                                                            type="submit">Register</button>
                                                    </div>

                                                    <div class="mt-4 text-center">
                                                        <p class="mb-0">By registering you agree to the zPortal <a href="#"
                                                                class="text-primary">Terms of Use</a></p>
                                                    </div>
                                                </form>
                                            @else
                                                <a href="{{ route('auth.redirect') }}" class="btn btn-primary waves-effect waves-light">Sign Up with Zoho</a>
                                            @endif

                                            <div class="mt-3 text-center">
                                                <p>Already have an account ? <a href="{{ url('login') }}"
                                                        class="fw-medium text-primary"> Login</a> </p>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="mt-4 mt-md-3 text-center">
                                        <p class="mb-0">© <script>
                                                document.write(new Date().getFullYear())

                                            </script> Colorado Home Realty</p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('build/js/pages/auth-2-carousel.init.js') }}"></script>
    @endsection

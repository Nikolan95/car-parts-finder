<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <title>Metrica - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body id="body" class="auth-page" style="background-image: url('assets/images/p-1.png'); background-size: cover; background-position: center center;">
    <!-- Log In page -->
        <div class="container-md">
            <div class="row vh-100 d-flex justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 mx-auto">
                                <div class="card">
                                    <div class="card-body p-0 auth-header-box">
                                        <div class="text-center p-3">
                                            <a href="" class="logo logo-admin">
                                                <img src="{{ asset('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                            </a>
                                            <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Let's Get Started Metrica</h4>
                                            <p class="text-muted  mb-0">Sign up to continue to Metrica.</p>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <form class="my-4" action="{{ route('user.register') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="username">Vorname</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Geben Sie den Vorname ein" value="{{ old('firstname') }}">
                                                </div>
                                                @error('firstname')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="username">Zuname</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Geben Sie den Zuname ein" value="{{ old('lastname') }}">
                                                </div>
                                                @error('lastname')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="username">Nutzername</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="Geben Sie den Nutzername ein" value="{{ old('username') }}">
                                                </div>
                                                @error('username')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="username">Email</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="email" id="email" placeholder="Geben Sie den Email ein" value="{{ old('email') }}">
                                                </div>
                                                @error('email')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="username">Adresse</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="address" id="address" placeholder="Geben Sie den Adresse ein" value="{{ old('address') }}">
                                                </div>
                                                @error('address')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="username">Stadt</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon">
                                                        <i class="dripicons-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="city" id="city" placeholder="Geben Sie den Stadt ein" value="{{ old('city') }}">
                                                </div>
                                                @error('city')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="userpassword">Passwort</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon"><i class="dripicons-lock"></i></span>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Passwort eingeben">
                                                </div>
                                                @error('password')
                                                    <div class="color" >
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div><!--end form-group-->

                                            <div class="form-group">
                                                <label for="conf_password">Bestätige das Passwort</label>
                                                <div class="input-group mb-3">
                                                    <span class="auth-form-icon"><i class="dripicons-lock"></i></span>
                                                    <input type="password" class="form-control" name="password_confirmation" id="conf_password" placeholder="Bestätige das Passwort">
                                                </div>
                                                @error('password_confirmation')
                                                    <div class="color">
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                @enderror()
                                            </div>

                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                    <div class="d-grid mt-3">
                                                        <button class="btn btn-primary" type="submit">Register <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                    </div>
                                                </div><!--end col-->
                                            </div> <!--end form-group-->
                                        </form><!--end form-->
                                        <div class="m-3 text-center text-muted">
                                            <p class="mb-0">Already have an account ? <a href="{{ route('login') }}" class="text-primary ms-2">Log in</a></p>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-body-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- vendor js -->

        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
    </body>
</html>

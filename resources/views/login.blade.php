<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login - Matriks Kegiatan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ URL::asset('assets/img/logo-bps.png') }}" rel="icon">
    <link href="{{ URL::asset('assets/img/logo-bps.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="{{ URL::asset('https://fonts.gstatic.com') }}" rel="preconnect">
    <link
        href="{{ URL::asset('https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i') }}"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ URL::asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->


    <style>
        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: .75rem 1.25rem;
            color: inherit;
        }

        button.close {
            padding: 0;
            background-color: transparent;
            border: 0;
            -webkit-appearance: none;
        }

        .close:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a class="logo d-flex align-items-center w-auto">
                                    <img src="assets/img/logo-bps.png" alt="">
                                    <span class="d-none d-lg-block">Matriks Kegiatan BPSDS</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body mb-3">


                                    @if (session('login_error'))
                                        <div class="alert alert-danger alert-dismissible mt-3 fade show" role="alert">
                                            <span> <h6>{{ session('login_error') }}</h6></span>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    {{-- <form action="{{ url('/postlogin') }}" method="POST"></form> --}}
                                    <div class="pt-0 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                        <p class="text-center small">Masukkan NIP baru BPS dan password Anda</p>
                                    </div>

                                    <form class="row g-3 needs-validation" action="{{ url('/postlogin') }}"
                                        method="POST" novalidate>
                                        @csrf
                                        <div class="col-12">
                                            <label for="nip" class="form-label">NIP</label>
                                            <div class="input-group has-validation">
                                                {{-- <span class="input-group-text" id="inputGroupPrepend">340</span> --}}
                                                <input type="text" name="nip"
                                                    class="form-control @error('nip') is-invalid @enderror"
                                                    id="nip" placeholder="340______" autofocus
                                                    value="{{ old('nip') }}">
                                                {{-- <div class="invalid-feedback">Please enter your NIP (9 digits).</div> --}}
                                                @error('nip')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password">
                                            {{-- <div class="invalid-feedback">Please enter your password!</div> --}}
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="credits">
                                <!-- All the links in the footer should remain intact. -->
                                <!-- You can delete the links only if you purchased the pro version. -->
                                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ URL::asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ URL::asset('assets/js/main.js') }}"></script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - Matriks Kegiatan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ URL::asset('assets/img/logo-bps.png') }}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ URL::asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet') }}">
    <link href="{{ URL::asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">


    <!-- Template Main CSS File -->
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        table.dataTable td {
            font-size: 0.9em;
        }

        .select-selection--multiple {
            overflow: hidden !important;
            height: auto !important;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        input[type=number]::-webkit-inner-spin-button {
            opacity: 1;
        }

        .form-group.required .control-label:before {
            content: "*  ";
            color: red;
        }

        table.display tbody tr:hover td {
            background-color: #f6f6fe !important;
        }

        div.dt-layout-row.dt-layout-table>div>div>div.dt-scroll-head>div>table>thead>tr>th {
            background-color: #012970;
            color: #f6f6fe;
            font-family: "Poppins", "sans-serif" !important;
            font-weight: 100;
        }

        table.dataTable thead tr th {
            background-color: #012970;
            color: #f6f6fe;
            font-family: "Poppins", "sans-serif" !important;
            font-weight: 100;
        }
    </style>


    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<?php
function getRole($admin){
    switch ($admin) {
        case 0:
            return 'Anggota';
        case 1:
            return 'Admin';
        case 2:
            return 'Ketua Tim';
        case 3:
            return 'Viewer';
        default:
        return;
    }
}
?>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center ">
            <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                <img src="{{ URL::asset('assets/img/logo-bps.png') }}" alt="">
                <span>Matriks Kegiatan</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ URL::asset('assets/img/profile-image.png') }}" alt="Profile"
                            class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->nama }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->nama }}</h6>
                            <span>{{ getRole(auth()->user()->admin) }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            @auth
                                <form action="{{url('logout')}}" method="post">
                                    @csrf
                                    <button class="dropdown-item d-flex align-items-center" name="logout">
                                        <i class="bi bi-box-arrow-right" style="color:red;"></i>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            @endauth

                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" href="{{ url('/') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" data-bs-target="#kegiatan-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi bi-menu-button-wide"></i><span>Kegiatan</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="kegiatan-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ url('view-kegiatan') }}" id="kegiatan">
                            <i class="bi bi-circle"></i><span>Kelola Kegiatan</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ url('view-alokasikegiatan') }}" id="alokasikegiatan">
                            <i class="bi bi-circle"></i><span>Alokasi Kegiatan</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ url('view-pekerjaan') }}" id="pekerjaan">
                            <i class="bi bi-circle"></i><span>Uraian Pekerjaan</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" data-bs-target="#matriks-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Matriks</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="matriks-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ url('matriks-kegiatan') }}" id="matriks-kegiatan">
                            <i class="bi bi-circle"></i><span>Matriks Kegiatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('matriks-pekerjaan') }}" id="matriks-pekerjaan">
                            <i class="bi bi-circle"></i><span>Matriks Pekerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('matriks-biaya') }}" id="matriks-biaya">
                            <i class="bi bi-circle"></i><span>Matriks Biaya</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            {{-- 
            <li class="nav-heading">Pages</li> --}}

            {{-- Master --}}
            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="ri-database-2-line"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ url('view-pegawai') }}" id="master-pegawai">
                            <i class="bi bi-circle"></i><span>Master Pegawai</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('view-mitra') }}" id="master-mitra">
                            <i class="bi bi-circle"></i><span>Master Mitra</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            {{-- 
            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" href="{{ url('view-pegawai') }}">
                    <i class="bi bi-person"></i>
                    <span>Pegawai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" href="{{ url('view-mitra') }}">
                    <i class="bi bi-person"></i>
                    <span>Mitra</span>
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" href="{{ url('view-registeredmitra') }}">
                    <i class="bi bi-person"></i>
                    <span>Mitra Statistik Tahunan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="tab-menu nav-link collapsed" href="{{ url('download-report') }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Report Bulanan</span>
                </a>
            </li>
            <!-- End Profile Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        @stack('before-container')
        @yield('container')
        @stack('after-container')
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; BPS DS <strong><span>2024</span></strong>
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ URL::asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!--datatable jQuery-->
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> --}}

    <script src="{{ URL::asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <!--Select2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">

    {{-- <!--Bootstrap-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script> --}}


    <!-- BOOTSTRAP SELECT STARTS HERE -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <!-- bootstrap-select: Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- BOOTSTRAP SELECT FINISH HERE -->

    <!-- Template Main JS File -->
    <script src="{{ URL::asset('assets/js/main.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/jquery-cookie-master/src/jquery.cookie.js') }}"></script>
</body>
@stack('before-script')
@yield('includes.script')
@stack('after-script')

</html>

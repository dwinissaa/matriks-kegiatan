@extends('layout.default')
@section('container')
    <!--jQuery first-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!--datatable jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- BOOTSTRAP SELECT STARTS HERE -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <!-- bootstrap-select: Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- BOOTSTRAP SELECT FINISH HERE -->

    <style>
        table.dataTable td {
            font-size: 0.9em;
        }

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
    </style>

    <!-- Halaman -->
    <script>
        const page = 3;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
            document.querySelector('#master-nav').classList.add('show');
            document.querySelector('#master-mitra').classList.add('active');
        });
    </script>
    <!-- End Halaman -->

    @if (Session::has('status_create_mitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_mitra') }}",
                });
            })
        </script>
    @endif
    @if (Session::has('status_update_mitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_update_mitra') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_destroy_mitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_mitra') }}",
                });
            })
        </script>
    @endif

    <div class="pagetitle">
        <h1>Master mitra</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Mitra</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mitra</h5>
                        @can('for-admin')
                            <div class="row mb-3">
                                <div class="col-sm-20">
                                    <div class="d-flex">
                                        <a href="{{ url('/create-mitra') }}" data-toggle="tooltip" data-placement="top"
                                            title="Tambah" style="color: aliceblue">
                                            <button type="button" class="btn btn-primary pull-left">
                                                <i class="bi bi-person-plus"></i>&nbsp;&nbsp;<span>Tambah Mitra</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="row mb-3">
                            <div class="col-12 col-md-3">
                                <label><b>Kecamatan</b></label>
                                <select id="filter_kecamatan"
                                    name="filter_kecamatan"class="filter @error('filter_kecamatan') is-invalid @enderror">
                                    <option value="" selected>Kecamatan</option>
                                    @foreach ($kecamatan as $kec)
                                        <option value="{{ $kec->id_kec }}">
                                            {{ $kec->kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <table id="mitra-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th>Tahun Pendaftaran</th> --}}
                                    <th>SOBAT ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Asal Kecamatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('[data-toggle=tooltip]').tooltip();
            let kecamatan_filter = $('#filter_kecamatan').val();
            // DataTable
            var table = $('#mitra-table').DataTable({
                'scrollX': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'view-mitra',
                    data: function(d) {
                        d.kecamatan_filter = kecamatan_filter;
                        return d;
                    }
                },
                columns: [{
                        data: 'sobatid',
                        name: 'sobatid'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ],
                lengthMenu: [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                pageLength: 25,
            });
            $('.filter').on('change', function() {
                kecamatan_filter = $('#filter_kecamatan').val();
                table.ajax.reload(null, false);
            });


            $('select[name="filter_kecamatan"]').select2({
                allowClear: true,
                placeholder: "Filter Kecamatan",
                width: '100%',
            });
        });
    </script>
@endsection

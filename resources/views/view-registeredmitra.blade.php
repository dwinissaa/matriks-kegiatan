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
    <!--Select2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">
    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"> --}}

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
    </style>

    <!-- Halaman -->
    <script>
        const page = 4;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
        });
    </script>

    <!-- End Halaman -->

    <?php
    function role($admin)
    {
        if ($admin == 1) {
            return 'Administrator';
        } else {
            return 'User';
        }
    }
    ?>
    @if (Session::has('status_create_registeredmitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_registeredmitra') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_gagalcreate_registeredmitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "{{ Session::get('status_gagalcreate_registeredmitra') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_warningcreate_registeredmitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "info",
                    title: "Warning",
                    text: "{{ Session::get('status_warningcreate_registeredmitra') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_destroy_registeredmitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_registeredmitra') }}",
                });
            })
        </script>
    @endif

    <div class="pagetitle">
        <h1>Mitra Statistik Tahunan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Mitra Terdaftar</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mitra Terdaftar</h5>
                        <div class="row mb-3">
                                <div class="col-12">
                                    @can('for-admin')
                                    <div class="d-flex">
                                        <a data-toggle="tooltip" title="Tambah" style="color: aliceblue">
                                            <button type="button" class="btn btn-primary pull-left" data-toggle="modal"
                                                data-target="#exampleModal" id="addRegisteredMitra">
                                                <i class="bi bi-person-plus"></i>&nbsp;&nbsp;<span>Tambah Mitra</span>
                                            </button>
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                            {{-- <script>
                                // Do this before you initialize any of your modals
                                $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                            </script> --}}
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Pendaftaran</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3 needs-validation" novalidate id="formAddRegisteredMitra"
                                                action="{{ url('/store-registeredmitra') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label for="selected_tahun" class="form-label">Tahun</label>
                                                    <?php
                                                    $tahun_arr = [];
                                                    for ($i = 2024; $i <= (int) date('Y'); $i++) {
                                                        # code...
                                                        $tahun_arr[] = $i;
                                                    }
                                                    ?>
                                                    <select class="form-select" name="selected_tahun" required>
                                                        <option selected disabled value="">Pilih Tahun...</option>
                                                        @foreach ($tahun_arr as $item)
                                                            <option value="{{ $item }}"
                                                                @if ($item == (int) date('Y')) selected @endif>
                                                                {{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a valid Tahun.
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="selected_mitra[]" class="form-label">Pilih Mitra</label>
                                                    <select data-width="100%" name="selected_mitra[]" style="display:none"
                                                        multiple="multiple" required>
                                                        {{-- @foreach ($mitra_dropdown as $md)
                                                            <option value="{{ $md->sobatid }}">
                                                                {{ $md->nama . ' (' . $md->email . ') ' }}
                                                            </option>
                                                        @endforeach --}}
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a valid Mitra.
                                                    </div>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </form><!-- End Custom Styled Validation -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                            <button class="btn btn-primary" type="submit" form="formAddRegisteredMitra"
                                                value="Submit">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-3">
                                <label><b>Filter Tahun</b></label>
                                <select name="filter_tahun" class="filter">
                                    <option value=""></option>
                                    @foreach ($tahun as $t)
                                        <option value={{ $t->tahun }}
                                            {{ (int) date('Y') == $t->tahun ? 'selected' : '' }}>
                                            {{ $t->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label><b>Filter Kecamatan</b></label>
                                <select name="filter_kecamatan" class="filter">
                                </select>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <table id="registeredmitra-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th>Tahun Pendaftaran</th> --}}
                                    <th>Tahun Pendaftaran</th>
                                    <th>SOBAT ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Asal Kecamatan</th>
                                    <th>Ditambahkan pada</th>
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
    </section>

    <script>
        $(document).ready(function() {
            // Tooltip
            $('[data-toggle=tooltip]').tooltip();

            // ELEMENT
            let selected_mitra = $('select[name="selected_mitra"]').val()
            let tahun = $('select[name="filter_tahun"]')
            let kecamatan = $('select[name="filter_kecamatan"]')
            let t = tahun.val()

            // FILTER TAHUN INIT
            $.ajax({
                url: 'filterRegistered/tahun-' + t,
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    if (tahun.val()) {
                        t = tahun.val()
                    } else {
                        t = '00';
                    }
                    $('select[name="filter_kecamatan"]').empty();
                    $('select[name="filter_kecamatan"]').append(
                        '<option value=""></option>')
                    $.each(data, function(key, kec) {
                        $('select[name="filter_kecamatan"]').append(
                            '<option class="fil-kec" value="' + kec.id_kec +
                            '">' + '[' + kec.id_kec + '] ' + kec.kecamatan +
                            '</option>'
                        )
                    })
                }
            })


            // FILTER TAHUN
            $('select[name="filter_tahun"]').on('change', function() {
                if (tahun.val()) {
                    t = tahun.val()
                } else {
                    t = '00';
                }
                $.ajax({
                    url: 'filterRegistered/tahun-' + t,
                    type: "GET",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('select[name="filter_kecamatan"]').empty();
                        $('select[name="filter_kecamatan"]').val('').trigger('change');
                        $('select[name="filter_kecamatan"]').append(
                            '<option value=""></option>')
                        $.each(data, function(key, kec) {
                            $('select[name="filter_kecamatan"]').append(
                                '<option value="' + kec.id_kec +
                                '">' + '[' + kec.id_kec + '] ' + kec.kecamatan +
                                '</option>'
                            )
                        })
                    }
                })
            })



            // DataTable catch filter value
            var table = $('#registeredmitra-table').DataTable({
                'columnDefs': [{
                    "targets": 0, // your case first column
                    "className": "text-center",
                    "width": "4%"
                }, ],
                'scrollX': true,
                'responsive': true,
                'autowidth': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'view-registeredmitra',
                    data: function(d) {
                        d.tahun_filter = $('select[name="filter_tahun"]').val();
                        d.kecamatan_filter = $('select[name="filter_kecamatan"]').val();
                        d.selected_mitra = selected_mitra;
                        return d;
                    }
                },
                'columns': [{

                        data: 'tahun',
                        name: 'tahun'
                    }, {

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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },


                ],
                'order': [
                    [5, 'desc']
                ],
                'lengthMenu': [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                'pageLength': 25,


            });

            // Filter
            $('.filter').on('change', function() {
                tahun_filter = $('select[name="filter_tahun"]').val();
                kecamatan_filter = $('select[name="filter_kecamatan"]').val();
                table.ajax.reload(null, false);
            });

            // Select2
            $('select[name="selected_mitra[]"]').select2({
                allowClear: true,
                minimumResultsForSearch: -1,
                width: 600,
                placeholder: "Pilih Mitra",
                dropdownParent: $('#exampleModal')
            });

            $('select[name="filter_tahun"]').select2({
                allowClear: true,
                placeholder: "Filter Tahun",
                width: '100%'
            });

            $('select[name="filter_kecamatan"]').select2({
                allowClear: true,
                placeholder: "Filter Kecamatan",
                width: '100%'
            });


            // FILTER TAHUN INIT
            $.ajax({
                url: "registeredmitra-list/tahun=" + $('select[name="selected_tahun"]').val(),
                type: "GET",
                // data: {
                //     _token: "{{ csrf_token() }}",
                // },
                dataType: "json",
                success: function(data) {
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').append('<option value=""></option>');
                    $.each(data, function(key, mitra) {
                        $('select[name="selected_mitra[]"]').append(
                            "<option value='" +
                            mitra.sobatid +
                            "'>" +
                            mitra.nama + " (" + mitra.email + ")" +
                            "</option>"
                        );
                    });
                }
            })

        });
    </script>
@endsection

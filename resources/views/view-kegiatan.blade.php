@extends('layout.default')
@section('container')
    <!--jQuery first-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!--datatable jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>


    <script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/dataTables.fixedColumns.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/fixedColumns.dataTables.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.0/css/fixedColumns.dataTables.css">

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
    </style>

    <!-- Halaman -->
    <script>
        const page = 1;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
            document.querySelector('#kegiatan-nav').classList.add('show');
            document.querySelector('#kegiatan').classList.add('active');
        });

        let bulan_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
            'November', 'Desember'
        ];
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

    @if (Session::has('status_destroy_kegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_kegiatan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif

    @if (Session::has('status_create_kegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_kegiatan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif

    @if (Session::has('status_update_kegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_update_kegiatan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif

    <div class="pagetitle">
        <h1>Kelola Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Kelola Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Kegiatan</h5>


                        <div>
                            <!-- General Form Elements -->
                            <form action="" method="GET" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                    <div class="col-sm-8">
                                        <select name="tahun" class="filter @error('tahun') is-invalid @enderror">
                                            <option value="" disable selected>Choose Tahun</option>
                                            @foreach ($tahun as $item)
                                                <option value={{ $item->tahun }}
                                                    @if ($item->tahun == (int) date('Y')) selected @endif>
                                                    {{ $item->tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label id="bulan" class="col-sm-2 col-form-label">Bulan</label>
                                    <div class="col-sm-8">
                                        <select name="bulan" class="filter @error('bulan') is-invalid @enderror">
                                            <option value="" disable selected>Choose Bulan</option>
                                        </select>
                                        @error('bulan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label id="tim" class="col-sm-2 col-form-label">Tim</label>
                                    <div class="col-sm-8">
                                        <select name="tim" class="filter @error('tim') is-invalid @enderror">
                                            <option value="" disable selected>Choose Tim</option>
                                        </select>
                                        @error('tim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->
                        </div>


                        @can('for-admin-ketim')
                            <div class="row mb-3" style="float:left">
                                <div class="col-sm-20">
                                    <a href="{{ url('/create-kegiatan') }}" class="btn btn-primary" data-toggle="tooltip"
                                        data-placement="top" title="Tambah">
                                        <i class="bi bi-menu-button-wide"></i>&nbsp;&nbsp;<span>Tambah Kegiatan</span>
                                    </a>
                                </div>
                            </div>
                        @endcan
                        <!-- Table with stripped rows -->
                        <table id="kegiatan-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Kegiatan</th>
                                    <th>Subject Meter</th>
                                    <th>Ketua Tim</th>
                                    <th>Anggota</th>
                                    <th>Pekerjaan</th>
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
            $('select[name="tahun"]').select2({
                allowClear: true,
                placeholder: "Pilih Tahun",
                width: '100%'
            });
            $('select[name="bulan"]').select2({
                allowClear: true,
                placeholder: "Pilih Bulan",
                width: '100%'
            });
            $('select[name="tim"]').select2({
                allowClear: true,
                placeholder: "Pilih Tim",
                width: '100%'
            });

            // ELEMENT
            let tahun = $('select[name="tahun"]')
            let bulan = $('select[name="bulan"]')
            let tim = $('select[name="tim"]')
            tahun.val(2024).trigger('change')
            bulan.val('00').trigger('change')
            tim.val('00').trigger('change')
            let t = tahun.val()
            let b = bulan.val()
            let ti = tim.val()
            let r = '00'

            // FILTER TAHUN INIT
            $.ajax({
                url: 'filterPekerjaan/role=' + r + '/tahun=' + t,
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
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').append(
                        '<option value=""></option>')
                    $.each(data, function(key, bulan, $bulan_arr) {
                        $('select[name="bulan"]').append(
                            '<option value=' + bulan.bulan +
                            '>' +
                            bulan_arr[bulan.bulan - 1] +
                            '</option>'
                        )
                    })
                }
            })


            // FILTER TAHUN
            $('select[name="tahun"]').on('change', function() {
                if (tahun.val()) {
                    t = tahun.val()
                    $.ajax({
                        url: 'filterPekerjaan/role=' + r + '/tahun=' + t,
                        type: "GET",
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('select[name="bulan"]').empty();
                            $('select[name="bulan"]').append(
                                '<option value=""></option>')
                            $.each(data, function(key, bulan, $bulan_arr) {
                                $('select[name="bulan"]').append(
                                    '<option value=' + bulan.bulan +
                                    '>' +
                                    bulan_arr[bulan.bulan - 1] +
                                    '</option>'
                                )
                            });
                        }
                    })
                } else {
                    t = '00';
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').val('').trigger('change');
                }
            })

            // FILTER BULAN
            $('select[name="bulan"]').on('change', function() {
                if (bulan.val()) {
                    b = bulan.val()
                    $.ajax({
                        url: 'filterPekerjaan/role=' + r + '/tahun=' + t + '/bulan=' + b,
                        type: "GET",
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('select[name="tim"]').empty();
                            $('select[name="tim"]').append(
                                '<option value=""></option>')
                            $.each(data, function(key, tim) {
                                $('select[name="tim"]').append(
                                    '<option value=' + tim.subject_meter +
                                    '>' + tim.tim +
                                    '</option>'
                                )
                            })
                        }
                    })
                } else {
                    b = '00';
                    $('select[name="tim"]').empty();
                    $('select[name="tim"]').val('').trigger('change');
                }
            })

            var table = $('#kegiatan-table').DataTable({
                "order": [],
                'scrollX': "100%",
                'responsive': true,
                'autowidth': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'view-kegiatan',
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun"]').val();
                        d.bulan_filter = $('select[name="bulan"]').val();
                        d.tim_filter = $('select[name="tim"]').val();
                        return d;
                    }
                },
                'columnDefs': [{
                    "targets": 0, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, {
                    "targets": 2, // your case first column
                    "width": "20%"
                }, {
                    "targets": 4, // your case first column
                    "className": "text-center",
                    "width": "4%"
                }, {
                    "targets": 5, // your case first column
                    "className": "text-center",
                    "width": "4%"
                }, ],
                'columns': [{

                        data: 'periode',
                        name: 'periode',
                    }, {

                        data: 'kegiatan',
                        name: 'kegiatan',
                    },
                    {
                        data: 'tim',
                        name: 'tim',
                    },
                    {
                        data: 'ketua_tim',
                        name: 'ketua_tim'
                    },
                    {
                        data: 'jlh_anggota',
                        name: 'jlh_anggota'
                    },
                    {
                        data: 'jlh_pekerjaan',
                        name: 'jlh_pekerjaan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        width: "6em",
                    },
                ],


                lengthMenu: [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                pageLength: 25,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                scrollCollapse: true,
                scrollX: true,
                scrollY: 500,



            });

            // Filter
            $('.filter').on('change', function() {
                table.ajax.reload(null, false);
            });

            $('[data-toggle=tooltip]').tooltip();

        });
    </script>
@endsection

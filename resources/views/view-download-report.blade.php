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
        const page = 5;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
            // document.querySelector('#kegiatan-nav').classList.add('show');
            // document.querySelector('#kegiatan').classList.add('active');
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

    <div class="pagetitle">
        <h1>Report Bulanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Report Bulanan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="matkeg_pegawai_tab" data-bs-toggle="tab"
                                        data-bs-target="#matkeg_pegawai" type="button" role="tab"
                                        aria-controls="matkeg_pegawai" aria-selected="true">Pegawai</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="matkeg_mitra_tab" data-bs-toggle="tab"
                                        data-bs-target="#matkeg_mitra" type="button" role="tab"
                                        aria-controls="matkeg_mitra" aria-selected="false">Mitra</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-4" id="myTabContent">
                                <!-- PEGAWAI SECTION -->
                                <div class="tab-pane fade show active" id="matkeg_pegawai" role="tabpanel"
                                    aria-labelledby="matkeg_pegawai_tab">
                                    <div>
                                        <h5 class="card-title">Filter Periode</h5>
                                        <div class="alert alert-info">
                                            <b><i class="bi bi-info-circle-fill"></i>&emsp;Info: </b>Untuk bisa melihat
                                            <i>report</i>,
                                            anda harus memilih tahun dan bulan
                                            kegiatan terlebih dahulu
                                        </div>
                                        <div>
                                            <!-- General Form Elements -->
                                            <form action="" method="GET" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row mb-3">
                                                    <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                                    <div class="col-sm-8">
                                                        <select name="tahun"
                                                            class="filter @error('tahun') is-invalid @enderror">
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
                                                        <select name="bulan"
                                                            class="filter @error('bulan') is-invalid @enderror">
                                                            <option value="" disable selected>Choose Bulan</option>
                                                        </select>
                                                        @error('bulan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </form><!-- End General Form Elements -->
                                        </div>
                                    </div>
                                    <!-- Table with stripped rows -->
                                    <table id="download-pekerjaan-table" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Periode</th>
                                                <th>ID Anggota</th>
                                                <th>Nama Anggota</th>
                                                <th>Jlh Kegiatan</th>
                                                <th>Jlh Pekerjaan</th>
                                                <th>Unduh Report</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <!-- End Table with stripped rows -->
                                </div>
                                <!-- END PEGAWAI SECTION -->

                                <!-- MITRA SECTION -->
                                <div class="tab-pane fade" id="matkeg_mitra" role="tabpanel"
                                    aria-labelledby="matkeg_mitra_tab">
                                    <div>
                                        <h5 class="card-title">Filter Periode</h5>
                                        <div class="alert alert-info">
                                            <b><i class="bi bi-info-circle-fill"></i>&emsp;Info: </b>Untuk bisa melihat
                                            <i>report</i>,
                                            anda harus memilih tahun dan bulan
                                            kegiatan terlebih dahulu
                                        </div>


                                        <div>
                                            <!-- General Form Elements -->
                                            <form action="" method="GET" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row mb-3">
                                                    <label id="tahun_" class="col-sm-2 col-form-label">Tahun</label>
                                                    <div class="col-sm-8">
                                                        <select name="tahun_"
                                                            class="filter_ @error('tahun_') is-invalid @enderror">
                                                            <option value="" disable selected>Choose Tahun</option>
                                                            @foreach ([2023, 2024] as $item)
                                                                <option value={{ $item }}
                                                                    @if ($item == (int) date('Y')) selected @endif>
                                                                    {{ $item }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('tahun_')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label id="bulan_" class="col-sm-2 col-form-label">Bulan</label>
                                                    <div class="col-sm-8">
                                                        <select name="bulan_"
                                                            class="filter_ @error('bulan_') is-invalid @enderror">
                                                            <option value="" disable selected>Choose Bulan</option>
                                                        </select>
                                                        @error('bulan_')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </form><!-- End General Form Elements -->
                                        </div>
                                    </div>
                                    <!-- Table with stripped rows -->
                                    <table id="download-pekerjaan-table-mitra" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Periode</th>
                                                <th>ID Anggota</th>
                                                <th>Nama Anggota</th>
                                                <th>Jlh Kegiatan</th>
                                                <th>Jlh Pekerjaan</th>
                                                <th>Unduh Report</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <!-- End Table with stripped rows -->
                                </div>
                                <!-- END MITRA SECTION -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // PEGAWAI SECTION
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

            // ELEMENT
            let tahun = $('select[name="tahun"]')
            let bulan = $('select[name="bulan"]')
            tahun.val(2024).trigger('change')
            bulan.val('00').trigger('change')
            let t = tahun.val()
            let b = bulan.val()
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
                            console.log($('select[name="tahun"]').val(), $(
                                'select[name="bulan"]').val())
                        }
                    })
                } else {
                    t = '00';
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').val('').trigger('change');
                }
            })

            var table = $('#download-pekerjaan-table').DataTable({
                "order": [],
                'scrollX': "100%",
                'responsive': true,
                'autowidth': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'download-report',
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun"]').val();
                        d.bulan_filter = $('select[name="bulan"]').val();
                        return d;
                    }
                },
                'columnDefs': [{
                    "targets": 0, // your case first column
                    "className": "text-center",
                    "width": "20%"
                }, {
                    "targets": 1, // your case first column
                    "className": "text-center",
                    "width": "20%"
                }, {
                    "targets": 2, // your case first column
                    "width": "30%"
                }, {
                    "targets": 3, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, {
                    "targets": 4, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, {
                    "targets": 5, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, ],
                'columns': [{

                        data: 'periode',
                        name: 'periode',
                    },
                    {
                        data: 'id_anggota',
                        name: 'id_anggota'
                    },
                    {
                        data: 'nama_anggota',
                        name: 'nama_anggota'
                    },
                    {
                        data: 'jlh_kegiatan',
                        name: 'jlh_kegiatan'
                    },
                    {
                        data: 'jlh_pekerjaan',
                        name: 'jlh_pekerjaan'
                    },
                    {
                        data: 'unduh',
                        name: 'unduh',
                        width: "6em",
                    },
                ],
                // "language": {
                //     "emptyTable": "Mohon filter reoprt sampai level Bulan terlebih dahulu :)"
                // },
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
            // END PEGAWAI SECTION

            //MITRA SECTION
            $('select[name="tahun_"]').select2({
                allowClear: false,
                placeholder: "Pilih Tahun",
                width: '100%'
            });
            $('select[name="bulan_"]').select2({
                allowClear: true,
                placeholder: "Pilih Bulan",
                width: '100%'
            });

            // ELEMENT
            let tahun_ = $('select[name="tahun_"]')
            let bulan_ = $('select[name="bulan_"]')
            tahun_.val(2024).trigger('change')
            bulan_.val('00').trigger('change')
            let t_ = tahun_.val()
            let b_ = bulan_.val()

            // FILTER TAHUN INIT
            $.ajax({
                url: 'filterPekerjaan/role=mitra/tahun=' + t_,
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    if (tahun_.val()) {
                        t_ = tahun_.val()
                    } else {
                        t_ = '00';
                    }
                    $('select[name="bulan_"]').empty();
                    $('select[name="bulan_"]').append(
                        '<option value=""></option>')
                    $.each(data, function(key, bulan, $bulan_arr) {
                        $('select[name="bulan_"]').append(
                            '<option value=' + bulan.bulan +
                            '>' +
                            bulan_arr[bulan.bulan - 1] +
                            '</option>'
                        )
                    })
                }
            })

            // FILTER TAHUN
            $('select[name="tahun_"]').on('change', function() {
                if (tahun_.val()) {
                    t_ = tahun_.val()
                    $.ajax({
                        url: 'filterPekerjaan/role=mitra/tahun=' + t_,
                        type: "GET",
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('select[name="bulan_"]').empty();
                            $('select[name="bulan_"]').append(
                                '<option value=""></option>')
                            $.each(data, function(key, bulan, $bulan_arr) {
                                $('select[name="bulan_"]').append(
                                    '<option value=' + bulan.bulan +
                                    '>' +
                                    bulan_arr[bulan.bulan - 1] +
                                    '</option>'
                                )
                            });
                        }
                    })
                } else {
                    t_ = '00';
                    $('select[name="bulan_"]').empty();
                    $('select[name="bulan_"]').val('').trigger('change');
                }
            })

            var table_mit = $('#download-pekerjaan-table-mitra').DataTable({
                "order": [],
                'scrollX': "100%",
                'responsive': true,
                'autowidth': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'download-report-mitra',
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun_"]').val();
                        d.bulan_filter = $('select[name="bulan_"]').val();
                        return d;
                    }
                },
                'columnDefs': [{
                    "targets": 0, // your case first column
                    "className": "text-center",
                    "width": "20%"
                }, {
                    "targets": 1, // your case first column
                    "className": "text-center",
                    "width": "20%"
                }, {
                    "targets": 2, // your case first column
                    "width": "30%"
                }, {
                    "targets": 3, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, {
                    "targets": 4, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, {
                    "targets": 5, // your case first column
                    "className": "text-center",
                    "width": "10%"
                }, ],
                'columns': [{

                        data: 'periode',
                        name: 'periode',
                    },
                    {
                        data: 'id_anggota',
                        name: 'id_anggota'
                    },
                    {
                        data: 'nama_anggota',
                        name: 'nama_anggota'
                    },
                    {
                        data: 'jlh_kegiatan',
                        name: 'jlh_kegiatan'
                    },
                    {
                        data: 'jlh_pekerjaan',
                        name: 'jlh_pekerjaan'
                    },
                    {
                        data: 'unduh',
                        name: 'unduh',
                        width: "6em",
                    },
                ],
                // "language": {
                //     "emptyTable": "Mohon filter reoprt sampai level Bulan terlebih dahulu :)"
                // },
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
            // END MITRA SECTION

            // Filter
            $('.filter').on('change', function() {
                table.ajax.reload(null, false);
            });
            $('.filter_').on('change', function() {
                table_mit.ajax.reload(null, false);
            });
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                table.columns.adjust().draw()
                table_mit.columns.adjust().draw()

            })
            $('[data-toggle=tooltip]').tooltip();

        });
    </script>
@endsection

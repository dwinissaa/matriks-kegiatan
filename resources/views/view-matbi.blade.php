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
        table.dataTable tfoot th {
            text-align: right;
        }
    </style>

    <!-- Halaman -->


    <script>
        const page = 2;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
            document.querySelector('#matriks-nav').classList.add('show');
            document.querySelector('#matriks-biaya').classList.add('active');

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
        <h1>Matriks Biaya</h1>
        <span id="puthere"></span>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Matriks Biaya</li>
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
                                <div class="tab-pane fade show active" id="matkeg_pegawai" role="tabpanel"
                                    aria-labelledby="matkeg_pegawai_tab">
                                    <div>
                                        <h5 class="card-title">Filter Periode</h5>
                                        <!-- General Form Elements -->
                                        <form action="" method="GET" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row mb-3">
                                                <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                                <div class="col-sm-8">
                                                    <select name="tahun"
                                                        class="filter @error('tahun') is-invalid @enderror">
                                                        <option value="" disable selected>Choose Tahun</option>
                                                        @foreach ([2023, 2024] as $item)
                                                            <option value={{ $item }}
                                                                @if ($item == (int) date('Y')) selected @endif>
                                                                {{ $item }}
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
                                    <h5 class="card-title">Matriks Biaya Pegawai</h5>
                                    <!-- Table with stripped rows -->
                                    <table id="matkeg-table-peg" class="display" style="width:100% ">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- End Table with stripped rows -->
                                </div>
                                <div class="tab-pane fade" id="matkeg_mitra" role="tabpanel"
                                    aria-labelledby="matkeg_mitra_tab">
                                    <div>
                                        <h5 class="card-title">Filter Periode</h5>
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

                                    <h5 class="card-title">Matriks Biaya Mitra</h5>
                                    <!-- Table with stripped rows -->
                                    <table id="matkeg-table-mit" class="display" style="width:100% ">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- End Table with stripped rows -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('select[name="tahun"]').select2({
                allowClear: false,
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

            // FILTER TAHUN INIT
            $.ajax({
                url: 'filterPekerjaan/role=pegawai/tahun=' + t,
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
                        url: 'filterPekerjaan/role=pegawai/tahun=' + t,
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
                        url: 'filterPekerjaan/role=pegawai/tahun=' + t + '/bulan=' + b,
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




            var table_peg;
            var kolom_js = [{
                "data": "id_anggota",
                "name": "id_anggota",
                "mData": "id_anggota",
                "sName": "id_anggota"
            }, {
                "data": "nama",
                "name": "nama",
                "mData": "nama",
                "sName": "nama"
            }];
            var style_peg = [{
                'targets': 0,
                'className': 'text-center',
                'width': '10%',
            }, ];
            // <------------------ Initialize datatable with Ajax ------------------>
            var f_bulan;
            if (!$('select[name="bulan"]').val()) {
                f_bulan = '00';
            } else {
                f_bulan = $('select[name="bulan"]').val()
            }
            $.ajax({
                url: 'getUserBi/role=pegawai/tahun=' + $('select[name="tahun"]').val() + '/bulan=' + f_bulan,
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {

                    $.each(['ID Anggota', 'Nama'], function(k, colObj) {
                        str = "<th>" + colObj + "</th>";
                        $(str).appendTo('#matkeg-table-peg' + ">thead>tr");
                    });

                    $.each(data[1], function(k, colObj) {
                        str = "<th>" + colObj.kegiatan + "</th>";
                        $(str).appendTo('#matkeg-table-peg' + ">thead>tr");
                        kolom_js.push({
                            "data": "keg_" + data[1][k]['id_keg'],
                            "name": "keg_" + data[1][k]['id_keg'],
                            "mData": "keg_" + data[1][k]['id_keg'],
                            "sName": "keg_" + data[1][k]['id_keg'],
                        });
                        style_peg.push({
                            'targets': k + 2,
                            'className': 'text-center',
                            'width': '10%',
                        });
                    });
                    $('<th>Total Biaya</th>').appendTo('#matkeg-table-peg' + ">thead>tr");
                    kolom_js.push({
                        "data": "total_pekerjaan",
                        "name": "total_pekerjaan"
                    });
                    style_peg.push({
                        'targets': data[1].length + 2,
                        'className': 'text-center',
                        'width': '10%',
                    });




                    table_peg = $('#matkeg-table-peg').DataTable({
                        order: [],
                        autowidth: false,
                        processing: true,
                        serverSide: true,
                        lengthMenu: [
                            [25, 50, 75, -1],
                            [25, 50, 75, "All"]
                        ],
                        pageLength: 25,
                        ajax: {
                            url: 'matriks-biaya',
                            data: function(d) {
                                if (!$('select[name="bulan"]').val()) {
                                    f_bulan = '00';
                                } else {
                                    f_bulan = $('select[name="bulan"]').val()
                                }
                                d.tahun_filter = $('select[name="tahun"]').val();
                                d.bulan_filter = f_bulan;
                                return d;
                            },
                        },
                        columnDefs: style_peg,
                        columns: kolom_js,
                        order: [
                            [kolom_js.length - 1, 'desc']
                        ],
                        fixedColumns: {
                            start: 2
                        },
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        scrollCollapse: true,
                        scrollX: true,
                        scrollY: 500,
                        // destroy: true,
                    });
                }
            });
            // <------------------ End Initialize datatable with Ajax ------------------>


            $('.filter').on('change', function() {
                // <------------------ Onchange datatable with Ajax ------------------>
                // Kuncinya
                $('#matkeg-table-peg').DataTable().clear().destroy();
                $('#matkeg-table-peg').empty();

                var kolom_js = [{
                    "data": "id_anggota",
                    "name": "id_anggota",
                    "mData": "id_anggota",
                    "sName": "id_anggota"
                }, {
                    "data": "nama",
                    "name": "nama",
                    "mData": "nama",
                    "sName": "nama"
                }]
                var style_peg = [{
                    'targets': 0,
                    'className': 'text-center',
                    'width': '10%',
                }, ];


                if (!$('select[name="bulan"]').val()) {
                    f_bulan = '00';
                } else {
                    f_bulan = $('select[name="bulan"]').val()
                }

                $.ajax({
                    url: 'getUserBi/role=pegawai/tahun=' + $('select[name="tahun"]').val() +
                        '/bulan=' + f_bulan,
                    type: "GET",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {

                        // Kuncinya
                        $('#matkeg-table-peg').find('th').remove();

                        $.each(['ID Anggota', 'Nama'], function(k, colObj) {
                            str = "<th>" + colObj + "</th>";
                            $(str).appendTo('#matkeg-table-peg' + ">thead>tr");
                        });
                        $.each(data[1], function(k, colObj) {
                            str = "<th>" + colObj.kegiatan + "</th>";
                            $(str).appendTo('#matkeg-table-peg' + ">thead>tr");
                            kolom_js.push({
                                "data": "keg_" + data[1][k]['id_keg'],
                                "name": "keg_" + data[1][k]['id_keg'],
                                "mData": "keg_" + data[1][k]['id_keg'],
                                "sName": "keg_" + data[1][k]['id_keg'],
                            });
                            style_peg.push({
                                'targets': k + 2,
                                'className': 'text-center',
                                'width': '10%',
                            });
                        });

                        $('<th>Total Biaya</th>').appendTo('#matkeg-table-peg' +
                            ">thead>tr");
                        kolom_js.push({
                            "data": "total_pekerjaan",
                            "name": "total_pekerjaan"
                        });
                        style_peg.push({
                            'targets': data[1].length + 2,
                            'className': 'text-center',
                            'width': '10%',
                        });

                        // Reinitialize datatable
                        table_peg = $('#matkeg-table-peg').DataTable({
                            order: [],
                            autowidth: false,
                            processing: true,
                            serverSide: true,
                            lengthMenu: [
                                [25, 50, 75, -1],
                                [25, 50, 75, "All"]
                            ],
                            pageLength: 25,
                            ajax: {
                                url: 'matriks-biaya',
                                data: function(d) {
                                    d.tahun_filter = $('select[name="tahun"]')
                                        .val();
                                    d.bulan_filter = f_bulan;
                                    return d;
                                },
                            },
                            columnDefs: style_peg,
                            columns: kolom_js,
                            order: [
                                [kolom_js.length - 1, 'desc']
                            ],

                            fixedColumns: {
                                start: 2
                            },
                            fixedHeader: {
                                header: true,
                                footer: true
                            },
                            scrollCollapse: true,
                            scrollX: true,
                            scrollY: 500,
                            // destroy: true,
                        });
                    }
                });
                // <------------------ End onchange datatable with Ajax ------------------>
            })



















            // <--------------------------- MITRA SECTION --------------------------->
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
            $('select[name="tim_"]').select2({
                allowClear: true,
                placeholder: "Pilih Tim",
                width: '100%'
            });

            // ELEMENT
            let tahun_ = $('select[name="tahun_"]')
            let bulan_ = $('select[name="bulan_"]')
            let tim_ = $('select[name="tim_"]')
            // tahun_.val(2024).trigger('change')
            // bulan_.val('00').trigger('change')
            // tim_.val('00').trigger('change')
            let t_ = tahun_.val()
            let b_ = bulan_.val()
            let ti_ = tim_.val()

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

            // FILTER BULAN
            $('select[name="bulan_"]').on('change', function() {
                if (bulan_.val()) {
                    b_ = bulan_.val()
                    $.ajax({
                        url: 'filterPekerjaan/role=mitra/tahun=' + t_ + '/bulan=' + b_,
                        type: "GET",
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('select[name="tim_"]').empty();
                            $('select[name="tim_"]').append(
                                '<option value=""></option>')
                            $.each(data, function(key, tim) {
                                $('select[name="tim_"]').append(
                                    '<option value=' + tim.subject_meter +
                                    '>' + tim.tim +
                                    '</option>'
                                )
                            })
                        }
                    })
                } else {
                    b_ = '00';
                    $('select[name="tim_"]').empty();
                    $('select[name="tim_"]').val('').trigger('change');
                }
            })




            var table_mit;
            var kolom_mit = [{
                "data": "id_anggota",
                "name": "id_anggota",
                "mData": "id_anggota",
                "sName": "id_anggota"
            }, {
                "data": "nama",
                "name": "nama",
                "mData": "nama",
                "sName": "nama"
            }];
            var style_mit = [{
                'targets': 0,
                'className': 'text-center',
                'width': '10%',
            }, ];
            // // <------------------ Initialize datatable with Ajax ------------------>
            var f_bulan_;
            if (!$('select[name="bulan_"]').val()) {
                f_bulan_ = '00';
            } else {
                f_bulan_ = $('select[name="bulan_"]').val()
            }
            $.ajax({
                url: 'getUserBi/role=mitra/tahun=' + $('select[name="tahun_"]').val() + '/bulan=' + f_bulan_,
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {

                    $.each(['ID Anggota', 'Nama'], function(k, colObj) {
                        str = "<th>" + colObj + "</th>";
                        $(str).appendTo('#matkeg-table-mit' + ">thead>tr");
                    });

                    $.each(data[1], function(k, colObj) {
                        str = "<th>" + colObj.kegiatan + "</th>";
                        $(str).appendTo('#matkeg-table-mit' + ">thead>tr");
                        kolom_mit.push({
                            "data": "keg_" + data[1][k]['id_keg'],
                            "name": "keg_" + data[1][k]['id_keg'],
                            "mData": "keg_" + data[1][k]['id_keg'],
                            "sName": "keg_" + data[1][k]['id_keg'],
                        });
                        style_mit.push({
                            'targets': k + 2,
                            'className': 'text-center',
                            'width': '10%',
                        });
                    });
                    $('<th>Total Pekerjaan</th>').appendTo('#matkeg-table-mit' + ">thead>tr");
                    kolom_mit.push({
                        "data": "total_pekerjaan",
                        "name": "total_pekerjaan"
                    });
                    style_mit.push({
                        'targets': data[1].length + 2,
                        'className': 'text-center',
                        'width': '10%',
                    });



                    table_mit = $('#matkeg-table-mit').DataTable({
                        order: [],
                        autowidth: false,
                        processing: true,
                        serverSide: true,
                        lengthMenu: [
                            [25, 50, 75, -1],
                            [25, 50, 75, "All"]
                        ],
                        pageLength: 25,
                        ajax: {
                            url: 'matriks-biaya-mitra',
                            data: function(d) {
                                if (!$('select[name="bulan_"]').val()) {
                                    f_bulan_ = '00';
                                } else {
                                    f_bulan_ = $('select[name="bulan_"]').val()
                                }
                                d.tahun_filter = $('select[name="tahun_"]').val();
                                d.bulan_filter = f_bulan_;
                                return d;
                            },
                        },
                        columnDefs: style_mit,
                        columns: kolom_mit,
                        order: [
                            [kolom_mit.length - 1, 'desc']
                        ],
                        fixedColumns: {
                            start: 2
                        },
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        scrollCollapse: true,
                        scrollX: true,
                        scrollY: 500,
                        destroy: true,
                    });
                }
            });
            // <------------------ End Initialize datatable with Ajax ------------------>


            $('.filter_').on('change', function() {
                // <------------------ Onchange datatable with Ajax ------------------>
                // Kuncinya
                $('#matkeg-table-mit').DataTable().clear().destroy();
                $('#matkeg-table-mit').empty();

                var kolom_mit = [{
                    "data": "id_anggota",
                    "name": "id_anggota",
                    "mData": "id_anggota",
                    "sName": "id_anggota"
                }, {
                    "data": "nama",
                    "name": "nama",
                    "mData": "nama",
                    "sName": "nama"
                }]
                var style_mit = [{
                    'targets': 0,
                    'className': 'text-center',
                    'width': '10%',
                }, ];


                if (!$('select[name="bulan_"]').val()) {
                    f_bulan_ = '00';
                } else {
                    f_bulan_ = $('select[name="bulan_"]').val()
                }

                $.ajax({
                    url: 'getUserBi/role=mitra/tahun=' + $('select[name="tahun_"]').val() +
                        '/bulan=' + f_bulan_,
                    type: "GET",
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {

                        // Kuncinya
                        $('#matkeg-table-mit').find('th').remove();

                        $.each(['ID Anggota', 'Nama'], function(k, colObj) {
                            str = "<th>" + colObj + "</th>";
                            $(str).appendTo('#matkeg-table-mit' + ">thead>tr");
                        });
                        $.each(data[1], function(k, colObj) {
                            str = "<th>" + colObj.kegiatan + "</th>";
                            $(str).appendTo('#matkeg-table-mit' + ">thead>tr");
                            kolom_mit.push({
                                "data": "keg_" + data[1][k]['id_keg'],
                                "name": "keg_" + data[1][k]['id_keg'],
                                "mData": "keg_" + data[1][k]['id_keg'],
                                "sName": "keg_" + data[1][k]['id_keg'],
                            });
                            style_mit.push({
                                'targets': k + 2,
                                'className': 'text-center',
                                'width': '10%',
                            });
                        });

                        $('<th>Total Pekerjaan</th>').appendTo('#matkeg-table-mit' +
                            ">thead>tr");
                        kolom_mit.push({
                            "data": "total_pekerjaan",
                            "name": "total_pekerjaan"
                        });
                        style_mit.push({
                            'targets': data[1].length + 2,
                            'className': 'text-center',
                            'width': '10%',
                        });

                        // Reinitialize datatable
                        table_mit = $('#matkeg-table-mit').DataTable({
                            order: [],
                            autowidth: false,
                            processing: true,
                            serverSide: true,
                            lengthMenu: [
                                [25, 50, 75, -1],
                                [25, 50, 75, "All"]
                            ],
                            pageLength: 25,

                            ajax: {
                                url: 'matriks-biaya-mitra',
                                data: function(d) {
                                    if (!$('select[name="bulan_"]').val()) {
                                        f_bulan_ = '00';
                                    } else {
                                        f_bulan_ = $('select[name="bulan_"]').val()
                                    }
                                    d.tahun_filter = $('select[name="tahun_"]')
                                    .val();
                                    d.bulan_filter = f_bulan_;
                                    return d;
                                },
                            },
                            columnDefs: style_mit,
                            columns: kolom_mit,
                            order: [
                                [kolom_mit.length - 1, 'desc']
                            ],

                            fixedColumns: {
                                start: 2
                            },
                            fixedHeader: {
                                header: true,
                                footer: true
                            },
                            scrollCollapse: true,
                            scrollX: true,
                            scrollY: 500,
                            // destroy: true,
                        });
                    }
                });
                // <------------------ End onchange datatable with Ajax ------------------>
            })

            //<--------------------------- END MITRA SECTION ------------------------------->


            $('.filter').on('change', function() {
                table_peg.ajax.reload(null, false);
            });

            $('.filter_').on('change', function() {
                table_mit.ajax.reload(null, false);
            });

            $('[data-toggle=tooltip]').tooltip();

            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                table_mit.columns.adjust().draw()

            })
        });
    </script>




    <style>
        .dataTables_scrollHead {
            position: sticky !important;
            top: 0px;
            z-index: 99;
            background-color: white;
            box-shadow: 0px 5px 5px 0px rgba(82, 63, 105, 0.08);
        }
    </style>
@endsection

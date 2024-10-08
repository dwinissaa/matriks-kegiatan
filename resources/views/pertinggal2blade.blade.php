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
            document.querySelector('#matriks-kegiatan').classList.add('active');
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

    <script type="text/javascript"></script>

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
    <span id="some_span">

    </span>

    <div class="pagetitle">
        <h1>Matriks Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Matriks Kegiatan</li>
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
                                        <h5 class="card-title">Filter Kegiatan</h5>
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
                                            {{-- 
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
                                            <div class="row mb-3">
                                                <label id="tim" class="col-sm-2 col-form-label">Tim</label>
                                                <div class="col-sm-8">
                                                    <select name="tim"
                                                        class="filter @error('tim') is-invalid @enderror">
                                                        <option value="" disable selected>Choose Tim</option>
                                                    </select>
                                                    @error('tim')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> --}}
                                        </form><!-- End General Form Elements -->
                                    </div>
                                    <h5 class="card-title">Matriks Kegiatan Pegawai</h2>
                                        <!-- Table with stripped rows -->
                                        <table id="matkeg-table-peg" class="display" style="width:100% ">
                                            <thead>
                                                <tr>
                                                    <th>ID Anggota</th>
                                                    <th>Nama Anggota</th>
                                                    @for ($i = 0; $i < count($unique_keg_p); $i++)
                                                        <th>{{ $unique_keg_p[$i]->kegiatan }}</th>
                                                    @endfor
                                                    <th>Total Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                {{-- <tr>
                                                    <th colspan="2">Total Pekerjaan</th>
                                                    @for ($i = 1; $i < count($unique_keg_p) + 2; $i++)
                                                        <th></th>
                                                    @endfor
                                                </tr> --}}
                                            </tfoot>
                                        </table>
                                        <!-- End Table with stripped rows -->
                                </div>
                                <div class="tab-pane fade" id="matkeg_mitra" role="tabpanel"
                                    aria-labelledby="matkeg_mitra_tab">
                                    <div>
                                        <h5 class="card-title">Filter Kegiatan</h5>
                                        <!-- General Form Elements -->
                                        <form action="" method="GET" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row mb-3">
                                                <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                                <div class="col-sm-8">
                                                    <select name="tahun"
                                                        class="filter @error('tahun') is-invalid @enderror">
                                                        <option value="" disable selected>Choose Tahun</option>
                                                        @foreach ([2024] as $item)
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
                                            {{-- <div class="row mb-3">
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

                                            <div class="row mb-3">
                                                <label id="tim" class="col-sm-2 col-form-label">Tim</label>
                                                <div class="col-sm-8">
                                                    <select name="tim"
                                                        class="filter @error('tim') is-invalid @enderror">
                                                        <option value="" disable selected>Choose Tim</option>
                                                    </select>
                                                    @error('tim')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> --}}
                                        </form><!-- End General Form Elements -->
                                    </div>
                                    {{-- <h5 class="card-title">Matriks Kegiatan Mitra</h2>
                                        <!-- Table with stripped rows -->
                                        <table id="matkeg-table-mit" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID Anggota</th>
                                                    <th>Nama Anggota</th>
                                                    @for ($i = 0; $i < count($unique_keg_m); $i++)
                                                        <th>{{ $unique_keg_m[$i]->kegiatan }}</th>
                                                    @endfor
                                                    <th>Total Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr colspan="0">
                                                    <th colspan="2">Total Pekerjaan</th>
                                                    @for ($i = 1; $i < count($unique_keg_m) + 2; $i++)
                                                        <th></th>
                                                    @endfor
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!-- End Table with stripped rows --> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var kolom_js;
        $(document).ready(function() {
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
                url: '/filterPekerjaan/tahun=' + t,
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
                        url: '/filterPekerjaan/tahun=' + t,
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
                        url: '/filterPekerjaan/tahun=' + t + '/bulan=' + b,
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

            // var kolom_js;


            var getValue = $('select[name="tahun"]').val();

            kolom_js = [{
                "data": "id_anggota",
                "name": "id_anggota"
            }, {
                "data": "nama",
                "name": "nama"
            }];
            //Ajax call

            $.ajax({
                url: '/getUser/role=pegawai/tahun=' + getValue,
                type: "GET",
                // data: {
                //     '_token': '{{ csrf_token() }}'
                // },
                dataType: 'json',
                success: function(data) {
                    var html = "<th>id_anggota</th><th>nama</th>"
                    for (let index = 0; index < data[1].length; index++) {
                        kolom_js.push({
                            "data": "keg_" + data[1][index]['id_keg'],
                            "name": "keg_" + data[1][index]['id_keg']
                        });
                        html+="<th>"+data[1][index]['kegiatan']+"</th>"
                    }
                    kolom_js.push({
                        "data": "total_pekerjaan",
                        "name": "total_pekerjaan"
                    });
                    html+="<th>total_pekerjaan</th>"
                    // $('#matkeg-table-peg thead tr').append(html);
                }
            });
            // $.get('/getUser/role=pegawai/tahun=' + getValue, {
            //     selectedValue: getValue
            // }, function(data) {
            //     console.log(data);
            //     for (let index = 0; index < data[1].length; index++) {
            //         kolom_js.push({
            //             "data": "keg_" + data[1][index]['id_keg'],
            //             "name": "keg_" + data[1][index]['id_keg']
            //         });
            //     }
            //     kolom_js.push({
            //         "data": "total_pekerjaan",
            //         "name": "total_pekerjaan"
            //     });
            // });
            // table_peg.destroy();

            var table_peg = $('#matkeg-table-peg').DataTable({
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
                    url: '/matriks-kegiatan',
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun"]').val();
                        d.bulan_filter = $('select[name="bulan"]').val();
                        d.tim_filter = $('select[name="tim"]').val();
                        return d;
                    },
                    // dataSrc: function(json) {
                    //     console.log(json);
                    //     kolom_js = [{
                    //         "data": "id_anggota",
                    //         "name": "id_anggota"
                    //     }, {
                    //         "data": "nama",
                    //         "name": "nama"
                    //     }];
                    //     for (var key in json.data[0]) {
                    //         if (key.startsWith("keg_")) {
                    //             kolom_js.push({
                    //                 "data": key,
                    //                 "name": key
                    //             });
                    //         }
                    //     }
                    //     kolom_js.push({
                    //         "data": "total_pekerjaan",
                    //         "name": "total_pekerjaan"
                    //     })
                    //     console.log(kolom_js);
                    //     return json.data;
                    // },
                },
                // drawCallback: function(settings) {
                //     // Here the response
                //     var response = settings.json;
                //     console.log(response.data[0]);
                //     for (var key in response.data[0]) {
                //         console.log(key);
                //     }
                // },
                // columnDefs: style_js,
                columns: kolom_js,
                // order: [
                //     [idx_ord, 'desc']
                // ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                },
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

            });


            $('select[name="tahun"]').change(function() {
                getValue = $(this).val();
                //Ajax call
                $.get('/getUser/role=pegawai/tahun=' + getValue, {
                    selectedValue: getValue
                }, function(data) {
                    kolom_js = [{
                        "data": "id_anggota",
                        "name": "id_anggota"
                    }, {
                        "data": "nama",
                        "name": "nama"
                    }];
                    let html = "<th>ID Anggota</th><th>Nama Anggota</th>"
                    for (let index = 0; index < data[1].length; index++) {
                        const element = data[1][index]['kegiatan'];
                        html += "<th>" + element + "</th>";
                        kolom_js.push({
                            "data": "keg_" + data[1][index]['id_keg'],
                            "name": "keg_" + data[1][index]['id_keg']
                        });
                    }
                    html += "<th>Total Pekerjaan</th>"
                    kolom_js.push({
                        "data": "total_pekerjaan",
                        "name": "total_pekerjaan"
                    });
                    console.log("html", html);
                    $('#matkeg-table-peg thead tr').append(html);
                });
                // table_peg.destroy();

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
                        url: '/matriks-kegiatan',
                        data: function(d) {
                            d.tahun_filter = $('select[name="tahun"]').val();
                            d.bulan_filter = $('select[name="bulan"]').val();
                            d.tim_filter = $('select[name="tim"]').val();
                            // <?php echo json_encode($unique_keg_p); ?>
                            return d;
                        },
                        dataSrc: function(json) {
                            console.log(json);
                            kolom_js = [{
                                "data": "id_anggota",
                                "name": "id_anggota"
                            }, {
                                "data": "nama",
                                "name": "nama"
                            }];
                            for (var key in json.data[0]) {
                                if (key.startsWith("keg_")) {
                                    kolom_js.push({
                                        "data": key,
                                        "name": key
                                    });
                                }
                            }
                            kolom_js.push({
                                "data": "total_pekerjaan",
                                "name": "total_pekerjaan"
                            })
                            console.log(kolom_js);
                            return json.data;
                        },
                    },
                    // drawCallback: function(settings) {
                    //     // Here the response
                    //     var response = settings.json;
                    //     console.log(response.data[0]);
                    //     for (var key in response.data[0]) {
                    //         console.log(key);
                    //     }
                    // },
                    // columnDefs: style_js,
                    columns: kolom_js,
                    // order: [
                    //     [idx_ord, 'desc']
                    // ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                    },
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

                });

            });



            $('.filter').on('change', function() {
                table_peg.ajax.reload(null, false);
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

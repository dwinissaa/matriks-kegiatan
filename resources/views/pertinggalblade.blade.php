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
        /* table.dataTable td {
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

            :root {
                overflow-x: auto;
            } */

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
    
    // For data Pegawai
    $kolom = [['data' => 'id_anggota', 'name' => 'id_anggota'], ['data' => 'nama', 'name' => 'nama']];
    for ($i = 0; $i < count($unique_keg_p); $i++) {
        # code...
        array_push($kolom, [
            'data' => 'keg_' . $unique_keg_p[$i]->id_keg,
            'name' => 'keg_' . $unique_keg_p[$i]->id_keg,
        ]);
    }
    array_push($kolom, ['data' => 'total_pekerjaan', 'name' => 'total_pekerjaan']);
    $kolom_php = json_decode(json_encode($kolom));
    
    // For data Mitra
    $kolom_ = [['data' => 'id_anggota', 'name' => 'id_anggota'], ['data' => 'nama', 'name' => 'nama']];
    for ($i = 0; $i < count($unique_keg_m); $i++) {
        # code...
        array_push($kolom_, [
            'data' => 'keg_' . $unique_keg_m[$i]->id_keg,
            'name' => 'keg_' . $unique_keg_m[$i]->id_keg,
        ]);
    }
    array_push($kolom_, ['data' => 'total_pekerjaan', 'name' => 'total_pekerjaan']);
    $kolom_php_ = json_decode(json_encode($kolom_));
    
    // For style Pegawai
    $style = [
        [
            'targets' => 0,
            'className' => 'text-center',
            'width' => '10%',
        ],
    ];
    for ($i = 2; $i < count($unique_keg_p) + 3; $i++) {
        array_push($style, [
            'targets' => $i,
            'className' => 'text-center',
            'width' => '10%',
        ]);
    }
    $style_php = json_decode(json_encode($style));
    
    // For style Mitra
    $style_ = [
        [
            'targets' => 0,
            'className' => 'text-center',
            'width' => '10%',
        ],
    ];
    for ($i = 2; $i < count($unique_keg_m) + 3; $i++) {
        array_push($style_, [
            'targets' => $i,
            'className' => 'text-center',
            'width' => '10%',
        ]);
    }
    $style_php_ = json_decode(json_encode($style_));
    
    ?>

    <script type="text/javascript">
        var kolom_js = JSON.parse('<?php echo json_encode($kolom_php); ?>');
        var idx_ord = JSON.parse('<?php echo count($unique_keg_p) + 2; ?>');
        var style_js = JSON.parse('<?php echo json_encode($style_php); ?>');


        var kolom_js_ = JSON.parse('<?php echo json_encode($kolom_php_); ?>');
        var idx_ord_ = JSON.parse('<?php echo count($unique_keg_m) + 2; ?>');
        var style_js_ = JSON.parse('<?php echo json_encode($style_php_); ?>');
    </script>

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
                                                <tr>
                                                    <th colspan="2">Total Pekerjaan</th>
                                                    @for ($i = 1; $i < count($unique_keg_p) + 2; $i++)
                                                        <th></th>
                                                    @endfor
                                                </tr>
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
                                    <h5 class="card-title">Matriks Kegiatan Mitra</h2>
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
                    }
                },
                columnDefs: style_js,
                columns: kolom_js,
                order: [
                    [idx_ord, 'desc']
                ],
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

                    if (idx_ord >= 3) {
                        for (let col = 2; col < idx_ord + 1; col++) {
                            // Total over all pages
                            total = api
                                .column(col)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(col).footer()).html(
                                total
                            );
                        }
                    }

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







                rowCallback: function(row, data, index) {
                    console.log(data[3] == 0);
                    // if (data[3]=="0") {
                    //     $(row).find('td:eq(3)').css('color', 'red');
                    // }
                    // if (data[2].toUpperCase() == 'EE') {
                    //     $(row).find('td:eq(2)').css('color', 'blue');
                    // }
                }









            });

            var table_mit = $('#matkeg-table-mit').DataTable({
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
                    url: '/matriks-kegiatan-mitra',
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun"]').val();
                        d.bulan_filter = $('select[name="bulan"]').val();
                        d.tim_filter = $('select[name="tim"]').val();
                        return d;
                    }
                },
                columnDefs: style_js_,
                columns: kolom_js_,
                order: [
                    [idx_ord_, 'desc']
                ],
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

                    if (idx_ord_ >= 3) {
                        for (let col = 2; col < idx_ord_ + 1; col++) {
                            // Total over all pages
                            total = api
                                .column(col)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(col).footer()).html(
                                total
                            );
                        }
                    }

                },
                fixedColumns: {
                    leftColumns: 2
                },
                fixedHeader: {
                    header: true,
                    footer: true
                },
                scrollCollapse: true,
                scrollX: true,
                scrollY: 500,
                responsive: true,


            });

            $('.filter').on('change', function() {
                table.ajax.reload(null, false);
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


// footerCallback: function(row, data, start, end, display) {
    //     var api = this.api(),
    //         data;

    //     // converting to interger to find total
    //     var intVal = function(i) {
    //         return typeof i === 'string' ?
    //             i.replace(/[\$,]/g, '') * 1 :
    //             typeof i === 'number' ?
    //             i : 0;
    //     };

    //     if (kolom_js.length > 3) {
    //         // console.log("kolkol", kolom_js)
    //         for (let col = 2; col < kolom_js
    //             .length; col++) {
    //             // Total over all pages
    //             total = api
    //                 .column(col)
    //                 .data()
    //                 .reduce(function(a, b) {
    //                     return intVal(a) + intVal(b);
    //                 }, 0);
    //             console.log("total", total);
    //             // Update footer
    //             $(api.column(col).footer()).html(
    //                 total
    //             );
    //         }
    //     }
    // },

    
    // $('<th colspan="2">Total Pekerjaan</th>').appendTo(
        //     "#matkeg-table-peg>tfoot>tr");

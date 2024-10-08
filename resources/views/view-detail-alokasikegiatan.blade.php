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

    @if (Session::has('status_create_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_alokasikegiatan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif

    @if (Session::has('status_gagalcreate_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "{{ Session::get('status_gagalcreate_alokasikegiatan') }}",
                    showConfirmButton: true,
                });
            })
        </script>
    @endif

    @if (Session::has('status_warningcreate_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "info",
                    title: "Warning",
                    html: "{{ Session::get('status_warningcreate_alokasikegiatan') }}",
                    showConfirmButton: true,
                });
            })
        </script>
    @endif


    @if (Session::has('status_destroy_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_alokasikegiatan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif

    <?php
    $bulan_arr = [
        [
            'nama' => 'Januari',
            'name' => 'January',
            'number' => 1,
        ],
        [
            'nama' => 'Februari',
            'name' => 'February',
            'number' => 2,
        ],
        [
            'nama' => 'Maret',
            'name' => 'March',
            'number' => 3,
        ],
        [
            'nama' => 'April',
            'name' => 'April',
            'number' => 4,
        ],
        [
            'nama' => 'Mei',
            'name' => 'May',
            'number' => 5,
        ],
        [
            'nama' => 'Juni',
            'name' => 'June',
            'number' => 6,
        ],
        [
            'nama' => 'Juli',
            'name' => 'July',
            'number' => 7,
        ],
        [
            'nama' => 'Agustus',
            'name' => 'August',
            'number' => 8,
        ],
        [
            'nama' => 'September',
            'name' => 'September',
            'number' => 9,
        ],
        [
            'nama' => 'Oktober',
            'name' => 'October',
            'number' => 10,
        ],
        [
            'nama' => 'November',
            'name' => 'November',
            'number' => 11,
        ],
        [
            'nama' => 'Desember',
            'name' => 'December',
            'number' => 12,
        ],
    ]; ?>

    <div class="pagetitle">
        <h1>Alokasi Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-kegiatan') }}">Kelola Kegiatan</a></li>
                <li class="breadcrumb-item active">{{ $kegiatan->id_keg . '_' . $kegiatan->kegiatan }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <h4 class="card-title">Detail Kegiatan</h4>
                            <div>
                                <?php $A = [['ID Kegiatan', $kegiatan->id_keg], ['Nama Kegiatan', $kegiatan->kegiatan], ['Waktu Pelaksanaan', $bulan_arr[array_search($kegiatan->bulan, array_column($bulan_arr, 'number'))]['nama'] . ' ' . $kegiatan->tahun], ['Subject Meter', $kegiatan->tim], ['Ketua Tim', $kegiatan->nama . ' (' . $kegiatan->email . ')'], ['Jumlah Anggota', $total_alok . ' orang']]; ?>
                                <table id="ket-kegiatan">
                                    <tbody>
                                        @for ($i = 0; $i < sizeof($A); $i++)
                                            <tr>
                                                <td><b>{{ $A[$i][0] }}</b></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{ $A[$i][1] }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <div>
                                <h4 class="card-title">Anggota Kegiatan</h4>
                                <div class="alert alert-info">
                                    <p><b><i class="bi bi-info-circle-fill"></i>&emsp;Info: </b></p>
                                    <ul>
                                        <li>
                                            Anggota kegiatan bisa berupa
                                            pegawai dan/atau mitra</li>
                                        <li>
                                            Ketua Tim <b>juga ditambahkan</b> pada menu ini sebagai anggota kegiatan (jika
                                            terlibat)
                                        </li>
                                    </ul>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex">
                                            <a data-toggle="tooltip" data-placement="top" title="Tambah"
                                                style="color: aliceblue">
                                                <button type="button" class="btn btn-primary pull-left" data-toggle="modal"
                                                    data-target="#exampleModal">
                                                    <i class="bi bi-person-plus"></i>&nbsp;&nbsp;<span>Tambah Anggota</span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota Kegiatan:
                                                        {{ $kegiatan->id_keg . '_' . $kegiatan->kegiatan . ' ' . $bulan_arr[array_search($kegiatan->bulan, array_column($bulan_arr, 'number'))]['nama'] . ' ' . $kegiatan->tahun }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <p><b><i class="bi bi-info-circle-fill"></i>&emsp;Info: </b></p>
                                                        <ul>
                                                            <li>
                                                                Anda dapat menambah Pegawai ataupun Mitra yang terlibat pada
                                                                kegiatan ini
                                                            </li>
                                                            <li>
                                                                Ketua Tim <b>juga ditambahkan</b> pada menu ini sebagai
                                                                anggota kegiatan (jika
                                                                terlibat)
                                                            </li>
                                                            <li>
                                                                Nama mitra yang muncul hanya yang terdaftar pada Mitra
                                                                Statistik Tahunan {{ $kegiatan->tahun }}
                                                            </li>
                                                            <li>
                                                                Silahkan ketik nama / email dari anggota kegiatan ini
                                                            </li>
                                                            <li>
                                                                Tekan <code>Click + Ctrl</code> untuk <i>multiple
                                                                    selection</i> anggota kegiatan
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <form class="row g-3 needs-validation" novalidate
                                                        id="formAddAlokasiKegiatan" action="{{ url('/store-alok') }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="selected_anggota[]" class="form-label">Pilih
                                                                Anggota</label>
                                                            <select data-width="100%" name="selected_anggota[]"
                                                                multiple="multiple" required>
                                                                @if ($pilihan_peg)
                                                                    <optgroup label="Pegawai">
                                                                        @foreach ($pilihan_peg as $md)
                                                                            <option value="{{ $md->id }}">
                                                                                {{ $md->nama . ' (' . $md->email . ') ' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endif
                                                                @if ($pilihan_mitra)
                                                                    <optgroup label="Mitra">
                                                                        @foreach ($pilihan_mitra as $md)
                                                                            <option value="{{ $md->id }}">
                                                                                {{ $md->nama . ' (' . $md->email . ') ' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endif
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please select a valid Anggota.
                                                            </div>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="display: none">
                                                            <input name="kegiatan" type="text"
                                                                value={{ $kegiatan->id_keg }}>
                                                        </div>
                                                    </form><!-- End Custom Styled Validation -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                    <button class="btn btn-primary" type="submit"
                                                        form="formAddAlokasiKegiatan" value="Submit">Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table id="alokasikegiatan-table" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID Anggota</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Ditambahkan pada</th>
                                        <th>Jlh Pekerjaan</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script>
        $(document).ready(function() {
            $('[data-toggle=tooltip]').tooltip();

            // Select2
            $('select[name="selected_anggota[]"]').select2({
                allowClear: true,
                minimumResultsForSearch: -1,
                width: 600,
                placeholder: "Ketik nama pegawai atau mitra..",
                dropdownParent: $('#exampleModal')
            });

            var table = $('#alokasikegiatan-table').DataTable({
                scrollX: true,
                responsive: true,
                autowidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{{ url("/view/alokasi-" . $kegiatan->id_keg) }}',
                    "data": function(d) {
                        return d;
                    },
                    "type": "GET",
                },
                columns: [{
                        data: 'id_anggota',
                        name: 'id_anggota'
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'jlh_pekerjaan',
                        name: 'jlh_pekerjaan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },

                ],
                columnDefs: [{
                        targets: 4, // your case first column
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                    },
                    {
                        targets: 5, // your case first column
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                    }
                ],
            });


        })
    </script>
@endsection

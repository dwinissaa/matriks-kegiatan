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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
            document.querySelector('#pekerjaan').classList.add('active');
        });
    </script>

    <div class="pagetitle">
        <h1>Import Pekerjaan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-pekerjaan') }}">Uraian Pekerjaan</a></li>
                <li class="breadcrumb-item active">Import Pekerjaan: {{ $keg->id_keg . '_' . $keg->kegiatan }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="card">
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <h4 class="card-title">Import Pekerjaan</h4>
                    @if (session('status_error_import_pekerjaan'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('status_error_import_pekerjaan') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('status_import_pekerjaan'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status_import_pekerjaan') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (isset($errors) && $errors->any())
                        @foreach ($errors->all() as $item)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span>{{ $item }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    @if (session()->has('failures'))
                        @if (count(session()->get('failures')) == 1)
                            <h5 style="color:red">There is {{ count(session()->get('failures')) }} Error Import!</h5>
                        @else
                            <h5 style="color:red">There are {{ count(session()->get('failures')) }} Errors Import!</h5>
                        @endif
                        <table class="table table-danger">
                            <tr>
                                <th>Row</th>
                                <th>Attribute</th>
                                <th>Errors</th>
                            </tr>
                            @foreach (session()->get('failures') as $validation)
                                <tr>
                                    <td>{{ $validation->row() }}</td>
                                    <td>{{ $validation->attribute() }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($validation->errors() as $e)
                                                <li>{{ $e }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    {{-- <td>{{ $validation->values()[$validation->attribute()] }}</td> --}}
                                </tr>
                            @endforeach
                        </table>
                    @endif
                    <div style="margin-bottom: 20px;">
                        <?php $A = [['ID Kegiatan', $keg->id_keg], ['Nama Kegiatan', $keg->kegiatan], ['Waktu Pelaksanaan', $bulan_arr[array_search($keg->bulan, array_column($bulan_arr, 'number'))]['nama'] . ' ' . $keg->tahun], ['Subject Meter', $keg->tim], ['Ketua Tim', $keg->nama . ' (' . $keg->email . ')']]; ?>
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
                    <form action="{{ url('import/pekerjaan/id_keg=' . $keg->id_keg) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" enctype="multipart/form-data">
                            <input type="file" name="file" />
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

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

    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
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


    <!-- End Halaman -->
    <div class="pagetitle">
        <h1>Tambah Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-kegiatan') }}">Kegiatan</a></li>
                <li class="breadcrumb-item active">Tambah Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        {{-- <div class="row">
            <div class="col"> --}}

        <div class="card" style="padding: 0;50px">
            <div class="card-body">
                <h5 class="card-title">Tambah Kegiatan</h5>

                <!-- General Form Elements -->
                <form action="{{ url('store-kegiatan') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                        <div class="col-sm-9">
                            <input name="kegiatan" type="text"
                                class="form-control @error('kegiatan') is-invalid @enderror" value="{{ old('kegiatan') }}"
                                required minlength="3" maxlength="150"/>
                            <div class="invalid-feedback">Please input nama kegiatan (minimal 3 karakter, maksimal 150 karakter)</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bulan" class="col-sm-3 col-form-label">Bulan</label>
                        <div class="col-sm-3">
                            <select name="bulan" required>
                                @foreach ($bulan_arr as $item)
                                    <option value="{{ $item['number'] }}" @if ($item['number'] == (int) date('m')) selected @endif>
                                        {{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please input bulan</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-3">
                            <?php
                            $tahun_arr = [];
                            for ($i = 2024; $i <= (int) date('Y'); $i++) {
                                $tahun_arr[] = $i;
                            }
                            ?>
                            <select name="tahun" required>
                                @foreach ($tahun_arr as $item)
                                    <option value="{{ $item }}" @if ($item == (int) date('Y')) selected @endif>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please input tahun</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="subject_meter" class="col-sm-3 col-form-label">Subject Meter</label>
                        <div class="col-sm-9">
                            <select name="subject_meter" required>
                                <option value="" disabled selected>Pilih Subject Meter</option>
                                @foreach ($sm_arr as $item)
                                    <option value="{{ $item->id_tim }}">
                                        {{ $item->tim }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please input subject meter kegiatan</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>

                    <div class="row mb-3" style="float:right; margin:0px; 5px;">
                        <div class="col-sm-20">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                    <div class="row mb-3" style="float:right">
                        <div class="col-sm-20">
                            <button type="button" class="btn btn-danger" onclick="javascript:history.back()">Batal</button>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->

            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('select[name="bulan"]').select2({
                    placeholder: "Pilih Bulan",
                    width: '100%'
                });
                $('select[name="tahun"]').select2({
                    placeholder: "Pilih Tahun",
                    width: '100%'
                });
                $('select[name="subject_meter"]').select2({
                    allowClear: true,
                    placeholder: "Pilih Subject Meter",
                    width: '100%',
                });
            });

            // // Example starter JavaScript for disabling form submissions if there are invalid fields
            // (function() {
            //     'use strict'

            //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
            //     var forms = document.querySelectorAll('.needs-validation')

            //     // Loop over them and prevent submission
            //     Array.prototype.slice.call(forms)
            //         .forEach(function(form) {
            //             form.addEventListener('submit', function(event) {
            //                 if (!form.checkValidity()) {
            //                     event.preventDefault()
            //                     event.stopPropagation()
            //                 }

            //                 form.classList.add('was-validated')
            //             }, false)
            //         })
            // })()
        </script>
    </section>
@endsection

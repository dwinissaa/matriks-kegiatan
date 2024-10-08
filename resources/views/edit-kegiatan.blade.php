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

    @if (Session::has('status_edit_kegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire("Good job!", "You clicked the button!", "{{ Session::get('status_create_kegiatan') }}");
            });
        </script>
    @endif

    <!-- End Halaman -->
    <div class="pagetitle">
        <h1>Edit Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-kegiatan') }}">Kegiatan</a></li>
                <li class="breadcrumb-item active">Edit Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        {{-- <div class="row">
            <div class="col"> --}}

        <div class="card" style="padding: 0;50px">
            <div class="card-body">
                <h5 class="card-title">Edit Kegiatan</h5>

                <!-- General Form Elements -->
                <form action="{{ url('/update/kegiatan-' . $kegiatan->id_keg) }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="row mb-3">
                        <label id="kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                        <div class="col-sm-9">
                            <input name="kegiatan" type="text"
                                class="form-control has-validation @error('kegiatan') is-invalid @enderror"
                                value="{{ old('kegiatan', $kegiatan->kegiatan) }}" required />
                            <div class="invalid-feedback invalid-kegiatan">Required!</div>
                            @error('kegiatan')
                                <script>
                                    document.getElementsByClassName('invalid-kegiatan')[0].innerHTML = "<?php echo $message; ?>"
                                </script>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label id="bulan" class="col-sm-3 col-form-label">Bulan</label>
                        <div class="col-sm-3">
                            <?php
                            $bulan_arr = [['Januari', 1], ['Februari', 2], ['Maret', 3], ['April', 4], ['Mei', 5], ['Juni', 6], ['Juli', 7], ['Agustus', 8], ['September', 9], ['Oktober', 10], ['November', 11], ['Desember', 12]];
                            ?>
                            <select name="bulan" class=" has-validation @error('bulan') is-invalid @enderror">
                                @foreach ($bulan_arr as $item)
                                    <option value={{ $item[1] }} @if ($item[1] == $kegiatan->bulan) selected @endif>
                                        {{ $item[0] }}</option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback">Please input bulan!</div>
                            <div class="valid-feedback">Looks good!</div>

                        </div>
                        <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-3">
                            <?php
                            $tahun_arr = [2024];
                            ?>
                            <select name="tahun" class=" has-validation @error('tahun') is-invalid @enderror">
                                @foreach ($tahun_arr as $item)
                                    <option value={{ $item }}
                                        @if ($item == $kegiatan->tahun) selected @endif>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please input tahun!</div>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label id="subject_meter" class="col-sm-3 col-form-label">Subject Meter</label>
                        <div class="col-sm-9">
                            <select name="subject_meter" class=" has-validation @error('subject_meter') is-invalid @enderror">
                                @foreach ($sm_arr as $item)
                                    <option value={{ $item->id_tim }} @if ($item->id_tim == $kegiatan->subject_meter) selected @endif>
                                        {{ $item->tim }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please input subject meter!</div>
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
    </section>
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
                placeholder: "Pilih Subject Meter",
                width: '100%',
            });
        });
    </script>
@endsection

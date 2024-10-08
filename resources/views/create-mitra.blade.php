@extends('layout.default')
@section('container')
    <!--jQuery first-->
    {{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}

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
        const page = 3;
        $(document).ready(function() {
            const array = document.querySelectorAll('.tab-menu');
            for (let i = 0; i < array.length; i++) {
                if (i == page) {
                    array[i].classList.remove("collapsed")
                }
            }
            document.querySelector('#master-nav').classList.add('show');
            document.querySelector('#master-mitra').classList.add('active');
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

    @if (Session::has('status_gagalcreate_mitra'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "{{ Session::get('status_gagalcreate_mitra') }}",
                });
            })
        </script>
    @endif



    <!-- End Halaman -->
    <div class="pagetitle">
        <h1>Tambah Mitra</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-mitra') }}">Mitra</a></li>
                <li class="breadcrumb-item active">Tambah Mitra</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Mitra</h5>

                        <!-- General Form Elements -->
                        <form action="{{ url('/store-mitra') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label id="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input name="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                        placeholder="Nama" />
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="sobatid" class="col-sm-2 col-form-label">SOBAT ID</label>
                                <div class="col-sm-10">
                                    <input name="sobatid" class="form-control @error('sobatid') is-invalid @enderror"
                                        value="{{ old('sobatid') }}" placeholder="12 digit" />

                                    @error('sobatid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input name="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="Email BPS" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="asal_kecamatan" class="col-sm-2 col-form-label">Asal Kecamatan</label>
                                <div class="col-sm-8">
                                    <select
                                        name="asal_kecamatan"class="@error('asal_kecamatan') is-invalid @enderror">
                                        <option value="" disable selected>Asal Kecamatan</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id_kec }}"
                                                {{ \Request::old('asal_kecamatan') == $kec->id_kec ? 'selected' : '' }}>
                                                [{{ $kec->id_kec }}]
                                                {{ $kec->kecamatan }}</option>
                                        @endforeach
                                    </select>
                                    @error('asal_kecamatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>

                            <div class="row mb-3" style="float:right; margin:0px; 5px;">
                                <div class="col-sm-20">
                                    <button type="submit" class="btn btn-primary">Tambah Mitra</button>
                                </div>
                            </div>
                            <div class="row mb-3" style="float:right">
                                <div class="col-sm-20">
                                    <button type="button" class="btn btn-danger"
                                        onclick="javascript:history.back()">Batal</button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->

                    </div>
                </div>

            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('select[name="asal_kecamatan"]').select2({
                    placeholder: "Pilih Kecamatan",
                    width: '100%'
                });
            });
        </script>
    </section>
@endsection

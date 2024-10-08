@extends('layout.default')
@section('container')
    <!--jQuery first-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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
    </style>
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
            document.querySelector('#master-pegawai').classList.add('active');
        });
    </script>

    @if (Session::has('status_gagaledit_pegawai'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "{{ Session::get('status_gagaledit_pegawai') }}",
                });
            })
        </script>
    @endif

    <!-- End Halaman -->
    <div class="pagetitle">
        <h1>Edit Pegawai</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/view-pegawai') }}">Pegawai</a></li>
                <li class="breadcrumb-item active">Edit Pegawai</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Pegawai</h5>

                        <!-- General Form Elements -->
                        <form action="{{ url('/update/pegawai-' . $edited_pegawai->nip) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label id="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input name="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ $edited_pegawai->nama }}" />
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        <script>
                                            $('input[name="nama"]').attr("value", "{{ old('nama') }}")
                                        </script>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="nip" class="col-sm-2 col-form-label disabled">NIP</label>
                                <div class="col-sm-10">
                                    <input name="nip" class="form-control" value="{{ $edited_pegawai->nip }}"
                                        disabled />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input name="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ $edited_pegawai->email }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        <script>
                                            $('input[name="email"]').attr("value", "{{ old('email') }}")
                                        </script>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label id="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                                <div class="col-sm-10">
                                    <select name="jabatan"class="selectpicker" data-width="100%" data-live-search="true">
                                        @foreach ($jabatan as $item)
                                            <option value="{{ $item->jabatan }}"
                                                @if ($edited_pegawai->jabatan == $item->jabatan) selected @endif>{{ $item->jabatan }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Role</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="admin" id="radio-admin"
                                            value="1" @if ($edited_pegawai->admin == 1) checked @endif>
                                        <label class="form-check-label" for="radio-admin">
                                            Administrator
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="admin" id="radio-user"
                                            value="0" @if ($edited_pegawai->admin == 0) checked @endif>
                                        <label class="form-check-label" for="radio-user">
                                            User
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row mb-3" style="float:right; margin:0px; 5px;">
                                <div class="col-sm-20">
                                    <button type="submit" class="btn btn-primary">Update Pegawai</button>
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
                $('select[name="jabatan"]').select2({
                    placeholder: "Pilih Jabatan",
                    width: '100%'
                });
            });
        </script>
    </section>
@endsection

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

    @if (Session::has('status_gagalcreate_pekerjaan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "{{ Session::get('status_gagalcreate_pekerjaan') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_warningcreate_pekerjaan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "info",
                    title: "Warning",
                    html: "{{ Session::get('status_warningcreate_pekerjaan') }}",
                });
            })
        </script>
    @endif


    @if (Session::has('status_destroy_pekerjaan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_pekerjaan') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif
    <script>
        let bulan_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
            'November', 'Desember'
        ];
    </script>
    <div class="pagetitle">
        <h1>Uraian Pekerjaan dan Biaya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Uraian Pekerjaan dan Biaya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <h4 class="card-title">Pilih Kegiatan</h4>
                            <div class="alert alert-info">
                                <b><i class="bi bi-info-circle-fill"></i>&emsp;Info: </b>Untuk bisa menambahkan pekerjaan,
                                anda harus memilih tahun, bulan, tim dan anggota
                                kegiatan terlebih dahulu
                            </div>
                            <div>
                                <!-- General Form Elements -->
                                <form action="" method="GET" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label id="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                        <div class="col-sm-8">
                                            <select name="tahun" class="filter @error('tahun') is-invalid @enderror">
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
                                            <select name="bulan" class="filter @error('bulan') is-invalid @enderror">
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
                                            <select name="tim" class="filter @error('tim') is-invalid @enderror">
                                                <option value="" disable selected>Choose Tim</option>
                                            </select>
                                            @error('tim')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label id="kegiatan" class="col-sm-2 col-form-label">Kegiatan</label>
                                        <div class="col-sm-8">
                                            <select name="kegiatan" class="filter @error('kegiatan') is-invalid @enderror">
                                                <option value="" disable selected>Choose Kegiatan</option>
                                            </select>
                                            @error('kegiatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label id="anggota" class="col-sm-2 col-form-label">Anggota</label>
                                        <div class="col-sm-8">
                                            <select name="anggota" class="filter @error('anggota') is-invalid @enderror">
                                                <option value="" disable selected>Choose Anggota</option>
                                            </select>
                                            @error('anggota')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </form><!-- End General Form Elements -->
                            </div>
                        </div>
                        <div class="container" {{-- style="display: none;" --}}>
                            <div>
                                <h4 class="card-title">Uraian Pekerjaan dan Biaya</h4>
                                @can('for-admin-ketim')
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <a>
                                                    {{-- <form action="{{ url('') }}" id="add-pekerjaan2"
                                                    style="display: none;">
                                                    <input type="submit" value="Go to Google" />
                                                </form> --}}
                                                    <button type="button" class="btn btn-warning pull-left" id="add-pekerjaan2"
                                                        style="display: none;">
                                                        <i class="bi bi-file-earmark-excel"></i>&nbsp;&nbsp;<span>Template
                                                            Import</span>
                                                    </button>
                                                    <button type="button" class="btn btn-success pull-left"
                                                        {{-- data-toggle="modal" data-target="#pekerjaanModal"  --}} id="add-pekerjaan3" style="display: none;">
                                                        <i class="bi bi-file-earmark-excel-fill"></i>&nbsp;&nbsp;<span>Upload
                                                            Pekerjaan</span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary pull-left" data-toggle="modal"
                                                        data-target="#pekerjaanModal" id="add-pekerjaan" style="display: none;">
                                                        <i class="bi bi-person-plus"></i>&nbsp;&nbsp;<span>Tambah
                                                            Pekerjaan</span>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="pekerjaanModal" tabindex="-1" role="dialog"
                                            aria-labelledby="pekerjaanModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pekerjaanModalLabel">Tambah Uraian
                                                            Pekerjaan
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" enctype="multipart/form-data"
                                                            id="formAddPekerjaan" class="needs-validation" novalidate>
                                                            @csrf
                                                            <div class="row">
                                                                <label for="tahunStatic"
                                                                    class="col-sm-3 col-form-label disabled">Tahun :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="tahunStatic" type="text" readonly
                                                                        id="tahunStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="bulanStatic"
                                                                    class="col-sm-3 col-form-label disabled">Bulan :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="bulanStatic" type="text" readonly
                                                                        id="bulanStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="kegiatanStatic"
                                                                    class="col-sm-3 col-form-label disabled">Kegiatan :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="kegiatanStatic" type="text" readonly
                                                                        id="kegiatanStatic" class="form-control-plaintext"
                                                                        value="" disabled>
                                                                    <input name="kegiatanVal" readonly style="display: none;"
                                                                        id="kegiatanVal" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="anggotaStatic"
                                                                    class="col-sm-3 col-form-label disabled">Anggota :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="anggotaStatic" type="text" readonly
                                                                        id="anggotaStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                    <input name="anggotaVal" readonly style="display: none;"
                                                                        class="form-control-plaintext" value=""
                                                                        id="anggotaVal" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 form-group required">
                                                                <label for="uraian_pekerjaan"
                                                                    class="col-sm-3 col-form-label control-label disabled">Uraian
                                                                    Pekerjaan :</label>
                                                                <div class="col-sm-8">
                                                                    <textarea class="form-control @error('uraian_pekerjaan') is-invalid @enderror" value="{{ old('uraian_pekerjaan') }}"
                                                                        name="uraian_pekerjaan" id="uraian_pekerjaan" rows="3"></textarea>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 form-group required">
                                                                <div class="col-md-3">
                                                                    <label for="target"
                                                                        class="form-label control-label computed_field">Target
                                                                        :</label>
                                                                    <input type="number"
                                                                        class="form-control @error('target') is-invalid @enderror"
                                                                        value="{{ old('target') }}" name="target"
                                                                        id="target" min="0" max="999999">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                                <div class="col-md-3 form-group required">
                                                                    <label for="satuan"
                                                                        class="form-label control-label">Satuan :</label>
                                                                    <select name="satuan" id="satuan"
                                                                        class="form-control @error('satuan') is-invalid @enderror"
                                                                        value="{{ old('satuan') }}">
                                                                        <option value="Dokumen">Dokumen</option>
                                                                        <option value="Kunjungan">Kunjungan</option>
                                                                        <option value="Kegiatan">Kegiatan</option>
                                                                        <option value="BS">BS</option>
                                                                        <option value="SLS">SLS</option>
                                                                        <option value="Lainnya">Lainnya</option>
                                                                    </select>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                                <div class="col-md-4" style="display: none;"
                                                                    id="satuan_lain_container">
                                                                    <label for="satuan_lain"
                                                                        class="form-label control-label">Satuan
                                                                        Lainnya:</label>
                                                                    <input type="text"
                                                                        class="form-control @error('satuan_lain') is-invalid @enderror"
                                                                        value="{{ old('satuan_lain') }}" id="satuan_lain"
                                                                        name="satuan_lain">
                                                                    <div class="invalid-feedback">Please input bulan</div>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="input-group col">
                                                                    <label for="harga_satuan"
                                                                        class="col-sm-3 col-form-label disabled">Harga Per
                                                                        Satuan :</label>
                                                                    <span class="input-group-text">Rp</span>
                                                                    <input type="text"
                                                                        class="form-control price computed_field @error('harga_satuan') is-invalid @enderror"
                                                                        value="{{ old('harga_satuan') }}" id="harga_satuan"
                                                                        name="harga_satuan"
                                                                        aria-describedby="inputGroupPrepend">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                    <input type="text"
                                                                        class="form-control price computed_field @error('harga_satuanVal') is-invalid @enderror"
                                                                        value="{{ old('harga_satuanVal') }}"
                                                                        id="harga_satuanVal" name="harga_satuanVal"
                                                                        aria-describedby="inputGroupPrepend"
                                                                        style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="input-group col">
                                                                    <label for="harga"
                                                                        class="col-sm-3 col-form-label disabled">Total Biaya
                                                                        :</label>
                                                                    <span class="input-group-text">Rp</span>
                                                                    <input type="text" class="form-control price"
                                                                        id="harga" name="harga"
                                                                        aria-describedby="inputGroupPrepend" disabled>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        {{-- <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button> --}}
                                                        <button class="btn btn-primary" type="submit" {{-- form="formAddPekerjaan"  --}}
                                                            value="Submit" id="saveBtn">Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                                @can('for-admin-ketim')
                                    <div class="row mb-3" id="edit-pekerjaan">
                                        <div class="modal fade" id="editpekerjaanModal" tabindex="-1" role="dialog"
                                            aria-labelledby="editpekerjaanModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editpekerjaanModalLabel">Edit Uraian
                                                            Pekerjaan
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" enctype="multipart/form-data"
                                                            id="formEditPekerjaan" class="needs-validation" novalidate>
                                                            @csrf
                                                            <div class="row">
                                                                <label for="edit_tahunStatic"
                                                                    class="col-sm-3 col-form-label disabled">Tahun :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="edit_tahunStatic" type="text" readonly
                                                                        id="edit_tahunStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="edit_bulanStatic"
                                                                    class="col-sm-3 col-form-label disabled">Bulan :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="edit_bulanStatic" type="text" readonly
                                                                        id="edit_bulanStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="edit_kegiatanStatic"
                                                                    class="col-sm-3 col-form-label disabled">Kegiatan :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="edit_kegiatanStatic" type="text" readonly
                                                                        id="edit_kegiatanStatic"
                                                                        class="form-control-plaintext" value=""
                                                                        disabled>
                                                                    <input name="edit_kegiatanVal" readonly
                                                                        style="display: none;" id="edit_kegiatanVal"
                                                                        class="form-control-plaintext" value=""
                                                                        disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <label for="edit_anggotaStatic"
                                                                    class="col-sm-3 col-form-label disabled">Anggota :</label>
                                                                <div class="col-sm-8">
                                                                    <input name="edit_anggotaStatic" type="text" readonly
                                                                        id="edit_anggotaStatic" class="form-control-plaintext"
                                                                        value="" disabled />
                                                                    <input name="edit_anggotaVal" readonly
                                                                        style="display: none;" class="form-control-plaintext"
                                                                        value="" id="edit_anggotaVal" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 form-group required">
                                                                <label for="edit_uraian_pekerjaan"
                                                                    class="col-sm-3 col-form-label control-label disabled">Uraian
                                                                    Pekerjaan :</label>
                                                                <div class="col-sm-8">
                                                                    <textarea class="form-control @error('edit_uraian_pekerjaan') is-invalid @enderror"
                                                                        value="{{ old('edit_uraian_pekerjaan') }}" name="edit_uraian_pekerjaan" id="edit_uraian_pekerjaan"
                                                                        rows="3"></textarea>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <textarea class="form-control @error('edit_uraian_pekerjaan') is-invalid @enderror"
                                                                        value="{{ old('edit_uraian_pekerjaan') }}" name="edit_uraian_pekerjaanVal" id="edit_uraian_pekerjaanVal"
                                                                        rows="3" style="display: none;"></textarea>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 form-group required">
                                                                <div class="col-md-3">
                                                                    <label for="edit_target"
                                                                        class="form-label control-label computed_field">Target
                                                                        :</label>
                                                                    <input type="number"
                                                                        class="form-control @error('edit_target') is-invalid @enderror"
                                                                        value="{{ old('edit_target') }}" name="edit_target"
                                                                        id="edit_target" min="0" max="999999">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                                <div class="col-md-3 form-group required">
                                                                    <label for="edit_satuan"
                                                                        class="form-label control-label">Satuan :</label>
                                                                    <select name="edit_satuan" id="edit_satuan"
                                                                        class="form-control @error('edit_satuan') is-invalid @enderror"
                                                                        value="{{ old('edit_satuan') }}">
                                                                        <option value="Dokumen">Dokumen</option>
                                                                        <option value="Kunjungan">Kunjungan</option>
                                                                        <option value="Kegiatan">Kegiatan</option>
                                                                        <option value="BS">BS</option>
                                                                        <option value="SLS">SLS</option>
                                                                        <option value="Lainnya">Lainnya</option>
                                                                    </select>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                                <div class="col-md-4" style="display: none;"
                                                                    id="edit_satuan_lain_container">
                                                                    <label for="edit_satuan_lain"
                                                                        class="form-label control-label">Satuan
                                                                        Lainnya:</label>
                                                                    <input type="text"
                                                                        class="form-control @error('edit_satuan_lain') is-invalid @enderror"
                                                                        value="{{ old('edit_satuan_lain') }}"
                                                                        id="edit_satuan_lain" name="edit_satuan_lain">
                                                                    <div class="invalid-feedback">Please input bulan</div>
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="input-group col">
                                                                    <label for="edit_harga_satuan"
                                                                        class="col-sm-3 col-form-label disabled">Harga Per
                                                                        Satuan :</label>
                                                                    <span class="input-group-text">Rp</span>
                                                                    <input type="text"
                                                                        class="form-control edit_price computed_field @error('edit_harga_satuan') is-invalid @enderror"
                                                                        value="{{ old('edit_harga_satuan') }}"
                                                                        id="edit_harga_satuan" name="edit_harga_satuan"
                                                                        aria-describedby="inputGroupPrepend">
                                                                    <div class="valid-feedback">Looks good!</div>
                                                                    <input type="text"
                                                                        class="form-control edit_price computed_field @error('edit_harga_satuanVal') is-invalid @enderror"
                                                                        value="{{ old('edit_harga_satuanVal') }}"
                                                                        id="edit_harga_satuanVal" name="edit_harga_satuanVal"
                                                                        aria-describedby="inputGroupPrepend"
                                                                        style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="input-group col">
                                                                    <label for="edit_harga"
                                                                        class="col-sm-3 col-form-label disabled">Total Biaya
                                                                        :</label>
                                                                    <span class="input-group-text">Rp</span>
                                                                    <input type="text" class="form-control edit_price"
                                                                        id="edit_harga" name="edit_harga"
                                                                        aria-describedby="inputGroupPrepend" disabled>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        {{-- <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button> --}}
                                                        <button class="btn btn-primary" type="submit" {{-- form="formAddPekerjaan"  --}}
                                                            value="Submit" id="editBtn">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan

                            </div>
                            <!-- Table with stripped rows -->

                            <div>
                                <button style="margin-bottom: 15px; display:none;"
                                    class="btn btn-danger delete_selected btn-sm"><i
                                        class="bi bi-trash"></i>&nbsp;&nbsp;Delete
                                    selected row(s)</button>
                            </div>
                            <table id="pekerjaan-table" class="display" style="width:100%">
                                <thead {{-- bgcolor="#012970" --}}>
                                    <tr>
                                        <th scope="col">Select All<br><input type="checkbox" id="master"></th>
                                        <th>Periode</th>
                                        <th>Tim</th>
                                        <th>Kegiatan</th>
                                        <th>ID Anggota</th>
                                        <th>Nama Anggota</th>
                                        <th>Uraian Pekerjaan</th>
                                        <th>Target</th>
                                        <th>Harga Per Satuan</th>
                                        <th>Biaya</th>
                                        <th>Update Terakhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
                <!-- Position it -->
                <div style="position: absolute; top: 0; right: 0;">

                    <!-- Then put toasts within -->
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            {{-- <img src="..." class="rounded mr-2" alt="..."> --}}
                            <strong class="mr-auto">Bootstrap</strong>
                            <small class="text-muted">just now</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            See? Just like this.
                        </div>
                    </div>

                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            {{-- <img src="..." class="rounded mr-2" alt="..."> --}}
                            <strong class="mr-auto">Bootstrap</strong>
                            <small class="text-muted">2 seconds ago</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            Heads up, toasts will stack automatically
                        </div>
                    </div>
                </div>
            </div>


    </section>

    <script>
        let btn = null;

        $('#add-pekerjaan3').click(function() {
            var id_keg = $('select[name="kegiatan"]').val();
            document.location.href = 'import-pekerjaan/id_keg=' + id_keg;
        });

        $('#add-pekerjaan2').click(function() {
            document.location.href = 'export/templatepekerjaan/id_keg=' + $('select[name="kegiatan"]').val()
        });

        $(document).ready(function() {
            // Parse cookie to variablee.log(param);
            $cookie_keg = null;
            $cookie_pek = null;
            try {
                if ($.cookie("cookie_keg")) {
                    $cookie_keg = $.cookie("cookie_keg").split(',');
                    console.log($cookie_keg);
                }

            } catch (error) {
                console.log("error");
                delete $cookie_keg;
            }
            try {


                if ($.cookie("cookie_pek")) {
                    console.log("inidia", $.cookie("cookie_pek"));
                    $cookie_pek = JSON.parse($.cookie("cookie_pek"));
                    console.log($cookie_pek);
                }
            } catch (error) {

                console.log("error");
                delete $cookie_pek;
            }

            $('select[name="tahun"]').select2({
                allowClear: true,
                placeholder: "Pilih Tahun",
                width: "100%",
            });
            $('select[name="bulan"]').select2({
                allowClear: true,
                placeholder: "Pilih Bulan",
                width: "100%",
            });
            $('select[name="tim"]').select2({
                allowClear: true,
                placeholder: "Pilih Tim",
                width: "100%",
            });
            $('select[name="kegiatan"]').select2({
                allowClear: true,
                placeholder: "Pilih Kegiatan",
                width: "100%",
            });
            $('select[name="anggota"]').select2({
                allowClear: true,
                placeholder: "Pilih Anggota",
                width: "100%",
            });
            $('select[name="satuan"]').select2({
                allowClear: false,
                dropdownParent: $("#pekerjaanModal"),
                placeholder: "Pilih Satuan",
                width: "100%",
            });
            $('select[name="edit_satuan"]').select2({
                allowClear: false,
                dropdownParent: $("#editpekerjaanModal"),
                placeholder: "Pilih Satuan",
                width: "100%",
            });

            // ELEMENT
            let tahun = $('select[name="tahun"]');
            let bulan = $('select[name="bulan"]');
            let tim = $('select[name="tim"]');
            let kegiatan = $('select[name="kegiatan"]');
            let anggota = $('select[name="anggota"]');
            tahun.val(2024).trigger("change");
            bulan.val("00").trigger("change");
            tim.val("00").trigger("change");
            kegiatan.val("00").trigger("change");
            anggota.val("00").trigger("change");
            let t = tahun.val();
            let b = bulan.val();
            let ti = tim.val();
            let k = kegiatan.val();
            let a = anggota.val();
            let r = '00';

            //HIDE ELEMENTS CONTAINER
            // $('.filter').on('change', function() {
            //     if (($('select[name="tahun"]').val()) && ($('select[name="bulan"]').val()) && (
            //             $('select[name="kegiatan"]').val())) {

            //         $('.container').show()
            //     } else {
            //         $('.container').hide()
            //     }
            // })

            // HIDE ELEMENT BUTTON
            $('select[name="kegiatan"]').on("change", function() {
                if ($('select[name="kegiatan"]').val()) {
                    $("#add-pekerjaan2").show();
                    $("#add-pekerjaan3").show();
                } else {
                    $("#add-pekerjaan2").hide();
                    $("#add-pekerjaan3").hide();
                }
            });


            $('select[name="anggota"]').on("change", function() {
                if ($('select[name="anggota"]').val()) {
                    $("#add-pekerjaan").show();
                    // ADD TEXT
                    $('input[name="tahunStatic"]').attr(
                        "value",
                        $('select[name="tahun"]').find(":selected").val()
                    );

                    $('input[name="bulanStatic"]').attr(
                        "value",
                        bulan_arr[$('select[name="bulan"]').find(":selected").val() - 1]
                    );

                    $('input[name="kegiatanStatic"]').attr(
                        "value",
                        $('select[name="kegiatan"]').find(":selected").text()
                    );
                    $('input[name="kegiatanVal"]').attr(
                        "value",
                        $('select[name="kegiatan"]').find(":selected").val()
                    );

                    $('input[name="anggotaStatic"]').attr(
                        "value",
                        $('select[name="anggota"]').find(":selected").text()
                    );
                    $('input[name="anggotaVal"]').attr(
                        "value",
                        $('select[name="anggota"]').find(":selected").val()
                    );
                } else {
                    $("#add-pekerjaan").hide();
                }
            });

            $('select[name="satuan"]').on("change", function() {
                if ($('select[name="satuan"]').val() == "Lainnya") {
                    $("#satuan_lain_container").show();
                } else {
                    $("#satuan_lain_container").hide();
                }
            });

            $('#editpekerjaanModal').on('show.bs.modal', function(e) {
                btn = $(e.relatedTarget)[0];
                let edit_tahun = btn.getAttribute('tahun');
                let edit_bulan = btn.getAttribute('bulan');
                let edit_id_keg = btn.getAttribute('id_keg');
                let edit_kegiatan = btn.getAttribute('kegiatan');
                let edit_id_anggota = btn.getAttribute('id_anggota');
                let edit_name = btn.getAttribute('name');
                let edit_id_pekerjaan = btn.getAttribute('id_pekerjaan');
                let edit_pekerjaan = btn.getAttribute('uraian_pekerjaan');
                let edit_target = btn.getAttribute('target');
                let edit_satuan = btn.getAttribute('satuan');
                let edit_harga_satuan = btn.getAttribute('harga_satuan');
                console.log('hola!', edit_id_keg);


                // ADD TEXT
                $('input[name="edit_tahunStatic"]').val(edit_tahun);
                $('input[name="edit_bulanStatic"]').val(bulan_arr[edit_bulan - 1]);
                $('input[name="edit_kegiatanStatic"]').val(edit_kegiatan);
                $('input[name="edit_kegiatanVal"]').val(edit_id_keg);
                $('input[name="edit_anggotaStatic"]').val(edit_name);
                $('input[name="edit_anggotaVal"]').val(edit_id_anggota);
                $('textarea[name="edit_uraian_pekerjaan"]').val(edit_pekerjaan);
                $('textarea[name="edit_uraian_pekerjaanVal"]').val(edit_id_pekerjaan);
                $('select[name="edit_satuan"]').val(edit_satuan).trigger('change');
                if ($('select[name="edit_satuan"]').val() == null) {
                    $('select[name="edit_satuan"]').val("Lainnya").trigger('change');
                    $("#edit_satuan_lain_container").show();
                    $('input[name="edit_satuan_lain"]').val(edit_satuan);
                }
                $('input[name="edit_harga_satuan"]').val(parseFloat(edit_harga_satuan));
                $('input[name="edit_target"]').val(parseFloat(edit_target));
                console.log("ini biang keroknya", edit_harga_satuan);
                // Do magical things
                let angka = stringtoFloat($("#edit_harga_satuan").val());
                $("#edit_harga").val(function(index, value) {
                    return (angka * $("#edit_target").val())
                        .toString()
                        .replace(".", ",");
                });
                console.log("iya kok", stringtoFloat($("#edit_harga_satuan").val()))

                // Save the real value
                $("#edit_harga_satuanVal").attr(
                    "value",
                    stringtoFloat($("#edit_harga_satuan").val())
                );

                if ($("#edit_harga_satuanVal").first().attr("value") == "NaN") {
                    $("#edit_harga_satuanVal").removeAttr("value");
                }
                $(".edit_price").val(function(index, value) {
                    return value
                        .replace(/(?!\,)\D/g, "")
                        .replace(/(?<=\,.*)\,/g, "")
                        .replace(/(?<=\,\d\d).*/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                $('select[name="edit_satuan"]').on("change", function() {
                    if ($('select[name="edit_satuan"]').val() == "Lainnya") {
                        $("#edit_satuan_lain_container").show();
                    } else {
                        $("#edit_satuan_lain_container").hide();
                    }
                });
            })


            // FILTER TAHUN INIT
            $.ajax({
                url: "filterPekerjaan/role=" + r + "/tahun=" + t,
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                dataType: "json",
                success: function(data) {
                    try {
                        if ($cookie_pek["tahun"] != new Date().getFullYear()) {
                            $('select[name="tahun"]')
                                .val($cookie_pek["tahun"])
                                .trigger("change");
                        }
                    } catch (error) {
                        delete $cookie_pek;
                    }

                    try {
                        if (!isNaN($cookie_keg[0])) {
                            if ($cookie_keg[0] != new Date().getFullYear()) {
                                $('select[name="tahun"]')
                                    .val($cookie_keg[0])
                                    .trigger("change");
                            }
                        }
                    } catch (error) {
                        delete $cookie_keg;
                    }

                    if (tahun.val()) {
                        t = tahun.val();
                    } else {
                        t = "00";
                    }
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').append('<option value=""></option>');
                    $.each(data, function(key, bulan, $bulan_arr) {
                        $('select[name="bulan"]').append(
                            "<option value=" +
                            bulan.bulan +
                            ">" +
                            bulan_arr[bulan.bulan - 1] +
                            "</option>"
                        );
                    });
                    // cookie
                    try {
                        if ($cookie_pek) {
                            $('select[name="bulan"]')
                                .val($cookie_pek["bulan"])
                                .trigger("change");
                        }
                    } catch (error) {
                        delete $cookie_pek;
                    }

                    try {
                        if (!isNaN($cookie_keg[0])) {
                            $('select[name="bulan"]')
                                .val($cookie_keg[1])
                                .trigger("change");
                        }
                    } catch (error) {
                        delete $cookie_keg;
                    }
                },
            });

            // FILTER TAHUN
            $('select[name="tahun"]').on("change", function() {
                if (tahun.val()) {
                    t = tahun.val();
                    $.ajax({
                        url: "filterPekerjaan/role=" + r + "/tahun=" + t,
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        success: function(data) {
                            $('select[name="bulan"]').empty();
                            $('select[name="bulan"]').append(
                                '<option value=""></option>'
                            );
                            $.each(data, function(key, bulan, $bulan_arr) {
                                $('select[name="bulan"]').append(
                                    "<option value=" +
                                    bulan.bulan +
                                    ">" +
                                    bulan_arr[bulan.bulan - 1] +
                                    "</option>"
                                );
                            });
                            // cookie
                            try {
                                if ($cookie_pek) {
                                    $('select[name="bulan"]')
                                        .val($cookie_pek["bulan"])
                                        .trigger("change");
                                }
                            } catch (error) {
                                delete $cookie_pek;
                            }

                            try {
                                if (!isNaN($cookie_keg[0])) {
                                    if ($cookie_keg[0] != new Date().getFullYear()) {
                                        $('select[name="bulan"]').val($cookie_keg[1]).trigger(
                                            'change');
                                    }
                                }
                            } catch (error) {
                                delete $cookie_keg;
                            }
                        },
                    });
                } else {
                    t = "00";
                    $('select[name="bulan"]').empty();
                    $('select[name="bulan"]').val("").trigger("change");
                }
            });

            // FILTER BULAN
            $('select[name="bulan"]').on("change", function() {
                if (bulan.val()) {
                    b = bulan.val();
                    $.ajax({
                        url: "filterPekerjaan/role=" + r + "/tahun=" + t + "/bulan=" + b,
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        success: function(data) {
                            $('select[name="tim"]').empty();
                            $('select[name="tim"]').append(
                                '<option value=""></option>'
                            );
                            $.each(data, function(key, tim) {
                                $('select[name="tim"]').append(
                                    "<option value=" +
                                    tim.subject_meter +
                                    ">" +
                                    tim.tim +
                                    "</option>"
                                );
                            });
                            // cookie
                            try {
                                if ($cookie_pek) {
                                    $('select[name="tim"]')
                                        .val($cookie_pek["id_tim"])
                                        .trigger("change");
                                }
                            } catch (error) {
                                delete $cookie_pek;
                            }

                            try {
                                if (!isNaN($cookie_keg[0])) {
                                    $('select[name="tim"]')
                                        .val($cookie_keg[2])
                                        .trigger("change");
                                }
                            } catch (error) {
                                delete $cookie_keg;
                            }
                        },
                    });
                } else {
                    b = "00";
                    $('select[name="tim"]').empty();
                    $('select[name="tim"]').val("").trigger("change");
                }
            });

            // FILTER TIM
            $('select[name="tim"]').on("change", function() {
                if (tim.val()) {
                    ti = tim.val();
                    $.ajax({
                        url: "filterPekerjaan/role=" + r + "/tahun=" +
                            t +
                            "/bulan=" +
                            b +
                            "/tim=" +
                            ti,
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        success: function(data) {
                            $('select[name= "kegiatan"]').empty();
                            $('select[name= "kegiatan"]').append(
                                '<option value=""></option>'
                            );
                            $.each(data, function(key, kegiatan) {
                                $('select[name= "kegiatan"]').append(
                                    "<option value=" +
                                    kegiatan.id_keg +
                                    ">" +
                                    kegiatan.kegiatan +
                                    "</option>"
                                );
                            });
                            // cookie
                            try {
                                if ($cookie_pek) {
                                    $('select[name="kegiatan"]')
                                        .val($cookie_pek["id_keg"])
                                        .trigger("change");
                                }
                            } catch (error) {
                                delete $cookie_pek;
                            }
                            try {
                                if (!isNaN($cookie_keg[0])) {
                                    $('select[name="kegiatan"]')
                                        .val($cookie_keg[3])
                                        .trigger("change");
                                    // delete cookie
                                    delete $cookie_keg;
                                    $.cookie("cookie_keg", null, {
                                        path: "/",
                                    });
                                }
                            } catch (error) {
                                delete $cookie_keg;
                            }
                        },
                    });
                } else {
                    ti = "00";
                    $('select[name="kegiatan"]').empty();
                    $('select[name="kegiatan"]').val("").trigger("change");
                }
            });

            // FILTER KEGIATAN
            $('select[name="kegiatan"]').on("change", function() {
                if (kegiatan.val()) {
                    k = kegiatan.val();
                    $.ajax({
                        url: "filterPekerjaan/role=" + r + "/kegiatan=" + k,
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        success: function(data) {
                            $('select[name="anggota"]').empty();
                            $('select[name="anggota"]').append(
                                '<option value=""></option>'
                            );
                            $.each(data, function(key, anggota) {
                                $('select[name="anggota"]').append(
                                    "<option value=" +
                                    anggota.id_anggota +
                                    ">" +
                                    anggota.nama + " (" + anggota.id_anggota + ")" +
                                    "</option>"
                                );
                            });
                            // cookie
                            try {
                                $('select[name="anggota"]')
                                    .val($cookie_pek["id_anggota"])
                                    .trigger("change");
                                // delete cookie
                                delete $cookie_pek;
                                $.cookie("cookie_pek", null, {
                                    path: "/",
                                });
                            } catch (error) {
                                delete $cookie_pek;
                            }

                            try {
                                if ($cookie_keg) {
                                    // delete cookie
                                    delete $cookie_keg;
                                    $.cookie("cookie_keg", null, {
                                        path: "/",
                                    });
                                }
                            } catch (error) {
                                delete $cookie_keg;
                            }
                        },
                    });
                } else {
                    k = "00";
                    $('select[name="anggota"]').empty();
                    $('select[name="anggota"]').val("").trigger("change");
                }
            });
            var myCallback = function(settings, json) {
                $('.sub_chk').on('click', function() {
                    if ($(".sub_chk:checked").length > 0) {
                        $('.delete_selected').show()
                    } else {
                        $('.delete_selected').hide()
                    }
                })
                $('#master').on('click', function() {
                    if ($(".sub_chk:checked").length > 0) {
                        $('.delete_selected').show()
                    } else {
                        $('.delete_selected').hide()
                    }
                })
            }

            var table = $("#pekerjaan-table").DataTable({
                order: [],
                scrollX: "100%",
                responsive: true,
                autowidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "view-pekerjaan",
                    data: function(d) {
                        d.tahun_filter = $('select[name="tahun"]').val();
                        d.bulan_filter = $('select[name="bulan"]').val();
                        d.tim_filter = $('select[name="tim"]').val();
                        d.kegiatan_filter = $('select[name="kegiatan"]').val();
                        d.anggota_filter = $('select[name="anggota"]').val();


                        return d;
                    },
                },
                columnDefs: [{
                    targets: 0, // your case first column
                    className: "text-center",
                    width: "4%",
                    orderable: false,
                }],
                columns: [{
                        data: "select_all",
                        name: "select_all",
                    }, {
                        data: "periode",
                        name: "periode",
                    },
                    {
                        data: "tim",
                        name: "tim",
                    },
                    {
                        data: "kegiatan",
                        name: "kegiatan",
                    },
                    {
                        data: "id_anggota",
                        name: "id_anggota",
                    },
                    {
                        data: "nama",
                        name: "nama",
                    },
                    {
                        data: "uraian_pekerjaan",
                        name: "uraian_pekerjaan",
                    },
                    {
                        data: "target",
                        name: "target",
                    },
                    {
                        data: "harga_satuan",
                        name: "harga_satuan",
                    },
                    {
                        data: "biaya",
                        name: "biaya",
                    },
                    {
                        data: "update_terakhir",
                        name: "update_terakhir",
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                    },
                ],
                order: [
                    [10, 'desc']
                ],


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
                initComplete: myCallback


            });

            $("[data-toggle=tooltip]").tooltip();

            // Filter
            $(".filter").on("change", function() {
                table.ajax.reload(null, false);
                table.ajax.reload(myCallback);
            });



            // SENDS PEKERJAAN
            $("#saveBtn").on("click", function(e) {
                $("#formAddPekerjaan").find(".is-invalid").removeClass("is-invalid");
                $("#formAddPekerjaan").find(".invalid-feedback").remove();

                $.ajax({
                    type: "POST",
                    url: "store-pekerjaan",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kegiatanVal: $("#kegiatanVal").first().attr("value"),
                        anggotaVal: $("#anggotaVal").first().attr("value"),
                        uraian_pekerjaan: $("#uraian_pekerjaan").val(),
                        target: $("#target").first().val(),
                        satuan: $("#satuan").first().val(),
                        satuan_lain: $("#satuan_lain").first().val(),
                        harga_satuan: $("#harga_satuanVal").first().attr("value"),
                    },
                    async: false,
                    success: function(response, status) {
                        console.log(response); //log
                        $.cookie("cookie_pek", response); //set cookie
                        Swal.fire({
                                icon: "success",
                                title: "Sukses",
                                text: "Pekerjaan berhasil ditambahkan",
                                showConfirmButton: false,
                                timer: 1500,
                            })
                            .then(function() {
                                window.location = "view-pekerjaan";
                            });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        const data = XMLHttpRequest.responseJSON;
                        let html = "";
                        if (data.errors) {
                            console.log(data.errors);
                            $.each(data.errors, function(key, value) {
                                $("#" + key)
                                    .addClass("is-invalid")
                                    .after(
                                        '<div class="invalid-feedback">' +
                                        value +
                                        "</div>"
                                    );
                            });
                        } else if (data.message) {
                            html = `<li>${data.message}</li>`;
                            Swal.fire({
                                icon: "error",
                                title: "GALAT",
                                html: "Gagal merekam store data Anda. ERR=" +
                                    `<ul>${html}</ul>`,
                            });
                        }
                    },
                });
                // return false;
            });
            // $('form').find('.help-block').remove();
            // $('form').find('.form-group').removeClass('has-error');

            // EDITPEKERJAAN
            $("#editBtn").on("click", function(e) {
                $("#formEditPekerjaan").find(".is-invalid").removeClass("is-invalid");
                $("#formEditPekerjaan").find(".invalid-feedback").remove();

                $.ajax({
                    type: "POST",
                    url: "update/pekerjaan-" + $('#edit_uraian_pekerjaanVal').val(),
                    data: {
                        _token: "{{ csrf_token() }}",
                        kegiatanVal: $("#edit_kegiatanVal").val(),
                        anggotaVal: $("#edit_anggotaVal").val(),
                        uraian_pekerjaan: $("#edit_uraian_pekerjaan").val(),
                        target: $("#edit_target").val(),
                        satuan: $("#edit_satuan").val(),
                        satuan_lain: $("#edit_satuan_lain").val(),
                        harga_satuan: $("#edit_harga_satuanVal").first().attr('value'),
                    },
                    async: false,
                    success: function(response, status) {
                        console.log(response); //log
                        $.cookie("cookie_pek", response); //set cookie
                        Swal.fire({
                                icon: "success",
                                title: "Sukses",
                                text: "Pekerjaan berhasil diedit",
                                showConfirmButton: false,
                                timer: 1500,
                            })
                            .then(function() {
                                window.location = "view-pekerjaan";
                            });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        const data = XMLHttpRequest.responseJSON;
                        let html = "";
                        if (data.errors) {
                            console.log(data.errors);
                            $.each(data.errors, function(key, value) {
                                $("#edit_" + key)
                                    .addClass("is-invalid")
                                    .after(
                                        '<div class="invalid-feedback">' +
                                        value +
                                        "</div>"
                                    );
                            });
                        } else if (data.message) {
                            html = `<li>${data.message}</li>`;
                            Swal.fire({
                                icon: "error",
                                title: "GALAT",
                                html: "Gagal merekam editan Anda. ERR=" +
                                    `<ul>${html}</ul>`,
                            });
                        }
                    },
                });
                // return false;
            });



            // BULK DELETE
            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('.delete_selected').on('click', function(e) {
                var checkedRows = [];
                $(".sub_chk:checked").each(function() {
                    checkedRows.push($(this).attr('data-id'));
                });
                var url = "destroy/selectedPekerjaan";
                console.log(checkedRows.length);
                if (checkedRows.length <= 0) {
                    Swal.fire({
                        title: "Ups",
                        text: "Mohon centang terlebih dahulu data yang ingin dihapus :)",
                        icon: "warning"
                    });
                } else {
                    //var join_selected_values = checkedRows.join(",");
                    Swal.fire({
                            title: "Yakin?",
                            text: "Anda ingin menghapus " + checkedRows.length +
                                " baris data pekerjaan anggota?",
                            icon: "warning",
                            showCancelButton: true,
                        })
                        .then((willDelete) => {
                            if (willDelete.isConfirmed) {
                                $.get(url, {
                                    row_ids: checkedRows
                                }, function(data) {
                                    if (data.code == 1) {
                                        Swal.fire({
                                                icon: "success",
                                                title: "Sukses",
                                                text: data
                                                    .status_destroy_selectedPekerjaan,
                                                showConfirmButton: false,
                                                timer: 1500,
                                            })
                                            .then(function(result) {
                                                window.location = "view-pekerjaan"
                                            })
                                    }
                                })
                            }
                        })
                }
            });
        });


        // <------ Document ready ends here

        // <------------ INPUT HARGA ------------>
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"); // $& means the whole matched string
        }

        function replaceAll(str, find, replace) {
            return str.replace(new RegExp(escapeRegExp(find), "g"), replace);
        }

        function stringtoFloat(price) {
            return parseFloat(replaceAll(replaceAll(price, ".", ""), ",", "."));
        }
        // function stringtoFloat2(price) {
        //     return parseFloat(replaceAll(replaceAll(price, ",", "."), ".", ","));
        // }

        $("#target, #harga_satuan").on("input", function(event) {
            // Do magical things
            let angka = stringtoFloat($("#harga_satuan").val());
            $("#harga").val(function(index, value) {
                return (angka * parseFloat($("#target").val()))
                    .toString()
                    .replace(".", ",");
            });

            // Save the real value
            $("#harga_satuanVal").attr(
                "value",
                stringtoFloat($("#harga_satuan").val())
            );
            if ($("#harga_satuanVal").first().attr("value") == "NaN") {
                $("#harga_satuanVal").removeAttr("value");
            }
            $(".price").val(function(index, value) {
                return value
                    .replace(/(?!\,)\D/g, "")
                    .replace(/(?<=\,.*)\,/g, "")
                    .replace(/(?<=\,\d\d).*/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        });

        $("#edit_target, #edit_harga_satuan").on("input", function(event) {
            // Do magical things
            let angka = stringtoFloat($("#edit_harga_satuan").val());
            $("#edit_harga").val(function(index, value) {
                return (angka * $("#edit_target").val())
                    .toString()
                    .replace(".", ",");
            });
            console.log("iya kok", stringtoFloat($("#edit_harga_satuan").val()))

            // Save the real value
            $("#edit_harga_satuanVal").attr(
                "value",
                stringtoFloat($("#edit_harga_satuan").val())
            );

            if ($("#edit_harga_satuanVal").first().attr("value") == "NaN") {
                $("#edit_harga_satuanVal").removeAttr("value");
            }
            $(".edit_price").val(function(index, value) {
                return value
                    .replace(/(?!\,)\D/g, "")
                    .replace(/(?<=\,.*)\,/g, "")
                    .replace(/(?<=\,\d\d).*/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        });
    </script>
@endsection

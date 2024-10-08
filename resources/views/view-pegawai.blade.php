@extends('layout.default')
@section('container')
    <!--jQuery first-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!--datatable jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>


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
    <!-- End Halaman -->

    @if (Session::has('status_create_pegawai'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_pegawai') }}",
                });
            })
        </script>
    @endif
    @if (Session::has('status_update_pegawai'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_update_pegawai') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_destroy_pegawai'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_pegawai') }}",
                });
            })
        </script>
    @endif

    <div class="pagetitle">
        <h1>Master Pegawai</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Pegawai</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pegawai</h5>

                        @can('for-admin')
                            <div class="row mb-3">
                                <div class="col-sm-20">
                                    <div class="d-flex">
                                        <a href="{{ url('/create-pegawai') }}" data-toggle="tooltip" data-placement="top"
                                            title="Tambah" style="color: aliceblue">
                                            <button type="button" class="btn btn-primary pull-left">
                                                <i class="bi bi-person-plus"></i>&nbsp;&nbsp;<span>Tambah Pegawai</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <!-- Table with stripped rows -->
                        <table id="pegawai-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jabatan</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('[data-toggle=tooltip]').tooltip();

            // DataTable
            var table = $('#pegawai-table').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'view-pegawai',
                    data: function(d) {
                        return d;
                    }
                },
                columns: [{
                        data: 'nip',
                        name: 'nip'
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
                        data: 'jabatan',
                        name: 'jabatan'
                    },
                    {
                        data: 'admin',
                        name: 'admin'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ],
                lengthMenu: [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                pageLength: 25,
            });


        });
    </script>
@endsection

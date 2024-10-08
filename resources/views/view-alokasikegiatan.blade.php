{{-- @extends('layout.default')
@section('container')
    <!--jQuery first-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!--datatable jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

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
            document.querySelector('#alokasikegiatan').classList.add('active');
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


    @if (Session::has('status_destroy_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_destroy_alokasikegiatan') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_create_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_create_alokasikegiatan') }}",
                });
            })
        </script>
    @endif

    @if (Session::has('status_update_alokasikegiatan'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "{{ Session::get('status_update_alokasikegiatan') }}",
                });
            })
        </script>
    @endif

    <div class="pagetitle">
        <h1>Alokasi Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Alokasi Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Alokasi Kegiatan</h5>
                        <!-- Table with stripped rows -->
                        <table id="alokasikegiatan-table" class="table display dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Subject Meter</th>
                                    <th>Ketua Tim</th>
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
            var table = $('#alokasikegiatan-table').DataTable({
                'scrollX': true,
                'responsive': true,
                'autowidth': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'view-alokasikegiatan',
                    data: function(d) {
                        // d.tahun_filter = tahun_filter;
                        // d.kecamatan_filter = kecamatan_filter;
                        // d.selected_mitra = selected_mitra;
                        return d;
                    }

                },
                'columns': [
                    {

                        data: 'kegiatan',
                        name: 'kegiatan'
                    }, {

                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'tim',
                        name: 'tim'
                    },
                    {
                        data: 'ketua_tim',
                        name: 'ketua_tim'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]

            });
        });

        $('.delete-row').click(function() {
            var dataid = $(this).attr('dataid');
            var name = $(this).attr('name');
            Swal.fire({
                    title: "Yakin?",
                    text: "Anda ingin menghapus kegiatan " + dataid + "_" + name,
                    icon: "warning",
                    showCancelButton: true,
                })
                .then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        window.location = '/hapus/kegiatan-' + dataid
                    }
                });
        })
    </script>
@endsection --}}

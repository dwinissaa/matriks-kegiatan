<?php

namespace App\Http\Controllers;

use App\Exports\pekerjaanExport;
use App\Exports\templatepekerjaanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\PDF;

class ExportController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    public function export_templatepekerjaan(Request $request, $id_keg)
    {
        return $this->excel->download(new templatepekerjaanExport($id_keg), 'templatepekerjaan.xlsx');
    }

    //
    public function export_pekerjaan(Request $request, $tahun, $bulan, $id_anggota, $format)
    {
        if ($format == 'Excel') {
            return $this->excel->download(new pekerjaanExport($tahun, $bulan, $id_anggota, $format), 'pekerjaan.xlsx');
        } else if ($format == 'Pdf') {
            return $this->excel->download(new pekerjaanExport($tahun, $bulan, $id_anggota, $format), 'pekerjaan.pdf', \Maatwebsite\Excel\Excel::MPDF);
        } else if ($format == 'View') {
            $path = 'http://matriks-kegiatan.test/export/pekerjaan/tahun=' . $tahun . '/bulan=' . $bulan . '/id_anggota=' . $id_anggota . '/format=Pdf';
            $filename = 'example.pdf';
        }
    }


    function query($tahun, $bulan)
    {
        if ($tahun != null) {
            if ($bulan != null) {
                return 'WHERE tahun =' . $tahun . ' and bulan =' . $bulan;
            }
        }
        return '';
    }


    public function index(Request $request)
    {
        $tahun = DB::table('kegiatan')->select('tahun')->distinct()->get();

        if ($request->ajax()) {

            $download_list = DB::table('users')
                ->leftJoin(
                    DB::raw('(SELECT alokasikegiatan.id_anggota, COUNT(alokasikegiatan.id_keg) as jlh_kegiatan
                                FROM alokasikegiatan
                                LEFT JOIN kegiatan
                                ON kegiatan.id_keg = alokasikegiatan.id_keg '
                        . $this->query($request->tahun_filter, $request->bulan_filter) .
                        ' GROUP BY id_anggota) as alokasikegiatantable'),
                    function ($join) {
                        $join->on('users.nip', '=', 'alokasikegiatantable.id_anggota');
                    }
                )
                ->leftJoin(
                    DB::raw('(SELECT pekerjaan.id_anggota, COUNT(pekerjaan.id_pekerjaan) as jlh_pekerjaan
                                FROM pekerjaan
                                RIGHT JOIN alokasikegiatan
                                ON alokasikegiatan.id_keg = pekerjaan.id_keg AND alokasikegiatan.id_anggota = pekerjaan.id_anggota
                                LEFT JOIN kegiatan
                                ON kegiatan.id_keg = pekerjaan.id_keg '
                        . $this->query($request->tahun_filter, $request->bulan_filter)  .
                        ' GROUP BY id_anggota) as pekerjaantable'),
                    function ($join) {
                        $join->on('users.nip', '=', 'pekerjaantable.id_anggota');
                    }
                )
                ->select('nip as id_anggota', 'nama',  DB::raw('IFNULL(jlh_kegiatan,0) as jlh_kegiatan'), DB::raw('IFNULL(pekerjaantable.jlh_pekerjaan,0) as jlh_pekerjaan'));


            if ($request->bulan_filter == null) {
                $download_list = [];
            }
            return DataTables::of($download_list)
                ->addColumn('periode', function ($data) use ($request) {
                    $bulan_arr = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                    return $bulan_arr[($request->bulan_filter - 1)] . ' ' . $request->tahun_filter;
                })
                ->addColumn('id_anggota', function ($data) {
                    return $data->id_anggota;
                })->addColumn('nama_anggota', function ($data) {
                    return $data->nama;
                })
                ->addColumn('jlh_kegiatan', function ($data) {
                    return $data->jlh_kegiatan;
                })->addColumn('jlh_pekerjaan', function ($data) {
                    return $data->jlh_pekerjaan;
                })
                ->addColumn('unduh', function ($data) use ($request) {
                    return '
                    <style>
                    .btn-circle {
                        width: 35px;
                        height: 35px;
                        padding: 5px;
                        border-radius: 100px;
                        font-size: 15px;
                        text-align: center;
                    }
                    </style>

                    <a href="' . url('/export/pekerjaan/tahun=' . $request->tahun_filter . '/bulan=' . $request->bulan_filter . '/id_anggota=' . $data->id_anggota . '/format=Excel') . '"
                        class="btn btn-success font-weight-bold btn-circle alokasi-row btn-sm" data-toggle="tooltip" data-placement="top" title="Donwload Excel">
                        <i class="bi bi-file-earmark-excel"></i>
                    </a>
                    <a href="' . url('/export/pekerjaan/tahun=' . $request->tahun_filter . '/bulan=' . $request->bulan_filter . '/id_anggota=' . $data->id_anggota . '/format=Pdf') . '"
                        class="btn btn-danger font-weight-bold btn-circle alokasi-row btn-sm" data-toggle="tooltip" data-placement="top" title="Donwload Pdf">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>

                    <script>
                        $("[data-toggle=tooltip]").tooltip();
                    </script>
                    ';
                })
                ->rawColumns(['unduh'])
                ->make(true);
        }
        return view('/view-download-report', compact('request', 'tahun'));
    }

    public function index_mitra(Request $request)
    {
        $tahun = DB::table('kegiatan')->select('tahun')->distinct()->get();

        if ($request->ajax()) {

            $download_list = DB::table('registeredmitra')
                ->join('mitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')
                ->where('registeredmitra.tahun', '=', $request->tahun_filter)
                ->leftJoin(
                    DB::raw('(SELECT alokasikegiatan.id_anggota, COUNT(alokasikegiatan.id_keg) as jlh_kegiatan
                                FROM alokasikegiatan
                                LEFT JOIN kegiatan
                                ON kegiatan.id_keg = alokasikegiatan.id_keg '
                        . $this->query($request->tahun_filter, $request->bulan_filter) .
                        ' GROUP BY id_anggota) as alokasikegiatantable'),
                    function ($join) {
                        $join->on('mitra.sobatid', '=', 'alokasikegiatantable.id_anggota');
                    }
                )
                ->leftJoin(
                    DB::raw('(SELECT pekerjaan.id_anggota, COUNT(pekerjaan.id_pekerjaan) as jlh_pekerjaan
                                FROM pekerjaan
                                RIGHT JOIN alokasikegiatan
                                ON alokasikegiatan.id_keg = pekerjaan.id_keg AND alokasikegiatan.id_anggota = pekerjaan.id_anggota
                                LEFT JOIN kegiatan
                                ON kegiatan.id_keg = pekerjaan.id_keg '
                        . $this->query($request->tahun_filter, $request->bulan_filter)  .
                        ' GROUP BY id_anggota) as pekerjaantable'),
                    function ($join) {
                        $join->on('mitra.sobatid', '=', 'pekerjaantable.id_anggota');
                    }
                )
                ->select('registeredmitra.sobatid as id_anggota', 'nama',  DB::raw('IFNULL(jlh_kegiatan,0) as jlh_kegiatan'), DB::raw('IFNULL(pekerjaantable.jlh_pekerjaan,0) as jlh_pekerjaan'));


            if ($request->bulan_filter == null) {
                $download_list = [];
            }
            return DataTables::of($download_list)
                ->addColumn('periode', function ($data) use ($request) {
                    $bulan_arr = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                    return $bulan_arr[($request->bulan_filter - 1)] . ' ' . $request->tahun_filter;
                })
                ->addColumn('id_anggota', function ($data) {
                    return $data->id_anggota;
                })->addColumn('nama_anggota', function ($data) {
                    return $data->nama;
                })
                ->addColumn('jlh_kegiatan', function ($data) {
                    return $data->jlh_kegiatan;
                })->addColumn('jlh_pekerjaan', function ($data) {
                    return $data->jlh_pekerjaan;
                })
                ->addColumn('unduh', function ($data) use ($request) {
                    return '
                    <style>
                    .btn-circle {
                        width: 35px;
                        height: 35px;
                        padding: 5px;
                        border-radius: 100px;
                        font-size: 15px;
                        text-align: center;
                    }
                    </style>

                    <a href="' . url('/export/pekerjaan/tahun=' . $request->tahun_filter . '/bulan=' . $request->bulan_filter . '/id_anggota=' . $data->id_anggota . '/format=Excel') . '"
                        class="btn btn-success font-weight-bold btn-circle alokasi-row btn-sm" data-toggle="tooltip" data-placement="top" title="Donwload Excel">
                        <i class="bi bi-file-earmark-excel"></i>
                    </a>
                    <a href="' . url('/export/pekerjaan/tahun=' . $request->tahun_filter . '/bulan=' . $request->bulan_filter . '/id_anggota=' . $data->id_anggota . '/format=Pdf') . '"
                        class="btn btn-danger font-weight-bold btn-circle alokasi-row btn-sm" data-toggle="tooltip" data-placement="top" title="Donwload Pdf">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>

                    <script>
                        $("[data-toggle=tooltip]").tooltip();
                    </script>
                    ';
                })
                ->rawColumns(['unduh'])
                ->make(true);
        }
        return view('/view-download-report', compact('request', 'tahun'));
    }
}

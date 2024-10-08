<?php

namespace App\Http\Controllers;

use App\Models\AlokasiKegiatan;
use App\Models\Kegiatan;
use App\Models\Pekerjaan;
use App\Models\RegisteredMitra;
use App\Models\Users;
use App\Models\Tim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use \Yajra\DataTables\Facades\DataTables;
use Exception;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Object_;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tahun = DB::table('kegiatan')->select('tahun')->orderBy('tahun', 'asc')->groupBy('tahun')->get();
        $data = DB::table('kegiatan')
            ->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->join('users', 'users.nip', '=', 'tim.nip_ketim', 'left')->orderBy('kegiatan.tahun', 'DESC')->orderBy('kegiatan.bulan', 'DESC')->orderBy('kegiatan.created_at', 'DESC')
            ->leftJoin(DB::raw('(SELECT alokasikegiatan.id_keg, COUNT(alokasikegiatan.id_anggota) as jlh_anggota FROM alokasikegiatan GROUP BY id_keg) as alokasikegiatantable'), function ($join) {
                $join->on('kegiatan.id_keg', '=', 'alokasikegiatantable.id_keg');
            })
            ->leftJoin(
                DB::raw('(SELECT COUNT(id_pekerjaan) as jlh_pekerjaan, id_keg FROM `pekerjaan` GROUP BY id_keg) as pekerjaantable'),
                function ($join) {
                    $join->on('kegiatan.id_keg', '=', 'pekerjaantable.id_keg');
                }
            )
            ->select(DB::raw('IFNULL(pekerjaantable.jlh_pekerjaan,0) as jlh_pekerjaan'), DB::raw('IFNULL(alokasikegiatantable.jlh_anggota,0) as jlh_anggota'), 'kegiatan.tahun', 'kegiatan.bulan', 'subject_meter', 'kegiatan.id_keg', 'kegiatan', 'id_tim', 'tim', 'nip_ketim', 'nama')
            ->get();

        if ($request->ajax()) {
            if ($request->tahun_filter != null) {
                $data = $data->where('tahun', '=', $request->tahun_filter);
            }
            if ($request->bulan_filter != null) {
                $data = $data->where('bulan', '=', $request->bulan_filter);
            }
            if ($request->tim_filter != null) {
                $data = $data->where('subject_meter', '=', $request->tim_filter);
            }
            return DataTables::of($data)
                ->addColumn('periode', function ($data) {
                    $bulan_arr = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                    return $bulan_arr[($data->bulan - 1)] . ' ' . $data->tahun;
                })
                ->addColumn('kegiatan', function ($data) {
                    return $data->kegiatan;
                })->addColumn('subject_meter', function ($data) {
                    return $data->subject_meter;
                })->addColumn('ketua_tim', function ($data) {
                    return $data->nip_ketim . ' - ' . $data->nama;
                })->addColumn('jlh_anggota', function ($data) {
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

                    <a href="' . url('view/alokasi-' . $data->id_keg) . '"
                        class="btn btn-primary font-weight-bold btn-circle alokasi-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Alokasi">
                        ' . $data->jlh_anggota . '
                    </a>
                    ';
                })->addColumn('jlh_pekerjaan', function ($data) {
                    return '
                    <a id_keg="' . $data->id_keg . '" tahun="' . $data->tahun . '" bulan="' . $data->bulan . '" id_tim="' . $data->subject_meter . '"
                        class="btn btn-primary font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Pekerjaan">
                        ' . $data->jlh_pekerjaan . '
                    </a>
                    <script>
                    $(".kegiatan_pek").on("click", function(){
                        var tahun_ = $(this).attr("tahun");
                        var bulan_ = $(this).attr("bulan");
                        var id_tim_ = $(this).attr("id_tim");
                        var id_keg_ = $(this).attr("id_keg");

                        console.log([tahun_, bulan_, id_tim_ ,id_keg_]);
                        var cookie_keg =[tahun_,bulan_,id_tim_ ,id_keg_];
                        var now = new Date();
                        var expireTime = now.getTime() + 5;
                        now.setTime(expireTime);
                        $.cookie("cookie_keg", cookie_keg, { path: "/" });
                        window.location = "'.url("view-pekerjaan").'"
                    })
                    </script>
                    ';
                })->addColumn('aksi', function ($data) {
                    $results = '';
                    if (auth()->user()->admin == 1 || auth()->user()->admin == 2) {
                        $results = $results . '<style>
                                        .btn-aksi {
                                            width: 30px;
                                            height: 30px;
                                            padding: 4px;
                                            border-radius: 60px;
                                            font-size: 15px;
                                            text-align: center;
                                        }
                                    </style>

                                    <a href="' . url('edit/kegiatan-' . $data->id_keg) . '"
                                        class="btn btn-aksi btn-success edit-row btn-sm" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="#" id_keg="' . $data->id_keg . '" kegiatan="' . $data->kegiatan . '" 
                                        class="btn btn-aksi btn-danger delete-row btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <script>       
                                        
                                    $(".delete-row").click(function() {
                                        var id_keg = $(this).attr("id_keg");
                                        var kegiatan = $(this).attr("kegiatan");
                                        Swal.fire({
                                                title: "Yakin?",
                                                html: `<b>Anda ingin menghapus SELURUH data kegiatan ` + kegiatan + ` dengan ID ` + id_keg +` ?</b><br><br><p style="color:#0d6efd;">Tindakan ini bisa menghapus basis data kegiatan, anggota dan pekerjaan dari kegiatan terpilih dan tindakan ini tidak bisa dibatalkan</p>`,
                                                icon: "question",
                                                showCancelButton: true,
                                                confirmButtonColor: "#3085d6",
                                                cancelButtonColor: "#d33",
                                                confirmButtonText: "Ya, saya yakin!(2)",
                                                cancelButtonText: "Batal"
                                            })
                                            .then((willDelete2) => {
                                                if (willDelete2.isConfirmed) {
                                                    Swal.fire({
                                                        title: "Yakin?",
                                                        html: `<b>Anda ingin menghapus SELURUH data kegiatan ` + kegiatan + ` dengan ID ` + id_keg +` ?</b><br><br><p style="color:#0d6efd;">Tindakan ini bisa menghapus basis data kegiatan, anggota dan pekerjaan dari kegiatan terpilih dan tindakan ini tidak bisa dibatalkan</p>`,
                                                        icon: "question",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "Ya, saya yakin!!(1)",
                                                        cancelButtonText: "Batal"
                                                    }).then((willDelete1) => {
                                                        if (willDelete1.isConfirmed) {
                                                            Swal.fire({
                                                                title: "Yakin?",
                                                                html: `<b>Anda ingin menghapus SELURUH data kegiatan ` + kegiatan + ` dengan ID ` + id_keg +` ?</b><br><br><p style="color:#0d6efd;">Tindakan ini bisa menghapus basis data kegiatan, anggota dan pekerjaan dari kegiatan terpilih dan tindakan ini tidak bisa dibatalkan</p>`,
                                                                icon: "question",
                                                                showCancelButton: true,
                                                                confirmButtonColor: "#3085d6",
                                                                cancelButtonColor: "#d33",
                                                                confirmButtonText: "Ya, saya yakin!!!",
                                                                cancelButtonText: "Batal"
                                                            }).then((willDelete) => {
                                                                if (willDelete.isConfirmed) {
                                                                    window.location = "hapus/kegiatan-" + id_keg + ""
                                                                }
                                                            })
                                                        }
                                                    })
                                                }
                                            });
                                    });
                                    $("[data-toggle=tooltip]").tooltip();
                                    
                                    </script>';
                    }
                    return $results;
                })
                ->rawColumns(['jlh_anggota', 'jlh_pekerjaan', 'aksi'])
                ->make(true);
        }
        return view('/view-kegiatan', compact('request', 'tahun'));
    }

    public function filter_tahun(Request $request, $role, $tahun)
    {
        if ($tahun == "00") {
            $bulan = DB::table('kegiatan')->select('bulan');
        } else {
            $bulan = DB::table('kegiatan')->where('tahun', $tahun)->select('bulan');
        }

        if ($role == 'pegawai') {
            $bulan = $bulan->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')->select('kegiatan.bulan');
        } else if ($role == 'mitra') {
            $bulan = $bulan->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNull('users.nip')->select('kegiatan.bulan');
        }
        return $bulan->distinct()->get();
    }

    public function filter_bulan(Request $request, $role, $tahun, $bulan)
    {
        if ($bulan == "00") {
            $tim = DB::table('kegiatan')->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')->select('subject_meter', 'tim.tim');
        } else {
            $tim = DB::table('kegiatan')->where('tahun', $tahun)->where('bulan', $bulan)->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')->select('subject_meter', 'tim.tim');
        }

        if ($role == 'pegawai') {
            $tim = $tim->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')->select('subject_meter', 'tim.tim');
        } else if ($role == 'mitra') {
            $tim = $tim->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNull('users.nip')->select('subject_meter', 'tim.tim');
        }
        return $tim->distinct()->get();
    }

    public function filter_tim(Request $request, $role, $tahun, $bulan, $tim)
    {
        if ($tim == "00") {
            $kegiatan = DB::table('kegiatan')->select('id_keg', 'kegiatan');
        } else {
            $kegiatan = DB::table('kegiatan')->where('tahun', $tahun)->where('bulan', $bulan)->where('kegiatan.subject_meter', $tim)->select('id_keg', 'kegiatan');
        }

        if ($role == 'pegawai') {
            $kegiatan = $kegiatan->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')->select('id_keg', 'kegiatan');
        } else if ($role == 'mitra') {
            $kegiatan = $kegiatan->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNull('users.nip')->select('id_keg', 'kegiatan');
        }
        return $kegiatan->distinct()->get();
    }

    public function filter_kegiatan(Request $request, $role, $kegiatan)
    {
        if ($kegiatan == "00") {
            $anggota_mitra = DB::table('alokasikegiatan')->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')->select('id_anggota', 'mitra.email', 'mitra.nama', 'alokasikegiatan.created_at', 'id_keg')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $anggota = DB::table('alokasikegiatan')->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')->union($anggota_mitra)->select('id_anggota', 'users.email', 'users.nama', 'alokasikegiatan.created_at', 'id_keg')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai')
                ->orderBy('pegawai', 'desc')
                ->orderBy('created_at', 'desc')->get();
        } else {
            $anggota_mitra = DB::table('alokasikegiatan')->where('id_keg', $kegiatan)->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')->select('id_anggota', 'mitra.email', 'mitra.nama', 'alokasikegiatan.created_at', 'id_keg')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $anggota = DB::table('alokasikegiatan')->where('id_keg', $kegiatan)->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')->union($anggota_mitra)->select('id_anggota', 'users.email', 'users.nama', 'alokasikegiatan.created_at', 'id_keg')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai')
                ->orderBy('pegawai', 'desc')
                ->orderBy('created_at', 'desc')->get();
        }

        return $anggota;
    }

    public function index_pekerjaan(Request $request)
    {
        $tahun = DB::table('kegiatan')->select('tahun')->orderBy('tahun', 'asc')->groupBy('tahun')->get();
        $data_peg = DB::table('pekerjaan')->join('kegiatan', 'kegiatan.id_keg', '=', 'pekerjaan.id_keg', 'left')->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->join('users', 'users.nip', '=', 'pekerjaan.id_anggota', 'left')
            ->select('pekerjaan.id_pekerjaan', 'tim.id_tim', 'tim.tim', 'kegiatan.id_keg', 'pekerjaan.id_anggota', 'pekerjaan.uraian_pekerjaan', 'target', 'satuan', 'harga_satuan', 'kegiatan.tahun', 'kegiatan.bulan', 'kegiatan', 'subject_meter', 'email', 'nama', 'pekerjaan.updated_at')
            ->wherenotNull('users.nama');
        $data_mit = DB::table('pekerjaan')->join('kegiatan', 'kegiatan.id_keg', '=', 'pekerjaan.id_keg', 'left')->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->join('mitra', 'mitra.sobatid', '=', 'pekerjaan.id_anggota', 'left')
            ->select('pekerjaan.id_pekerjaan', 'tim.id_tim', 'tim.tim', 'kegiatan.id_keg', 'pekerjaan.id_anggota', 'pekerjaan.uraian_pekerjaan', 'target', 'satuan', 'harga_satuan', 'kegiatan.tahun', 'kegiatan.bulan', 'kegiatan', 'subject_meter', 'email', 'nama', 'pekerjaan.updated_at')
            ->wherenotNull('mitra.sobatid');
        $data = $data_peg->union($data_mit)->get();

        // dd($data);
        if ($request->ajax()) {
            if ($request->tahun_filter != null) {
                $data = $data->where('tahun', '=', $request->tahun_filter);
            }
            if ($request->bulan_filter != null) {
                $data = $data->where('bulan', '=', $request->bulan_filter);
            }
            if ($request->tim_filter != null) {
                $data = $data->where('subject_meter', '=', $request->tim_filter);
            }
            if ($request->kegiatan_filter != null) {
                $data = $data->where('id_keg', '=', $request->kegiatan_filter);
            }
            if ($request->anggota_filter != null) {
                $data = $data->where('id_anggota', '=', $request->anggota_filter);
            }

            return DataTables::of($data)
                ->addColumn('select_all', function ($data) {
                    return '<td><input type="checkbox" class="sub_chk" data-id="' . $data->id_pekerjaan . '"></td>';
                })->addColumn('periode', function ($data) {
                    $bulan_arr = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                    return $bulan_arr[($data->bulan) - 1] . ' ' . $data->tahun;
                })
                ->addColumn('tim', function ($data) {
                    return $data->tim;
                })
                ->addColumn('kegiatan', function ($data) {
                    return $data->kegiatan;
                })->addColumn('id_anggota', function ($data) {
                    return $data->id_anggota;
                })->addColumn('nama', function ($data) {
                    return $data->nama;
                })->addColumn('uraian_pekerjaan', function ($data) {
                    return $data->uraian_pekerjaan;
                })->addColumn('target', function ($data) {
                    return $data->target . ' ' . $data->satuan;
                })->addColumn('harga_satuan', function ($data) {
                    return '@ Rp ' . number_format($data->harga_satuan, 2, ',', '.');
                })->addColumn('biaya', function ($data) {
                    return 'Rp ' . number_format($data->target * $data->harga_satuan, 2, ',', '.');
                })
                ->addColumn('update_terakhir', function ($data) {
                    return $data->updated_at;
                })
                ->addColumn('aksi', function ($data) {
                    $results = '';
                    if (auth()->user()->admin == 1 || auth()->user()->admin == 2) {
                        $results = $results .
                            '<style>
                        .btn-aksi {
                            width: 30px;
                            height: 30px;
                            padding: 4px;
                            border-radius: 60px;
                            font-size: 15px;
                            text-align: center;
                        }
                        </style>
                        

                        <span data-toggle="tooltip" data-placement="top" title="Edit">
                            <button type="button" class="btn btn-aksi btn-success edit-row btn-sm" data-toggle="modal" data-target="#editpekerjaanModal"
                            tahun="' . $data->tahun . '"bulan="' . $data->bulan . '"id_keg="' . $data->id_keg . '"kegiatan="' . $data->kegiatan . '" id_anggota="' . $data->id_anggota . '" name="' . $data->nama . '" id_pekerjaan="' . $data->id_pekerjaan . '" uraian_pekerjaan="' . $data->uraian_pekerjaan . '"target="' . $data->target . '"satuan="' . $data->satuan . '"harga_Satuan="' . $data->harga_satuan . '">
                                <i class="bi bi-pencil"></i>
                            </button>
                        <span>

                        <button href="#" data-toggle="tooltip" data-placement="top" title="Hapus" id_keg="' . $data->id_keg . '" dataid="' . $data->id_anggota . '" name="' . $data->nama . '" id_pekerjaan="' . $data->id_pekerjaan . '" uraian pekerjaan="' . $data->uraian_pekerjaan . '" class="btn btn-aksi btn-danger delete-row btn-sm">
                                <i class="bi bi-trash"></i>
                        </button>
                        <script>             
                            $(".delete-row").click(function() {
                                var id_keg = $(this).attr("id_keg");
                                var dataid = $(this).attr("dataid");
                                var name = $(this).attr("name");
                                var id_pekerjaan = $(this).attr("id_pekerjaan");
                                var pekerjaan = $(this).attr("pekerjaan");
                                Swal.fire({
                                        title: "Yakin?",
                                        text: "Anda ingin menghapus pekerjaan " + pekerjaan + " oleh anggota "+ dataid + "_" + name + "?",
                                        icon: "question",
                                        showCancelButton: true,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#d33",
                                        confirmButtonText: "Ya, saya yakin!",
                                        cancelButtonText: "Batal"
                                    })
                                    .then((willDelete) => {
                                        if (willDelete.isConfirmed) {
                                            window.location = "hapus/pekerjaan-" + id_pekerjaan;
                                        }
                                    });
                            })

                            $("[data-toggle=tooltip]").tooltip();
                        </script>
                        ';
                    }
                    return $results;
                })
                ->rawColumns(['select_all', 'aksi'])
                ->make(true);
        }
        return view('view-pekerjaan', compact('data', 'tahun'));
    }

    // DESTROY SELECTED
    public function destroySelectedPekerjaan(Request $request)
    {
        $ids = $request->row_ids;
        Pekerjaan::whereIn('id_pekerjaan', $ids)->delete();
        return response()->json(['code' => 1, 'status_destroy_selectedPekerjaan' => count($ids) . ' baris data pegawai berhasil dihapus.']);
    }


    public function detail_alokasi(Request $request, $id_keg)
    {
        $kegiatan = DB::table('kegiatan')->where('id_keg', $id_keg)->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->join('users', 'users.nip', '=', 'tim.nip_ketim', 'left')->first();
        $data_mitra = DB::table('alokasikegiatan')
            ->where('alokasikegiatan.id_keg', $id_keg)
            ->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')
            ->whereNotNull('mitra.sobatid')
            ->select('alokasikegiatan.id_anggota', 'mitra.email', 'mitra.nama', 'alokasikegiatan.created_at', 'alokasikegiatan.id_keg', DB::raw('COUNT(pekerjaan.id_pekerjaan) as jlh_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota', 'alokasikegiatan.id_keg', 'mitra.email', 'mitra.nama', 'alokasikegiatan.created_at')
            ->selectSub(function ($query) {
                $query->selectRaw(0);
            }, 'pegawai')
            ->leftJoin(
                'pekerjaan',
                function ($join) {
                    $join->on('alokasikegiatan.id_anggota', '=', 'pekerjaan.id_anggota');
                    $join->on('alokasikegiatan.id_keg', '=', 'pekerjaan.id_keg');
                }
            );
        // dd($data_mitra->where('alokasikegiatan.id_keg','=',13)->get());
        $data = DB::table('alokasikegiatan')
            ->where('alokasikegiatan.id_keg', $id_keg)
            ->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')
            ->whereNotNull('users.nip')
            ->union($data_mitra)
            ->select('alokasikegiatan.id_anggota', 'users.email', 'users.nama', 'alokasikegiatan.created_at', 'alokasikegiatan.id_keg', DB::raw('COUNT(pekerjaan.id_pekerjaan) as jlh_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota', 'alokasikegiatan.id_keg', 'users.email', 'users.nama', 'alokasikegiatan.created_at')
            ->selectSub(function ($query) {
                $query->selectRaw(1);
            }, 'pegawai')
            ->leftJoin(
                'pekerjaan',
                function ($join) {
                    $join->on('alokasikegiatan.id_anggota', '=', 'pekerjaan.id_anggota');
                    $join->on('alokasikegiatan.id_keg', '=', 'pekerjaan.id_keg');
                }
            )
            ->orderBy('pegawai', 'desc')
            ->orderBy('created_at', 'desc')->get();
        $total_alok = count($data);

        $pilihan_peg = DB::table('users')
            ->leftJoin(DB::raw('(SELECT alokasikegiatan.id_anggota FROM alokasikegiatan WHERE alokasikegiatan.id_keg=' . $id_keg . ') as alokasitable'), function ($join) {
                $join->on('users.nip', '=', 'alokasitable.id_anggota');
            })->select(['users.nip as id', 'email', 'nama', 'id_anggota'])->whereNull('id_anggota')
            ->get();
        $pilihan_mitra = DB::table('registeredmitra')->where('registeredmitra.tahun', '=', DB::table('kegiatan')->where('id_keg', $id_keg)->first()->tahun)->join('mitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')
            ->leftJoin(DB::raw('(SELECT alokasikegiatan.id_anggota FROM alokasikegiatan WHERE alokasikegiatan.id_keg=' . $id_keg . ') as alokasitable'), function ($join) {
                $join->on('registeredmitra.sobatid', '=', 'alokasitable.id_anggota');
            })
            ->select(['registeredmitra.sobatid as id', 'email', 'nama', 'id_anggota'])->whereNull('id_anggota')
            // ->union($pilihan_peg)->get();
            ->get();
        // dd($pilihan_mitra);

        if ($request->ajax()) {
            $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id_keg)->get()->first();

            $tab = DataTables::of($data, $id_keg)
                ->addColumn('id_anggota', function ($data) {
                    return $data->id_anggota;
                })->addColumn('nama', function ($data) {
                    return $data->nama;
                })->addColumn('email', function ($data) {
                    return $data->email;
                });

            $tab->addColumn('jlh_pekerjaan', function ($data) use ($keg) {
                return '<style>
                .btn-circle {
                    width: 35px;
                    height: 35px;
                    padding: 5px;
                    border-radius: 100px;
                    font-size: 15px;
                    text-align: center;
                }
                </style>
                <span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                    class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;color: #4154f1;background: #f6f6fe;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Pekerjaan">
                    ' . $data->jlh_pekerjaan . '
                </span>
                <script>
                $(".kegiatan_pek").on("click", function(){
                    var tahun_ = $(this).attr("tahun");
                    var bulan_ = $(this).attr("bulan");
                    var id_tim_ = $(this).attr("id_tim");
                    var id_keg_ = $(this).attr("id_keg");
                    var id_anggota_ = $(this).attr("id_anggota");

                    console.log([tahun_, bulan_, id_tim_ ,id_keg_, id_anggota_]);
                    var cookie_pek ={tahun:tahun_,bulan:bulan_,id_tim:id_tim_ ,id_keg:id_keg_, id_anggota:id_anggota_};
                    console.log("xixixi",cookie_pek);
                    var now = new Date();
                    var expireTime = now.getTime() + 5;
                    now.setTime(expireTime);
                    $.cookie("cookie_pek", JSON.stringify(cookie_pek), { path: "/" });
                    document.location = "'.url("view-pekerjaan").'"
                })
                $("[data-toggle=tooltip]").tooltip();
                </script>';
            })->addColumn('created_at', function ($data) {
                return $data->created_at;
            })->addColumn('aksi', function ($data) {
                return
                    '<style>
                        .btn-circle {
                            width: 35px;
                            height: 35px;
                            padding: 5px;
                            border-radius: 100px;
                            font-size: 15px;
                            text-align: center;
                        }
                        </style>
                        <button href="#" id_keg="' . $data->id_keg . '" dataid="' . $data->id_anggota . '" name="' . $data->nama . '" data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-danger delete-row btn-circle">
                                <i class="bi bi-x-lg"></i>
                        </button>
                        
                        <script> 
                        $("[data-toggle=tooltip]").tooltip();              
                            $(".delete-row").click(function() {
                                var id_keg = $(this).attr("id_keg");
                                var dataid = $(this).attr("dataid");
                                var name = $(this).attr("name");
                                Swal.fire({
                                        title: "Yakin?",
                                        html: `<b>Anda ingin menghapus anggota kegiatan atas nama ` + dataid + `_` + name + `?</b><br><br><p style="color:#0d6efd;">Tindakan ini bisa menghapus basis data pekerjaan dari anggota kegiatan terpilih dan tindakan ini tidak bisa dibatalkan.</p>`,
                                        icon: "question",
                                        showCancelButton: true,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#d33",
                                        confirmButtonText: "Ya, saya yakin!!",
                                        cancelButtonText: "Batal"
                                    })
                                    .then((willDelete) => {
                                        if (willDelete.isConfirmed) {
                                            window.location = "hapus/alokasikegiatan-" + id_keg + "-" + dataid
                                        }
                                    });
                            })

                            $("[data-toggle=tooltip]").tooltip();
                        </script>
                        ';
            });
            return $tab->rawColumns(['jlh_pekerjaan', 'aksi'])->make(true);
        }
        return view('/view-detail-alokasikegiatan', compact('request', 'kegiatan', 'total_alok', 'pilihan_peg', 'pilihan_mitra'));
    }

    public function getUser($role, $tahun, $bulan)
    {
        // dd($tahun);

        if ($tahun == null) {
            $tahun = date("Y");
        }
        $user = DB::table('alokasikegiatan')
            ->leftJoin('pekerjaan', function ($join) {
                $join->on('pekerjaan.id_anggota', '=', 'alokasikegiatan.id_anggota')
                    ->on('pekerjaan.id_keg', '=', 'alokasikegiatan.id_keg');
            })
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left');


        $unique_keg = (object) array();
        // choose role
        if ($role == 'pegawai') {
            $user = $user->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai');
        } else if ($role == 'mitra') {
            $user = $user->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct()->get();
        }
        // Initialize
        $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct();
        // dd($unique_keg->get());

        // Filter
        $user = $user->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $user = $user->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $unique_keg = $unique_keg->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->get();

        // Select User JLH KEGIATAN
        $user = $user->select('alokasikegiatan.id_anggota', 'nama', DB::raw('count(DISTINCT alokasikegiatan.id_keg) as total_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota', 'nama');
        $user = $user->orderBy('id_anggota', 'ASC');

        // TEST JLH KEGIATAN
        $result = (object) array();
        for ($i = 0; $i < count($unique_keg); $i++) {
            $id_keg = $unique_keg[$i]->id_keg;
            $id_keg2 = 'keg_' . $unique_keg[$i]->id_keg;

            $temp = DB::table($user, 't2')
                ->leftJoin(DB::raw(
                    "( SELECT id_anggota, COUNT(id_keg) as jlh_pekerjaan 
                    FROM alokasikegiatan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                ) as query"
                ), function ($join) {
                    $join->on('query.id_anggota', '=', 't2.id_anggota');
                })
                ->select(DB::raw("t2.id_anggota,t2.nama, IF (query.jlh_pekerjaan IS NOT NULL, 1, '-') as '$id_keg2',t2.total_pekerjaan"))
                ->orderBy('t2.id_anggota', 'ASC')->get();
            if ($i == 0) {
                $result = $temp;
            }
            $result = $result->map(function ($elem, $index) use ($id_keg2, $temp) {
                $elem->$id_keg2 = $temp[$index]->$id_keg2;
                return $elem;
            });
        }
        return [$result, $unique_keg];
    }


    public function getUserPek($role, $tahun, $bulan)
    {
        if ($tahun == null) {
            $tahun = date("Y");
        }
        $user = DB::table('alokasikegiatan')
            ->leftJoin('pekerjaan', function ($join) {
                $join->on('pekerjaan.id_anggota', '=', 'alokasikegiatan.id_anggota')
                    ->on('pekerjaan.id_keg', '=', 'alokasikegiatan.id_keg');
            })
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left');


        $unique_keg = (object) array();
        // choose role
        if ($role == 'pegawai') {
            $user = $user->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai');
        } else if ($role == 'mitra') {
            $user = $user->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct()->get();
        }
        // Initialize
        $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct();

        // Filter
        $user = $user->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $user = $user->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $unique_keg = $unique_keg->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->get();

        // Select User JLH PEKERJAAN
        $user = $user->select('alokasikegiatan.id_anggota', 'nama', DB::raw('count(id_pekerjaan) as total_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota', 'nama');
        $user = $user->orderBy('id_anggota', 'ASC');

        // TEST JLH PEKERJAAN
        $result = (object) array();
        for ($i = 0; $i < count($unique_keg); $i++) {
            $id_keg = $unique_keg[$i]->id_keg;
            $id_keg2 = 'keg_' . $unique_keg[$i]->id_keg;

            $temp = DB::table($user, 't2')
                ->select(DB::raw("t2.id_anggota, t2.nama, IFNULL(hasil.jlh_pekerjaan,'-') as '$id_keg2', t2.total_pekerjaan"))
                ->leftJoin(DB::raw(
                    "(SELECT query2.id_anggota, query2.jlh_kegiatan, IF(query.jlh_pekerjaan IS NULL, 0, query.jlh_pekerjaan) as jlh_pekerjaan
                FROM
                    ( SELECT id_anggota, COUNT(id_pekerjaan) as jlh_pekerjaan 
                    FROM pekerjaan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                    ) as query 
                RIGHT JOIN 
                    (SELECT id_anggota, COUNT(id_keg) as jlh_kegiatan 
                    FROM alokasikegiatan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                    ) as query2 
                ON query.id_anggota=query2.id_anggota
                ) as hasil"
                ), function ($join) {
                    $join->on('hasil.id_anggota', '=', 't2.id_anggota');
                })
                ->orderBy('t2.id_anggota', 'ASC')->get();
            // dd($temp);
            if ($i == 0) {
                $result = $temp;
            }
            $result = $result->map(function ($elem, $index) use ($id_keg2, $temp) {
                $elem->$id_keg2 = $temp[$index]->$id_keg2;
                return $elem;
            });
        }
        // dd([$result, $unique_keg]);
        return [$result, $unique_keg];
    }

    public function getUserBi($role, $tahun, $bulan)
    {
        // dd($tahun);

        if ($tahun == null) {
            $tahun = date("Y");
        }
        $user = DB::table('alokasikegiatan')
            ->leftJoin('pekerjaan', function ($join) {
                $join->on('pekerjaan.id_anggota', '=', 'alokasikegiatan.id_anggota')
                    ->on('pekerjaan.id_keg', '=', 'alokasikegiatan.id_keg');
            })
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left');


        $unique_keg = (object) array();
        // choose role
        if ($role == 'pegawai') {
            $user = $user->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('users.nip')
                ->selectSub(function ($query) {
                    $query->selectRaw(1);
                }, 'pegawai');
        } else if ($role == 'mitra') {
            $user = $user->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')->whereNotNull('mitra.sobatid')
                ->selectSub(function ($query) {
                    $query->selectRaw(0);
                }, 'pegawai');
            $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct()->get();
        }
        // Initialize
        $unique_keg = $user->select('kegiatan.id_keg', 'kegiatan.kegiatan')->distinct();

        // Filter
        $user = $user->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $user = $user->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->where('kegiatan.tahun', '=', $tahun);
        if ($bulan != '00') {
            $unique_keg = $unique_keg->where('kegiatan.bulan', '=', $bulan);
        }
        $unique_keg = $unique_keg->get();

        // Select User JLH BIAYA
        $user = $user->select('alokasikegiatan.id_anggota', 'nama', DB::raw('SUM(harga_satuan*target) as total_pekerjaan'))
            ->groupBy('alokasikegiatan.id_anggota','nama');
        $user = $user->orderBy('id_anggota', 'ASC');

        // GET JLH BIAYA
        $result = (object) array();
        for ($i = 0; $i < count($unique_keg); $i++) {
            $id_keg = $unique_keg[$i]->id_keg;
            $id_keg2 = 'keg_' . $unique_keg[$i]->id_keg;

            $temp = DB::table($user, 't2')
                ->select(DB::raw("t2.id_anggota, t2.nama, IFNULL(hasil.jlh_biaya,'-') as '$id_keg2', t2.total_pekerjaan"))
                ->leftJoin(DB::raw(
                    "(SELECT query2.id_anggota, query2.jlh_kegiatan, IF(query.jlh_biaya IS NULL, 0, query.jlh_biaya) as jlh_biaya
                FROM
                    ( SELECT id_anggota, SUM(harga_satuan*target) as jlh_biaya
                    FROM pekerjaan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                    ) as query 
                RIGHT JOIN 
                    (SELECT id_anggota, COUNT(id_keg) as jlh_kegiatan 
                    FROM alokasikegiatan
                    WHERE id_keg='$id_keg'
                    GROUP BY id_anggota
                    ) as query2 
                ON query.id_anggota=query2.id_anggota
                ) as hasil"
                ), function ($join) {
                    $join->on('hasil.id_anggota', '=', 't2.id_anggota');
                })
                ->orderBy('t2.id_anggota', 'ASC')->get();
            // dd($temp);
            if ($i == 0) {
                $result = $temp;
            }
            $result = $result->map(function ($elem, $index) use ($id_keg2, $temp) {
                $elem->$id_keg2 = $temp[$index]->$id_keg2;
                return $elem;
            });
        }
        return [$result, $unique_keg];
    }



    public function index_matkeg(Request $request)
    {
        $tahun = DB::table('alokasikegiatan')->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')->select('tahun')->distinct()->get();

        [$result_p, $unique_keg_p] = $this->getUser('pegawai', $request->tahun_filter, $request->bulan_filter);

        if (count($unique_keg_p) == 0) {
            # code...
            $result_p = [];
        }

        if ($request->ajax()) {
            // Draw Pegawai
            $dt_p = Datatables::of($result_p);
            $dt_p->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_p); $i++) {
                $id = $unique_keg_p[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_p->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                                class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Alokasi">
                                ' . $pek . '
                                </span>                       
                                <style>
                                .btn-circle {
                                    width: 35px;
                                    height: 35px;
                                    padding: 5px;
                                    border-radius: 100px;
                                    font-size: 15px;
                                    text-align: center;
                                    color: #4154f1;
                                    background: #f6f6fe;
                                }
                                </style>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_p->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . $data->total_pekerjaan . '</span>
              </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_p->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matkeg', compact('tahun', 'result_p', 'unique_keg_p'));
    }

    public function index_matpek(Request $request)
    {
        $tahun = DB::table('alokasikegiatan')->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')->select('tahun')->distinct()->get();
        $request->tahun_filter;
        [$result_p, $unique_keg_p] = $this->getUserPek('pegawai', $request->tahun_filter, $request->bulan_filter);

        if (count($unique_keg_p) == 0) {
            # code...
            $result_p = [];
        }

        if ($request->ajax()) {
            // Draw Pegawai
            $dt_p = Datatables::of($result_p);
            $dt_p->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_p); $i++) {
                $id = $unique_keg_p[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_p->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "0") {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                            class="btn btn-danger btn-circle font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Belum diinput. Input Pekerjaan!">
                            ' . $pek . '
                        </span>
                        <style>
                        .btn-circle {
                            width: 35px;
                            height: 35px;
                            padding: 5px;
                            border-radius: 100px;
                            font-size: 15px;
                            text-align: center;
                            color: #4154f1;
                            background: #f6f6fe;
                        }
                        </style>';
                        } else if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                            class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Pekerjaan">
                            ' . $pek . '
                        </span>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_p->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . $data->total_pekerjaan . '</span>
              </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_p->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matpek', compact('tahun', 'result_p', 'unique_keg_p'));
    }

    public function index_matbi(Request $request)
    {
        $tahun = DB::table('alokasikegiatan')->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')->select('tahun')->distinct()->get();

        [$result_p, $unique_keg_p] = $this->getUserBi('pegawai', $request->tahun_filter, $request->bulan_filter);
        if (count($unique_keg_p) == 0) {
            # code...
            $result_p = [];
        }
        if ($request->ajax()) {
            // Draw Pegawai
            $dt_p = Datatables::of($result_p);
            $dt_p->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_p); $i++) {
                $id = $unique_keg_p[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_p->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "0") {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                                    class="btn btn-danger btn-circle font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Belum diinput. Input Biaya!">
                                    ' . "Rp " . number_format($data->$idk, 2, ',', '.') . '
                                </span>
                                <style>
                                .btn-circle {
                                    font-size: 15px;
                                    text-align: center;
                                    color: #4154f1;
                                    background: #f6f6fe;
                                }
                                </style>';
                        } else if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Biaya">
                ' . "Rp " . number_format($data->$idk, 2, ',', '.') . '
            </span>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_p->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . "Rp " . number_format($data->total_pekerjaan, 2, ',', '.') . '</span>
  </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_p->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matbi', compact('tahun', 'result_p', 'unique_keg_p'));
    }



    public function getcol(Request $request)
    {
        if (true) {
            dd($request->selectedValue);
            // do your logic here

        }
    }


    public function index_matkeg_mitra(Request $request)
    {
        // $tahun = DB::table('alokasikegiatan')->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')->select('tahun')->distinct()->get();

        [$result_m, $unique_keg_m] = $this->getUser('mitra', $request->tahun_filter, $request->bulan_filter);

        if (count($unique_keg_m) == 0) {
            # code...
            $result_m = [];
        }
        // dd([$result_m, $unique_keg_m] );
        if ($request->ajax()) {
            // Draw Pegawai
            $dt_m = Datatables::of($result_m);

            $dt_m->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_m); $i++) {
                $id = $unique_keg_m[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_m->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                            class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Alokasi">
                            ' . $pek . '
                        </span>                       
                        <style>
                        .btn-circle {
                            width: 35px;
                            height: 35px;
                            padding: 5px;
                            border-radius: 100px;
                            font-size: 15px;
                            text-align: center;
                            color: #4154f1;
                            background: #f6f6fe;
                        }
                        </style>

                        <script>
                        $(".kegiatan_pek").on("click", function(){
                            var tahun_ = $(this).attr("tahun");
                            var bulan_ = $(this).attr("bulan");
                            var id_tim_ = $(this).attr("id_tim");
                            var id_keg_ = $(this).attr("id_keg");
                            var id_anggota_ = $(this).attr("id_anggota");

                            window.location = "view/alokasi-"+id_keg_;
                        })
                        $("[data-toggle=tooltip]").tooltip();
                        </script>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_m->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . $data->total_pekerjaan . '</span>
              </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_m->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matkeg', compact('result_m', 'unique_keg_m'));
    }


    public function index_matpek_mitra(Request $request)
    {
        [$result_m, $unique_keg_m] = $this->getUserPek('mitra', $request->tahun_filter, $request->bulan_filter);
        if (count($unique_keg_m) == 0) {
            # code...
            $result_m = [];
        }
        if ($request->ajax()) {
            // Draw Pegawai
            $dt_m = Datatables::of($result_m);
            $dt_m->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_m); $i++) {
                $id = $unique_keg_m[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_m->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "0") {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                            class="btn btn-danger btn-circle font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Belum diinput. Input Pekerjaan!">
                            ' . $pek . '
                        </span>
                        <style>
                        .btn-circle {
                            width: 35px;
                            height: 35px;
                            padding: 5px;
                            border-radius: 100px;
                            font-size: 15px;
                            text-align: center;
                            color: #4154f1;
                            background: #f6f6fe;
                        }
                        </style>';
                        } else if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                            class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Pekerjaan">
                            ' . $pek . '
                        </span>
                        <script>
                        $(".kegiatan_pek").on("click", function(){
                            var tahun_ = $(this).attr("tahun");
                            var bulan_ = $(this).attr("bulan");
                            var id_tim_ = $(this).attr("id_tim");
                            var id_keg_ = $(this).attr("id_keg");
                            var id_anggota_ = $(this).attr("id_anggota");

                            console.log([tahun_, bulan_, id_tim_ ,id_keg_, id_anggota_]);
                            var cookie_pek ={tahun:tahun_,bulan:bulan_,id_tim:id_tim_ ,id_keg:id_keg_, id_anggota:id_anggota_};
                            console.log("xixixi",cookie_pek);
                            var now = new Date();
                            var expireTime = now.getTime() + 5;
                            now.setTime(expireTime);
                            $.cookie("cookie_pek", JSON.stringify(cookie_pek), { path: "/" });
                            console.log($.cookie("cookie_pek"));
                            window.location = "'.url("view-pekerjaan").'"
                        })
                        $("[data-toggle=tooltip]").tooltip();
                        </script>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_m->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . $data->total_pekerjaan . '</span>
              </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_m->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matpek', compact('result_m', 'unique_keg_m'));
    }

    public function index_matbi_mitra(Request $request)
    {
        [$result_m, $unique_keg_m] = $this->getUserBi('mitra', $request->tahun_filter, $request->bulan_filter);
        if (count($unique_keg_m) == 0) {
            # code...
            $result_m = [];
        }
        if ($request->ajax()) {
            // Draw Pegawai
            $dt_m = Datatables::of($result_m);
            $dt_m->addColumn('id_anggota', function ($data) {
                return $data->id_anggota;
            })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                });
            $nama_keg = [];
            for ($i = 0; $i < count($unique_keg_m); $i++) {
                $id = $unique_keg_m[$i]->id_keg;
                $idk = 'keg_' . $id;
                array_push($nama_keg, $idk);
                $keg =  DB::table('kegiatan')->where('kegiatan.id_keg', '=', $id)->get()->first();
                $dt_m->addColumn(
                    $idk,
                    function ($data) use ($id, $idk, $keg) {
                        $pek = $data->$idk;
                        if ($data->$idk == "0") {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                                    class="btn btn-danger btn-circle font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Belum diinput. Input Biaya!">
                                    ' . "Rp " . number_format($data->$idk, 2, ',', '.') . '
                                </span>
                                <style>
                                .btn-circle {
                                    font-size: 15px;
                                    text-align: center;
                                    color: #4154f1;
                                    background: #f6f6fe;
                                }
                                </style>
                                <script>
                                $(".kegiatan_pek").on("click", function(){
                                    var tahun_ = $(this).attr("tahun");
                                    var bulan_ = $(this).attr("bulan");
                                    var id_tim_ = $(this).attr("id_tim");
                                    var id_keg_ = $(this).attr("id_keg");
                                    var id_anggota_ = $(this).attr("id_anggota");
        
                                    console.log([tahun_, bulan_, id_tim_ ,id_keg_, id_anggota_]);
                                    var cookie_pek ={tahun:tahun_,bulan:bulan_,id_tim:id_tim_ ,id_keg:id_keg_, id_anggota:id_anggota_};
                                    console.log("xixixi",cookie_pek);
                                    var now = new Date();
                                    var expireTime = now.getTime() + 5;
                                    now.setTime(expireTime);
                                    $.cookie("cookie_pek", JSON.stringify(cookie_pek), { path: "/" });
                                    console.log($.cookie("cookie_pek"));
                                    window.location = "'.url("view-pekerjaan").'"
                                })
                                $("[data-toggle=tooltip]").tooltip();
                                </script>';
                        } else if ($data->$idk == "-") {
                            return $data->$idk;
                        } else {
                            return
                                '<span id_keg="' . $keg->id_keg . '" tahun="' . $keg->tahun . '" bulan="' . $keg->bulan . '" id_tim="' . $keg->subject_meter . '" id_anggota="' . $data->id_anggota . '"
                class="btn btn-info font-weight-bold kegiatan_pek btn-circle style="opacity:0.5;" pekerjaan-row btn-sm" data-toggle="tooltip" data-placement="top" title="Lihat Biaya">
                ' . "Rp " . number_format($data->$idk, 2, ',', '.') . '
            </span>';
                        }
                    }
                );
                // ->rawColumns([$idk]);
            }
            $dt_m->addColumn('total_pekerjaan', function ($data) {
                return '<button type="button"  class="btn btn-circle" disabled>' . "Rp " . number_format($data->total_pekerjaan, 2, ',', '.') . '</span>
  </button>';
            });
            array_push($nama_keg, 'total_pekerjaan');
            return $dt_m->rawColumns($nama_keg)->make(true);
        }
        return view('/view-matbi', compact('result_m', 'unique_keg_m'));
    }

    // tesaja
    public function tesaja(Request $request)
    {
        return view('tesaja');
    }


    public function store_alok(Request $request)
    {
        //
        $request->except(['_token']);

        $validator = Validator::make($request->all(), [
            'kegiatan' => 'required',
            'selected_anggota' => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $error_message = [];
            for ($i = 0; $i < count($request->selected_anggota); $i++) {
                # code...
                try {
                    //code...
                    Alokasikegiatan::insert([
                        'id_keg' => $request->kegiatan,
                        'id_anggota' => ($request->selected_anggota)[$i],
                        'created_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    $error_alok = $this->getAnggota(($request->selected_anggota)[$i]);
                    array_push($error_message, $error_alok->nama . ' (' . $error_alok->email . ')');
                }
            }
        }
        if ($error_message == []) {
            Session::flash('status_create_alokasikegiatan', count($request->selected_anggota) . ' Alokasi berhasil ditambahkan');
        } elseif (count($error_message) == count($request->selected_anggota)) {
            Session::flash('status_gagalcreate_alokasikegiatan', 'Gagal menambahkan ' . count($error_message) . ' anggota: ' . implode(', ', $error_message));
        } else {
            Session::flash('status_warningcreate_alokasikegiatan', 'Anggota berhasil ditambahkan: ' . (count($request->selected_anggota) - count($error_message)) . ' orang' . '; Anggota gagal ditambahkan: ' . count($error_message)  . ' orang' . '->' . implode(', ', $error_message));
        }
        return redirect('/view/alokasi-' . $request->kegiatan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getSmArr()
    {
        return DB::table("tim")->select("id_tim", "tim")->get();
    }


    public function create()
    {
        return view('create-kegiatan')->with(['sm_arr' => $this->getSmArr()]);
    }

    public function forDashboard()
    {
        $jlh_keg_without_anggota = DB::table('kegiatan')->join('alokasikegiatan', 'alokasikegiatan.id_keg', '=', 'kegiatan.id_keg', 'left')->whereNull('alokasikegiatan.id_anggota')->distinct()->count();
        $jlh_keg_without_pek = DB::table('kegiatan')->join('pekerjaan', 'pekerjaan.id_keg', '=', 'kegiatan.id_keg', 'left')->whereNull('pekerjaan.id_pekerjaan')->distinct()->count();
        $jlh_alok_without_pek = DB::table('alokasikegiatan')
            ->leftJoin('pekerjaan', function ($join) {
                $join->on('pekerjaan.id_anggota', '=', 'alokasikegiatan.id_anggota');
                $join->on('pekerjaan.id_keg', '=', 'alokasikegiatan.id_keg');
            })->whereNull('pekerjaan.id_pekerjaan')->distinct()->count();
        return view('index', compact('jlh_keg_without_anggota', 'jlh_keg_without_pek', 'jlh_alok_without_pek'));
    }

    // public function downloadReport()
    // {

    //     return view('view-download-report');
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->except(['_token']);

        $validator = Validator::make($request->all(), [
            'kegiatan' => 'required|min:3|max:150',
            'tahun' => 'required',
            'bulan' => 'required|min:1|max:12',
            'subject_meter' => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator);
        } else {
            Kegiatan::insert([
                'kegiatan' => $request->kegiatan,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'subject_meter' => $request->subject_meter,
            ]);
        }

        Session::flash('status_create_kegiatan', 'Kegiatan berhasil ditambahkan');
        return redirect('/view-kegiatan');
    }

    public function view_pekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kegiatanVal' => 'required',
            'anggotaVal' => 'required',
            'uraian_pekerjaan' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'satuan_lain' => 'required_if:satuan,==,"Lainnya"|nullable',
            'harga_satuan' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        } else {
            try {
                $arr_pek = array(
                    'id_keg' => $request->kegiatanVal,
                    'id_anggota' => $request->anggotaVal,
                    'uraian_pekerjaan' => $request->uraian_pekerjaan,
                    'target' => $request->target,
                    'satuan' => $request->satuan,
                    'harga_satuan' => $request->harga_satuan,
                    'created_at' => Carbon::now(),
                );
                if ($request->satuan_lain) {
                    $arr_pek['satuan'] = $request->satuan_lain;
                }
                Pekerjaan::insert($arr_pek);
                $arr_keg = DB::table('kegiatan')->where('id_keg', $request->kegiatanVal)->first();
                echo json_encode(array(
                    'tahun' => $arr_keg->tahun,
                    'bulan' => $arr_keg->bulan,
                    'id_tim' => $arr_keg->subject_meter,
                    'id_keg' => $arr_keg->id_keg,
                    'id_anggota' => $request->anggotaVal
                ));
            } catch (\Throwable $th) {
                Session::flash('status_gagalcreate_pekerjaan', 'Gagal Menambahkan pekerjaan');
                return redirect('/view-pekerjaan');
            }
        }
        return Session::flash('status_create_pekerjaan', 'Pekerjaan berhasil ditambahkan');
    }


    public function update_pekerjaan(Request $request, $id_pekerjaan)
    {
        // $request->except(['_token']);
        $validator = Validator::make($request->all(), [
            'kegiatanVal' => 'required',
            'anggotaVal' => 'required',
            'uraian_pekerjaan' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'satuan_lain' => 'required_if:satuan,==,"Lainnya"|nullable',
            'harga_satuan' => 'required',
        ]);
        if ($validator->fails()) {
            # code...
            return response()->json(array(
                'success' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        } else {
            try {
                //code...
                $arr_pek = array(
                    'id_keg' => $request->kegiatanVal,
                    'id_anggota' => $request->anggotaVal,
                    'uraian_pekerjaan' => $request->uraian_pekerjaan,
                    'target' => $request->target,
                    'satuan' => $request->satuan,
                    'harga_satuan' => $request->harga_satuan,
                    'created_at' => Carbon::now(),
                );
                if ($request->satuan_lain) {
                    $arr_pek['satuan'] = $request->satuan_lain;
                }
                $item = Pekerjaan::findOrFail($id_pekerjaan);
                $item->update($arr_pek);
                $arr_keg = DB::table('kegiatan')->where('id_keg', $request->kegiatanVal)->first();
                echo json_encode(array(
                    'tahun' => $arr_keg->tahun,
                    'bulan' => $arr_keg->bulan,
                    'id_tim' => $arr_keg->subject_meter,
                    'id_keg' => $arr_keg->id_keg,
                    'id_anggota' => $request->anggotaVal
                ));
            } catch (\Throwable $th) {
                //throw $th;
                Session::flash('status_gagaledit_pekerjaan', 'Gagal Mengedit pekerjaan');
                return redirect('/view-pekerjaan');
            }
        }
        // return response()->json(array('success' => true), 200);
        return Session::flash('status_edit_pekerjaan', 'Pekerjaan berhasil diedit');
    }

    public function store_pekerjaan(Request $request)
    {
        // $request->except(['_token']);
        // var_dump($request->kegiatanVal);
        $validator = Validator::make($request->all(), [
            'kegiatanVal' => 'required|exists:kegiatan,id_keg',
            'anggotaVal' => 'required|exists:alokasikegiatan,id_anggota,id_keg,' . $request->kegiatanVal,
            'uraian_pekerjaan' => 'required',
            'target' => 'required|numeric',
            'satuan' => 'required',
            'satuan_lain' => 'required_if:satuan,==,"Lainnya"|nullable',
            'harga_satuan' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        } else {
            try {
                $arr_pek = array(
                    'id_keg' => $request->kegiatanVal,
                    'id_anggota' => $request->anggotaVal,
                    'uraian_pekerjaan' => $request->uraian_pekerjaan,
                    'target' => $request->target,
                    'satuan' => $request->satuan,
                    'harga_satuan' => $request->harga_satuan,
                    'created_at' => Carbon::now(),
                );
                if ($request->satuan_lain) {
                    $arr_pek['satuan'] = $request->satuan_lain;
                }
                Pekerjaan::insert($arr_pek);
                $arr_keg = DB::table('kegiatan')->where('id_keg', $request->kegiatanVal)->first();
                echo json_encode(array(
                    'tahun' => $arr_keg->tahun,
                    'bulan' => $arr_keg->bulan,
                    'id_tim' => $arr_keg->subject_meter,
                    'id_keg' => $arr_keg->id_keg,
                    'id_anggota' => $request->anggotaVal
                ));
            } catch (\Throwable $th) {
                //throw $th;
                Session::flash('status_gagalcreate_pekerjaan', 'Gagal Menambahkan pekerjaan');
                return redirect('/view-pekerjaan');
            }
        }
        // return response()->json(array('success' => true), 200);
        return Session::flash('status_create_pekerjaan', 'Pekerjaan berhasil ditambahkan');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function show($id_keg)
    {
        //
        $kegiatan = Kegiatan::findOrFail($id_keg);
        $arr_tim = $this->getSmArr();
        $sm_arr = $arr_tim;
        return view('edit-kegiatan')->with(['kegiatan' => $kegiatan, 'sm_arr' => $sm_arr]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kegiatan $kegiatan)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_keg)
    {
        $request->except(['_token']);
        $item = Kegiatan::findOrFail($id_keg);

        $validator = Validator::make($request->all(), [
            'kegiatan' => 'required|min:3|max:150',
            'tahun' => 'required',
            'bulan' => 'required',
            'subject_meter' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $item->update([
                'kegiatan' => $request->kegiatan,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'subject_meter' => $request->subject_meter,
            ]);
        }

        Session::flash('status_update_kegiatan', 'Kegiatan berhasil diperbarui');
        return redirect('/view-kegiatan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_keg)
    {
        Kegiatan::findOrFail($id_keg)->delete();
        return redirect('/view-kegiatan')->with('status_destroy_kegiatan', 'Kegaiatan berhasil dihapus');
    }

    public function getAnggota($id_anggota)
    {
        $anggota = '';
        $row_mit = DB::table('mitra')->where('sobatid', $id_anggota)->first();
        if ($row_mit) {
            $anggota = $row_mit;
        } else {
            $row = DB::table('users')->where('nip', $id_anggota)->first();
            if ($row) {
                $anggota = $row;
            }
        }
        return $anggota;
    }

    public function destroy_alokasikegiatan($id_keg, $id_anggota)
    {
        DB::table('alokasikegiatan')->where('id_keg', $id_keg)->where('id_anggota', $id_anggota)->delete();
        return redirect('/view/alokasi-' . $id_keg)->with('status_destroy_alokasikegiatan', 'Anggota kegiatan: ' . $id_anggota . '_' . $this->getAnggota($id_anggota)->nama . ' berhasil dihapus');
    }

    public function destroy_pekerjaan(Request $request, $id_pekerjaan)
    {
        $pekerjaan = DB::table('pekerjaan')->where('id_pekerjaan', $id_pekerjaan)->get()->first();
        DB::table('pekerjaan')->where('id_pekerjaan', $id_pekerjaan)->delete();
        return redirect('/view-pekerjaan')->with('status_destroy_pekerjaan', 'Uraian Pekerjaan: ' . $pekerjaan->id_anggota . '_' . $this->getAnggota($pekerjaan->id_anggota)->nama . ' berhasil dihapus');
    }
}

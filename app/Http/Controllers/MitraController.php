<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Models\RegisteredMitra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use \Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kecamatan = DB::table('kecamatan')->get();
        if ($request->ajax()) {
            $data = DB::table('mitra')->join('kecamatan', 'kecamatan.id_kec', '=', 'mitra.id_kec', 'left')->get();
            if ($request->input('kecamatan_filter') != null) {
                $data = $data->where('id_kec', '=', $request->kecamatan_filter);
            }
            return DataTables::of($data)
                ->addColumn('sobatid', function ($data) {
                    return $data->sobatid;
                })->addColumn('nama', function ($data) {
                    return $data->nama;
                })->addColumn('email', function ($data) {
                    return $data->email;
                })->addColumn('kecamatan', function ($data) {
                    return $data->kecamatan;
                })->addColumn('aksi', function ($data) {
                    $results = '';
                    if (auth()->user()->admin == 1) {
                        $results = $results .
                            '<a href="' . url('/edit/mitra-' . $data->sobatid) . '"
                                class="btn btn-warning btn-sm"
                                data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="#" id="' . $data->sobatid . '" data-id="' . $data->sobatid . '"
                                class="btn btn-danger delete-row btn-sm"  data-toggle="tooltip" data-placement="top" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>';
                        $results = $results . '
                            <script>
                            $(".delete-row").click(function() {
                                var sobatid = $(this).attr("id");
                                var dataid = $(this).attr("data-id");
                                Swal.fire({
                                        title: "Yakin?",
                                        text: "Anda ingin menghapus data mitra dengan SOBATID " + sobatid + "",
                                        icon: "warning",
                                        showCancelButton: true,
                                    })
                                    .then((willDelete) => {
                                        if (willDelete.isConfirmed) {
                                            window.location = "/hapus/mitra-" + dataid + ""
                                        }
                                    });
                            })
                            
                            $("[data-toggle=tooltip]").tooltip();
                            </script>
                            ';
                    }
                    return $results;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('/view-mitra', compact('request', 'kecamatan'));
    }

    public function filter_tahun($tahun)
    {
        if ($tahun == "00") {
            $kecamatan = DB::table('registeredmitra')->join('mitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')->join('kecamatan', 'kecamatan.id_kec', '=', 'mitra.id_kec', 'left')->select('kecamatan.id_kec', 'kecamatan.kecamatan')->groupBy('kecamatan.id_kec', 'kecamatan.kecamatan')
                ->get();
        } else {
            $kecamatan = DB::table('registeredmitra')->where('tahun', $tahun)->join('mitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')->join('kecamatan', 'kecamatan.id_kec', '=', 'mitra.id_kec', 'left')->select('kecamatan.id_kec', 'kecamatan.kecamatan')->groupBy('kecamatan.id_kec', 'kecamatan.kecamatan')
                ->get();
        }
        return $kecamatan;
    }

    public function mitra_dropdown($tahun)
    {

        $mitra_dropdown = DB::table('mitra')
            ->join('registeredmitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')
            ->where(function ($query) use ($tahun) {
                $query->where('registeredmitra.tahun', '=', null)
                    ->orWhere('registeredmitra.tahun', '!=', $tahun);
            })
            ->select('mitra.sobatid','mitra.nama','mitra.email')
            // ->orderBy('registeredmitra.sobatid', 'asc')
            ->get();
        return $mitra_dropdown;
    }

    public function index_registered(Request $request)
    {
        $data = DB::table('registeredmitra')->join('mitra', 'mitra.sobatid', '=', 'registeredmitra.sobatid', 'left')->join('kecamatan', 'kecamatan.id_kec', '=', 'mitra.id_kec', 'left')->select('mitra.sobatid', 'mitra.nama', 'mitra.email', 'registeredmitra.tahun', 'kecamatan.id_kec', 'kecamatan.kecamatan', 'registeredmitra.created_at')->get();
        $tahun = RegisteredMitra::select('tahun')->orderBy('tahun', 'asc')->groupBy('tahun')->get();
        $mitra_dropdown = null;

        $kecamatan = DB::table('kecamatan')->get();
        if ($request->ajax()) {
            if ($request->tahun_filter != "") {
                $data = $data->where('tahun', '=', $request->tahun_filter);
            }
            if ($request->kecamatan_filter != "") {
                $data = $data->where('id_kec', '=', $request->kecamatan_filter);
            }
            return DataTables::of($data)
                ->addColumn('tahun', function ($data) {
                    return '<span class="d-inline-flex mb-3 px-2 py-1 fw-semibold text-primary bg-primary bg-opacity-10 border border-success border-opacity-10 rounded-2">' . $data->tahun . '</span>';
                })
                ->addColumn('sobatid', function ($data) {
                    return $data->sobatid;
                })->addColumn('nama', function ($data) {
                    return $data->nama;
                })->addColumn('email', function ($data) {
                    return $data->email;
                })->addColumn('kecamatan', function ($data) {
                    if ($data->kecamatan) {
                        # code...
                        return '<span class="d-inline-flex mb-3 px-2 py-1 fw-semibold text-primary bg-primary bg-opacity-10 border border-success border-opacity-10 rounded-2">' . '[' . $data->id_kec . '] ' . $data->kecamatan . '</span>';
                    } else {
                        return '<span class="d-inline-flex mb-3 px-2 py-1 fw-semibold text-danger bg-danger bg-opacity-10 border border-danger border-opacity-10 rounded-2">' . 'DILUAR KAB. DELI SERDANG' . '</span>';
                    }
                })->addColumn('created_at', function ($data) {
                    return $data->created_at;
                })->addColumn('aksi', function ($data) {
                    $results = '';
                    if (auth()->user()->admin == 1) {
                        $results = $results . '<a href="#" id="' . $data->sobatid . '" data-id="' . $data->sobatid . '" nama="' . $data->nama . '"
                                    class="btn btn-danger delete-row btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="top" data-bs-original-title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>';
                    }
                    $results = $results . '<script>              
                                            $(".delete-row").click(function() {
                                                var sobatid = $(this).attr("id");
                                                var dataid = $(this).attr("data-id");
                                                var tahun = ' . $data->tahun . ';
                                                var nama = $(this).attr("nama");
                                                Swal.fire({
                                                        title: "Yakin?",
                                                        text: "Anda ingin menghapus data pendafatran mitra dengan SOBATID " + sobatid + " atas nama " + nama + " pada tahun " + tahun +"?",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                    })
                                                    .then((willDelete) => {
                                                        if (willDelete.isConfirmed) {
                                                            window.location = "hapus/registeredmitra-" + dataid + "-" + tahun
                                                        }
                                                    });
                                            })
                                            </script>';
                    return $results;
                })
                ->rawColumns(['tahun', 'kecamatan', 'aksi'])
                ->make(true);
        }
        // dd($mitra_dropdown);
        return view('/view-registeredmitra', compact('request', 'tahun', 'kecamatan', 'mitra_dropdown'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $kecamatan = DB::table('kecamatan')->get();
        return view('/create-mitra')->with(['kecamatan' => $kecamatan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->except(['_token']);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:55',
            'sobatid' => 'required|digits:12',
            'email' => 'required|max:30',
            'asal_kecamatan' => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            try {
                //code...
                Mitra::insert([
                    'nama' => $request->nama,
                    'sobatid' => $request->sobatid,
                    'email' => $request->email,
                    'id_kec' => $request->asal_kecamatan,
                ]);
            } catch (\Exception $e) {
                Session::flash('status_gagalcreate_mitra', $e->getMessage());
                return Redirect::back()->withInput();
            }
        }
        Session::flash('status_create_mitra', 'Mitra baru berhasil ditambahkan');
        return redirect('/view-mitra');
    }


    public function store_registered(Request $request)
    {
        // dd("Yiha");
        $request->except(['_token']);

        $validator = Validator::make($request->all(), [
            'selected_tahun' => 'required',
            'selected_mitra' => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $error_message = [];
            // dd($request->selected_mitra);
            for ($i = 0; $i < count($request->selected_mitra); $i++) {
                # code...
                try {
                    //code...
                    RegisteredMitra::insert([
                        'tahun' => $request->selected_tahun,
                        'sobatid' => ($request->selected_mitra)[$i],
                        'created_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    $error_mitra = Mitra::findOrFail(($request->selected_mitra)[$i]);
                    array_push($error_message, $error_mitra->nama . ' (' . $error_mitra->email . ')');
                }
            }
        }
        if ($error_message == []) {
            Session::flash('status_create_registeredmitra', count($request->selected_mitra) . ' Mitra berhasil ditambahkan');
        } elseif (count($error_message) == count($request->selected_mitra)) {
            Session::flash('status_gagalcreate_registeredmitra', 'Gagal menambahkan mitra: ' . implode(', ', $error_message));
        } else {
            Session::flash('status_warningcreate_registeredmitra', '<b>Mitra berhasil ditambahkan: ' . (count($request->selected_mitra) - count($error_message)) . ' orang</b>' . '<p>Mitra gagal ditambahkan: ' . count($error_message)  . ' orang:' . '</p>' . implode(',', $error_message));
        }
        return redirect('view-registeredmitra');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mitra  $Mitra
     * @return \Illuminate\Http\Response
     */
    public function show($sobatid)
    {
        //
        $edited_mitra = DB::table('mitra')->where('sobatid', $sobatid)->first();
        // dd($edited_mitra);
        $kecamatan = DB::table('kecamatan')->get();
        return view('/edit-mitra')->with(['kecamatan' => $kecamatan, 'edited_mitra' => $edited_mitra]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mitra  $Mitra
     * @return \Illuminate\Http\Response
     */
    public function edit($sobatid)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mitra  $Mitra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sobatid)
    {
        //
        $request->except(['_token']);
        $item = Mitra::findOrFail($sobatid);

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator);
        } else {
            try {
                //code...
                $item->update([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'id_kec' => $request->asal_kecamatan,
                ]);
            } catch (\Exception $e) {
                //throw $th;
                Session::flash('status_gagaledit_mitra', $e->getMessage());
                return Redirect::back()->withInput();
            }
        }

        Session::flash('status_update_mitra', 'Mitra berhasil diperbarui');
        return redirect('/view-mitra');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mitra  $Mitra
     * @return \Illuminate\Http\Response
     */
    public function destroy($sobatid)
    {
        //
        Mitra::findOrFail($sobatid)->delete();
        return redirect('/view-mitra')->with('status_destroy_mitra', 'Mitra berhasil dihapus');
    }
    public function destroy_registered($sobatid, $tahun)
    {
        //
        DB::table('registeredmitra')->where('sobatid', $sobatid)->where('tahun', $tahun)->delete();
        return redirect('/view-registeredmitra')->with('status_destroy_registeredmitra', 'Pendaftaran mitra berhasil dihapus');
    }
}

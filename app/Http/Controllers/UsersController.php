<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\users;
use App\Models\jabatan;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        function role($admin)
        {
            switch ($admin) {
                case 0:
                    return 'Anggota';
                case 1:
                    return 'Admin';
                case 2:
                    return 'Ketua Tim';
                case 3:
                    return 'Viewer';
                default:
                    return;
            }
        }
        $data = DB::table('users')->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('nip', function ($data) {
                    return $data->nip;
                })->addColumn('nama', function ($data) {
                    return $data->nama;
                })->addColumn('email', function ($data) {
                    return $data->email;
                })->addColumn('jabatan', function ($data) {
                    return $data->jabatan;
                })->addColumn('admin', function ($data) {
                    return role($data->admin);
                })->addColumn(
                    'aksi',
                    function ($data) {
                        $results = '';
                        if (auth()->user()->admin == 1) {
                            $results = $results .
                                '<a href="' . url('/edit/pegawai-' . $data->nip) . '" 
                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-original-title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="#" id="' . $data->nip . '" data-id="' . $data->nip . '"
                                class="btn btn-danger delete-row btn-sm" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-original-title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>

                            <script>
                            $(".delete-row").click(function() {
                                var nip = $(this).attr("id");
                                var dataid = $(this).attr("data-id");
                                Swal.fire({
                                        title: "Yakin?",
                                        text: "Anda ingin menghapus data pegawai dengan NIP " + nip + "",
                                        icon: "warning",
                                        showCancelButton: true,
                                    })
                                    .then((willDelete) => {
                                        if (willDelete.isConfirmed) {
                                            window.location = "hapus/pegawai-" + dataid + ""
                                        }
                                    });
                            })
                            </script>';
                        }
                        return $results;
                    }
                )->rawColumns(['aksi'])
                ->make(true);
        }
        return view('/view-pegawai', compact('request', 'data'));
    }

    public function profil()
    {
        return view('/view-profil');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $jabatan = DB::table('jabatan')->get('jabatan');
        return view('/create-pegawai')->with(['jabatan' => $jabatan]);
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
            'nip' => 'required|digits:9',
            'email' => 'required|max:30',
            'jabatan' => 'required',
            'admin' => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return Redirect::back()->withErrors($validator)->withInput();;
        } else {
            try {
                //code...
                Users::insert([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'email' => $request->email,
                    'jabatan' => $request->jabatan,
                    'admin' => $request->admin,
                ]);
            } catch (\Exception $e) {
                //throw $th;
                Session::flash('status_gagalcreate_pegawai', $e->getMessage());
                return Redirect::back()->withInput();
            }
        }

        Session::flash('status_create_pegawai', 'Pegawai berhasil ditambahkan');
        return redirect('/view-pegawai');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\users  $users
     * @return \Illuminate\Http\Response
     */
    public function show($nip)
    {
        $edited_pegawai = Users::findOrFail($nip);
        $jabatan = DB::table('jabatan')->get('jabatan');
        return view('/edit-pegawai')->with(['edited_pegawai' => $edited_pegawai, 'jabatan' => $jabatan]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nip)
    {
        //
        $request->except(['_token']);
        $item = Users::findOrFail($nip);

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'admin' => 'required',
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
                    'jabatan' => $request->jabatan,
                    'admin' => $request->admin,
                ]);
            } catch (Exception $e) {
                //throw $th;
                Session::flash('status_gagaledit_mitra', $e->getMessage());
                return Redirect::back()->withInput();
            }
        }

        Session::flash('status_update_pegawai', 'Pegawai berhasil diperbarui');
        return redirect('/view-pegawai');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($nip)
    {
        Users::findOrFail($nip)->delete();
        return redirect('/view-pegawai')->with('status_destroy_pegawai', 'Pegawai berhasil dihapus');
    }
}

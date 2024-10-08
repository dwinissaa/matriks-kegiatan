<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\pekerjaanImport;
use App\Imports\valpekerjaanImport;
use App\Imports\pekerjaanSheetImport;
use Illuminate\Support\Facades\Validator;
use App\Models\Pekerjaan;
use Maatwebsite\Excel\HeadingRowImport;


class ImportController extends Controller
{
    public function index(Request $request, $id_keg)
    {
        $keg = DB::table('kegiatan')->where('id_keg', $id_keg)
            ->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->join('users', 'users.nip', '=', 'tim.nip_ketim', 'left')
            ->first();
        $data_mitra = DB::table('alokasikegiatan')->where('id_keg', $id_keg)
            ->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')
            ->whereNotNull('mitra.sobatid')
            ->select('id_anggota', 'mitra.email', 'mitra.nama', 'alokasikegiatan.created_at', 'id_keg')
            ->selectSub(function ($query) {
                $query->selectRaw(0);
            }, 'pegawai');
        $data = DB::table('alokasikegiatan')->where('id_keg', $id_keg)
            ->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')
            ->whereNotNull('users.nip')->union($data_mitra)
            ->select('id_anggota', 'users.email', 'users.nama', 'alokasikegiatan.created_at', 'id_keg')
            ->selectSub(function ($query) {
                $query->selectRaw(1);
            }, 'pegawai')
            ->orderBy('pegawai', 'desc')
            ->orderBy('created_at', 'desc')->get();
        return view('imports.pekerjaan', compact('keg'));
    }

    public function valSheetName(Request $request, $id_keg)
    {
        // Check Sheet Names
        $file = $request->file('file')->store('import');
        $import2 = new valpekerjaanImport($id_keg);
        $import2->import($file);
        return $import2->getSheetNames();
    }

    public function importValidated(Request $request, $id_keg)
    {
        //Validated
        return (new pekerjaanImport($id_keg))->import($request->file('file')->store('import'));
    }


    // STORE DATA IMPORT EXCEL
    public function updateOrCreate(Request $request, $id_keg)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx, csv, xls'
        ]);

        // $res = $this->valSheetName($request, $id_keg);
        // $sheetName = $res;

        // $ketemu = false;
        // $est = "Template Impor";
        // foreach ($sheetName as $key => $value) {
        //     if ($value == $est) {
        // $file = $request->file('file')->store('import');
        // (new pekerjaanImport($id_keg))->import($file);
        $this->importValidated($request, $id_keg);
        //         $ketemu = true;
        //         break;
        //     }
        // }
        // if (!$ketemu) {
        //     return back()->with('status_error_import_pekerjaan', 'Nama Sheet harus: "' . $est . '"');
        // }
        return back()->with('status_import_pekerjaan', 'Excel file imported successfully.');
    }
}

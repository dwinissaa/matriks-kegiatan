<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Route;
use Sabberworm\CSS\Property\Import;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->middleware('auth');
Route::get('/', [KegiatanController::class, 'forDashboard'])->middleware('auth');

// Login
Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::post('/postlogin', [LoginController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// Read
Route::get('/view-profil', [UsersController::class, 'profil'])->middleware('auth'); // OK
Route::get('/view-pegawai', [UsersController::class, 'index'])->middleware('auth'); // OK
Route::get('/view-mitra', [MitraController::class, 'index'])->middleware('auth'); // OK
Route::get('/view-registeredmitra', [MitraController::class, 'index_registered'])->middleware('auth'); // OK
Route::get('/registeredmitra-list/tahun={tahun}', [MitraController::class, 'mitra_dropdown'])->middleware('auth')->middleware('is_admin'); // OK

Route::get('/view-kegiatan', [KegiatanController::class, 'index'])->middleware('auth'); // OK
Route::get('/view/alokasi-{id_keg}', [KegiatanController::class, 'detail_alokasi'])->middleware('auth'); // OK
Route::get('/matriks-kegiatan', [KegiatanController::class, 'index_matkeg'])->middleware('auth'); // ok
Route::get('/matriks-kegiatan-mitra', [KegiatanController::class, 'index_matkeg_mitra'])->middleware('auth'); // ok
Route::get('/getUser/role={role}/tahun={tahun}/bulan={bulan}', [KegiatanController::class, 'getUser'])->middleware('auth'); // ok
Route::get('/matriks-pekerjaan', [KegiatanController::class, 'index_matpek'])->middleware('auth'); // ok
Route::get('/matriks-pekerjaan-mitra', [KegiatanController::class, 'index_matpek_mitra'])->middleware('auth'); // ok
Route::get('/getUserPek/role={role}/tahun={tahun}/bulan={bulan}', [KegiatanController::class, 'getUserPek'])->middleware('auth'); // ok
Route::get('/matriks-biaya', [KegiatanController::class, 'index_matbi'])->middleware('auth');
Route::get('/matriks-biaya-mitra', [KegiatanController::class, 'index_matbi_mitra'])->middleware('auth');
Route::get('/getUserBi/role={role}/tahun={tahun}/bulan={bulan}', [KegiatanController::class, 'getUserBi'])->middleware('auth');
Route::get('/download-report', [ExportController::class, 'index'])->middleware('auth');
Route::get('/download-report-mitra', [ExportController::class, 'index_mitra'])->middleware('auth');
Route::get('/import-pekerjaan/id_keg={id_keg}', [ImportController::class, 'index'])->middleware('auth')->middleware('is_admin_ketim');

// Export
Route::get('/export/pekerjaan/tahun={tahun}/bulan={bulan}/id_anggota={id_anggota}/format={format}', [ExportController::class, 'export_pekerjaan'])->middleware('auth');
Route::get('/export/templatepekerjaan/id_keg={id_keg}', [ExportController::class, 'export_templatepekerjaan'])->middleware('auth');

// Import
Route::post('/import/pekerjaan/id_keg={id_keg}', [ImportController::class, 'updateOrCreate'])->middleware('auth')->middleware('is_admin_ketim');

// Route::get('/tesaja',[KegiatanController::class, 'tesaja'])->middleware('auth');

// Filter
Route::get('/filterRegistered/tahun-{tahun}', [MitraController::class, 'filter_tahun'])->middleware('auth'); // OK
Route::get('/view-pekerjaan', [KegiatanController::class, 'index_pekerjaan'])->middleware('auth'); // OK
Route::get('/filterPekerjaan/role={role}/tahun={tahun}', [KegiatanController::class, 'filter_tahun'])->middleware('auth'); // OK
Route::get('/filterPekerjaan/role={role}/tahun={tahun}/bulan={bulan}', [KegiatanController::class, 'filter_bulan'])->middleware('auth'); // OK
Route::get('/filterPekerjaan/role={role}/tahun={tahun}/bulan={bulan}/tim={tim}', [KegiatanController::class, 'filter_tim'])->middleware('auth'); // OK
Route::get('/filterPekerjaan/role={role}/kegiatan={kegiatan}', [KegiatanController::class, 'filter_kegiatan'])->middleware('auth'); // OK

// Create
Route::get('/create-pegawai', [UsersController::class, 'create'])->middleware('auth')->middleware('is_admin'); // OK
Route::get('/create-mitra', [MitraController::class, 'create'])->middleware('auth')->middleware('is_admin'); // OK
Route::get('/create-kegiatan', [KegiatanController::class, 'create'])->middleware('auth')->middleware('is_admin_ketim'); // OK

// Store
Route::post('store-pegawai', [UsersController::class, 'store'])->middleware('auth')->middleware('is_admin'); // OK
Route::post('store-kegiatan', [KegiatanController::class, 'store'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::post('store-alok', [KegiatanController::class, 'store_alok'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::post('store-mitra', [MitraController::class, 'store'])->middleware('auth')->middleware('is_admin'); // OK
Route::post('store-registeredmitra', [MitraController::class, 'store_registered'])->middleware('auth')->middleware('is_admin'); // OK
Route::post('store-pekerjaan', [KegiatanController::class, 'store_pekerjaan'])->middleware('auth')->middleware('is_admin_ketim'); // OK

// Edit
Route::get('/edit/pegawai-{nip}', [UsersController::class, 'show'])->middleware('auth')->middleware('is_admin'); // OK
Route::get('/edit/kegiatan-{id_keg}', [KegiatanController::class, 'show'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::get('/edit/mitra-{sobatid}', [MitraController::class, 'show'])->middleware('auth')->middleware('is_admin');

// Update
Route::post('/update/pegawai-{nip}', [UsersController::class, 'update'])->middleware('auth')->middleware('is_admin'); // OK
Route::post('/update/kegiatan-{id_keg}', [KegiatanController::class, 'update'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::post('/update/mitra-{sobatid}', [MitraController::class, 'update'])->middleware('auth')->middleware('is_admin'); // OK
Route::post('/update/pekerjaan-{id_pekerjaan}', [KegiatanController::class, 'update_pekerjaan'])->middleware('auth')->middleware('is_admin_ketim'); // OK

// Destroy
Route::get('/hapus/pegawai-{nip}', [UsersController::class, 'destroy'])->middleware('auth')->middleware('is_admin'); // OK
Route::get('/hapus/mitra-{sobatid}', [MitraController::class, 'destroy'])->middleware('auth')->middleware('is_admin'); // OK
Route::get('/hapus/kegiatan-{id_keg}', [KegiatanController::class, 'destroy'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::get('/hapus/registeredmitra-{sobatid}-{tahun}', [MitraController::class, 'destroy_registered'])->middleware('auth')->middleware('is_admin'); //OK
Route::get('/hapus/alokasikegiatan-{id_keg}-{id_anggota}', [KegiatanController::class, 'destroy_alokasikegiatan'])->middleware('auth')->middleware('is_admin_ketim'); // OK
Route::get('/hapus/pekerjaan-{id_pekerjaan}', [KegiatanController::class, 'destroy_pekerjaan'])->middleware('auth')->middleware('is_admin_ketim'); // OK

// Destroy Selected
Route::get('/destroy/selectedPekerjaan', [KegiatanController::class, 'destroySelectedPekerjaan'])->middleware('auth')->middleware('is_admin_ketim'); // OK
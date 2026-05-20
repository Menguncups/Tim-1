<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\VerifikasiController;

Route::get('/', function () {
    return view('welcome');
});



/*
|--------------------------------------------------------------------------
| OPERATOR
|--------------------------------------------------------------------------
*/

Route::prefix('operator')->group(function () {

    Route::get('/dashboard', function () {
        return view('operator.dashboard');
    });

    Route::get('/tambah-data', [PegawaiController::class, 'create']);
    Route::post('/tambah-data', [PegawaiController::class, 'store']);

    Route::get('/verifikasi/jabfung', [VerifikasiController::class, 'jabfung']);
    Route::post('/verifikasi/jabfung/{id}', [VerifikasiController::class, 'verifikasiJabfung']);

    Route::get('/verifikasi/panggol', [VerifikasiController::class, 'panggol']);
    Route::post('/verifikasi/panggol/{id}', [VerifikasiController::class, 'verifikasiPanggol']);

    Route::get('/verifikasi/surtug', [VerifikasiController::class, 'surtug']);
    Route::post('/verifikasi/surtug/{id}', [VerifikasiController::class, 'verifikasiSurtug']);
});



/*
|--------------------------------------------------------------------------
| DOSEN / TENDIK
|--------------------------------------------------------------------------
*/

Route::prefix('datadiri')->group(function () {
   
    /*
    |--------------------------------------------------------------------------
    | DATA DIRI
    |--------------------------------------------------------------------------
    */

    Route::get('/{kategori}/read', [PegawaiController::class, 'showRead'])->name('pegawai.read');
    
    // URL Tampilan Form Ubah Kontak (Contoh: /biodata/dosen/update)
    Route::get('/{kategori}/update', [PegawaiController::class, 'showEdit'])->name('pegawai.edit');
    
    // URL Eksekusi simpan data via AJAX POST dari form HTML kamu
    Route::post('/{kategori}/update', [PegawaiController::class, 'processUpdate'])->name('pegawai.update');



    /*
    |--------------------------------------------------------------------------
    | SURAT TUGAS
    |--------------------------------------------------------------------------
    */

    Route::get('/pengajuan/surtug', [PengajuanController::class, 'readSurtug']);

    Route::get('/pengajuan/surtug/create', [PengajuanController::class, 'createSuratTugas']);

    Route::post('/pengajuan/surtug/store', [PengajuanController::class, 'storeSuratTugas']);

    Route::get('/pengajuan/surtug/edit/{id}', [PengajuanController::class, 'editSuratTugas']);

    Route::put('/pengajuan/surtug/update/{id}', [PengajuanController::class, 'updateSuratTugas']);



    /*
    |--------------------------------------------------------------------------
    | JABATAN FUNGSIONAL
    |--------------------------------------------------------------------------
    */

    Route::get('/pengajuan/jabfung', [PengajuanController::class, 'readJabfung']);

    Route::get('/pengajuan/jabfung/create', [PengajuanController::class, 'createJabatanFungsional']);

    Route::post('/pengajuan/jabfung/store', [PengajuanController::class, 'storeJabatanFungsional']);

    Route::get('/pengajuan/jabfung/edit/{id}', [PengajuanController::class, 'editJabatanFungsional']);

    Route::put('/pengajuan/jabfung/update/{id}', [PengajuanController::class, 'updateJabatanFungsional']);



    /*
    |--------------------------------------------------------------------------
    | PANGKAT GOLONGAN
    |--------------------------------------------------------------------------
    */

    Route::get('/pengajuan/panggol', [PengajuanController::class, 'readPanggol']);

    Route::get('/pengajuan/panggol/create', [PengajuanController::class, 'createPangkatGolongan']);

    Route::post('/pengajuan/panggol/store', [PengajuanController::class, 'storePangkatGolongan']);

    Route::get('/pengajuan/panggol/edit/{id}', [PengajuanController::class, 'editPangkatGolongan']);

    Route::put('/pengajuan/panggol/update/{id}', [PengajuanController::class, 'updatePangkatGolongan']);
});



/*
|--------------------------------------------------------------------------
| PIMPINAN
|--------------------------------------------------------------------------
*/

Route::prefix('pimpinan')->group(function () {

    Route::get('/dashboard', function () {
        return view('pimpinan.dashboard');
    });

    Route::get('/persetujuan/jabfung', [VerifikasiController::class, 'persetujuanJabfung']);

    Route::post('/persetujuan/jabfung/{id}', [VerifikasiController::class, 'setujuiJabfung']);



    Route::get('/persetujuan/panggol', [VerifikasiController::class, 'persetujuanPanggol']);

    Route::post('/persetujuan/panggol/{id}', [VerifikasiController::class, 'setujuiPanggol']);



    Route::get('/persetujuan/surtug', [VerifikasiController::class, 'persetujuanSurtug']);

    Route::post('/persetujuan/surtug/{id}', [VerifikasiController::class, 'setujuiSurtug']);
});
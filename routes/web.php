<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/daftar-pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

    Route::get('/tambah-data', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/tambah-data', [PegawaiController::class, 'store'])->name('pegawai.store');

    Route::get('/edit-pegawai/{id}', [PegawaiController::class, 'edit']);
    Route::put('/update-pegawai/{id}', [PegawaiController::class, 'update']);
    
    Route::delete('/hapus-pegawai/{id}', [PegawaiController::class, 'destroy']);
    
    Route::get('/verifikasi/jabfung', [VerifikasiController::class, 'jabfung']);
    Route::post('/verifikasi/jabfung/{id}', [VerifikasiController::class, 'verifikasiJabfung']);

    Route::get('/verifikasi/panggol', [VerifikasiController::class, 'panggol']);
    Route::post('/verifikasi/panggol/{id}', [VerifikasiController::class, 'verifikasiPanggol']);

    Route::get('/verifikasi/surtug', [VerifikasiController::class, 'surtug']);
    Route::post('/verifikasi/surtug/{id}', [VerifikasiController::class, 'verifikasiSurtug']);

    Route::get('/verifikasi/tambah-pegawai', function () {
        return redirect('/operator/tambah-data');
    });
});

/*
|--------------------------------------------------------------------------
| DOSEN / TENDIK
|--------------------------------------------------------------------------
*/

Route::prefix('dosen')->group(function () {

    Route::get('/dashboard', function () {
        return view('dosen.dashboard');
    });

    Route::get('/datadiri', [PegawaiController::class, 'show']);
    Route::get('/datadiri/edit', [PegawaiController::class, 'edit']);
    Route::put('/datadiri/update', [PegawaiController::class, 'update']);

    Route::get('/pengajuan/surtug', [PengajuanController::class, 'readSurtug']);
    Route::get('/pengajuan/surtug/create', [PengajuanController::class, 'createSuratTugas']);
    Route::post('/pengajuan/surtug/store', [PengajuanController::class, 'storeSuratTugas']);
    Route::get('/pengajuan/surtug/edit/{id}', [PengajuanController::class, 'editSuratTugas']);
    Route::put('/pengajuan/surtug/update/{id}', [PengajuanController::class, 'updateSuratTugas']);

    Route::get('/pengajuan/jabfung', [PengajuanController::class, 'readJabfung']);
    Route::get('/pengajuan/jabfung/create', [PengajuanController::class, 'createJabatanFungsional']);
    Route::post('/pengajuan/jabfung/store', [PengajuanController::class, 'storeJabatanFungsional']);
    Route::get('/pengajuan/jabfung/edit/{id}', [PengajuanController::class, 'editJabatanFungsional']);
    Route::put('/pengajuan/jabfung/update/{id}', [PengajuanController::class, 'updateJabatanFungsional']);

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

Route::get('/test-sidebar', function () {
    return view('operator.test');
});

Route::get('/operator/verifikasi/surat-tugas', function () {
    return view('operator.suratTugas');
});

Route::get('/operator/verifikasi/jabfung', function () {
    return view('operator.jabatanFungsional');
});

Route::get('/operator/verifikasi/panggol', function () {
    return view('operator.pangkatGolongan');
});

<?php

use App\Http\Controllers\DostenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::get('/pilih-role', [LoginController::class, 'showRole'])->name('login.role');
Route::post('/pilih-role', [LoginController::class, 'chooseRole'])->name('login.role.process');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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

    Route::get('/validasi/surtug', [VerifikasiController::class, 'operatorSurtug'])
        ->name('operator.surtug.index');

    Route::put('/validasi/surtug/{id}/proses', [VerifikasiController::class, 'prosesSurtug'])
        ->name('operator.surtug.proses');

    Route::put('/validasi/surtug/{id}/tolak', [VerifikasiController::class, 'tolakSurtug'])
        ->name('operator.surtug.tolak');

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

Route::prefix('dosten')->group(function () {

    Route::get('/dashboard', [DostenController::class, 'dashboard']);

    Route::get('/data-diri', [DostenController::class, 'dataDiri']);
    Route::get('/data-diri/edit', [DostenController::class, 'editDataDiri']);
    Route::post('/data-diri/update', [DostenController::class, 'updateDataDiri']);

    Route::get('/pengajuan/surtug', [DostenController::class, 'surtug'])
        ->name('dosten.surtug.index');

    Route::get('/pengajuan/surtug/create', [DostenController::class, 'createSuratTugas'])
        ->name('dosten.surtug.create');

    Route::post('/pengajuan/surtug/store', [DostenController::class, 'storeSuratTugas'])
        ->name('dosten.surtug.store');

    Route::get('/pengajuan/jabfung', [DostenController::class, 'jabfung'])
        ->name('dosten.jabfung.index');

    Route::get('/pengajuan/jabfung/create', [DostenController::class, 'createJabfung'])
        ->name('dosten.jabfung.create');

    Route::post('/pengajuan/jabfung/store', [DostenController::class, 'storeJabfung'])
        ->name('dosten.jabfung.store');

    Route::get('/pengajuan/panggol', [DostenController::class, 'panggol'])
        ->name('dosten.panggol.index');

    Route::get('/pengajuan/panggol/create', [DostenController::class, 'createPanggol'])
        ->name('dosten.panggol.create');

    Route::post('/pengajuan/panggol/store', [DostenController::class, 'storePanggol'])
        ->name('dosten.panggol.store');
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

    Route::get('/verifikasi/surtug', [VerifikasiController::class, 'pimpinanSurtug'])
        ->name('pimpinan.surtug.index');

    Route::put('/verifikasi/surtug/{id}/terima', [VerifikasiController::class, 'terimaSurtug'])
        ->name('pimpinan.surtug.terima');

    Route::put('/verifikasi/surtug/{id}/tolak', [VerifikasiController::class, 'tolakSurtugPimpinan'])
        ->name('pimpinan.surtug.tolak');

    Route::get('/verifikasi/jabfung', function () {
        return view('pimpinan.pimpinanJabfung');
    });

    Route::get('/verifikasi/panggol', function () {
        return view('pimpinan.pimpinanPanggol');
    });

});

<?php

use App\Http\Controllers\DostenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OperatorDashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PimpinanDashboardController;
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

    Route::get('/dashboard', [OperatorDashboardController::class, 'index']);

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

    Route::get('/validasi/jabfung', [VerifikasiController::class, 'operatorJabfung'])
        ->name('operator.jabfung.index');

    Route::put('/validasi/jabfung/{id}/proses', [VerifikasiController::class, 'prosesJabfung'])
        ->name('operator.jabfung.proses');

    Route::put('/validasi/jabfung/{id}/tolak', [VerifikasiController::class, 'tolakJabfung'])
        ->name('operator.jabfung.tolak');

    Route::get('/validasi/panggol', [VerifikasiController::class, 'operatorPanggol'])
        ->name('operator.panggol.index');

    Route::put('/validasi/panggol/{id}/proses', [VerifikasiController::class, 'prosesPanggol'])
        ->name('operator.panggol.proses');

    Route::put('/validasi/panggol/{id}/tolak', [VerifikasiController::class, 'tolakPanggol'])
        ->name('operator.panggol.tolak');

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

    Route::get('/pengajuan/surtug/{id}/edit', [DostenController::class, 'editSuratTugas'])
        ->name('dosten.surtug.edit');

    Route::put('/pengajuan/surtug/{id}/update', [DostenController::class, 'updateSuratTugas'])
        ->name('dosten.surtug.update');

    Route::delete('/pengajuan/surtug/{id}/hapus', [DostenController::class, 'destroySuratTugas'])
        ->name('dosten.surtug.destroy');

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

    Route::get('/dashboard', [PimpinanDashboardController::class, 'index']);

    Route::get('/verifikasi/surtug', [VerifikasiController::class, 'pimpinanSurtug'])
        ->name('pimpinan.surtug.index');

    Route::put('/verifikasi/surtug/{id}/terima', [VerifikasiController::class, 'terimaSurtug'])
        ->name('pimpinan.surtug.terima');

    Route::put('/verifikasi/surtug/{id}/tolak', [VerifikasiController::class, 'tolakSurtugPimpinan'])
        ->name('pimpinan.surtug.tolak');

    Route::get('/verifikasi/jabfung', [VerifikasiController::class, 'pimpinanJabfung'])
        ->name('pimpinan.jabfung.index');

    Route::put('/verifikasi/jabfung/{id}/terima', [VerifikasiController::class, 'terimaJabfung'])
        ->name('pimpinan.jabfung.terima');

    Route::put('/verifikasi/jabfung/{id}/tolak', [VerifikasiController::class, 'tolakJabfungPimpinan'])
        ->name('pimpinan.jabfung.tolak');

    Route::get('/verifikasi/panggol', [VerifikasiController::class, 'pimpinanPanggol'])
        ->name('pimpinan.panggol.index');

    Route::put('/verifikasi/panggol/{id}/terima', [VerifikasiController::class, 'terimaPanggol'])
        ->name('pimpinan.panggol.terima');

    Route::put('/verifikasi/panggol/{id}/tolak', [VerifikasiController::class, 'tolakPanggolPimpinan'])
        ->name('pimpinan.panggol.tolak');

});

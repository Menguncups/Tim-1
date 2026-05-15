<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function store(Request $request)
    {
        Verifikasi::create([
            'id_verifikasi' => 'V'.time(),
            'tanggal_verifikasi' => now(),
            'tahap_verifikasi' => $request->tahap_verifikasi,
            'catatan' => $request->catatan,

            'user_id' => Auth::id(),
            'pengajuan_id_pengajuan' => $request->pengajuan_id_pengajuan,
        ]);

        $pengajuan = Pengajuan::query()->findOrFail($request->pengajuan_id_pengajuan);

        $pengajuan->update([
            'status' => $request->status,
        ]);

        return redirect('/verifikasi');
    }
}

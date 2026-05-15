<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Verifikasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function store(Request $request)
    {
        Verifikasi::create([
            'id_verifikasi' => 'V'.time(),
            'tanggal_verifikasi' => now(),
            'tahap_verifikasi' => $request->tahap_verifikasi,
            'catatan' => $request->catatan,
            'user_id_user' => $request->user_id_user,
            'pengajuan_id_pengajuan' => $request->pengajuan_id_pengajuan,
        ]);

        $pengajuan = Pengajuan::findOrFail(
            $request->pengajuan_id_pengajuan
        );

        $pengajuan->update([
            'status' => $request->status,
        ]);

        return redirect('/verifikasi');
    }
}

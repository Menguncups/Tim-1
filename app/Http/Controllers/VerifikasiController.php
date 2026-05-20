<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function operatorSurtug()
    {
        $suratTugas = DB::table('pengajuan')
            ->join('pengajuan_surat_tugas', 'pengajuan.id_pengajuan', '=', 'pengajuan_surat_tugas.id_pengajuan')
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
            ->where('pengajuan.jenis_pengajuan', 'surat_tugas')
            ->whereIn('pengajuan.status', ['menunggu', 'ditolak'])
            ->select(
                'pengajuan.id_pengajuan',
                'pengajuan.tanggal_pengajuan',
                'pengajuan.status',
                'pengajuan.catatan',
                'pegawai.nama',
                'pegawai.email',
                'pegawai.nip',
                'pegawai.homebase',
                'pengajuan_surat_tugas.nama_pengusul',
                'pengajuan_surat_tugas.waktu_pelaksana',
                'pengajuan_surat_tugas.lama_pelaksanaan',
                'pengajuan_surat_tugas.perihal',
                'pengajuan_surat_tugas.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.created_at')
            ->get();

        return view('operator.operatorSurtug', compact('suratTugas'));
    }

    public function prosesSurtug($id)
    {
        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'surat_tugas')
            ->whereIn('status', ['menunggu', 'ditolak'])
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
            ]);
        }

        DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status' => 'diproses',
                'catatan' => 'Diproses oleh operator.',
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('operator.surtug.index')
            ->with('success', 'Pengajuan surat tugas berhasil diproses dan diteruskan ke pimpinan.');
    }

    public function tolakSurtug(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:250'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 250 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'surat_tugas')
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah pernah ditolak/diproses.',
            ]);
        }

        DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('operator.surtug.index')
            ->with('success', 'Pengajuan surat tugas berhasil ditolak.');
    }

    public function pimpinanSurtug()
{
    $suratTugas = DB::table('pengajuan')
        ->join('pengajuan_surat_tugas', 'pengajuan.id_pengajuan', '=', 'pengajuan_surat_tugas.id_pengajuan')
        ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
        ->where('pengajuan.jenis_pengajuan', 'surat_tugas')
        ->where('pengajuan.status', 'diproses')
        ->select(
            'pengajuan.id_pengajuan',
            'pengajuan.tanggal_pengajuan',
            'pengajuan.status',
            'pengajuan.catatan',
            'pegawai.nama',
            'pegawai.email',
            'pegawai.nip',
            'pegawai.homebase',
            'pengajuan_surat_tugas.nama_pengusul',
            'pengajuan_surat_tugas.waktu_pelaksana',
            'pengajuan_surat_tugas.lama_pelaksanaan',
            'pengajuan_surat_tugas.perihal',
            'pengajuan_surat_tugas.berkas_pendukung'
        )
        ->orderByDesc('pengajuan.updated_at')
        ->get();

    return view('pimpinan.pimpinanSurtug', compact('suratTugas'));
}

public function terimaSurtug($id)
{
    $pengajuan = DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->where('jenis_pengajuan', 'surat_tugas')
        ->where('status', 'diproses')
        ->first();

    if (! $pengajuan) {
        return back()->withErrors([
            'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
        ]);
    }

    DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->update([
            'status' => 'diterima',
            'catatan' => 'Disetujui oleh pimpinan.',
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('pimpinan.surtug.index')
        ->with('success', 'Pengajuan surat tugas berhasil diterima.');
}

public function tolakSurtugPimpinan(Request $request, $id)
{
    $request->validate([
        'catatan' => ['required', 'string', 'max:250'],
    ], [
        'catatan.required' => 'Catatan penolakan wajib diisi.',
        'catatan.max' => 'Catatan maksimal 250 karakter.',
    ]);

    $pengajuan = DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->where('jenis_pengajuan', 'surat_tugas')
        ->where('status', 'diproses')
        ->first();

    if (! $pengajuan) {
        return back()->withErrors([
            'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
        ]);
    }

    DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan,
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('pimpinan.surtug.index')
        ->with('success', 'Pengajuan surat tugas berhasil ditolak.');
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    // 1. Menampilkan Profil Read Berdasarkan Keberadaan NIDN
    public function showRead($kategori)
    {
        if (!in_array($kategori, ['dosen', 'tendik'])) {
            abort(404);
        }

        // Cari data murni berdasarkan logika NIDN di database
        if ($kategori === 'dosen') {
            $pegawai = Pegawai::whereNotNull('nidn')->firstOrFail();
        } else {
            $pegawai = Pegawai::whereNull('nidn')->firstOrFail();
        }

        return view("biodata.{$kategori}-read", compact('pegawai'));
    }

    // 2. Menampilkan Form Update
    public function showEdit($kategori)
    {
        if (!in_array($kategori, ['dosen', 'tendik'])) {
            abort(404);
        }

        if ($kategori === 'dosen') {
            $pegawai = Pegawai::where('id_pegawai', request()->id)
                              ->whereNotNull('nidn')
                              ->firstOrFail();
        } else {
            $pegawai = Pegawai::where('id_pegawai', request()->id)
                              ->whereNull('nidn')
                              ->firstOrFail();
        }

        return view("biodata.{$kategori}-update", compact('pegawai'));
    }

    // 3. Memproses Update Kontak & Foto via AJAX POST
    public function processUpdate(Request $request, $kategori)
    {
        if (!in_array($kategori, ['dosen', 'tendik'])) {
            return response()->json(['success' => false, 'message' => 'Kategori tidak valid.']);
        }

        $pegawai = Pegawai::where('id_pegawai', $request->id_pegawai)->firstOrFail();

        $request->validate([
            'no_hp' => 'required|string|max:15',
            'no_hp_darurat' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pegawai->no_hp = $request->no_hp;
        $pegawai->no_hp_darurat = $request->no_hp_darurat;

        if ($request->hasFile('foto')) {
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            
            $path = $request->file('foto')->store('foto-profil', 'public');
            $pegawai->foto = $path;
        }

        $pegawai->save();

        return response()->json([
            'success' => true,
            'message' => 'Perubahan nomor telepon Anda telah berhasil disimpan langsung ke database.'
        ]);
    }
}
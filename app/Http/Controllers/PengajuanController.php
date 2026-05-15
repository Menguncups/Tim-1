<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanSuratTugas;
use App\Models\PerubahanDataPegawai;
use App\Models\JabatanFungsional;
use App\Models\PangkatGolongan;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with([
            'pegawai',
            'suratTugas',
            'perubahanData',
            'jabatanFungsional',
            'pangkatGolongan'
        ])->get();

        return view('pengajuan.index', compact('pengajuan'));
    }

    public function createSuratTugas()
    {
        return view('pengajuan.create_surat_tugas');
    }

    public function storeSuratTugas(Request $request)
    {
        $idPengajuan = 'P' . time();

        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'tanggal_pengajuan' => now(),
            'jenis_pengajuan' => 'surat_tugas',
            'status' => 'menunggu',
            'pegawai_id_pegawai' => $request->pegawai_id_pegawai,
        ]);

        PengajuanSuratTugas::create([
            'id_pengajuan' => $idPengajuan,
            'nama_pengusul' => $request->nama_pengusul,
            'waktu_pelaksana' => $request->waktu_pelaksana,
            'lama_pelaksanaan' => $request->lama_pelaksanaan,
            'perihal' => $request->perihal,
            'berkas_pendukung' => $request->berkas_pendukung,
        ]);

        return redirect('/pengajuan');
    }

    public function createPerubahanData()
    {
        return view('pengajuan.create_perubahan_data');
    }

    public function storePerubahanData(Request $request)
    {
        $idPengajuan = 'P' . time();

        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'tanggal_pengajuan' => now(),
            'jenis_pengajuan' => 'perubahan_data',
            'status' => 'menunggu',
            'pegawai_id_pegawai' => $request->pegawai_id_pegawai,
        ]);

        foreach ($request->kolom_diubah as $index => $kolom) {
            PerubahanDataPegawai::create([
                'id_pengajuan' => $idPengajuan,
                'kolom_diubah' => $kolom,
                'nilai_lama' => $request->nilai_lama[$index],
                'nilai_baru' => $request->nilai_baru[$index],
            ]);
        }

        return redirect('/pengajuan');
    }

    public function createJabatanFungsional()
    {
        return view('pengajuan.create_jabatan_fungsional');
    }

    public function storeJabatanFungsional(Request $request)
    {
        $idPengajuan = 'P' . time();

        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'tanggal_pengajuan' => now(),
            'jenis_pengajuan' => 'jabatan_fungsional',
            'status' => 'menunggu',
            'pegawai_id_pegawai' => $request->pegawai_id_pegawai,
        ]);

        JabatanFungsional::create([
            'id_pengajuan' => $idPengajuan,
            'nama_jabatan' => $request->nama_jabatan,
            'tmt' => $request->tmt,
            'berkas_pendukung' => $request->berkas_pendukung,
        ]);

        return redirect('/pengajuan');
    }

    public function createPangkatGolongan()
    {
        return view('pengajuan.create_pangkat_golongan');
    }

    public function storePangkatGolongan(Request $request)
    {
        $idPengajuan = 'P' . time();

        Pengajuan::create([
            'id_pengajuan' => $idPengajuan,
            'tanggal_pengajuan' => now(),
            'jenis_pengajuan' => 'pangkat_golongan',
            'status' => 'menunggu',
            'pegawai_id_pegawai' => $request->pegawai_id_pegawai,
        ]);

        PangkatGolongan::create([
            'id_pengajuan' => $idPengajuan,
            'pangkat' => $request->pangkat,
            'golongan' => $request->golongan,
            'tmt' => $request->tmt,
            'berkas_pendukung' => $request->berkas_pendukung,
        ]);

        return redirect('/pengajuan');
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'pegawai',
            'suratTugas',
            'perubahanData',
            'jabatanFungsional',
            'pangkatGolongan',
            'verifikasi'
        ])->findOrFail($id);

        return view('pengajuan.show', compact('pengajuan'));
    }

    public function destroy($id)
    {
        Pengajuan::destroy($id);

        return redirect('/pengajuan');
    }
}
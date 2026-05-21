<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function readDosen()
    {
        $pegawai = new Pegawai();
        $pegawai->id_pegawai = 'PEG001';
        $pegawai->nama = 'Khairul Umam Syaliman, S.T., M.Kom.';
        $pegawai->jenis_kelamin = 'Laki - laki';
        $pegawai->nip = '199206212024061001';
        $pegawai->nidn = '1021069203';
        $pegawai->tgl_lahir = '1992-06-21';
        $pegawai->tempat_lahir = 'Lhokseumawe';
        $pegawai->homebase = 'S1 Teknik Informatika';
        $pegawai->no_hp = '081277972250';
        $pegawai->no_hp_darurat = '-';
        $pegawai->email = 'khairul.umam@lecturer.unri.ac.id';
        $pegawai->jabatan_fungsional = 'Lektor';
        $pegawai->pangkat_golongan = 'Penata Muda Tingkat I / III/b';
        $pegawai->foto = null;

        return view('datadiri.read_dosen', compact('pegawai'));
    }

    public function editDosen()
    {
        $pegawai = new Pegawai();
        $pegawai->id_pegawai = 'PEG001';
        $pegawai->nama = 'Khairul Umam Syaliman, S.T., M.Kom.';
        $pegawai->nip = '199206212024061001';
        $pegawai->nidn = '1021069203';
        $pegawai->no_hp = '081277972250';
        $pegawai->no_hp_darurat = '-';

        return view('datadiri.update_dosen', compact('pegawai'));
    }

    public function updateDosen(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Simulasi berhasil menyimpan data dosen (Preview Mode).'
        ]);
    }

    public function readTendik()
    {
        $pegawai = new Pegawai();
        $pegawai->id_pegawai = 'PEG002';
        $pegawai->nama = 'Jatwoko, S.T';
        $pegawai->jenis_kelamin = 'Laki - laki';
        $pegawai->nip = '197106042005011003';
        $pegawai->nidn = null; // Kosong sesuai aturan ERD Tendik
        $pegawai->tgl_lahir = '1971-04-06';
        $pegawai->tempat_lahir = 'Pekanbaru';
        $pegawai->homebase = 'Teknik Elektro';
        $pegawai->no_hp = '081371959595';
        $pegawai->no_hp_darurat = '-';
        $pegawai->email = 'jatwoko@staff.unri.ac.id';
        $pegawai->jabatan_fungsional = 'Teknisi/Laboran';
        $pegawai->pangkat_golongan = 'Penata Muda Tingkat I/ III-b';
        $pegawai->foto = null;

        return view('datadiri.read_tendik', compact('pegawai'));
    }

    public function editTendik()
    {
        $pegawai = new Pegawai();
        $pegawai->id_pegawai = 'PEG002';
        $pegawai->nama = 'Jatwoko, S.T';
        $pegawai->nip = '197106042005011003';
        $pegawai->no_hp = '081371959595';
        $pegawai->no_hp_darurat = '-';

        return view('datadiri.update_tendik', compact('pegawai'));
    }

    public function updateTendik(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Simulasi berhasil menyimpan data tendik (Preview Mode).'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanSuratTugas;
use App\Models\PerubahanDataPegawai;
use App\Models\JabatanFungsional;
use App\Models\PangkatGolongan;
use App\Models\Pegawai; 
use Illuminate\Support\Facades\Validator;

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

    /**
     * TAMPILAN: Form Tambah Jabatan Fungsional
     * (versi tanpa login)
     */
    public function createJabatanFungsional()
    {
        $pegawai = Pegawai::where('id_pegawai', 'PG002')->first();

        if (!$pegawai) {
            return "Data pegawai dengan ID PG002 belum tersedia di database. Silakan jalankan perintah 'php artisan db:seed' di terminal Anda terlebih dahulu.";
        }

        /* |--------------------------------------------------------------------------
        | PETUNJUK UNTUK FITUR LOGIN (JIKA MENGGUNAKAN AUTENTIKASI):
        |--------------------------------------------------------------------------
        | Hapus atau matikan (komen) kode bypass PG002 di atas dari baris:
        | '$pegawai = Pegawai::where...' sampai penutup '}' milik 'if (!$pegawai)'.
        | 
        | Kemudian, gunakan kode di bawah ini sebagai penggantinya:
        |
        | $userActive = \Illuminate\Support\Facades\Auth::user();
        | if (!$userActive) {
        |     return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        | }
        | $pegawai = Pegawai::where('id_pegawai', $userActive->id_pegawai)->first();
        | if (!$pegawai) {
        |     return "Profil data pegawai Anda tidak ditemukan di sistem.";
        | }
        */

        return view('pengajuan.create_jabatan_fungsional', compact('pegawai'));
    }

    
    public function storeJabatanFungsional(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan'             => 'required|string',
            'tmt'                      => 'required|date',
            'dokumen_sk_cpns'          => 'required|mimes:pdf|max:5120',
            'dokumen_sk_pns'           => 'required|mimes:pdf|max:5120',
            'dokumen_pak'              => 'required|mimes:pdf|max:5120',
            'dokumen_publikasi_ilmiah' => 'required|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Validasi: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $idPengajuan = 'P' . substr(time(), 1);
            $idPegawai = $request->pegawai_id_pegawai;

            Pengajuan::create([
                'id_pengajuan'       => $idPengajuan,
                'tanggal_pengajuan'  => now(),
                'jenis_pengajuan'    => 'jabatan_fungsional',
                'status'             => 'menunggu',
                'pegawai_id_pegawai' => $idPegawai,
            ]);

            $paths = [];
            $fileFields = ['dokumen_sk_cpns', 'dokumen_sk_pns', 'dokumen_pak', 'dokumen_publikasi_ilmiah'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . $idPengajuan . '.' . $file->getClientOriginalExtension();
                    
                    $paths[$field] = $file->storeAs('public/dokumen_jabfung', $filename);
                }
            }

            JabatanFungsional::create([
                'id_pengajuan'             => $idPengajuan,
                'id_pegawai'               => $idPegawai,
                'nama_jabatan'             => $request->nama_jabatan,
                'tmt'                      => $request->tmt,
                'dokumen_sk_cpns'          => $paths['dokumen_sk_cpns'] ?? null,
                'dokumen_sk_pns'           => $paths['dokumen_sk_pns'] ?? null,
                'dokumen_pak'              => $paths['dokumen_pak'] ?? null,
                'dokumen_publikasi_ilmiah' => $paths['dokumen_publikasi_ilmiah'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan jabatan fungsional baru berhasil disimpan ke database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * TAMPILAN: Form Tambah Pangkat Golongan
     * (versi bypass langsung ke data Dosen PG002 tanpa auth login))
     */
    public function createPangkatGolongan()
    {
        $pegawai = Pegawai::where('id_pegawai', 'PG002')->first();

        if (!$pegawai) {
            return "Data pegawai dengan ID PG002 belum tersedia di database. Silakan jalankan perintah 'php artisan db:seed' di terminal Anda terlebih dahulu.";
        }

        /* |--------------------------------------------------------------------------
        | PETUNJUK UNTUK FITUR LOGIN (JIKA MENGGUNAKAN AUTENTIKASI):
        |--------------------------------------------------------------------------
        | Hapus atau matikan (komen) kode bypass PG002 di atas dari baris:
        | '$pegawai = Pegawai::where...' sampai penutup '}' milik 'if (!$pegawai)'.
        | 
        | Kemudian, gunakan kode di bawah ini sebagai penggantinya:
        |
        | $userActive = \Illuminate\Support\Facades\Auth::user();
        | if (!$userActive) {
        |     return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        | }
        | $pegawai = Pegawai::where('id_pegawai', $userActive->id_pegawai)->first();
        | if (!$pegawai) {
        |     return "Profil data pegawai Anda tidak ditemukan di sistem.";
        | }
        */

        return view('pengajuan.create_pangkat_golongan', compact('pegawai'));
    }

    public function storePangkatGolongan(Request $request)
    {
        $request->validate([
            'pangkat_baru'             => 'required|string',
            'tmt'                      => 'required|date',
            'dokumen_sk_cpns'          => 'required|mimes:pdf|max:5120',
            'dokumen_sk_pns'           => 'required|mimes:pdf|max:5120',
            'dokumen_pak'              => 'required|mimes:pdf|max:5120',
            'dokumen_publikasi_ilmiah' => 'required|mimes:pdf|max:5120',
        ]);

        try {
            $idPengajuan = 'P' . substr(time(), 1);
            $idPegawai = $request->pegawai_id_pegawai;

            $rawPanggol = $request->pangkat_baru;
            $parts = explode('-', $rawPanggol);
            $pangkat = $parts[0] ?? '';   
            $golongan = $parts[1] ?? '';  

            Pengajuan::create([
                'id_pengajuan'       => $idPengajuan,
                'tanggal_pengajuan'  => now(),
                'jenis_pengajuan'    => 'pangkat_golongan',
                'status'             => 'menunggu',
                'pegawai_id_pegawai' => $idPegawai,
            ]);

            $paths = [];
            $fileFields = ['dokumen_sk_cpns', 'dokumen_sk_pns', 'dokumen_pak', 'dokumen_publikasi_ilmiah'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . $idPengajuan . '.' . $file->getClientOriginalExtension();
                    
                    $paths[$field] = $file->storeAs('public/dokumen_pangkat', $filename);
                }
            }

            PangkatGolongan::create([
                'id_pengajuan'             => $idPengajuan,
                'id_pegawai'               => $idPegawai, 
                'pangkat'                  => $pangkat,   
                'golongan'                 => $golongan,  
                'tmt'                      => $request->tmt,
                'dokumen_sk_cpns'          => $paths['dokumen_sk_cpns'] ?? null,
                'dokumen_sk_pns'           => $paths['dokumen_sk_pns'] ?? null,
                'dokumen_pak'              => $paths['dokumen_pak'] ?? null,
                'dokumen_publikasi_ilmiah' => $paths['dokumen_publikasi_ilmiah'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan pangkat golongan baru berhasil disimpan ke database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
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
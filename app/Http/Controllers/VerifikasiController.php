<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    private function generateIdVerifikasi()
    {
        $last = DB::table('verifikasi')
            ->where('id_verifikasi', 'like', 'VRF%')
            ->orderByDesc('id_verifikasi')
            ->lockForUpdate()
            ->first();

        if (! $last) {
            return 'VRF0000001';
        }

        $lastNumber = (int) substr($last->id_verifikasi, 3);

        return 'VRF'.str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
    }

    private function catatVerifikasi($idPengajuan)
    {
        $authUser = session('auth_user');

        if (! $authUser || ! isset($authUser['user_id'])) {
            throw new \Exception('User verifikator tidak ditemukan di session.');
        }

        DB::table('verifikasi')->insert([
            'id_verifikasi' => $this->generateIdVerifikasi(),
            'user_id' => $authUser['user_id'],
            'pengajuan_id_pengajuan' => $idPengajuan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | OPERATOR - SURAT TUGAS
    |--------------------------------------------------------------------------
    */

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
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Pengajuan tidak bisa diproses karena statusnya bukan menunggu.',
            ]);
        }

        DB::transaction(function () use ($id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'diproses',
                    'catatan' => 'Diproses oleh operator.',
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

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

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('operator.surtug.index')
            ->with('success', 'Pengajuan surat tugas berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | OPERATOR - JABATAN FUNGSIONAL
    |--------------------------------------------------------------------------
    */

    public function operatorJabfung()
    {
        $jabfungList = DB::table('pengajuan')
            ->join('jabatan_fungsional', 'pengajuan.id_pengajuan', '=', 'jabatan_fungsional.id_pengajuan')
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
            ->where('pengajuan.jenis_pengajuan', 'jabatan_fungsional')
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
                'jabatan_fungsional.nama_jabatan',
                'jabatan_fungsional.tmt',
                'jabatan_fungsional.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.created_at')
            ->get();

        return view('operator.operatorJabfung', compact('jabfungList'));
    }

    public function prosesJabfung($id)
    {
        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'jabatan_fungsional')
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Pengajuan tidak bisa diproses karena statusnya bukan menunggu.',
            ]);
        }

        DB::transaction(function () use ($id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'diproses',
                    'catatan' => 'Diproses oleh operator.',
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('operator.jabfung.index')
            ->with('success', 'Pengajuan jabatan fungsional berhasil diproses dan diteruskan ke pimpinan.');
    }

    public function tolakJabfung(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:250'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 250 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'jabatan_fungsional')
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah pernah ditolak/diproses.',
            ]);
        }

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('operator.jabfung.index')
            ->with('success', 'Pengajuan jabatan fungsional berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | OPERATOR - PANGKAT GOLONGAN
    |--------------------------------------------------------------------------
    */

    public function operatorPanggol()
    {
        $panggolList = DB::table('pengajuan')
            ->join('pangkat_golongan', 'pengajuan.id_pengajuan', '=', 'pangkat_golongan.id_pengajuan')
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
            ->where('pengajuan.jenis_pengajuan', 'pangkat_golongan')
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
                'pangkat_golongan.pangkat',
                'pangkat_golongan.golongan',
                'pangkat_golongan.tmt',
                'pangkat_golongan.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.created_at')
            ->get();

        return view('operator.operatorPanggol', compact('panggolList'));
    }

    public function prosesPanggol($id)
    {
        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'pangkat_golongan')
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Pengajuan tidak bisa diproses karena statusnya bukan menunggu.',
            ]);
        }

        DB::transaction(function () use ($id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'diproses',
                    'catatan' => 'Diproses oleh operator.',
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('operator.panggol.index')
            ->with('success', 'Pengajuan pangkat golongan berhasil diproses dan diteruskan ke pimpinan.');
    }

    public function tolakPanggol(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:250'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 250 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'pangkat_golongan')
            ->where('status', 'menunggu')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah pernah ditolak/diproses.',
            ]);
        }

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('operator.panggol.index')
            ->with('success', 'Pengajuan pangkat golongan berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN - SURAT TUGAS
    |--------------------------------------------------------------------------
    */

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

        DB::transaction(function () use ($id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'diterima',
                    'catatan' => 'Disetujui oleh pimpinan.',
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

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

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('pimpinan.surtug.index')
            ->with('success', 'Pengajuan surat tugas berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN - JABATAN FUNGSIONAL
    |--------------------------------------------------------------------------
    */

    public function pimpinanJabfung()
    {
        $jabfungList = DB::table('pengajuan')
            ->join('jabatan_fungsional', 'pengajuan.id_pengajuan', '=', 'jabatan_fungsional.id_pengajuan')
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
            ->where('pengajuan.jenis_pengajuan', 'jabatan_fungsional')
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
                'jabatan_fungsional.nama_jabatan',
                'jabatan_fungsional.tmt',
                'jabatan_fungsional.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.updated_at')
            ->get();

        return view('pimpinan.pimpinanJabfung', compact('jabfungList'));
    }

public function terimaJabfung($id)
{
    $pengajuan = DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->where('jenis_pengajuan', 'jabatan_fungsional')
        ->where('status', 'diproses')
        ->first();

    if (! $pengajuan) {
        return back()->withErrors([
            'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
        ]);
    }

    $jabfung = DB::table('jabatan_fungsional')
        ->where('id_pengajuan', $id)
        ->first();

    if (! $jabfung) {
        return back()->withErrors([
            'data' => 'Data detail jabatan fungsional tidak ditemukan.',
        ]);
    }

    DB::transaction(function () use ($id, $pengajuan, $jabfung) {
        // 1. Ubah status pengajuan
        DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status' => 'diterima',
                'catatan' => 'Disetujui oleh pimpinan.',
                'updated_at' => now(),
            ]);

        // 2. Update data utama pegawai
        DB::table('pegawai')
            ->where('id_pegawai', $pengajuan->pegawai_id_pegawai)
            ->update([
                'jabatan_fungsional' => $jabfung->nama_jabatan,
                'updated_at' => now(),
            ]);

        // 3. Catat siapa yang memverifikasi
        $this->catatVerifikasi($id);
    });

    return redirect()
        ->route('pimpinan.jabfung.index')
        ->with('success', 'Pengajuan jabatan fungsional berhasil diterima dan data pegawai sudah diperbarui.');
}

    public function tolakJabfungPimpinan(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:250'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 250 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'jabatan_fungsional')
            ->where('status', 'diproses')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
            ]);
        }

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('pimpinan.jabfung.index')
            ->with('success', 'Pengajuan jabatan fungsional berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN - PANGKAT GOLONGAN
    |--------------------------------------------------------------------------
    */

    public function pimpinanPanggol()
    {
        $panggolList = DB::table('pengajuan')
            ->join('pangkat_golongan', 'pengajuan.id_pengajuan', '=', 'pangkat_golongan.id_pengajuan')
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'pengajuan.pegawai_id_pegawai')
            ->where('pengajuan.jenis_pengajuan', 'pangkat_golongan')
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
                'pangkat_golongan.pangkat',
                'pangkat_golongan.golongan',
                'pangkat_golongan.tmt',
                'pangkat_golongan.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.updated_at')
            ->get();

        return view('pimpinan.pimpinanPanggol', compact('panggolList'));
    }

public function terimaPanggol($id)
{
    $pengajuan = DB::table('pengajuan')
        ->where('id_pengajuan', $id)
        ->where('jenis_pengajuan', 'pangkat_golongan')
        ->where('status', 'diproses')
        ->first();

    if (! $pengajuan) {
        return back()->withErrors([
            'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
        ]);
    }

    $panggol = DB::table('pangkat_golongan')
        ->where('id_pengajuan', $id)
        ->first();

    if (! $panggol) {
        return back()->withErrors([
            'data' => 'Data detail pangkat golongan tidak ditemukan.',
        ]);
    }

    DB::transaction(function () use ($id, $pengajuan, $panggol) {
        // 1. Ubah status pengajuan
        DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status' => 'diterima',
                'catatan' => 'Disetujui oleh pimpinan.',
                'updated_at' => now(),
            ]);

        // 2. Update data utama pegawai
        // Format disamakan dengan data pegawai kamu: "III/a - Penata Muda"
        DB::table('pegawai')
            ->where('id_pegawai', $pengajuan->pegawai_id_pegawai)
            ->update([
                'pangkat_golongan' => $panggol->golongan . ' - ' . $panggol->pangkat,
                'updated_at' => now(),
            ]);

        // 3. Catat siapa yang memverifikasi
        $this->catatVerifikasi($id);
    });

    return redirect()
        ->route('pimpinan.panggol.index')
        ->with('success', 'Pengajuan pangkat golongan berhasil diterima dan data pegawai sudah diperbarui.');
}

    public function tolakPanggolPimpinan(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:250'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 250 karakter.',
        ]);

        $pengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', $id)
            ->where('jenis_pengajuan', 'pangkat_golongan')
            ->where('status', 'diproses')
            ->first();

        if (! $pengajuan) {
            return back()->withErrors([
                'status' => 'Data pengajuan tidak ditemukan atau sudah diproses.',
            ]);
        }

        DB::transaction(function () use ($request, $id) {
            DB::table('pengajuan')
                ->where('id_pengajuan', $id)
                ->update([
                    'status' => 'ditolak',
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            $this->catatVerifikasi($id);
        });

        return redirect()
            ->route('pimpinan.panggol.index')
            ->with('success', 'Pengajuan pangkat golongan berhasil ditolak.');
    }
}

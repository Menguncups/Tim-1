<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DostenController extends Controller
{
    private function cekAksesDosten()
    {
        $role = session('auth_role');

        if (! in_array($role, ['dosen', 'tendik'])) {
            return redirect('/login');
        }

        return null;
    }

    private function getRoleData()
    {
        $role = session('auth_role');

        $isDosen = $role === 'dosen';

        return [
            'role' => $role,
            'isDosen' => $isDosen,
            'roleLabel' => $isDosen ? 'Dosen' : 'Tendik',
            'roleClass' => $isDosen ? 'role-dosen' : 'role-tendik',
        ];
    }

    private function getPegawaiLogin()
    {
        $authUser = session('auth_user');

        if (! $authUser || ! isset($authUser['id'])) {
            abort(403, 'Session login tidak valid.');
        }

        return Pegawai::findOrFail($authUser['id']);
    }

    public function dashboard()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $role = session('auth_role');

        if ($role === 'dosen') {
            return view('dosten.dosenDashboard');
        }

        if ($role === 'tendik') {
            return view('dosten.tendikDashboard');
        }

        return redirect('/login');
    }

    public function dataDiri()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $pegawai = $this->getPegawaiLogin();

        $roleData = $this->getRoleData();

        $pageSub = $roleData['isDosen']
            ? 'Informasi profil dan biodata dosen'
            : 'Informasi profil dan biodata tenaga kependidikan';

        return view('dosten.dostenDataDiri', [
            'pegawai' => $pegawai,
            'role' => $roleData['role'],
            'isDosen' => $roleData['isDosen'],
            'roleLabel' => $roleData['roleLabel'],
            'roleClass' => $roleData['roleClass'],
            'pageSub' => $pageSub,
        ]);
    }

    public function editDataDiri()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $pegawai = $this->getPegawaiLogin();

        $roleData = $this->getRoleData();

        $pageSub = $roleData['isDosen']
            ? 'Perbarui nomor handphone dan foto profil dosen.'
            : 'Perbarui nomor handphone dan foto profil tenaga kependidikan.';

        return view('dosten.dostenEditDataDiri', [
            'pegawai' => $pegawai,
            'role' => $roleData['role'],
            'isDosen' => $roleData['isDosen'],
            'roleLabel' => $roleData['roleLabel'],
            'roleClass' => $roleData['roleClass'],
            'pageSub' => $pageSub,
        ]);
    }

    public function updateDataDiri(Request $request)
    {
        if ($redirect = $this->cekAksesDosten()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak valid.',
            ], 403);
        }

        $pegawai = $this->getPegawaiLogin();

        $validator = Validator::make($request->all(), [
            'no_hp' => ['required', 'digits_between:10,14'],
            'no_hp_darurat' => ['nullable', 'digits_between:10,14'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'no_hp.required' => 'No. HP wajib diisi.',
            'no_hp.digits_between' => 'No. HP harus 10 sampai 14 angka.',
            'no_hp_darurat.digits_between' => 'No. HP darurat harus 10 sampai 14 angka.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data belum valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $namaFoto = $pegawai->foto;

        if ($request->hasFile('foto')) {
            $folder = public_path('photo');

            if (! file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            if ($pegawai->foto && file_exists(public_path('photo/'.$pegawai->foto))) {
                unlink(public_path('photo/'.$pegawai->foto));
            }

            $file = $request->file('foto');
            $namaFoto = 'foto_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $file->move($folder, $namaFoto);
        }

        $pegawai->update([
            'no_hp' => $request->no_hp,
            'no_hp_darurat' => $request->no_hp_darurat,
            'foto' => $namaFoto,
        ]);

        $authUser = session('auth_user');
        $authUser['foto'] = $namaFoto;
        $authUser['no_hp'] = $pegawai->no_hp;
        $authUser['no_hp_darurat'] = $pegawai->no_hp_darurat;

        session([
            'auth_user' => $authUser,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data diri berhasil diperbarui.',
            'redirect' => url('/dosten/data-diri'),
        ]);
    }

    public function surtug()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $pegawai = $this->getPegawaiLogin();

        $suratTugas = DB::table('pengajuan')
            ->join('pengajuan_surat_tugas', 'pengajuan.id_pengajuan', '=', 'pengajuan_surat_tugas.id_pengajuan')
            ->where('pengajuan.pegawai_id_pegawai', $pegawai->id_pegawai)
            ->where('pengajuan.jenis_pengajuan', 'surat_tugas')
            ->select(
                'pengajuan.id_pengajuan',
                'pengajuan.tanggal_pengajuan',
                'pengajuan.status',
                'pengajuan_surat_tugas.nama_pengusul',
                'pengajuan_surat_tugas.waktu_pelaksana',
                'pengajuan_surat_tugas.lama_pelaksanaan',
                'pengajuan_surat_tugas.perihal',
                'pengajuan_surat_tugas.berkas_pendukung'
            )
            ->orderByDesc('pengajuan.created_at')
            ->get();

        return view('dosten.dostenSurtug', compact('suratTugas'));
    }

    public function jabfung()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        return view('dosten.dostenJabfung');
    }

    public function panggol()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        return view('dosten.dostenPanggol');
    }

    public function createSuratTugas()
    {
        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $pegawai = $this->getPegawaiLogin();

        return view('dosten.dostenSurtugCreate', compact('pegawai'));
    }

    public function storeSuratTugas(Request $request)
    {

        if ($redirect = $this->cekAksesDosten()) {
            return $redirect;
        }

        $pegawai = $this->getPegawaiLogin();

        $request->merge([
            'lama_pelaksanaan' => (int) $request->input('lama_pelaksanaan'),
        ]);

        $validated = $request->validate([
            'nama_pengusul' => ['required', 'string', 'max:50', 'regex:/^[^0-9]+$/'],
            'waktu_pelaksana' => ['required', 'date'],
            'lama_pelaksanaan' => ['required', 'numeric', 'min:1', 'max:999'],
            'perihal' => ['required', 'string', 'max:50'],
            'berkas_pendukung' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'nama_pengusul.required' => 'Nama pengusul wajib diisi.',
            'nama_pengusul.regex' => 'Nama pengusul tidak boleh mengandung angka.',

            'waktu_pelaksana.required' => 'Waktu pelaksanaan wajib diisi.',

            'lama_pelaksanaan.required' => 'Lama pelaksanaan wajib diisi.',
            'lama_pelaksanaan.integer' => 'Lama pelaksanaan harus berupa angka.',
            'lama_pelaksanaan.min' => 'Lama pelaksanaan minimal 1 hari.',
            'lama_pelaksanaan.max' => 'Lama pelaksanaan maksimal 999 hari.',

            'perihal.required' => 'Perihal wajib diisi.',
            'perihal.max' => 'Perihal maksimal 50 karakter.',

            'berkas_pendukung.required' => 'Berkas pendukung wajib diunggah.',
            'berkas_pendukung.mimes' => 'Berkas harus PDF, JPG, JPEG, atau PNG.',
            'berkas_pendukung.max' => 'Ukuran berkas maksimal 2 MB.',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $pegawai) {
                $idPengajuan = $this->generateIdPengajuan();

                $folder = public_path('berkas/surat_tugas');

                if (! file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                $file = $request->file('berkas_pendukung');
                $namaBerkas = 'surtug_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

                $file->move($folder, $namaBerkas);

                DB::table('pengajuan')->insert([
                    'id_pengajuan' => $idPengajuan,
                    'tanggal_pengajuan' => now()->toDateString(),
                    'jenis_pengajuan' => 'surat_tugas',
                    'status' => 'menunggu',
                    'catatan' => null,
                    'pegawai_id_pegawai' => $pegawai->id_pegawai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('pengajuan_surat_tugas')->insert([
                    'id_pengajuan' => $idPengajuan,
                    'nama_pengusul' => $validated['nama_pengusul'],
                    'waktu_pelaksana' => $validated['waktu_pelaksana'],
                    'lama_pelaksanaan' => $validated['lama_pelaksanaan'],
                    'perihal' => $validated['perihal'],
                    'berkas_pendukung' => $namaBerkas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'database' => 'Gagal menyimpan pengajuan surat tugas: '.$e->getMessage(),
                ])
                ->withInput();
        }

        return redirect()
            ->route('dosten.surtug.index')
            ->with('success', 'Pengajuan surat tugas berhasil dikirim.');
    }

    private function generateIdPengajuan()
    {
        $lastPengajuan = DB::table('pengajuan')
            ->where('id_pengajuan', 'like', 'PGJ%')
            ->orderByDesc('id_pengajuan')
            ->lockForUpdate()
            ->first();

        if (! $lastPengajuan) {
            return 'PGJ0000001';
        }

        $lastNumber = (int) substr($lastPengajuan->id_pengajuan, 3);

        return 'PGJ'.str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
    }

    public function createJabfung()
{
    if ($redirect = $this->cekAksesDosten()) {
        return $redirect;
    }

    $pegawai = $this->getPegawaiLogin();

    return view('dosten.dostenJabfungCreate', compact('pegawai'));
}

public function storeJabfung(Request $request)
{
    if ($redirect = $this->cekAksesDosten()) {
        return $redirect;
    }

    $pegawai = $this->getPegawaiLogin();

    $validated = $request->validate([
        'nama_jabatan' => ['required', 'string', 'max:25'],
        'tmt' => ['required', 'date'],
        'berkas_pendukung' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
    ], [
        'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
        'tmt.required' => 'TMT wajib diisi.',
        'berkas_pendukung.required' => 'Berkas pendukung wajib diunggah.',
        'berkas_pendukung.mimes' => 'Berkas harus PDF, JPG, JPEG, atau PNG.',
        'berkas_pendukung.max' => 'Ukuran berkas maksimal 2 MB.',
    ]);

    try {
        DB::transaction(function () use ($request, $validated, $pegawai) {
            $idPengajuan = $this->generateIdPengajuan();

            $folder = public_path('berkas/jabfung');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $file = $request->file('berkas_pendukung');
            $namaBerkas = 'jabfung_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($folder, $namaBerkas);

            DB::table('pengajuan')->insert([
                'id_pengajuan' => $idPengajuan,
                'tanggal_pengajuan' => now()->toDateString(),
                'jenis_pengajuan' => 'jabatan_fungsional',
                'status' => 'menunggu',
                'catatan' => null,
                'pegawai_id_pegawai' => $pegawai->id_pegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('jabatan_fungsional')->insert([
                'id_pengajuan' => $idPengajuan,
                'nama_jabatan' => $validated['nama_jabatan'],
                'tmt' => $validated['tmt'],
                'berkas_pendukung' => $namaBerkas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    } catch (\Throwable $e) {
        return back()
            ->withErrors([
                'database' => 'Gagal menyimpan pengajuan jabatan fungsional: ' . $e->getMessage(),
            ])
            ->withInput();
    }

    return redirect()
        ->route('dosten.jabfung.index')
        ->with('success', 'Pengajuan jabatan fungsional berhasil dikirim.');
}

public function createPanggol()
{
    if ($redirect = $this->cekAksesDosten()) {
        return $redirect;
    }

    $pegawai = $this->getPegawaiLogin();

    return view('dosten.dostenPanggolCreate', compact('pegawai'));
}

public function storePanggol(Request $request)
{
    if ($redirect = $this->cekAksesDosten()) {
        return $redirect;
    }

    $pegawai = $this->getPegawaiLogin();

    $validated = $request->validate([
        'pangkat' => ['required', 'string', 'max:25'],
        'golongan' => ['required', 'string', 'max:5'],
        'tmt' => ['required', 'date'],
        'berkas_pendukung' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
    ], [
        'pangkat.required' => 'Pangkat wajib diisi.',
        'golongan.required' => 'Golongan wajib diisi.',
        'tmt.required' => 'TMT wajib diisi.',
        'berkas_pendukung.required' => 'Berkas pendukung wajib diunggah.',
        'berkas_pendukung.mimes' => 'Berkas harus PDF, JPG, JPEG, atau PNG.',
        'berkas_pendukung.max' => 'Ukuran berkas maksimal 2 MB.',
    ]);

    try {
        DB::transaction(function () use ($request, $validated, $pegawai) {
            $idPengajuan = $this->generateIdPengajuan();

            $folder = public_path('berkas/panggol');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $file = $request->file('berkas_pendukung');
            $namaBerkas = 'panggol_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($folder, $namaBerkas);

            DB::table('pengajuan')->insert([
                'id_pengajuan' => $idPengajuan,
                'tanggal_pengajuan' => now()->toDateString(),
                'jenis_pengajuan' => 'pangkat_golongan',
                'status' => 'menunggu',
                'catatan' => null,
                'pegawai_id_pegawai' => $pegawai->id_pegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('pangkat_golongan')->insert([
                'id_pengajuan' => $idPengajuan,
                'pangkat' => $validated['pangkat'],
                'golongan' => $validated['golongan'],
                'tmt' => $validated['tmt'],
                'berkas_pendukung' => $namaBerkas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    } catch (\Throwable $e) {
        return back()
            ->withErrors([
                'database' => 'Gagal menyimpan pengajuan pangkat golongan: ' . $e->getMessage(),
            ])
            ->withInput();
    }

    return redirect()
        ->route('dosten.panggol.index')
        ->with('success', 'Pengajuan pangkat golongan berhasil dikirim.');
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('roles')->get();

        return view('operator.daftarPegawai', compact('pegawai'));
    }

    public function create()
    {
        return view('operator.tambahPegawai');
    }

    public function store(Request $request)
    {
        $roles = $request->roles ?? [];

        $butuhNidn = in_array('dosen', $roles) || in_array('pimpinan', $roles);

        $validator = Validator::make($request->all(), [
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'roles' => ['required', 'array', 'min:1', 'max:2'],
            'roles.*' => ['required', 'in:dosen,pimpinan,operator,tendik'],

            'nama' => ['required', 'string', 'max:50', 'regex:/^[^0-9]+$/'],
            'email' => ['required', 'email', 'max:50', 'unique:pegawai,email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],

            'nip' => ['required', 'digits:18', 'unique:pegawai,nip'],

            'nidn' => [
                Rule::requiredIf($butuhNidn),
                'nullable',
                'digits:10',
                'unique:pegawai,nidn',
            ],

            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['required', 'date'],

            'no_hp' => ['required', 'digits_between:10,14'],
            'no_hp_darurat' => ['nullable', 'digits_between:10,14'],

            'homebase' => ['required', 'string', 'max:80'],
            'pangkat_golongan' => ['required', 'string', 'max:50'],
            'jabatan_fungsional' => ['required', 'string', 'max:50'],
        ], [
            'roles.required' => 'Role pegawai wajib dipilih.',
            'roles.min' => 'Role pegawai wajib dipilih.',
            'roles.max' => 'Role pegawai maksimal 2 role.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.regex' => 'Nama tidak boleh mengandung angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus 18 angka.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nidn.required' => 'NIDN wajib diisi untuk Dosen atau Pimpinan.',
            'nidn.digits' => 'NIDN harus 10 angka.',
            'nidn.unique' => 'NIDN sudah digunakan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'no_hp.digits_between' => 'No HP harus 10 sampai 14 angka.',
            'no_hp_darurat.digits_between' => 'No HP darurat harus 10 sampai 14 angka.',
            'homebase.required' => 'Homebase wajib dipilih.',
            'pangkat_golongan.required' => 'Pangkat / Golongan wajib dipilih.',
            'jabatan_fungsional.required' => 'Jabatan fungsional wajib dipilih.',
        ]);

        $validator->after(function ($validator) use ($roles) {
            $sortedRoles = $roles;
            sort($sortedRoles);

            if (count($sortedRoles) === 2) {
                $gabungan = implode(',', $sortedRoles);

                $kombinasiValid = [
                    'dosen,pimpinan',
                    'operator,tendik',
                ];

                if (! in_array($gabungan, $kombinasiValid)) {
                    $validator->errors()->add(
                        'roles',
                        'Kombinasi role hanya boleh Dosen + Pimpinan atau Operator + Tendik.'
                    );
                }
            }
        });

        $validated = $validator->validate();

        try {
            DB::transaction(function () use ($request, $validated, $butuhNidn) {
                $idPegawai = $this->generateIdPegawai();

                $namaFoto = null;

                if ($request->hasFile('foto')) {
                    $folder = public_path('photo');

                    if (! file_exists($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    $file = $request->file('foto');
                    $namaFoto = 'foto_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

                    $file->move($folder, $namaFoto);
                }

                $pegawai = Pegawai::create([
                    'id_pegawai' => $idPegawai,
                    'nip' => $validated['nip'],
                    'nidn' => $butuhNidn ? $validated['nidn'] : null,
                    'nama' => $validated['nama'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'no_hp' => $validated['no_hp'],
                    'no_hp_darurat' => $validated['no_hp_darurat'] ?? null,
                    'homebase' => $validated['homebase'],
                    'email' => $validated['email'],
                    'pangkat_golongan' => $validated['pangkat_golongan'],
                    'jabatan_fungsional' => $validated['jabatan_fungsional'],
                    'foto' => $namaFoto,
                ]);

                User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'pegawai_id_pegawai' => $idPegawai,
                ]);

                $roleIds = Role::query()
                    ->whereIn('nama_role', $validated['roles'])
                    ->pluck('id_role')
                    ->toArray();

                if (count($roleIds) !== count($validated['roles'])) {
                    throw ValidationException::withMessages([
                        'roles' => 'Data role belum lengkap di tabel role. Jalankan seeder role terlebih dahulu.',
                    ]);
                }

                $pegawai->roles()->sync($roleIds);
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'database' => 'Gagal menyimpan ke database: '.$e->getMessage(),
                ])
                ->withInput();
        }

        return redirect('/operator/daftar-pegawai')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    private function generateIdPegawai()
    {
        $lastPegawai = Pegawai::query()
            ->where('id_pegawai', 'like', 'PGW%')
            ->orderByDesc('id_pegawai')
            ->lockForUpdate()
            ->first();

        if (! $lastPegawai) {
            return 'PGW0000001';
        }

        $lastNumber = (int) substr($lastPegawai->id_pegawai, 3);

        return 'PGW'.str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
    }

    public function show()
    {
        return view('dosen.dataDiri');
    }

    public function edit($id = null)
    {
        // Edit pegawai dari operator
        if ($id) {
            $pegawai = Pegawai::with('roles', 'user')->findOrFail($id);

            return view('operator.tambahPegawai', compact('pegawai'));
        }

        // Edit data diri dosen
        return view('dosen.editDataDiri');
    }

    public function update(Request $request, $id = null)
    {
        if (! $id) {
            return redirect('/dosen/datadiri');
        }

        $pegawai = Pegawai::with('user', 'roles')->findOrFail($id);

        $roles = $request->roles ?? [];

        $butuhNidn = in_array('dosen', $roles) || in_array('pimpinan', $roles);

        $validator = Validator::make($request->all(), [
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'roles' => ['required', 'array', 'min:1', 'max:2'],
            'roles.*' => ['required', 'in:dosen,pimpinan,operator,tendik'],

            'nama' => ['required', 'string', 'max:50', 'regex:/^[^0-9]+$/'],

            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('pegawai', 'email')->ignore($pegawai->id_pegawai, 'id_pegawai'),
                Rule::unique('users', 'email')->ignore(optional($pegawai->user)->id),
            ],

            'password' => ['nullable', 'string', 'min:8'],

            'nip' => [
                'required',
                'digits:18',
                Rule::unique('pegawai', 'nip')->ignore($pegawai->id_pegawai, 'id_pegawai'),
            ],

            'nidn' => [
                Rule::requiredIf($butuhNidn),
                'nullable',
                'digits:10',
                Rule::unique('pegawai', 'nidn')->ignore($pegawai->id_pegawai, 'id_pegawai'),
            ],

            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['required', 'date'],

            'no_hp' => ['required', 'digits_between:10,14'],
            'no_hp_darurat' => ['nullable', 'digits_between:10,14'],

            'homebase' => ['required', 'string', 'max:80'],
            'pangkat_golongan' => ['required', 'string', 'max:50'],
            'jabatan_fungsional' => ['required', 'string', 'max:50'],
        ], [
            'roles.required' => 'Role pegawai wajib dipilih.',
            'roles.min' => 'Role pegawai wajib dipilih.',
            'roles.max' => 'Role pegawai maksimal 2 role.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.regex' => 'Nama tidak boleh mengandung angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus 18 angka.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nidn.required' => 'NIDN wajib diisi untuk Dosen atau Pimpinan.',
            'nidn.digits' => 'NIDN harus 10 angka.',
            'nidn.unique' => 'NIDN sudah digunakan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'no_hp.digits_between' => 'No HP harus 10 sampai 14 angka.',
            'no_hp_darurat.digits_between' => 'No HP darurat harus 10 sampai 14 angka.',
            'homebase.required' => 'Homebase wajib dipilih.',
            'pangkat_golongan.required' => 'Pangkat / Golongan wajib dipilih.',
            'jabatan_fungsional.required' => 'Jabatan fungsional wajib dipilih.',
        ]);

        $validator->after(function ($validator) use ($roles) {
            $sortedRoles = $roles;
            sort($sortedRoles);

            if (count($sortedRoles) === 2) {
                $gabungan = implode(',', $sortedRoles);

                $kombinasiValid = [
                    'dosen,pimpinan',
                    'operator,tendik',
                ];

                if (! in_array($gabungan, $kombinasiValid)) {
                    $validator->errors()->add(
                        'roles',
                        'Kombinasi role hanya boleh Dosen + Pimpinan atau Operator + Tendik.'
                    );
                }
            }
        });

        $validated = $validator->validate();

        try {
            DB::transaction(function () use ($request, $validated, $pegawai, $butuhNidn) {
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
                    'nip' => $validated['nip'],
                    'nidn' => $butuhNidn ? $validated['nidn'] : null,
                    'nama' => $validated['nama'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'no_hp' => $validated['no_hp'],
                    'no_hp_darurat' => $validated['no_hp_darurat'] ?? null,
                    'homebase' => $validated['homebase'],
                    'email' => $validated['email'],
                    'pangkat_golongan' => $validated['pangkat_golongan'],
                    'jabatan_fungsional' => $validated['jabatan_fungsional'],
                    'foto' => $namaFoto,
                ]);

                if ($pegawai->user) {
                    $userData = [
                        'name' => $validated['nama'],
                        'email' => $validated['email'],
                    ];

                    if (! empty($validated['password'])) {
                        $userData['password'] = Hash::make($validated['password']);
                    }

                    $pegawai->user->update($userData);
                }

                $roleIds = Role::query()
                    ->whereIn('nama_role', $validated['roles'])
                    ->pluck('id_role')
                    ->toArray();

                if (count($roleIds) !== count($validated['roles'])) {
                    throw ValidationException::withMessages([
                        'roles' => 'Data role belum lengkap di tabel role.',
                    ]);
                }

                $pegawai->roles()->sync($roleIds);
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'database' => 'Gagal memperbarui data pegawai: '.$e->getMessage(),
                ])
                ->withInput();
        }

        return redirect('/operator/daftar-pegawai')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::with('user', 'roles')->findOrFail($id);

        try {
            DB::transaction(function () use ($pegawai) {

                if ($pegawai->foto && file_exists(public_path('photo/'.$pegawai->foto))) {
                    unlink(public_path('photo/'.$pegawai->foto));
                }

                $pegawai->roles()->detach();

                if ($pegawai->user) {
                    $pegawai->user->delete();
                }

                $pegawai->delete();
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'delete' => 'Gagal menghapus data pegawai: '.$e->getMessage(),
                ]);
        }

        return redirect('/operator/daftar-pegawai')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}

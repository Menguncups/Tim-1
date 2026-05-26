<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    private array $roleRoutes = [
        'operator' => '/operator/dashboard',
        'dosen' => '/dosten/dashboard',
        'tendik' => '/dosten/dashboard',
        'pimpinan' => '/pimpinan/dashboard',
    ];

    private array $roleMeta = [
        'operator' => [
            'label' => 'Operator',
            'desc' => 'Pengelola & admin data pegawai',
            'cls' => 'op',
        ],
        'dosen' => [
            'label' => 'Dosen',
            'desc' => 'Tenaga pendidik / pengajar',
            'cls' => 'dos',
        ],
        'tendik' => [
            'label' => 'Tendik',
            'desc' => 'Tenaga kependidikan / staf',
            'cls' => 'ten',
        ],
        'pimpinan' => [
            'label' => 'Pimpinan',
            'desc' => 'Dekan, wadek & ketua jurusan',
            'cls' => 'pim',
        ],
    ];

    public function showLogin()
    {
        return view('auth.login', [
            'step' => 1,
            'loginUser' => null,
            'roleMeta' => $this->roleMeta,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'username.required' => 'Email wajib diisi.',
            'username.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::with('pegawai.roles')
            ->where('email', $request->username)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'username' => 'Email atau password salah.',
                ])
                ->withInput();
        }

        if (! $user->pegawai) {
            return back()
                ->withErrors([
                    'username' => 'Akun ini belum terhubung dengan data pegawai.',
                ])
                ->withInput();
        }

        $roles = $user->pegawai->roles
            ->pluck('nama_role')
            ->map(fn ($role) => strtolower($role))
            ->values()
            ->toArray();

        if (count($roles) < 1) {
            return back()
                ->withErrors([
                    'username' => 'Akun ini belum memiliki role.',
                ])
                ->withInput();
        }

        $loginUser = [
            'id' => $user->pegawai->id_pegawai,
            'user_id' => $user->id,
            'username' => $user->email,
            'email' => $user->email,
            'nama' => $user->pegawai->nama,
            'roles' => $roles,
            'nip' => $user->pegawai->nip,
            'nidn' => $user->pegawai->nidn,
            'foto' => $user->pegawai->foto,
            'homebase' => $user->pegawai->homebase,
            'pangkat_golongan' => $user->pegawai->pangkat_golongan,
            'jabatan_fungsional' => $user->pegawai->jabatan_fungsional,
        ];

        if (count($roles) === 1) {
            $role = $roles[0];

            session([
                'auth_user' => $loginUser,
                'auth_role' => $role,
            ]);

            return redirect($this->roleRoutes[$role] ?? '/login');
        }

        session([
            'login_user_temp' => $loginUser,
        ]);

        return redirect()->route('login.role');
    }

    public function showRole()
    {
        $loginUser = session('login_user_temp');

        if (! $loginUser) {
            return redirect()->route('login');
        }

        return view('auth.login', [
            'step' => 2,
            'loginUser' => $loginUser,
            'roleMeta' => $this->roleMeta,
        ]);
    }

    public function chooseRole(Request $request)
    {
        $request->validate([
            'role' => ['required'],
        ], [
            'role.required' => 'Role wajib dipilih.',
        ]);

        $loginUser = session('login_user_temp');

        if (! $loginUser) {
            return redirect()->route('login');
        }

        $role = strtolower($request->role);

        if (! in_array($role, $loginUser['roles'])) {
            return back()->withErrors([
                'role' => 'Akun Anda tidak memiliki akses role tersebut.',
            ]);
        }

        session([
            'auth_user' => $loginUser,
            'auth_role' => $role,
        ]);

        session()->forget('login_user_temp');

        return redirect($this->roleRoutes[$role] ?? '/login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'auth_user',
            'auth_role',
            'login_user_temp',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

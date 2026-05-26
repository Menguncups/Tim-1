<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PimpinanDashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = DB::table('pegawai')->count();

        $totalDosen = $this->countPegawaiByRole('dosen');
        $totalTendik = $this->countPegawaiByRole('tendik');

        // Pengajuan baru untuk pimpinan adalah pengajuan yang sudah diproses operator
        // dan menunggu keputusan pimpinan.
        $pengajuanBaru = DB::table('pengajuan')
            ->where('status', 'diproses')
            ->count();

        $jabfungChart = DB::table('pegawai')
            ->join('role_pegawai', 'pegawai.id_pegawai', '=', 'role_pegawai.pegawai_id_pegawai')
            ->join('role', 'role.id_role', '=', 'role_pegawai.role_id_role')
            ->where('role.nama_role', 'dosen')
            ->selectRaw('COALESCE(pegawai.jabatan_fungsional, "Tidak Ada") as label, COUNT(DISTINCT pegawai.id_pegawai) as total')
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        $panggolChart = DB::table('pegawai')
            ->selectRaw('COALESCE(pangkat_golongan, "Tidak Ada") as label, COUNT(*) as total')
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        $genderChart = DB::table('pegawai')
            ->selectRaw('COALESCE(jenis_kelamin, "Tidak Ada") as label, COUNT(*) as total')
            ->groupBy('label')
            ->pluck('total', 'label')
            ->toArray();

        $usiaChart = [
            '< 30 Tahun' => DB::table('pegawai')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 30')
                ->count(),

            '30 - 39 Tahun' => DB::table('pegawai')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 30 AND 39')
                ->count(),

            '40 - 49 Tahun' => DB::table('pegawai')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 40 AND 49')
                ->count(),

            '>= 50 Tahun' => DB::table('pegawai')
                ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 50')
                ->count(),
        ];

        return view('pimpinan.pimpinanDashboard', compact(
            'totalPegawai',
            'totalDosen',
            'totalTendik',
            'pengajuanBaru',
            'jabfungChart',
            'panggolChart',
            'genderChart',
            'usiaChart'
        ));
    }

    private function countPegawaiByRole(string $roleName): int
    {
        return DB::table('pegawai')
            ->join('role_pegawai', 'pegawai.id_pegawai', '=', 'role_pegawai.pegawai_id_pegawai')
            ->join('role', 'role.id_role', '=', 'role_pegawai.role_id_role')
            ->where('role.nama_role', $roleName)
            ->distinct()
            ->count('pegawai.id_pegawai');
    }
}
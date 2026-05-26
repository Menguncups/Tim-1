<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class OperatorDashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = DB::table('pegawai')->count();

        $totalDosen = $this->countPegawaiByRole('dosen');
        $totalTendik = $this->countPegawaiByRole('tendik');
        $totalOperator = $this->countPegawaiByRole('operator');
        $totalPimpinan = $this->countPegawaiByRole('pimpinan');

        $pengajuanBaru = DB::table('pengajuan')
            ->where('status', 'menunggu')
            ->count();

        $roleChart = [
            'Dosen' => $totalDosen,
            'Tendik' => $totalTendik,
            'Operator' => $totalOperator,
            'Pimpinan' => $totalPimpinan,
        ];

        $statusChart = [
            'Menunggu' => DB::table('pengajuan')->where('status', 'menunggu')->count(),
            'Diproses' => DB::table('pengajuan')->where('status', 'diproses')->count(),
            'Diterima' => DB::table('pengajuan')->where('status', 'diterima')->count(),
            'Ditolak' => DB::table('pengajuan')->where('status', 'ditolak')->count(),
        ];

        return view('operator.dashboard', compact(
            'totalPegawai',
            'totalDosen',
            'totalTendik',
            'pengajuanBaru',
            'roleChart',
            'statusChart'
        ));
    }

    private function countPegawaiByRole(string $roleName): int
    {
        return DB::table('pegawai')
            ->join('role_pegawai', 'pegawai.id_pegawai', '=', 'role_pegawai.pegawai_id_pegawai')
            ->join('role', 'role.id_role', '=', 'role_pegawai.role_id_role')
            ->where('role.nama_role', $roleName)
            ->distinct('pegawai.id_pegawai')
            ->count('pegawai.id_pegawai');
    }
}
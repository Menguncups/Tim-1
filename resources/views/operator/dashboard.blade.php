<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_operator.css') }}">
</head>

<body>
    <div class="app">

        @include('Sidebar.operatorSideBar')

        <div class="main-wrapper">
            <div class="content">

                <div class="welcome-banner">
                    <div class="welcome-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>

                    <div class="welcome-text">
                        <div class="greeting">Selamat datang, Operator</div>
                        <div class="title">Panel Operator, Sistem Kepegawaian FT-UNRI</div>
                        <div class="subtitle">
                            Pantau dan kelola seluruh data pegawai Fakultas Teknik dari satu tampilan terpadu.
                        </div>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-value">{{ $totalPegawai }}</div>
                        <div class="stat-label">Total Pegawai</div>
                    </div>

                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="stat-value">{{ $totalDosen }}</div>
                        <div class="stat-label">Total Dosen</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="stat-value">{{ $totalTendik }}</div>
                        <div class="stat-label">Tenaga Kependidikan</div>
                    </div>

                    <div class="stat-card amber">
                        <div class="stat-icon amber">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="stat-value">{{ $pengajuanBaru }}</div>
                        <div class="stat-label">Pengajuan Baru</div>
                    </div>
                </div>

                <div class="dashboard-chart-grid">
                    <div class="chart-card">
                        <div class="chart-title">Distribusi Role Pegawai</div>
                        <div class="chart-subtitle">Jumlah pegawai berdasarkan role</div>
                        <div class="chart-wrap">
                            <canvas id="chartRole"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-title">Status Pengajuan</div>
                        <div class="chart-subtitle">Rekap status seluruh pengajuan</div>
                        <div class="chart-wrap">
                            <canvas id="chartStatus"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            @include('Footer.footer')

        </div>
    </div>

    <script>
        window.dashboardOperatorData = {
            roleLabels: @json(array_keys($roleChart)),
            roleValues: @json(array_values($roleChart)),
            statusLabels: @json(array_keys($statusChart)),
            statusValues: @json(array_values($statusChart)),
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/operatorSideBar.js') }}"></script>

    <script src="{{ asset('js/dashboard_operator.js') }}"></script>
</body>

</html>

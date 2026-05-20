<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pimpinan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- Sidebar CSS --}}
    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">

    {{-- Footer CSS --}}
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard_pimpinan.css') }}">
</head>

<body>
    <div class="app">

        {{-- SIDEBAR --}}
        @include('Sidebar.pimpinanSidebar')

        <!-- MAIN -->
        <div class="main-wrapper">
            <div class="content">

                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <div class="welcome-icon">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>

                    <div class="welcome-text welcome-text-flex">
                        <div class="greeting">Selamat datang, Pimpinan</div>
                        <div class="title">Panel Pimpinan, Sistem Kepegawaian FT-UNRI</div>
                        <div class="subtitle">
                            Pantau dan kelola seluruh data pegawai Fakultas Teknik dari satu tampilan terpadu.
                        </div>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="stats-grid">
                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-value" id="cnt-total">0</div>
                        <div class="stat-label">Total Pegawai</div>
                    </div>

                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="stat-value" id="cnt-dosen">0</div>
                        <div class="stat-label">Total Dosen</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="stat-value" id="cnt-tendik">0</div>
                        <div class="stat-label">Tenaga Kependidikan</div>
                    </div>

                    <div class="stat-card amber">
                        <div class="stat-icon amber">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="stat-value" id="cnt-surat">0</div>
                        <div class="stat-label">Pengajuan Baru</div>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div>
                    <div class="section-header">
                        <div class="section-title">
                            <i class="bi bi-graph-up-arrow"></i>
                            Statistik &amp; Grafik
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <div class="chart-title">Jabatan Fungsional Dosen</div>
                            <div class="chart-subtitle">Distribusi per jenjang jabatan akademik</div>
                            <div class="chart-wrap">
                                <canvas id="chartJabfung"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-title">Pangkat &amp; Golongan</div>
                            <div class="chart-subtitle">Distribusi kepangkatan pegawai</div>
                            <div class="chart-wrap">
                                <canvas id="chartGolongan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="chart-title">Jenis Kelamin</div>
                        <div class="chart-subtitle">Rasio gender dosen</div>
                        <div class="chart-wrap-sm">
                            <canvas id="chartGender"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-title">Rentang Usia</div>
                        <div class="chart-subtitle">Distribusi umur pegawai</div>
                        <div class="chart-wrap-sm">
                            <canvas id="chartUsia"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            @include('Footer.footer')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
    <script src="{{ asset('js/dashboard_pimpinan.js') }}"></script>
</body>

</html>

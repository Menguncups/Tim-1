<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Bootstrap CSS, karena sidebar kamu pakai class Bootstrap seperti d-flex, me-2, collapse --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- CSS Sidebar --}}
    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">

    {{-- CSS Dashboard Operator --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard_operator.css') }}">
</head>

<body>
    <div class="app">

        {{-- Panggil Sidebar Operator --}}
        @include('Sidebar.operatorSidebar')

        <!-- MAIN -->
        <div class="main-wrapper">
            <div class="content">

                <!-- Welcome Banner -->
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
                        <div class="stat-value" id="cnt-pengajuan">0</div>
                        <div class="stat-label">Pengajuan Baru</div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            @include('Footer.footer')

        </div>
    </div>

    {{-- Bootstrap JS, wajib untuk collapse verifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS Dashboard Operator --}}
    <script src="{{ asset('js/dashboard_operator.js') }}"></script>
</body>

</html>

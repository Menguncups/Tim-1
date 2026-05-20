<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Tendik</title>

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  {{-- Bootstrap CSS, dibutuhkan untuk collapse sidebar --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  {{-- Chart --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  {{-- Sidebar CSS --}}
  <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">

  {{-- Footer CSS --}}
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

  {{-- Dashboard Tendik CSS --}}
  <link rel="stylesheet" href="{{ asset('css/dashboard_tendik.css') }}">
</head>

<body>
  <div class="app">

    {{-- SIDEBAR --}}
    @include('Sidebar.dostenSidebar')

    <!-- MAIN -->
    <div class="main-wrapper">
      <div class="content">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
          <div class="welcome-icon">
            <i class="bi bi-person-badge-fill"></i>
          </div>

          <div class="welcome-text">
            <div class="greeting">Selamat datang kembali, Tenaga Kependidikan</div>
            <div class="title">Panel Tenaga Kependidikan, Sistem Kepegawaian FT-UNRI</div>
            <div class="subtitle">
              Kelola data diri, pengajuan surat tugas, dan pantau status kepangkatan Anda di sini.
            </div>
          </div>
        </div>

        <!-- Profile Hero -->
        <div class="profile-hero">
          <div class="profile-hero-inner">
            <div class="profile-avatar" id="profileAvatar">
              <i class="bi bi-person-bounding-box"></i>
            </div>

            <div class="profile-meta">
              <div class="name" id="profileName">—</div>
              <div class="role-text" id="profileRole">—</div>

              <div class="profile-chips">
                <span class="chip">
                  <i class="bi bi-person-badge"></i>
                  NIK: <span id="profileNik">—</span>
                </span>

                <span class="chip">
                  <i class="bi bi-award"></i>
                  <span id="profileGolongan">—</span>
                </span>

                <span class="chip">
                  <i class="bi bi-building"></i>
                  <span id="profileUnit">—</span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div>
          <div class="section-header">
            <div class="section-title">
              <i class="bi bi-graph-up-arrow"></i>
              Statistik Saya
            </div>
          </div>

          <div class="charts-grid">

            <!-- Pangkat / Golongan -->
            <div class="chart-card">
              <div class="chart-title">Pangkat / Golongan</div>
              <div class="chart-subtitle">Posisi golongan kepangkatan Anda saat ini</div>
              <div class="chart-wrap">
                <canvas id="chartGolongan"></canvas>
              </div>
              <div class="legend-row" id="legendGolongan"></div>
            </div>

            <!-- Status Surat Tugas -->
            <div class="chart-card">
              <div class="chart-title">Status Surat Tugas</div>
              <div class="chart-subtitle">Rekap pengajuan surat tugas Anda</div>
              <div class="chart-wrap-donut">
                <canvas id="chartSurat"></canvas>
              </div>
              <div class="legend-row" id="legendSurat"></div>
            </div>

          </div>
        </div>

      </div>

      {{-- FOOTER --}}
      @include('Footer.footer')

    </div>
  </div>

  {{-- Bootstrap JS, dibutuhkan untuk collapse sidebar --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Sidebar JS --}}
  <script src="{{ asset('js/operatorSideBar.js') }}"></script>

  {{-- Dashboard Tendik JS --}}
  <script src="{{ asset('js/dashboard_tendik.js') }}"></script>
</body>

</html>
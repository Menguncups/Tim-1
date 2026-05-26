<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tendik</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_tendik.css') }}">
</head>

<body>
    <div class="app">

        @include('Sidebar.dostenSideBar')

        <div class="main-wrapper">
            <div class="content">

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

                <div class="profile-hero">
                    <div class="profile-hero-inner">

                        <div class="profile-avatar">
                            @if (!empty($pegawai->foto))
                                <img src="{{ asset('photo/' . $pegawai->foto) }}" alt="Foto {{ $pegawai->nama }}">
                            @else
                                <i class="bi bi-person-bounding-box"></i>
                            @endif
                        </div>

                        <div class="profile-meta">
                            <div class="name">
                                {{ $pegawai->nama ?? '-' }}
                            </div>

                            <div class="role-text">
                                Tenaga Kependidikan
                            </div>

                            <div class="profile-chips">
                                <span class="chip">
                                    <i class="bi bi-person-badge"></i>
                                    NIP: {{ $pegawai->nip ?? '-' }}
                                </span>

                                <span class="chip">
                                    <i class="bi bi-award"></i>
                                    {{ $pegawai->jabatan_fungsional ?? '-' }}
                                </span>

                                <span class="chip">
                                    <i class="bi bi-patch-check"></i>
                                    {{ $pegawai->pangkat_golongan ?? '-' }}
                                </span>

                                <span class="chip">
                                    <i class="bi bi-building"></i>
                                    {{ $pegawai->homebase ?? '-' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            @include('Footer.footer')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
</body>

</html>

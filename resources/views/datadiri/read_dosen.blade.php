<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri Dosen - SISTER</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary:       #b52a20;
            --primary-dark:  #8c1e15;
            --primary-light: #fdf1f0;
            --accent:        #e85d4a;
            --sidebar-bg:    #ffffff;
            --bg:            #f0f2f7;
            --card-bg:       #ffffff;
            --border:        #e2e6ed;
            --text-main:     #1e2235;
            --text-muted:    #7a8099;
            --radius-card:   16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
        }

        /* Sidebar Styling */
        #sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 24px;
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            border-bottom: 1px solid var(--border);
        }
        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
        }
        .nav-link-custom.active {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        /* Main Content */
        #main-content {
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        }
        .hero-avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .info-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
        }

        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <div id="sidebar">
        <div class="sidebar-brand"><i class="bi bi-mortarboard-fill me-2"></i>SISTER</div>
        <div class="py-4">
            <a href="#" class="nav-link-custom active"><i class="bi bi-person-vcard-fill fs-5"></i> Data Diri</a>
        </div>
    </div>

    <div id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-light d-lg-none" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
            <h4 class="fw-bold mb-0">Profil Dosen</h4>
            <a href="{{ route('pegawai.edit', 'dosen') }}" class="btn btn-danger px-4" style="background-color: var(--primary); border: none;">
                <i class="bi bi-pencil-square me-2"></i> Perbarui Data Diri
            </a>
        </div>

        <div class="profile-card mb-4">
            <div class="d-flex align-items-center gap-4">
                <div class="hero-avatar" id="heroAvatar">
                    <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : 'https://via.placeholder.com/150' }}" alt="Foto">
                </div>
                <div>
                    <h3 class="fw-bold mb-1">{{ $pegawai->nama }}</h3>
                    <p class="text-muted mb-0"><i class="bi bi-card-text me-1"></i> NIP. {{ $pegawai->nip }} | <span class="badge bg-danger text-white">Dosen</span></p>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h5 class="fw-bold mb-4 text-danger"><i class="bi bi-text-paragraph me-2"></i> Informasi Biodata</h5>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $pegawai->nama }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">NIP</div>
                    <div class="info-value">{{ $pegawai->nip }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">NIDN</div>
                    <div class="info-value">{{ $pegawai->nidn ?? '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $pegawai->jenis_kelamin }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">Tempat & Tanggal Lahir</div>
                    <div class="info-value">{{ $pegawai->tempat_lahir }}, {{ $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d F Y') : '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">Homebase (Prodi)</div>
                    <div class="info-value">{{ $pegawai->homebase }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">No. HP / WhatsApp</div>
                    <div class="info-value">{{ $pegawai->no_hp }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">No. HP Darurat</div>
                    <div class="info-value">{{ $pegawai->no_hp_darurat }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="info-label">Email Resmi</div>
                    <div class="info-value">{{ $pegawai->email }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
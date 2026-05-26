@php
    $role = session('auth_role');

    $isDosen = $role === 'dosen';
    $isTendik = $role === 'tendik';

    $roleLabel = $isDosen ? 'Dosen' : 'Tendik';
    $pageSub = $isDosen ? 'Informasi profil dan biodata dosen' : 'Informasi profil dan biodata tenaga kependidikan';

    $roleClass = $isDosen ? 'role-dosen' : 'role-tendik';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri {{ $roleLabel }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dostenDatadiri.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        @include('Sidebar.dostenSideBar')

        <div class="content-area">

            <div class="page-content">

                <div class="dd-page-header">
                    <div class="dd-header-left">
                        <div class="dd-header-icon {{ $roleClass }}">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <h4 class="dd-page-title">Data Diri {{ $roleLabel }}</h4>
                            <p class="dd-page-sub">{{ $pageSub }}</p>
                        </div>
                    </div>

                    <a href="{{ url('/dosten/data-diri/edit') }}" class="btn-dd-update {{ $roleClass }}">
                        <i class="bi bi-pencil-square"></i>
                        Perbarui Data Diri
                    </a>
                </div>

                <div class="profile-card profile-hero-card {{ $roleClass }} mb-4">
                    <div class="profile-hero-content">
                        <div class="hero-avatar {{ $roleClass }}">
                            @if (!empty($pegawai->foto))
                                <img src="{{ asset('photo/' . $pegawai->foto) }}" alt="Foto {{ $pegawai->nama }}">
                            @else
                                <div class="avatar-fallback">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="profile-name">{{ $pegawai->nama ?? '-' }}</h3>

                            <div class="profile-meta">
                                <span>
                                    <i class="bi bi-card-text"></i>
                                    NIP. {{ $pegawai->nip ?? '-' }}
                                </span>

                                <span class="role-badge {{ $roleClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="section-title {{ $roleClass }}">
                        <i class="bi bi-text-paragraph"></i>
                        Informasi Biodata
                    </div>

                    <div class="info-grid">

                        <div class="info-item">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $pegawai->nama ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">NIP</div>
                            <div class="info-value">{{ $pegawai->nip ?? '-' }}</div>
                        </div>

                        @if ($isDosen)
                            <div class="info-item">
                                <div class="info-label">NIDN</div>
                                <div class="info-value">{{ $pegawai->nidn ?? '—' }}</div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value">{{ $pegawai->jenis_kelamin ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Tanggal Lahir</div>
                            <div class="info-value">
                                {{ !empty($pegawai->tanggal_lahir) ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d-m-Y') : '—' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">{{ $isDosen ? 'Homebase' : 'Unit Kerja' }}</div>
                            <div class="info-value">{{ $pegawai->homebase ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Pangkat / Golongan</div>
                            <div class="info-value">{{ $pegawai->pangkat_golongan ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Jabatan Fungsional</div>
                            <div class="info-value">{{ $pegawai->jabatan_fungsional ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">No. HP</div>
                            <div class="info-value">{{ $pegawai->no_hp ?? '-' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">No. HP Darurat</div>
                            <div class="info-value">{{ $pegawai->no_hp_darurat ?? '—' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $pegawai->email ?? '-' }}</div>
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

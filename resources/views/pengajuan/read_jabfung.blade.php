<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jabatan Fungsional — FT UNRI</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/ReadJabPang.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('icon/unriteknik.png') }}" alt="Fakultas Teknik UNRI">
            </div>

            <nav class="sidebar-nav">
                <div class="nav-group-label">Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="/dosen/dashboard" class="nav-link">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    </li>
                </ul>

                <div class="nav-group-label">Data Diri</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="/dosen/datadiri" class="nav-link">
                            <i class="bi bi-person-badge"></i> Data Diri
                        </a>
                    </li>
                </ul>

                <div class="nav-group-label">Pengajuan</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="/dosen/pengajuan/surtug" class="nav-link">
                            <i class="bi bi-envelope-plus-fill"></i> Surat Tugas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/dosen/pengajuan/jabfung" class="nav-link active">
                            <i class="bi bi-person-vcard"></i> Jabatan Fungsional
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/dosen/pengajuan/panggol" class="nav-link">
                            <i class="bi bi-award"></i> Pangkat Golongan
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a href="#" class="logout-link">
                    <i class="bi bi-box-arrow-in-right"></i> Logout
                </a>
            </div>
        </aside>

        <div class="content-col">
            <main class="content-area">

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="header-icon">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                    <div>
                        <h4 class="page-title mb-0">Jabatan Fungsional</h4>
                        <p class="page-sub mb-0">Informasi Jabatan Fungsional dosen/tenaga kependidikan</p>
                    </div>
                </div>

                <div class="profile-hero">
                    <div class="hero-top">
                        <div class="avatar-wrap">
                            <div class="avatar-placeholder">
                                <i class="bi bi-person-bounding-box"></i>
                            </div>
                            <span class="status-badge-hero">Aktif</span>
                        </div>
                        <div class="hero-info">
                            <div class="hero-name">
                                {{ $pegawai->nama }}
                            </div>

                            <div class="hero-nip">
                                NIP: {{ $pegawai->nip }}
                            </div>
                            <span class="hero-tag">
                                <i class="bi bi-building"></i>
                                {{ $pegawai->homebase }}
                            </span>
                            <span class="hero-tag">
                                <i class="bi bi-upc"></i>
                                NIDN: {{ $pegawai->nidn }}
                            </span>
                            @if($pengajuanAktif)
                                <a href="#" class="btn-hero-edit btn-disabled">
                                    <i class="bi bi-lock-fill"></i> Pengajuan Belum Selesai
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="info-alert mt-3">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>Informasi</strong><br>
                        Pengajuan baru hanya dapat dilakukan setelah seluruh proses pengajuan sebelumnya selesai.
                    </div>
                </div>

                <div class="section-sep mt-4">
                    <span class="section-sep-label">
                        <i class="bi bi-table me-1"></i> Riwayat Jabatan Fungsional
                    </span>
                </div>

                <div class="info-section">
                    <div class="info-section-header">
                        <div class="info-section-title">
                            <i class="bi bi-archive-fill"></i> Data Riwayat Jabatan
                        </div>

                        @if(!$pengajuanAktif)
                            <a href="/dosen/pengajuan/jabfung/create" class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="background-color: #b52a20; border-color: #b52a20; padding: 6px 14px; font-weight: 500; border-radius: 6px;">
                                <i class="bi bi-plus-lg"></i> Tambah Pengajuan
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled style="padding: 6px 14px; font-weight: 500; border-radius: 6px;">
                                Pengajuan Masih Berjalan
                            </button>
                        @endif
                    </div>

                    <div class="table-wrapper">
                        <table id="jabfungTable" class="custom-table table align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Berkas</th>
                                    <th>Status</th>
                                    <th class="aksi-col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_jabatan }}</td>
                                    <td>
                                        {{ $item->tmt ? \Carbon\Carbon::parse($item->tmt)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $files = [
                                                'SK CPNS' => $item->dokumen_sk_cpns ?? null,
                                                'SK PNS' => $item->dokumen_sk_pns ?? null,
                                                'Berkas PAK' => $item->dokumen_pak ?? null,
                                                'Publikasi Ilmiah' => $item->dokumen_publikasi_ilmiah ?? null,
                                            ];
                                        @endphp

                                        @if(collect($files)->filter()->count() > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($files as $label => $file)
                                                    @if($file)
                                                        {{-- PERBAIKAN: Link berkas dibungkus Storage::url() agar jalur URL /storage/ valid --}}
                                                        <a href="{{ Storage::url(str_replace('public/', '', $file)) }}" target="_blank" style="font-size: 13px; text-decoration: none;">
                                                            <i class="bi bi-file-earmark-pdf text-danger"></i> {{ $label }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->pengajuan?->status == 'menunggu')
                                            <span class="badge-status">
                                                <i class="bi bi-hourglass-split"></i> Menunggu
                                            </span>
                                        @elseif($item->pengajuan?->status == 'disetujui')
                                            <span class="badge-status approved">
                                                <i class="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($item->pengajuan?->status == 'ditolak')
                                            <span class="badge-status draft">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @elseif($item->pengajuan?->status == 'draft' || !$item->pengajuan)
                                            <span class="badge-status draft">
                                                <i class="bi bi-file-earmark-text"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="aksi-group">
                                            @if($item->pengajuan && $item->pengajuan->status=='menunggu')
                                                <a href="/dosen/pengajuan/jabfung/edit/{{ $item->id_pengajuan }}" class="btn-action" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="/dosen/pengajuan/jabfung/{{ $item->id_pengajuan }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action text-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

            <footer class="site-footer">
                <div class="footer-left">
                    <div class="footer-logo-wrap">
                        <img src="{{ asset('icon/unriteknik.png') }}" alt="UNRI" onerror="this.style.display='none'">
                    </div>
                    <p class="footer-address mb-0">
                        Fakultas Teknik, Kampus Binawidya<br>
                        JL. HR Soebrantas KM.12.5, Simpang Baru, Panam
                    </p>
                </div>
                <div class="footer-right">
                    © 2026 Fakultas Teknik UNRI
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/Read_JabFung.js') }}"></script>

</body>
</html>

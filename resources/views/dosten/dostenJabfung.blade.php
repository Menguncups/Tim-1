<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Jabatan Fungsional</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        @include('Sidebar.dostenSideBar')

        <div class="content-area">

            <div class="page-content">

                <div class="du-page-header">
                    <div class="du-header-left">
                        <div class="header-icon">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <h4 class="du-page-title">Pengajuan Jabatan Fungsional</h4>
                            <p class="du-page-sub">Ajukan dan pantau perubahan jabatan fungsional Anda</p>
                        </div>
                    </div>

                    <a href="{{ route('dosten.jabfung.create') }}" class="btn-du-tambah">
                        <i class="bi bi-plus-circle-fill"></i>
                        Ajukan Jabfung
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan.</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="du-filter-bar">
                    <button class="du-pill active" data-status="semua">Semua</button>

                    <button class="du-pill" data-status="menunggu">
                        <i class="bi bi-hourglass-split"></i> Menunggu
                    </button>

                    <button class="du-pill" data-status="diproses">
                        <i class="bi bi-arrow-repeat"></i> Diproses
                    </button>

                    <button class="du-pill" data-status="diterima">
                        <i class="bi bi-check-circle-fill"></i> Diterima
                    </button>

                    <button class="du-pill" data-status="ditolak">
                        <i class="bi bi-x-circle-fill"></i> Ditolak
                    </button>
                </div>

                <div class="du-table-wrap">
                    <table id="tabelJabfung" class="display w-100 tabel-dosten" data-status-column="5">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jabatan</th>
                                <th>TMT</th>
                                <th>Berkas Pendukung</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($jabfungList as $item)
                                @php
                                    $status = strtolower($item->status);

                                    $statusLabel = match ($status) {
                                        'menunggu' => 'Menunggu',
                                        'diproses' => 'Diproses',
                                        'diterima' => 'Diterima',
                                        'ditolak' => 'Ditolak',
                                        default => ucfirst($item->status),
                                    };

                                    $statusClass = match ($status) {
                                        'menunggu' => 'surtug-status-menunggu',
                                        'diproses' => 'surtug-status-diproses',
                                        'diterima' => 'surtug-status-diterima',
                                        'ditolak' => 'surtug-status-ditolak',
                                        default => 'surtug-status-menunggu',
                                    };

                                    $fileUrl = asset('berkas/jabfung/' . $item->berkas_pendukung);
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div class="surtug-title">{{ $item->nama_jabatan }}</div>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tmt)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ $fileUrl }}" target="_blank" class="du-act du-act-file"
                                            title="Lihat Berkas">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        <span class="surtug-status {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $item->catatan ?? '—' }}
                                    </td>

                                    <td class="text-center">
                                        <div class="du-actions">
                                            <button type="button" class="du-act du-act-view btn-detail-jabfung"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailJabfung"
                                                data-id="{{ $item->id_pengajuan }}"
                                                data-nama-jabatan="{{ $item->nama_jabatan }}"
                                                data-tmt="{{ \Carbon\Carbon::parse($item->tmt)->format('d-m-Y') }}"
                                                data-berkas="{{ $item->berkas_pendukung }}"
                                                data-file-url="{{ $fileUrl }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}"
                                                data-status="{{ $statusLabel }}"
                                                data-catatan="{{ $item->catatan ?? '—' }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MODAL DETAIL JABFUNG --}}
                <div class="modal fade" id="modalDetailJabfung" tabindex="-1" aria-labelledby="modalDetailJabfungLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content detail-modal-content">

                            <div class="modal-header detail-modal-header">
                                <div>
                                    <h5 class="modal-title" id="modalDetailJabfungLabel">
                                        <i class="bi bi-person-vcard-fill me-2"></i>
                                        Detail Jabatan Fungsional
                                    </h5>
                                    <small>Informasi lengkap pengajuan jabatan fungsional</small>
                                </div>

                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body detail-modal-body">

                                <div class="detail-profile-box">
                                    <div class="detail-photo-wrap detail-surtug-icon">
                                        <i class="bi bi-person-vcard-fill"></i>
                                    </div>

                                    <div>
                                        <h5 id="detailJabfungNamaJabatan" class="detail-name mb-1">-</h5>
                                        <p id="detailJabfungTmtText" class="detail-email mb-2">-</p>
                                        <span id="detailJabfungStatus" class="detail-role-badge">-</span>
                                    </div>
                                </div>

                                <div class="detail-grid mt-4">

                                    <div class="detail-item">
                                        <span>ID Pengajuan</span>
                                        <strong id="detailJabfungId">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Nama Jabatan</span>
                                        <strong id="detailJabfungNama">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>TMT</span>
                                        <strong id="detailJabfungTmt">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Tanggal Pengajuan</span>
                                        <strong id="detailJabfungTanggal">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Catatan</span>
                                        <strong id="detailJabfungCatatan">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Berkas Pendukung</span>
                                        <strong>
                                            <a href="#" id="detailJabfungBerkasLink" target="_blank"
                                                class="text-decoration-none">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                                <span id="detailJabfungBerkas">-</span>
                                            </a>
                                        </strong>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary detail-btn-close"
                                    data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            @include('Footer.footer')

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
    <script src="{{ asset('js/dostenPengajuan.js') }}"></script>

</body>

</html>

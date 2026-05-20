<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Surat Tugas</title>

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

        @include('Sidebar.pimpinanSidebar')

        <div class="content-area">

            <div class="page-content">

                <div class="du-page-header">
                    <div class="du-header-left">
                        <div class="header-icon">
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>

                        <div>
                            <h4 class="du-page-title">Persetujuan Surat Tugas</h4>
                            <p class="du-page-sub">Kelola pengajuan surat tugas yang telah diproses operator</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
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

                    <button class="du-pill" data-status="diproses">
                        <i class="bi bi-arrow-repeat"></i> Diproses
                    </button>
                </div>

                <div class="du-table-wrap">
                    <table id="tabelPimpinanSurtug" class="display w-100 tabel-pimpinan" data-status-column="7">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengaju</th>
                                <th>NIP</th>
                                <th>Waktu Pelaksanaan</th>
                                <th>Lama</th>
                                <th>Perihal</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($suratTugas as $item)
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

                                    $fileUrl = asset('berkas/surat_tugas/' . $item->berkas_pendukung);
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div>
                                            <div class="du-user-name">{{ $item->nama }}</div>
                                            <div class="du-user-email">{{ $item->email }}</div>
                                        </div>
                                    </td>

                                    <td class="du-nip">{{ $item->nip }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->waktu_pelaksana)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        {{ $item->lama_pelaksanaan }} Hari
                                    </td>

                                    <td>
                                        <div class="surtug-title">{{ $item->perihal }}</div>
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

                                            <button type="button"
                                                class="du-act du-act-view btn-detail-surtug"
                                                title="Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetailSurtug"
                                                data-id="{{ $item->id_pengajuan }}"
                                                data-nama="{{ $item->nama }}"
                                                data-email="{{ $item->email }}"
                                                data-nip="{{ $item->nip }}"
                                                data-homebase="{{ $item->homebase }}"
                                                data-nama-pengusul="{{ $item->nama_pengusul }}"
                                                data-waktu="{{ \Carbon\Carbon::parse($item->waktu_pelaksana)->format('d-m-Y') }}"
                                                data-lama="{{ $item->lama_pelaksanaan }} Hari"
                                                data-perihal="{{ $item->perihal }}"
                                                data-berkas="{{ $item->berkas_pendukung }}"
                                                data-file-url="{{ $fileUrl }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}"
                                                data-status="{{ $statusLabel }}"
                                                data-catatan="{{ $item->catatan ?? '—' }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <a href="{{ $fileUrl }}"
                                                target="_blank"
                                                class="du-act du-act-file"
                                                title="Lihat Berkas">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </a>

                                            <form action="{{ route('pimpinan.surtug.terima', $item->id_pengajuan) }}"
                                                method="POST"
                                                class="d-inline form-terima-surtug"
                                                data-nama="{{ $item->nama }}">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    class="du-act du-act-approve"
                                                    title="Terima">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('pimpinan.surtug.tolak', $item->id_pengajuan) }}"
                                                method="POST"
                                                class="d-inline form-tolak-surtug"
                                                data-nama="{{ $item->nama }}">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="catatan" class="input-catatan-tolak">

                                                <button type="submit"
                                                    class="du-act du-act-reject"
                                                    title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="modalDetailSurtug" tabindex="-1"
                    aria-labelledby="modalDetailSurtugLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content detail-modal-content">

                            <div class="modal-header detail-modal-header">
                                <div>
                                    <h5 class="modal-title" id="modalDetailSurtugLabel">
                                        <i class="bi bi-envelope-paper-fill me-2"></i>
                                        Detail Surat Tugas
                                    </h5>
                                    <small>Informasi lengkap pengajuan surat tugas</small>
                                </div>

                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body detail-modal-body">

                                <div class="detail-profile-box">
                                    <div class="detail-photo-wrap detail-surtug-icon">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </div>

                                    <div>
                                        <h5 id="detailPerihal" class="detail-name mb-1">-</h5>
                                        <p id="detailNama" class="detail-email mb-2">-</p>
                                        <span id="detailStatus" class="detail-role-badge">-</span>
                                    </div>
                                </div>

                                <div class="detail-grid mt-4">

                                    <div class="detail-item">
                                        <span>ID Pengajuan</span>
                                        <strong id="detailId">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Nama Pegawai</span>
                                        <strong id="detailNamaPegawai">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Email</span>
                                        <strong id="detailEmail">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>NIP</span>
                                        <strong id="detailNip">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Homebase</span>
                                        <strong id="detailHomebase">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Nama Pengusul</span>
                                        <strong id="detailNamaPengusul">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Waktu Pelaksanaan</span>
                                        <strong id="detailWaktu">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Lama Pelaksanaan</span>
                                        <strong id="detailLama">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Tanggal Pengajuan</span>
                                        <strong id="detailTanggal">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Catatan</span>
                                        <strong id="detailCatatan">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Berkas Pendukung</span>
                                        <strong>
                                            <a href="#" id="detailBerkasLink" target="_blank"
                                                class="text-decoration-none">
                                                <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                                <span id="detailBerkas">-</span>
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
    <script src="{{ asset('js/pimpinanPersetujuan.js') }}"></script>

</body>

</html>
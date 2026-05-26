<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Jabatan Fungsional</title>

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

        @include('Sidebar.operatorSideBar')

        <div class="content-area">

            <div class="page-content">

                <div class="du-page-header">
                    <div class="du-header-left">
                        <div class="header-icon">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <h4 class="du-page-title">Validasi Jabatan Fungsional</h4>
                            <p class="du-page-sub">Kelola pengajuan perubahan jabatan fungsional pegawai</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                    <button class="du-pill" data-status="ditolak">
                        <i class="bi bi-x-circle-fill"></i> Ditolak
                    </button>
                </div>

                <div class="du-table-wrap">
                    <table id="tabelJabfung" class="display w-100" data-status-column="7">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengaju</th>
                                <th>NIP</th>
                                <th>Nama Jabatan</th>
                                <th>TMT</th>
                                <th>Berkas</th>
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
                                        <div>
                                            <div class="du-user-name">{{ $item->nama }}</div>
                                            <div class="du-user-email">{{ $item->email }}</div>
                                        </div>
                                    </td>

                                    <td class="du-nip">{{ $item->nip }}</td>

                                    <td>{{ $item->nama_jabatan }}</td>

                                    <td>{{ \Carbon\Carbon::parse($item->tmt)->format('d-m-Y') }}</td>

                                    <td>
                                        <a href="{{ $fileUrl }}" target="_blank" class="du-act du-act-file">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}</td>

                                    <td>
                                        <span class="surtug-status {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td>{{ $item->catatan ?? '—' }}</td>

                                    <td class="text-center">
                                        <div class="du-actions">

                                            <button type="button" class="du-act du-act-view btn-detail-jabfung"
                                                data-bs-toggle="modal" data-bs-target="#modalDetailJabfung"
                                                data-id="{{ $item->id_pengajuan }}" data-nama="{{ $item->nama }}"
                                                data-email="{{ $item->email }}" data-nip="{{ $item->nip }}"
                                                data-homebase="{{ $item->homebase }}"
                                                data-nama-jabatan="{{ $item->nama_jabatan }}"
                                                data-tmt="{{ \Carbon\Carbon::parse($item->tmt)->format('d-m-Y') }}"
                                                data-berkas="{{ $item->berkas_pendukung }}"
                                                data-file-url="{{ $fileUrl }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}"
                                                data-status="{{ $statusLabel }}"
                                                data-catatan="{{ $item->catatan ?? '—' }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <form action="{{ route('operator.jabfung.proses', $item->id_pengajuan) }}"
                                                method="POST" class="d-inline form-proses-jabfung"
                                                data-nama="{{ $item->nama }}">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="du-act du-act-approve" title="Proses">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>

                                            @if ($status === 'menunggu')
                                                <form
                                                    action="{{ route('operator.jabfung.tolak', $item->id_pengajuan) }}"
                                                    method="POST" class="d-inline form-tolak-jabfung"
                                                    data-nama="{{ $item->nama }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden" name="catatan" class="input-catatan-tolak">

                                                    <button type="submit" class="du-act du-act-reject" title="Tolak">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="du-act du-act-disabled" disabled>
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="modalDetailJabfung" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content detail-modal-content">
                            <div class="modal-header detail-modal-header">
                                <div>
                                    <h5 class="modal-title">
                                        <i class="bi bi-person-vcard-fill me-2"></i>Detail Jabatan Fungsional
                                    </h5>
                                    <small>Informasi lengkap pengajuan jabatan fungsional</small>
                                </div>

                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body detail-modal-body">
                                <div class="detail-grid mt-2">

                                    <div class="detail-item">
                                        <span>ID Pengajuan</span>
                                        <strong id="detailJabfungId">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Nama Pegawai</span>
                                        <strong id="detailJabfungNamaPegawai">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Email</span>
                                        <strong id="detailJabfungEmail">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>NIP</span>
                                        <strong id="detailJabfungNip">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Homebase</span>
                                        <strong id="detailJabfungHomebase">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Nama Jabatan</span>
                                        <strong id="detailJabfungNamaJabatan">-</strong>
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
                                        <span>Status</span>
                                        <strong id="detailJabfungStatus">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Catatan</span>
                                        <strong id="detailJabfungCatatan">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Berkas</span>
                                        <strong>
                                            <a href="#" id="detailJabfungBerkasLink" target="_blank">
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
    <script src="{{ asset('js/operatorValidasi.js') }}"></script>

</body>

</html>

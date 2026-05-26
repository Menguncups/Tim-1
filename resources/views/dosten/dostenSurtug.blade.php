<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Tugas</title>

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
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>

                        <div>
                            <h4 class="du-page-title">Pengajuan Surat Tugas</h4>
                            <p class="du-page-sub">Ajukan dan pantau status surat tugas Anda</p>
                        </div>
                    </div>

                    <a href="{{ route('dosten.surtug.create') }}" class="btn-du-tambah">
                        <i class="bi bi-plus-circle-fill"></i>
                        Ajukan Surat Tugas
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
                    <button class="du-pill active" data-status="semua">
                        Semua
                    </button>

                    <button class="du-pill" data-status="menunggu">
                        <i class="bi bi-hourglass-split"></i>
                        Menunggu
                    </button>

                    <button class="du-pill" data-status="diproses">
                        <i class="bi bi-arrow-repeat"></i>
                        Diproses
                    </button>

                    <button class="du-pill" data-status="diterima">
                        <i class="bi bi-check-circle-fill"></i>
                        Diterima
                    </button>

                    <button class="du-pill" data-status="ditolak">
                        <i class="bi bi-x-circle-fill"></i>
                        Ditolak
                    </button>
                </div>

                <div class="du-table-wrap">
                    <table id="tabelSurtug" class="display w-100 tabel-dosten" data-status-column="7">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengusul</th>
                                <th>Waktu Pelaksanaan</th>
                                <th>Lama Pelaksanaan</th>
                                <th>Perihal</th>
                                <th>Berkas Pendukung</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
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
                                        <div class="du-user-name">
                                            {{ $item->nama_pengusul }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->waktu_pelaksana)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        {{ $item->lama_pelaksanaan }} Hari
                                    </td>

                                    <td>
                                        <div class="surtug-title">
                                            {{ $item->perihal }}
                                        </div>
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

                                    <td class="text-center">
                                        <div class="du-actions">

                                            {{-- DETAIL --}}
                                            <button type="button" class="du-act du-act-view btn-detail-surtug"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailSurtug" data-id="{{ $item->id_pengajuan }}"
                                                data-nama="{{ $item->nama_pengusul }}"
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

                                            @if ($status === 'menunggu')
                                                {{-- EDIT --}}
                                                <a href="{{ route('dosten.surtug.edit', $item->id_pengajuan) }}"
                                                    class="du-act du-act-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                {{-- HAPUS --}}
                                                <form
                                                    action="{{ route('dosten.surtug.destroy', $item->id_pengajuan) }}"
                                                    method="POST" class="d-inline form-hapus-surtug"
                                                    data-perihal="{{ $item->perihal }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="du-act du-act-delete" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- EDIT DISABLED --}}
                                                <button type="button" class="du-act du-act-disabled"
                                                    title="Tidak bisa diedit karena status bukan menunggu" disabled>
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>

                                                {{-- HAPUS DISABLED --}}
                                                <button type="button" class="du-act du-act-disabled"
                                                    title="Tidak bisa dihapus karena status bukan menunggu" disabled>
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MODAL DETAIL SURAT TUGAS --}}
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

                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                        <span>Status</span>
                                        <strong id="detailStatusText">-</strong>
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
    <script src="{{ asset('js/dostenPengajuan.js') }}"></script>

</body>

</html>

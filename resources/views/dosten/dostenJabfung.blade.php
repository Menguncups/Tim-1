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

        @include('Sidebar.dostenSidebar')

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

                    <a href="#" class="btn-du-tambah">
                        <i class="bi bi-plus-circle-fill"></i>
                        Ajukan Jabfung
                    </a>
                </div>

                <div class="du-filter-bar">
                    <button class="du-pill active" data-status="semua">Semua</button>
                    <button class="du-pill" data-status="menunggu"><i class="bi bi-hourglass-split"></i>
                        Menunggu</button>
                    <button class="du-pill" data-status="diproses"><i class="bi bi-arrow-repeat"></i> Diproses</button>
                    <button class="du-pill" data-status="diterima"><i class="bi bi-check-circle-fill"></i>
                        Diterima</button>
                    <button class="du-pill" data-status="ditolak"><i class="bi bi-x-circle-fill"></i> Ditolak</button>
                </div>

                <div class="du-table-wrap">
                    <table id="tabelJabfung" class="display w-100 tabel-dosten" data-status-column="4">
                        <thead>
                            <tr>
            <th>No</th>
            <th>Nama Jabatan</th>
            <th>TMT</th>
            <th>Berkas Pendukung</th>
            <th>Tanggal Pengajuan</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- Data jabfung nanti dari controller --}}
                        </tbody>
                    </table>
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- DataTables + Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">

    <!-- Footer CSS -->
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        {{-- SIDEBAR --}}
        @include('Sidebar.operatorSidebar')

        {{-- CONTENT --}}
        <div class="content-area">

            <div class="page-content">

                <!-- PAGE HEADER -->
                <div class="du-page-header">
                    <div class="du-header-left">
                        <div class="header-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div>
                            <h4 class="du-page-title">Daftar Pengguna</h4>
                            <p class="du-page-sub">Kelola seluruh akun pengguna sistem</p>
                        </div>
                    </div>

                    <a href="{{ url('/operator/tambah-data') }}" class="btn-du-tambah">
                        <i class="bi bi-person-plus-fill"></i>
                        Tambah Pegawai
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

                <!-- FILTER PILLS -->
                <div class="du-filter-bar">
                    <button class="du-pill active" data-tab="semua">Semua</button>

                    <button class="du-pill" data-tab="pimpinan">
                        <i class="bi bi-star-fill"></i> Pimpinan
                    </button>

                    <button class="du-pill" data-tab="dosen">
                        <i class="bi bi-person-video3"></i> Dosen
                    </button>

                    <button class="du-pill" data-tab="tendik">
                        <i class="bi bi-people-fill"></i> Tendik
                    </button>

                    <button class="du-pill" data-tab="operator">
                        <i class="bi bi-shield-fill"></i> Operator
                    </button>
                </div>

                <!-- TABLE -->
                <div class="du-table-wrap">
                    <table id="tabelPengguna" class="display w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengguna</th>
                                <th>NIP</th>
                                <th>Prodi</th>
                                <th>Role</th>
                                <th>Jenis Kelamin</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pegawai as $p)
                                <tr data-role="{{ $p->roles->pluck('nama_role')->implode(' ') }}">
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div>
                                            <div class="du-user-name">{{ $p->nama }}</div>
                                            <div class="du-user-email">{{ $p->email }}</div>
                                        </div>
                                    </td>

                                    <td class="du-nip">{{ $p->nip }}</td>

                                    <td>{{ $p->homebase }}</td>

                                    <td>
                                        @foreach ($p->roles as $role)
                                            @php
                                                $roleName = strtolower($role->nama_role);
                                            @endphp

                                            <span class="du-badge du-badge-{{ $roleName }}">
                                                {{ $role->nama_role }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        @php
                                            $jk = strtolower($p->jenis_kelamin);
                                            $jkClass = $jk === 'laki-laki' ? 'laki' : 'perempuan';
                                        @endphp

                                        <span class="du-gender du-gender-{{ $jkClass }}">
                                            @if ($p->jenis_kelamin === 'Laki-laki')
                                                <i class="bi bi-gender-male"></i>
                                            @elseif ($p->jenis_kelamin === 'Perempuan')
                                                <i class="bi bi-gender-female"></i>
                                            @endif

                                            {{ $p->jenis_kelamin }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="du-actions">

                                            {{-- DETAIL --}}
                                            <button type="button" class="du-act du-act-view btn-detail-pegawai"
                                                title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailPegawai"
                                                data-foto="{{ $p->foto ? asset('photo/' . $p->foto) : '' }}"
                                                data-nama="{{ $p->nama }}" data-email="{{ $p->email }}"
                                                data-nip="{{ $p->nip }}" data-nidn="{{ $p->nidn ?? '-' }}"
                                                data-jenis-kelamin="{{ $p->jenis_kelamin }}"
                                                data-tanggal-lahir="{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d-m-Y') : '-' }}"
                                                data-no-hp="{{ $p->no_hp }}"
                                                data-no-hp-darurat="{{ $p->no_hp_darurat ?? '-' }}"
                                                data-homebase="{{ $p->homebase }}"
                                                data-pangkat-golongan="{{ $p->pangkat_golongan ?? '-' }}"
                                                data-jabatan-fungsional="{{ $p->jabatan_fungsional ?? '-' }}"
                                                data-role="{{ $p->roles->pluck('nama_role')->implode(', ') }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            {{-- EDIT --}}
                                            <a href="{{ url('/operator/edit-pegawai/' . $p->id_pegawai) }}"
                                                class="du-act du-act-edit" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            {{-- DELETE --}}
                                            <form action="{{ url('/operator/hapus-pegawai/' . $p->id_pegawai) }}"
                                                method="POST" class="d-inline form-hapus-pegawai"
                                                data-nama="{{ $p->nama }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="du-act du-act-delete" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MODAL DETAIL PEGAWAI -->
                <div class="modal fade" id="modalDetailPegawai" tabindex="-1"
                    aria-labelledby="modalDetailPegawaiLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content detail-modal-content">

                            <div class="modal-header detail-modal-header">
                                <div>
                                    <h5 class="modal-title" id="modalDetailPegawaiLabel">
                                        <i class="bi bi-person-lines-fill me-2"></i>Detail Pegawai
                                    </h5>
                                    <small>Informasi lengkap data pegawai</small>
                                </div>

                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body detail-modal-body">

                                <div class="detail-profile-box">
                                    <div class="detail-photo-wrap">
                                        <img id="detailFoto" src="" alt="Foto Pegawai" class="detail-photo">

                                        <div id="detailFotoFallback" class="detail-photo-fallback">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 id="detailNama" class="detail-name mb-1">-</h5>
                                        <p id="detailEmail" class="detail-email mb-2">-</p>
                                        <span id="detailRole" class="detail-role-badge">-</span>
                                    </div>
                                </div>

                                <div class="detail-grid mt-4">

                                    <div class="detail-item">
                                        <span>NIP</span>
                                        <strong id="detailNip">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>NIDN</span>
                                        <strong id="detailNidn">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Jenis Kelamin</span>
                                        <strong id="detailJenisKelamin">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Tanggal Lahir</span>
                                        <strong id="detailTanggalLahir">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>No. HP</span>
                                        <strong id="detailNoHp">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>No. HP Darurat</span>
                                        <strong id="detailNoHpDarurat">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Homebase</span>
                                        <strong id="detailHomebase">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Pangkat / Golongan</span>
                                        <strong id="detailPangkatGolongan">-</strong>
                                    </div>

                                    <div class="detail-item">
                                        <span>Jabatan Fungsional</span>
                                        <strong id="detailJabatanFungsional">-</strong>
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

            </div>{{-- end page-content --}}

            {{-- FOOTER --}}
            @include('Footer.footer')

        </div>{{-- end content-area --}}
    </div>{{-- end wrapper --}}

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Sidebar JS -->
    <script src="{{ asset('js/operatorSideBar.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('js/operator.js') }}"></script>

</body>

</html>

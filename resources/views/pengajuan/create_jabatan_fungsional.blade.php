<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jabatan Fungsional — FT UNRI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/CreateJabPang.css') }}">
</head>
<body>

<button class="mobile-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>

<div class="wrapper">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('icon/unriteknik.png') }}" alt="Fakultas Teknik UNRI">
            </div>
            <nav class="sidebar-nav">
                <div class="nav-group-label">Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="/dosen/dashboard" class="nav-link"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a href="/dosen/datadiri" class="nav-link"><i class="bi bi-person-bounding-box me-2"></i>Data Diri</a></li>
                    <div class="nav-group-label">Layanan Pengajuan</div>
                    <li class="nav-item"><a href="/dosen/pengajuan/surtug" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i>Surat Tugas</a></li>
                    <li class="nav-item"><a href="/dosen/pengajuan/jabfung" class="nav-link active"><i class="bi bi-award-fill me-2"></i>Jabatan Fungsional</a></li>
                    <li class="nav-item"><a href="/dosen/pengajuan/panggol" class="nav-link"><i class="bi bi-diagram-3-fill me-2"></i>Pangkat Golongan</a></li>
                </ul>
            </nav>
    </aside>

    <div class="content-col">
        <main class="content-area">

            <div class="page-header mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <h4 class="page-title mb-0">Tambah Jabatan Fungsional</h4>
                        <p class="page-sub mb-0">Lengkapi data riwayat jabatan fungsional dosen</p>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="card-header-strip">
                    <i class="bi bi-pencil-square me-2"></i>Form Input Jabatan Fungsional Baru
                </div>
                
                <form id="formJabfung" class="form-body" action="/dosen/pengajuan/jabfung/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pegawai_id_pegawai" value="{{ $pegawai->id_pegawai ?? '10' }}">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="nama_dosen">
                                    <i class="bi bi-person me-1"></i>Nama Dosen
                                </label>
                                <input type="text" id="nama_dosen" class="field-input field-readonly" value="{{ $pegawai->nama }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="nip_dosen">
                                    <i class="bi bi-card-text me-1"></i>NIP / NIDN
                                </label>
                                <input type="text" id="nip_dosen" class="field-input field-readonly" value="{{ $pegawai->nip }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="jabatan_sekarang">
                                    <i class="bi bi-briefcase me-1"></i>Jabatan Saat Ini
                                </label>
                                <input type="text" id="jabatan_sekarang" class="field-input field-readonly" value="{{ $pegawai->jabatan_fungsional }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="golongan_sekarang">
                                    <i class="bi bi-award me-1"></i>Pangkat / Golongan Saat Ini
                                </label>
                                <input type="text" id="golongan_sekarang" class="field-input field-readonly" value="{{ $pegawai->pangkat_golongan }}" readonly>
                            </div>
                        </div>

                        <div class="section-divider"><span><i class="bi bi-pencil-square"></i> Pengisian Data Baru</span></div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="nama_jabatan">
                                    <i class="bi bi-layers me-1"></i>Jabatan Fungsional Baru <span class="required-dot">*</span>
                                </label>
                                <select id="nama_jabatan" name="nama_jabatan" class="field-input field-select">
                                    <option value="" selected disabled>-- Pilih Jabatan --</option>
                                    <option value="Asisten Ahli">Asisten Ahli</option>
                                    <option value="Lektor">Lektor</option>
                                    <option value="Lektor Kepala">Lektor Kepala</option>
                                    <option value="Profesor">Guru Besar / Profesor</option>
                                </select>
                                <span id="error_jabatan" class="field-error"></span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="dokumen_sk_cpns">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen SK CPNS (PDF) <span class="required-dot">*</span>
                                </label>
                                <input type="file" id="dokumen_sk_cpns" name="dokumen_sk_cpns" class="field-input field-input-file" accept="application/pdf">
                                <span id="error_dokumen_sk_cpns" class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="dokumen_sk_pns">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen SK PNS (PDF) <span class="required-dot">*</span>
                                </label>
                                <input type="file" id="dokumen_sk_pns" name="dokumen_sk_pns" class="field-input field-input-file" accept="application/pdf">
                                <span id="error_dokumen_sk_pns" class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="tmt">
                                    <i class="bi bi-calendar-event me-1"></i>Tanggal Pengangkatan <span class="required-dot">*</span>
                                </label>
                                <input type="date" id="tmt" name="tmt" class="field-input">
                                <span id="error_tmt" class="field-error"></span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="dokumen_pak">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen Penetapan Angka Kredit (PAK) (PDF) <span class="required-dot">*</span>
                                </label>
                                <input type="file" id="dokumen_pak" name="dokumen_pak" class="field-input field-input-file" accept="application/pdf">
                                <span id="error_dokumen_pak" class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="dokumen_publikasi_ilmiah">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen Publikasi Ilmiah (PDF) <span class="required-dot">*</span>
                                </label>
                                <input type="file" id="dokumen_publikasi_ilmiah" name="dokumen_publikasi_ilmiah" class="field-input field-input-file" accept="application/pdf">
                                <span id="error_dokumen_publikasi_ilmiah" class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                            </div>
                        </div>

                    </div> 
                    <div class="action-bar mt-4">
                        <span class="required-note"><span class="required-dot">*</span> Wajib diisi</span>
                        
                        <div class="d-flex gap-2">
                            <button type="button" id="btnBatal" class="btn btn-danger px-4 py-2 rounded-3 fw-bold fs-6 d-inline-flex align-items-center">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </button>
                            
                            <button type="button" id="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold fs-6 d-inline-flex align-items-center">
                                <i class="bi bi-box-arrow-in-down me-2"></i>Simpan Data Jabfung
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </main>

        <footer class="site-footer">
            <div class="footer-left">
                <div class="footer-logo-wrap">
                    <img src="unriteknik.png" alt="UNRI" onerror="this.style.display='none'">
                </div>
                <p class="footer-address mb-0">
                    Fakultas Teknik, Kampus Binawidya<br>
                    JL. HR Soebrantas KM.12.5, Simpang Baru, Panam
                </p>
            </div>
            <div class="footer-right">© 2026 Fakultas Teknik UNRI</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/CreateJabFung.js?v=2.0_final') }}"></script>
</body>
</html>
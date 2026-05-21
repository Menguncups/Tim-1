<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jabatan Fungsional — FT UNRI</title>
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
                        <h4 class="page-title mb-0">Edit Jabatan Fungsional</h4>
                        <p class="page-sub mb-0">Lengkapi berkas perubahan data riwayat jabatan fungsional dosen</p>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="card-header-strip">
                    <i class="bi bi-pencil-square me-2"></i>Form Perubahan Berkas Dokumen
                </div>
                
                <form id="formEditJabfung" class="form-body" data-id="{{ $data->id_pengajuan }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        
                        <div class="col-md-12">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="bi bi-award me-1"></i>Jabatan Fungsional yang Diajukan
                                </label>
                                <input type="text" class="field-input field-readonly" value="{{ $data->nama_jabatan }}" readonly>
                                <small class="text-muted mt-1 d-block">*Nama kualifikasi jabatan fungsional tidak dapat diubah kembali.</small>
                            </div>
                        </div>

                        <div class="section-divider"><span><i class="bi bi-file-earmark-pdf"></i> Pengisian Berkas Pembaruan</span></div>
                        
                        <div class="col-md-12 mb-2">
                            <div class="alert alert-warning py-2 px-3 border-0 rounded-3" style="font-size: 13px;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Pilih file PDF baru <strong>hanya</strong> jika Anda ingin mengganti/merevisi dokumen lama. Jika tidak ada perubahan, kosongkan saja.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="dokumen_sk_cpns">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen SK CPNS (PDF)
                                </label>
                                <input type="file" id="dokumen_sk_cpns" name="dokumen_sk_cpns" class="field-input field-input-file" accept="application/pdf">
                                <span class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                                @if($data->dokumen_sk_cpns)
                                    <div class="mt-2"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Berkas sudah tersimpan di sistem</span></div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="dokumen_pak">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen Penetapan Angka Kredit (PAK) (PDF)
                                </label>
                                <input type="file" id="dokumen_pak" name="dokumen_pak" class="field-input field-input-file" accept="application/pdf">
                                <span class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                                @if($data->dokumen_pak)
                                    <div class="mt-2"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Berkas sudah tersimpan di sistem</span></div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="dokumen_sk_pns">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen SK PNS (PDF)
                                </label>
                                <input type="file" id="dokumen_sk_pns" name="dokumen_sk_pns" class="field-input field-input-file" accept="application/pdf">
                                <span class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                                @if($data->dokumen_sk_pns)
                                    <div class="mt-2"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Berkas sudah tersimpan di sistem</span></div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="dokumen_publikasi_ilmiah">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>Dokumen Publikasi Ilmiah (PDF)
                                </label>
                                <input type="file" id="dokumen_publikasi_ilmiah" name="dokumen_publikasi_ilmiah" class="field-input field-input-file" accept="application/pdf">
                                <span class="field-error"><small class="text-muted">Format: PDF, Maks. 5MB</small></span>
                                @if($data->dokumen_publikasi_ilmiah)
                                    <div class="mt-2"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Berkas sudah tersimpan di sistem</span></div>
                                @endif
                            </div>
                        </div>

                    </div> 
                    
                    <div class="action-bar mt-4">
                        <span class="required-note">Pembaruan Berkas</span>
                        
                        <div class="d-flex gap-2">
                            <a href="/dosen/pengajuan/jabfung" id="btnBatal" class="btn btn-danger px-4 py-2 rounded-3 fw-bold fs-6 d-inline-flex align-items-center" style="text-decoration: none;">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                            
                            <button type="submit" id="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold fs-6 d-inline-flex align-items-center">
                                <i class="bi bi-box-arrow-in-down me-2"></i>Simpan Perubahan Berkas
                            </button>
                        </div>
                    </div>
                </form>
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
            <div class="footer-right">© 2026 Fakultas Teknik UNRI</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
document.getElementById('formEditJabfung').addEventListener('submit', function(e) {
    e.preventDefault();

    const idPengajuan = this.getAttribute('data-id');
    const formData = new FormData(this);

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang menyimpan pembaruan berkas...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/dosen/pengajuan/jabfung/update/${idPengajuan}`, {
        method: 'POST', // Spoofing PUT di Laravel
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#198754'
            }).then(() => {
                window.location.href = '/dosen/pengajuan/jabfung';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Validasi Berkas',
                text: data.message,
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Sistem Error',
            text: 'Terjadi masalah koneksi database atau internal server.'
        });
        console.error(error);
    });
});

// Fitur klik hamburger menu untuk responsive hp biar jalan
const toggleBtn = document.getElementById('sidebarToggle');
if(toggleBtn) {
    toggleBtn.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
}
</script>
</body>
</html>
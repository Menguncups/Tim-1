<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pangkat Golongan — FT UNRI</title>
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
                <li class="nav-item"><a href="/dosen/pengajuan/jabfung" class="nav-link"><i class="bi bi-award-fill me-2"></i>Jabatan Fungsional</a></li>
                <li class="nav-item"><a href="/dosen/pengajuan/panggol" class="nav-link active"><i class="bi bi-diagram-3-fill me-2"></i>Pangkat Golongan</a></li>
            </ul>
        </nav>
    </aside>

    <div class="content-col">
        <main class="content-area">
            <div class="page-header mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    <div>
                        <h4 class="page-title mb-0">Edit Pangkat Golongan</h4>
                        <p class="page-sub mb-0">Lengkapi berkas perubahan</p>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="card-header-strip"><i class="bi bi-pencil-square me-2"></i>Form Perubahan Berkas Dokumen</div>
                
                <form id="formEditPanggol" class="form-body" data-id="{{ $data->id_pengajuan }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="field-group">
                                <label class="field-label">Pangkat / Golongan yang Diajukan</label>
                                <input type="text" class="field-input field-readonly" value="{{ $data->pangkat }} - {{ $data->golongan }}" readonly>
                            </div>
                        </div>

                        <div class="section-divider"><span><i class="bi bi-file-earmark-pdf"></i> Pengisian Berkas Pembaruan</span></div>
                        
                        @foreach(['dokumen_sk_cpns'=>'SK CPNS', 'dokumen_sk_pns'=>'SK PNS', 'dokumen_pak'=>'PAK', 'dokumen_publikasi_ilmiah'=>'Publikasi Ilmiah'] as $field => $label)
                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label" for="{{ $field }}">{{ $label }} (PDF)</label>
                                <input type="file" name="{{ $field }}" class="field-input field-input-file" accept="application/pdf">
                                @if($data->$field)
                                    <div class="mt-2"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Berkas tersimpan</span></div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div> 
                    
                    <div class="action-bar mt-4">
                        <a href="/dosen/pengajuan/panggol" class="btn btn-danger px-4 py-2 rounded-3 fw-bold"><i class="bi bi-x-circle me-2"></i>Batal</a>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold"><i class="bi bi-box-arrow-in-down me-2"></i>Simpan Perubahan Berkas</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('formEditPanggol').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = this.getAttribute('data-id');
    const formData = new FormData(this);

    fetch(`/dosen/pengajuan/panggol/update/${id}`, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            Swal.fire('Berhasil!', d.message, 'success').then(() => window.location.href = '/dosen/pengajuan/panggol');
        } else {
            Swal.fire('Gagal!', d.message, 'error');
        }
    });
});
</script>
</body>
</html>
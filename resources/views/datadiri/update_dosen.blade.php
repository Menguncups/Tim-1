<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perbarui Data Diri Dosen</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary:       #b52a20;
            --primary-light: #fdf1f0;
            --bg:            #f0f2f7;
            --card-bg:       #ffffff;
            --border:        #e2e6ed;
            --text-main:     #1e2235;
            --radius-card:   16px;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg); color: var(--text-main); }
        #sidebar { width: 280px; height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid var(--border); }
        #main-content { margin-left: 280px; padding: 40px; }
        .update-card { background: var(--card-bg); border-radius: var(--radius-card); border: 1px solid var(--border); padding: 30px; }
        .form-control-custom { border-radius: 8px; border: 1px solid var(--border); padding: 12px; }
        .form-control-custom:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        @media (max-width: 991.98px) { #sidebar { display: none; } #main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

    <div id="sidebar">
        <div class="p-4 fs-5 fw-bold border-bottom text-danger"><i class="bi bi-mortarboard-fill me-2"></i>SISTER</div>
        <div class="py-3">
            <a href="{{ route('pegawai.read', 'dosen') }}" class="nav-link text-dark p-3 px-4 fw-semibold"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
        </div>
    </div>

    <div id="main-content">
        <div class="mb-4">
            <h4 class="fw-bold">Perbarui Data Diri Dosen</h4>
            <p class="text-muted">Perubahan nomor handphone dan foto profil akan langsung diperbarui.</p>
        </div>

        <div class="update-card">
            <form id="formUpdateDosen" enctype="multipart/form-data">
                <input type="hidden" name="id_pegawai" value="{{ $pegawai->id_pegawai }}">
                
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP Aktif / WhatsApp</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control form-control-custom" value="{{ $pegawai->no_hp }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP Darurat</label>
                            <input type="text" name="no_hp_darurat" id="no_hp_darurat" class="form-control form-control-custom" value="{{ $pegawai->no_hp_darurat }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Profil Baru</label>
                            <input type="file" name="foto" id="inputFoto" class="form-control form-control-custom" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-danger px-4" style="background-color: var(--primary); border:none;"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
                    </div>
                    
                    <div class="col-md-5 d-flex flex-column align-items-center justify-content-center border-start">
                        <span class="text-muted fw-bold mb-2">Pratinjau Foto</span>
                        <img id="imgPreview" src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : 'https://via.placeholder.com/150' }}" class="rounded-circle border" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('inputFoto').addEventListener('change', function() {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('imgPreview').src = e.target.result;
            if(this.files[0]) reader.readAsDataURL(this.files[0]);
        });

        document.getElementById('formUpdateDosen').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('pegawai.update', 'dosen') }}", {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, confirmButtonColor: '#b52a20' })
                    .then(() => window.location.href = "{{ route('pegawai.read', 'dosen') }}");
                }
            });
        });
    </script>
</body>
</html>
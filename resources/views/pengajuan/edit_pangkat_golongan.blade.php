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

<div class="wrapper">
    <div class="content-col">
        <main class="content-area">
            <div class="page-header mb-4">
                <h4 class="page-title">Edit Pangkat Golongan</h4>
            </div>

            <div class="form-card">
                <div class="card-header-strip">Form Perubahan Berkas Dokumen</div>
                
                <form id="formEditPanggol" class="form-body" data-id="{{ $data->id_pengajuan }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="field-label">Pangkat / Golongan yang Diajukan</label>
                            <input type="text" class="field-input field-readonly" value="{{ $data->pangkat }} - {{ $data->golongan }}" readonly>
                        </div>

                        @foreach(['dokumen_sk_cpns' => 'SK CPNS', 'dokumen_sk_pns' => 'SK PNS', 'dokumen_pak' => 'PAK', 'dokumen_publikasi_ilmiah' => 'Publikasi Ilmiah'] as $field => $label)
                        <div class="col-md-6">
                            <label class="field-label" for="{{ $field }}">{{ $label }} (PDF)</label>
                            <input type="file" name="{{ $field }}" class="field-input field-input-file" accept="application/pdf">
                            @if($data->$field)
                                <div class="mt-2"><span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill"></i> Berkas tersimpan</span></div>
                            @endif
                        </div>
                        @endforeach
                    </div> 
                    
                    <div class="action-bar mt-4">
                        <a href="/dosen/pengajuan/panggol" class="btn btn-danger">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Perubahan Berkas</button>
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
    const idPengajuan = this.getAttribute('data-id');
    const formData = new FormData(this);

    fetch(`/dosen/pengajuan/panggol/update/${idPengajuan}`, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                window.location.href = '/dosen/pengajuan/panggol';
            });
        } else {
            Swal.fire('Gagal!', data.message, 'error');
        }
    });
});
</script>
</body>
</html>
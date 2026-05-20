<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Tugas</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dostenSurtugForm.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        @include('Sidebar.dostenSidebar')

        <div class="content-col">
            <main class="content-area">

                <div class="page-header mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="bi bi-envelope-plus-fill"></i>
                        </div>

                        <div>
                            <h4 class="page-title mb-0">Pengajuan Surat Tugas</h4>
                            <p class="page-sub mb-0">Lengkapi formulir permohonan surat tugas Anda</p>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-error-box">
                        <strong>Data belum berhasil disimpan.</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="intro-banner">
                    <div class="intro-banner-icon">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>

                    <div class="intro-banner-text">
                        <h5>Permohonan Surat Tugas Baru</h5>
                        <p>
                            Isi semua kolom wajib dengan informasi yang valid serta lampirkan berkas pendukung.
                        </p>
                    </div>
                </div>

                <div class="req-info">
                    <strong>
                        <i class="bi bi-info-circle me-1"></i>
                        Petunjuk Pengisian:
                    </strong>

                    <ul>
                        <li>Kolom bertanda <span class="text-danger fw-bold">*</span> wajib diisi.</li>
                        <li>Pastikan perihal dan waktu pelaksanaan diisi dengan benar.</li>
                        <li>Berkas pendukung wajib berformat PDF/JPG/PNG dengan ukuran maksimal 2 MB.</li>
                    </ul>
                </div>

                <div class="form-card">
                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Pengajuan Surat Tugas
                    </div>

                    <form id="formSuratTugas"
                        action="{{ route('dosten.surtug.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="form-body">
                        @csrf

                        <div class="section-divider">
                            <span>
                                <i class="bi bi-file-earmark-text-fill me-1"></i>
                                Detail Permohonan
                            </span>
                        </div>

                        <div class="row g-4 mt-1">

                            <div class="col-md-6">

                                <div class="field-group">
                                    <label class="field-label" for="nama_pengusul">
                                        <i class="bi bi-person me-1"></i>
                                        Nama Pengusul
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="text"
                                        id="nama_pengusul"
                                        name="nama_pengusul"
                                        class="field-input"
                                        value="{{ old('nama_pengusul', $pegawai->nama ?? '') }}"
                                        maxlength="50"
                                        placeholder="Masukkan nama lengkap pengusul">

                                    <div class="d-flex justify-content-between">
                                        <span id="error_nama_pengusul" class="field-error">
                                            @error('nama_pengusul')
                                                {{ $message }}
                                            @enderror
                                        </span>

                                        <span class="field-counter" id="counter_nama_pengusul">0/50</span>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="waktu_pelaksana">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Waktu Pelaksanaan
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="date"
                                        id="waktu_pelaksana"
                                        name="waktu_pelaksana"
                                        class="field-input"
                                        value="{{ old('waktu_pelaksana') }}">

                                    <span id="error_waktu_pelaksana" class="field-error">
                                        @error('waktu_pelaksana')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="lama_pelaksanaan">
                                        <i class="bi bi-clock me-1"></i>
                                        Lama Pelaksanaan
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="number"
                                        id="lama_pelaksanaan"
                                        name="lama_pelaksanaan"
                                        class="field-input"
                                        value="{{ old('lama_pelaksanaan') }}"
                                        min="1"
                                        max="999"
                                        placeholder="Contoh: 2">

                                    <span id="error_lama_pelaksanaan" class="field-error">
                                        @error('lama_pelaksanaan')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="field-group">
                                    <label class="field-label" for="perihal">
                                        <i class="bi bi-chat-left-text me-1"></i>
                                        Perihal Surat
                                        <span class="required-dot">*</span>
                                    </label>

                                    <textarea id="perihal"
                                        name="perihal"
                                        class="field-input textarea-input"
                                        maxlength="50"
                                        placeholder="Masukkan perihal surat">{{ old('perihal') }}</textarea>

                                    <div class="d-flex justify-content-between">
                                        <span id="error_perihal" class="field-error">
                                            @error('perihal')
                                                {{ $message }}
                                            @enderror
                                        </span>

                                        <span class="field-counter" id="counter_perihal">0/50</span>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="berkas_pendukung">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i>
                                        Berkas Pendukung
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="file"
                                        id="berkas_pendukung"
                                        name="berkas_pendukung"
                                        class="field-input"
                                        accept="image/png,image/jpeg,application/pdf">

                                    <span id="error_berkas_pendukung" class="field-error">
                                        @error('berkas_pendukung')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="action-bar mt-4">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('dosten.surtug.index') }}" class="btn-cancel">
                                    <i class="bi bi-chevron-left me-1"></i>
                                    Kembali
                                </a>

                                <button type="reset" class="btn-clear" id="btnResetSuratTugas">
                                    Clear
                                </button>
                            </div>

                            <button type="button" id="btnSubmitSuratTugas" class="btn-submit m-0">
                                <i class="bi bi-save2 me-2"></i>
                                Simpan Data
                            </button>
                        </div>

                    </form>
                </div>

            </main>

            @include('Footer.footer')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
    <script src="{{ asset('js/dostenSurtugForm.js') }}"></script>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Jabatan Fungsional</title>

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
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <h4 class="page-title mb-0">Pengajuan Jabatan Fungsional</h4>
                            <p class="page-sub mb-0">Lengkapi formulir pengajuan jabatan fungsional</p>
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
                        <h5>Permohonan Jabatan Fungsional Baru</h5>
                        <p>Isi data jabatan fungsional dan lampirkan berkas pendukung.</p>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Pengajuan Jabatan Fungsional
                    </div>

                    <form id="formJabfung"
                        action="{{ route('dosten.jabfung.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="form-body form-pengajuan"
                        data-form-type="jabfung">
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
                                    <label class="field-label" for="nama_jabatan">
                                        <i class="bi bi-person-vcard me-1"></i>
                                        Nama Jabatan
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="nama_jabatan" name="nama_jabatan" class="field-input field-select">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach (['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $jabatan)
                                            <option value="{{ $jabatan }}" {{ old('nama_jabatan') == $jabatan ? 'selected' : '' }}>
                                                {{ $jabatan }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span id="error_nama_jabatan" class="field-error">
                                        @error('nama_jabatan') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="tmt">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        TMT
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="date"
                                        id="tmt"
                                        name="tmt"
                                        class="field-input"
                                        value="{{ old('tmt') }}">

                                    <span id="error_tmt" class="field-error">
                                        @error('tmt') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
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
                                        @error('berkas_pendukung') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="action-bar mt-4">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('dosten.jabfung.index') }}" class="btn-cancel">
                                    <i class="bi bi-chevron-left me-1"></i>
                                    Kembali
                                </a>

                                <button type="reset" class="btn-clear">
                                    Clear
                                </button>
                            </div>

                            <button type="button" class="btn-submit m-0 btn-submit-pengajuan">
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
    <script src="{{ asset('js/dostenJabfungPanggolForm.js') }}"></script>

</body>

</html>
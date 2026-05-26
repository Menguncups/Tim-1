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

        @include('Sidebar.dostenSideBar')

        <div class="content-col">
            <main class="content-area">

                <div class="page-header mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <div>
                            <h4 class="page-title mb-0">Pengajuan Jabatan Fungsional</h4>
                            <p class="page-sub mb-0">
                                Lengkapi formulir pengajuan jabatan fungsional
                            </p>
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
                        <p>
                            Pilih jabatan fungsional baru, isi TMT, dan unggah seluruh dokumen pendukung.
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
                        <li>Jabatan fungsional tidak boleh sama atau lebih rendah dari jabatan aktif.</li>
                        <li>Seluruh dokumen wajib berformat PDF dengan ukuran maksimal 5 MB.</li>
                    </ul>
                </div>

                <div class="form-card">
                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Pengajuan Jabatan Fungsional
                    </div>

                    <form id="formJabfung" action="{{ route('dosten.jabfung.store') }}" method="POST"
                        enctype="multipart/form-data" class="form-body form-pengajuan" data-form-type="jabfung">
                        @csrf

                        {{-- Data aktif dari database untuk logic validasi --}}
                        <input type="hidden" id="jabatan_sekarang" value="{{ $pegawai->jabatan_fungsional }}">
                        <input type="hidden" id="pangkat_sekarang" value="{{ $pegawai->pangkat_golongan }}">

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
                                        Jabatan Fungsional Baru
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="nama_jabatan" name="nama_jabatan" class="field-input field-select">
                                        <option value="">-- Pilih Jabatan Fungsional --</option>

                                        <option value="Asisten Ahli"
                                            {{ old('nama_jabatan') == 'Asisten Ahli' ? 'selected' : '' }}>
                                            Asisten Ahli
                                        </option>

                                        <option value="Lektor" {{ old('nama_jabatan') == 'Lektor' ? 'selected' : '' }}>
                                            Lektor
                                        </option>

                                        <option value="Lektor Kepala"
                                            {{ old('nama_jabatan') == 'Lektor Kepala' ? 'selected' : '' }}>
                                            Lektor Kepala
                                        </option>

                                        <option value="Guru Besar"
                                            {{ old('nama_jabatan') == 'Guru Besar' ? 'selected' : '' }}>
                                            Guru Besar
                                        </option>
                                    </select>

                                    <span id="error_nama_jabatan" class="field-error">
                                        @error('nama_jabatan')
                                            {{ $message }}
                                        @enderror
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

                                    <input type="date" id="tmt" name="tmt" class="field-input"
                                        value="{{ old('tmt') }}">

                                    <span id="error_tmt" class="field-error">
                                        @error('tmt')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="dokumen_sk_cpns">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Dokumen SK CPNS
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="file" id="dokumen_sk_cpns" name="dokumen_sk_cpns"
                                        class="field-input" accept="application/pdf">

                                    <span id="error_dokumen_sk_cpns" class="field-error">
                                        @error('dokumen_sk_cpns')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="dokumen_sk_pns">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Dokumen SK PNS
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="file" id="dokumen_sk_pns" name="dokumen_sk_pns"
                                        class="field-input" accept="application/pdf">

                                    <span id="error_dokumen_sk_pns" class="field-error">
                                        @error('dokumen_sk_pns')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="dokumen_pak">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Dokumen PAK
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="file" id="dokumen_pak" name="dokumen_pak" class="field-input"
                                        accept="application/pdf">

                                    <span id="error_dokumen_pak" class="field-error">
                                        @error('dokumen_pak')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="dokumen_publikasi_ilmiah">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Berkas Publikasi Ilmiah
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="file" id="dokumen_publikasi_ilmiah"
                                        name="dokumen_publikasi_ilmiah" class="field-input" accept="application/pdf">

                                    <span id="error_dokumen_publikasi_ilmiah" class="field-error">
                                        @error('dokumen_publikasi_ilmiah')
                                            {{ $message }}
                                        @enderror
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

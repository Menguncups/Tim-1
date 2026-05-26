<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pangkat Golongan</title>

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
                            <i class="bi bi-award-fill"></i>
                        </div>

                        <div>
                            <h4 class="page-title mb-0">Pengajuan Pangkat Golongan</h4>
                            <p class="page-sub mb-0">
                                Lengkapi formulir pengajuan pangkat dan golongan
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
                        <h5>Permohonan Pangkat Golongan Baru</h5>
                        <p>
                            Pilih pangkat/golongan baru, isi TMT, dan unggah seluruh dokumen pendukung.
                        </p>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Pengajuan Pangkat Golongan
                    </div>

                    <form id="formPanggol" action="{{ route('dosten.panggol.store') }}" method="POST"
                        enctype="multipart/form-data" class="form-body form-pengajuan" data-form-type="panggol">
                        @csrf

                        {{-- Data aktif dari database untuk logic validasi --}}
                        <input type="hidden" id="jabatan_sekarang" value="{{ $pegawai->jabatan_fungsional }}">
                        <input type="hidden" id="pangkat_sekarang" value="{{ $pegawai->pangkat_golongan }}">

                        {{-- Data yang dikirim ke controller --}}
                        <input type="hidden" id="pangkat" name="pangkat" value="{{ old('pangkat') }}">
                        <input type="hidden" id="golongan" name="golongan" value="{{ old('golongan') }}">

                        <div class="section-divider">
                            <span>
                                <i class="bi bi-file-earmark-text-fill me-1"></i>
                                Detail Permohonan
                            </span>
                        </div>

                        <div class="row g-4 mt-1">

                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label" for="pangkat_baru">
                                        <i class="bi bi-award me-1"></i>
                                        Pangkat / Golongan Baru
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="pangkat_baru" class="field-input field-select">
                                        <option value="">-- Pilih Pangkat / Golongan --</option>

                                        <option value="Penata Muda - III/a">Penata Muda - III/a</option>
                                        <option value="Penata Muda Tk. I - III/b">Penata Muda Tk. I - III/b</option>
                                        <option value="Penata - III/c">Penata - III/c</option>
                                        <option value="Penata Tk. I - III/d">Penata Tk. I - III/d</option>
                                        <option value="Pembina - IV/a">Pembina - IV/a</option>
                                        <option value="Pembina Tk. I - IV/b">Pembina Tk. I - IV/b</option>
                                        <option value="Pembina Utama Muda - IV/c">Pembina Utama Muda - IV/c</option>
                                        <option value="Pembina Utama Madya - IV/d">Pembina Utama Madya - IV/d</option>
                                        <option value="Pembina Utama - IV/e">Pembina Utama - IV/e</option>
                                    </select>

                                    <span id="error_pangkat" class="field-error">
                                        @error('pangkat')
                                            {{ $message }}
                                        @enderror

                                        @error('golongan')
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
                                <a href="{{ route('dosten.panggol.index') }}" class="btn-cancel">
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

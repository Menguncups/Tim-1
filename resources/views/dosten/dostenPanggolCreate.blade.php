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

        @include('Sidebar.dostenSidebar')

        <div class="content-col">
            <main class="content-area">

                <div class="page-header mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        <div>
                            <h4 class="page-title mb-0">Pengajuan Pangkat Golongan</h4>
                            <p class="page-sub mb-0">Lengkapi formulir pengajuan pangkat dan golongan</p>
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
                        <p>Isi data pangkat, golongan, TMT, dan lampirkan berkas pendukung.</p>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Pengajuan Pangkat Golongan
                    </div>

                    <form id="formPanggol"
                        action="{{ route('dosten.panggol.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="form-body form-pengajuan"
                        data-form-type="panggol">
                        @csrf

                        <div class="section-divider">
                            <span>
                                <i class="bi bi-file-earmark-text-fill me-1"></i>
                                Detail Permohonan
                            </span>
                        </div>

                        <div class="row g-4 mt-1">

                            <div class="col-md-4">
                                <div class="field-group">
                                    <label class="field-label" for="pangkat">
                                        <i class="bi bi-award me-1"></i>
                                        Pangkat
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="pangkat" name="pangkat" class="field-input field-select">
                                        <option value="">-- Pilih Pangkat --</option>
                                        @foreach (['Pengatur Muda', 'Pengatur Muda Tk. I', 'Pengatur', 'Pengatur Tk. I', 'Penata Muda', 'Penata Muda Tk. I', 'Penata', 'Penata Tk. I', 'Pembina', 'Pembina Tk. I', 'Pembina Utama Muda', 'Pembina Utama Madya', 'Pembina Utama'] as $pangkat)
                                            <option value="{{ $pangkat }}" {{ old('pangkat') == $pangkat ? 'selected' : '' }}>
                                                {{ $pangkat }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span id="error_pangkat" class="field-error">
                                        @error('pangkat') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="field-group">
                                    <label class="field-label" for="golongan">
                                        <i class="bi bi-patch-check me-1"></i>
                                        Golongan
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="golongan" name="golongan" class="field-input field-select">
                                        <option value="">-- Pilih Golongan --</option>
                                        @foreach (['II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'] as $golongan)
                                            <option value="{{ $golongan }}" {{ old('golongan') == $golongan ? 'selected' : '' }}>
                                                {{ $golongan }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span id="error_golongan" class="field-error">
                                        @error('golongan') {{ $message }} @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
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
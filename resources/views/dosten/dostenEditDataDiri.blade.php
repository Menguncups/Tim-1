<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Perbarui Data Diri {{ $roleLabel }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dostenEditDataDiri.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        @include('Sidebar.dostenSideBar')

        <div class="content-area">

            <div class="page-content">

                <div class="edit-page-header">
                    <div class="edit-header-left">
                        <div class="edit-header-icon {{ $roleClass }}">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>
                            <h4 class="edit-page-title">Perbarui Data Diri {{ $roleLabel }}</h4>
                            <p class="edit-page-sub">{{ $pageSub }}</p>
                        </div>
                    </div>

                    <a href="{{ url('/dosten/data-diri') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>
                </div>

                <div class="update-card">
                    <form id="formUpdateDataDiri" enctype="multipart/form-data"
                        data-update-url="{{ url('/dosten/data-diri/update') }}"
                        data-redirect-url="{{ url('/dosten/data-diri') }}">

                        <div class="row g-4">

                            <div class="col-md-7">

                                <div class="field-group">
                                    <label class="field-label" for="no_hp">
                                        <i class="bi bi-phone me-1"></i>
                                        No. HP Aktif / WhatsApp
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="text" name="no_hp" id="no_hp" class="field-input"
                                        value="{{ old('no_hp', $pegawai->no_hp ?? '') }}"
                                        placeholder="Contoh: 08123456789">

                                    <span id="error_no_hp" class="field-error"></span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="no_hp_darurat">
                                        <i class="bi bi-phone-vibrate me-1"></i>
                                        No. HP Darurat
                                    </label>

                                    <input type="text" name="no_hp_darurat" id="no_hp_darurat" class="field-input"
                                        value="{{ old('no_hp_darurat', $pegawai->no_hp_darurat ?? '') }}"
                                        placeholder="Contoh: 08123456789">

                                    <span id="error_no_hp_darurat" class="field-error"></span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="inputFoto">
                                        <i class="bi bi-camera me-1"></i>
                                        Foto Profil Baru
                                    </label>

                                    <input type="file" name="foto" id="inputFoto" class="field-input"
                                        accept="image/png, image/jpeg">

                                    <span id="error_foto" class="field-error"></span>

                                    <div class="field-help">
                                        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                                    </div>
                                </div>

                                <div class="action-row">
                                    <button type="submit" class="btn-submit {{ $roleClass }}">
                                        <i class="bi bi-save me-2"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </div>

                            <div class="col-md-5">
                                <div class="preview-box">
                                    <span class="preview-label">Pratinjau Foto</span>

                                    <div class="preview-photo-wrap">
                                        @if (!empty($pegawai->foto))
                                            <img id="imgPreview" src="{{ asset('photo/' . $pegawai->foto) }}"
                                                alt="Foto {{ $pegawai->nama }}" class="preview-photo">
                                        @else
                                            <img id="imgPreview" src="" alt="Preview Foto"
                                                class="preview-photo d-none">

                                            <div id="previewFallback" class="preview-fallback">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="preview-name">
                                        {{ $pegawai->nama ?? '-' }}
                                    </div>

                                    <div class="preview-role {{ $roleClass }}">
                                        {{ $roleLabel }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

            @include('Footer.footer')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
    <script src="{{ asset('js/dostenEditDataDiri.js') }}"></script>

</body>

</html>

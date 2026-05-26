@php
    $isEdit = isset($pegawai);

    $selectedRoles = old('roles', $isEdit ? $pegawai->roles->pluck('nama_role')->toArray() : []);
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEdit ? 'Edit Pegawai' : 'Tambah Pegawai' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/operatorSidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/createPegawai.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>

<body>

    <button class="mobile-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="wrapper">

        @include('Sidebar.operatorSideBar')

        <div class="content-col">
            <main class="content-area">

                <div class="page-header mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-person-plus-fill' }}"></i>
                        </div>
                        <div>
                            <h4 class="page-title mb-0">
                                {{ $isEdit ? 'Edit Pegawai' : 'Tambah Pegawai' }}
                            </h4>
                            <p class="page-sub mb-0">
                                {{ $isEdit ? 'Perbarui informasi data pegawai' : 'Lengkapi informasi data pegawai baru' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-card">

                    <div class="card-header-strip">
                        <i class="bi bi-pencil-square me-2"></i>
                        {{ $isEdit ? 'Form Edit Data Pegawai' : 'Form Input Data Pegawai' }}
                    </div>

                    <form id="formData"
                        action="{{ $isEdit ? url('/operator/update-pegawai/' . $pegawai->id_pegawai) : url('/operator/tambah-data') }}"
                        method="POST" enctype="multipart/form-data" class="form-body"
                        data-mode="{{ $isEdit ? 'edit' : 'create' }}">
                        @csrf

                        @if ($isEdit)
                            @method('PUT')
                        @endif

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

                        {{-- Upload Foto --}}
                        <div class="field-group d-flex flex-column align-items-center mb-4">
                            <label class="field-label" for="foto">
                                <i class="bi bi-camera me-1"></i>Upload Pas Foto
                                @if (!$isEdit)
                                    <span class="required-dot">*</span>
                                @endif
                            </label>

                            <div class="foto-upload-wrap">
                                <label class="foto-upload-label" for="foto">
                                    <div class="foto-placeholder" id="fotoPlaceholder"
                                        style="{{ $isEdit && !empty($pegawai->foto) ? 'display:none;' : '' }}">
                                        <i class="bi bi-person-bounding-box"></i>
                                        <span>Klik untuk upload foto</span>
                                        <small>JPG / PNG, maks. 2 MB</small>
                                    </div>

                                    <img id="fotoPreview" class="foto-preview"
                                        src="{{ $isEdit && !empty($pegawai->foto) ? asset('photo/' . $pegawai->foto) : '' }}"
                                        alt="Preview Foto"
                                        style="{{ $isEdit && !empty($pegawai->foto) ? 'display:block;' : 'display:none;' }}">
                                </label>

                                <input type="file" id="foto" name="foto" class="d-none"
                                    accept="image/png, image/jpeg">

                                <button type="button" id="fotoHapus" class="foto-hapus-btn"
                                    style="{{ $isEdit && !empty($pegawai->foto) ? 'display:inline-flex;' : 'display:none;' }}">
                                    <i class="bi bi-x-circle-fill me-1"></i>Hapus Foto
                                </button>
                            </div>

                            <span id="error_foto" class="field-error">
                                @error('foto')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        {{-- Role --}}
                        <div class="field-group mb-4">
                            <label class="field-label">
                                <i class="bi bi-person-badge me-1"></i>Role Pegawai
                                <span class="required-dot">*</span>
                            </label>

                            <div class="role-selector">

                                <label class="role-card" data-role="dosen" data-group="A">
                                    <input type="checkbox" name="roles[]" value="dosen" class="role-checkbox"
                                        {{ in_array('dosen', $selectedRoles) ? 'checked' : '' }}>

                                    <div class="role-card-icon">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>

                                    <div class="role-card-body">
                                        <div class="role-card-title">Dosen</div>
                                        <div class="role-card-desc">Memiliki NIDN</div>
                                    </div>

                                    <div class="role-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </label>

                                <label class="role-card" data-role="pimpinan" data-group="A">
                                    <input type="checkbox" name="roles[]" value="pimpinan" class="role-checkbox"
                                        {{ in_array('pimpinan', $selectedRoles) ? 'checked' : '' }}>

                                    <div class="role-card-icon">
                                        <i class="bi bi-person-workspace"></i>
                                    </div>

                                    <div class="role-card-body">
                                        <div class="role-card-title">Pimpinan</div>
                                        <div class="role-card-desc">Bisa bersama Dosen</div>
                                    </div>

                                    <div class="role-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </label>

                                <label class="role-card" data-role="operator" data-group="B">
                                    <input type="checkbox" name="roles[]" value="operator" class="role-checkbox"
                                        {{ in_array('operator', $selectedRoles) ? 'checked' : '' }}>

                                    <div class="role-card-icon">
                                        <i class="bi bi-pc-display"></i>
                                    </div>

                                    <div class="role-card-body">
                                        <div class="role-card-title">Operator</div>
                                        <div class="role-card-desc">Tidak memakai NIDN</div>
                                    </div>

                                    <div class="role-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </label>

                                <label class="role-card" data-role="tendik" data-group="B">
                                    <input type="checkbox" name="roles[]" value="tendik" class="role-checkbox"
                                        {{ in_array('tendik', $selectedRoles) ? 'checked' : '' }}>

                                    <div class="role-card-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>

                                    <div class="role-card-body">
                                        <div class="role-card-title">Tendik</div>
                                        <div class="role-card-desc">Bisa bersama Operator</div>
                                    </div>

                                    <div class="role-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </label>

                            </div>

                            <div class="role-info">
                                Boleh pilih 1 role. Jika pilih 2 role, hanya boleh:
                                <b>Dosen + Pimpinan</b> atau <b>Operator + Tendik</b>.
                            </div>

                            <span id="error_role" class="field-error">
                                @error('roles')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <div class="row g-4">

                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">

                                <div class="field-group">
                                    <label class="field-label" for="nama">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="text" id="nama" name="nama" class="field-input"
                                        value="{{ old('nama', $pegawai->nama ?? '') }}"
                                        placeholder="Masukkan nama lengkap">

                                    <span id="error_nama" class="field-error">
                                        @error('nama')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="email">
                                        <i class="bi bi-envelope me-1"></i>Email
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="email" id="email" name="email" class="field-input"
                                        value="{{ old('email', $pegawai->email ?? '') }}"
                                        placeholder="contoh@unri.ac.id">

                                    <span id="error_email" class="field-error">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="password">
                                        <i class="bi bi-lock me-1"></i>Password
                                        @if (!$isEdit)
                                            <span class="required-dot">*</span>
                                        @endif
                                    </label>

                                    <div class="password-wrapper">
                                        <input type="password" id="password" name="password"
                                            class="field-input password-input"
                                            placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password' }}">

                                        <button type="button" class="password-toggle" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>

                                    <span id="error_password" class="field-error">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="nip">
                                        <i class="bi bi-card-text me-1"></i>NIP
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="text" id="nip" name="nip" class="field-input"
                                        value="{{ old('nip', $pegawai->nip ?? '') }}" placeholder="18 digit NIP"
                                        maxlength="18" minlength="18" inputmode="numeric" pattern="[0-9]{18}">

                                    <span id="error_nip" class="field-error">
                                        @error('nip')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div id="section_nidn" class="dynamic-section hidden">
                                    <div class="field-group">
                                        <label class="field-label" for="nidn">
                                            <i class="bi bi-upc me-1"></i>NIDN
                                            <span class="required-dot">*</span>
                                        </label>

                                        <input type="text" id="nidn" name="nidn" class="field-input"
                                            value="{{ old('nidn', $pegawai->nidn ?? '') }}"
                                            placeholder="10 digit NIDN" maxlength="10" minlength="10"
                                            inputmode="numeric" pattern="[0-9]{10}">

                                        <span id="error_nidn" class="field-error">
                                            @error('nidn')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="jenis_kelamin">
                                        <i class="bi bi-gender-ambiguous me-1"></i>Jenis Kelamin
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="jenis_kelamin" name="jenis_kelamin" class="field-input field-select">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki"
                                            {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                    </select>

                                    <span id="error_jk" class="field-error">
                                        @error('jenis_kelamin')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="tanggal_lahir">
                                        <i class="bi bi-calendar3 me-1"></i>Tanggal Lahir
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                        class="field-input"
                                        value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ?? '') }}">

                                    <span id="error_tgl" class="field-error">
                                        @error('tanggal_lahir')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">

                                <div class="field-group">
                                    <label class="field-label" for="no_hp">
                                        <i class="bi bi-phone me-1"></i>No. HP
                                        <span class="required-dot">*</span>
                                    </label>

                                    <input type="text" id="no_hp" name="no_hp" class="field-input"
                                        value="{{ old('no_hp', $pegawai->no_hp ?? '') }}"
                                        placeholder="Contoh: 08123456789">

                                    <span id="error_hp" class="field-error">
                                        @error('no_hp')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="no_hp_darurat">
                                        <i class="bi bi-phone-vibrate me-1"></i>No. HP Darurat
                                    </label>

                                    <input type="text" id="no_hp_darurat" name="no_hp_darurat"
                                        class="field-input"
                                        value="{{ old('no_hp_darurat', $pegawai->no_hp_darurat ?? '') }}"
                                        placeholder="Contoh: 08123456789">

                                    <span id="error_hp_darurat" class="field-error">
                                        @error('no_hp_darurat')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="homebase">
                                        <i class="bi bi-building me-1"></i>Homebase
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="homebase" name="homebase" class="field-input field-select">
                                        <option value="">-- Pilih Homebase --</option>

                                        <optgroup label="S1">
                                            <option value="S1 Teknik Sipil"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Sipil' ? 'selected' : '' }}>
                                                S1 Teknik Sipil</option>
                                            <option value="S1 Teknik Mesin"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Mesin' ? 'selected' : '' }}>
                                                S1 Teknik Mesin</option>
                                            <option value="S1 Teknik Elektro"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Elektro' ? 'selected' : '' }}>
                                                S1 Teknik Elektro</option>
                                            <option value="S1 Teknik Kimia"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Kimia' ? 'selected' : '' }}>
                                                S1 Teknik Kimia</option>
                                            <option value="S1 Teknik Lingkungan"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Lingkungan' ? 'selected' : '' }}>
                                                S1 Teknik Lingkungan</option>
                                            <option value="S1 Arsitektur"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Arsitektur' ? 'selected' : '' }}>
                                                S1 Arsitektur</option>
                                            <option value="S1 Teknik Informatika"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'S1 Teknik Informatika' ? 'selected' : '' }}>
                                                S1 Teknik Informatika</option>
                                        </optgroup>

                                        <optgroup label="D3">
                                            <option value="D3 Teknik Sipil"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'D3 Teknik Sipil' ? 'selected' : '' }}>
                                                D3 Teknik Sipil</option>
                                            <option value="D3 Teknik Mesin"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'D3 Teknik Mesin' ? 'selected' : '' }}>
                                                D3 Teknik Mesin</option>
                                            <option value="D3 Teknik Elektro"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'D3 Teknik Elektro' ? 'selected' : '' }}>
                                                D3 Teknik Elektro</option>
                                            <option value="D3 Teknik Kimia"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'D3 Teknik Kimia' ? 'selected' : '' }}>
                                                D3 Teknik Kimia</option>
                                            <option value="D3 Teknologi Pulp dan Kertas"
                                                {{ old('homebase', $pegawai->homebase ?? '') == 'D3 Teknologi Pulp dan Kertas' ? 'selected' : '' }}>
                                                D3 Teknologi Pulp dan Kertas</option>
                                        </optgroup>
                                    </select>

                                    <span id="error_homebase" class="field-error">
                                        @error('homebase')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="pangkat_golongan">
                                        <i class="bi bi-award me-1"></i>Pangkat / Golongan
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="pangkat_golongan" name="pangkat_golongan"
                                        class="field-input field-select">
                                        <option value="">-- Pilih Pangkat / Golongan --</option>

                                        @foreach (['II/a - Pengatur Muda', 'II/b - Pengatur Muda Tk. I', 'II/c - Pengatur', 'II/d - Pengatur Tk. I', 'III/a - Penata Muda', 'III/b - Penata Muda Tk. I', 'III/c - Penata', 'III/d - Penata Tk. I', 'IV/a - Pembina', 'IV/b - Pembina Tk. I', 'IV/c - Pembina Utama Muda', 'IV/d - Pembina Utama Madya', 'IV/e - Pembina Utama'] as $pangkat)
                                            <option value="{{ $pangkat }}"
                                                {{ old('pangkat_golongan', $pegawai->pangkat_golongan ?? '') == $pangkat ? 'selected' : '' }}>
                                                {{ $pangkat }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span id="error_pangkat" class="field-error">
                                        @error('pangkat_golongan')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="jabatan_fungsional">
                                        <i class="bi bi-briefcase me-1"></i>Jabatan Fungsional
                                        <span class="required-dot">*</span>
                                    </label>

                                    <select id="jabatan_fungsional" name="jabatan_fungsional"
                                        class="field-input field-select"
                                        data-old="{{ old('jabatan_fungsional', $pegawai->jabatan_fungsional ?? '') }}">
                                        <option value="">-- Pilih Jabatan Fungsional --</option>
                                    </select>

                                    <span id="error_jabatan" class="field-error">
                                        @error('jabatan_fungsional')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="action-bar mt-4">
                            <span class="required-note">
                                <span class="required-dot">*</span> Wajib diisi
                            </span>

                            <div class="d-flex gap-2">
                                <a href="{{ url('/operator/daftar-pegawai') }}" class="btn-cancel">
                                    <i class="bi bi-arrow-left me-2"></i>Batal
                                </a>

                                <button type="button" id="btnSimpan" class="btn-submit">
                                    <i class="bi bi-save2 me-2"></i>
                                    {{ $isEdit ? 'Update Data' : 'Simpan Data' }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </main>
            {{-- FOOTER --}}
            @include('Footer.footer')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/operatorSideBar.js') }}"></script>
    <script src="{{ asset('js/createPegawai.js') }}"></script>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — Sistem Kepegawaian Fakultas Teknik UNRI</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>

<body>

    <div class="login-outer">
        <div class="login-wrap">

            <!-- PANEL KIRI -->
            <div class="left-panel">
                <div class="left-top">
                    <img src="{{ asset('img/unriteknik.png') }}"
                        alt="Universitas Riau - Fakultas Teknik"
                        class="left-logo"
                        id="leftLogo">

                    <div id="logo-fallback" style="display:none;margin-bottom:20px">
                        <div class="logo-text-fallback">UNIVERSITAS RIAU</div>
                        <div class="logo-text-fallback-sub">FAKULTAS TEKNIK</div>
                    </div>

                    <p id="left-desc">
                        @if ($step == 1)
                            Universitas · Portal Terpadu<br>Data Pegawai &amp; Dosen
                        @else
                            Kredensial terverifikasi.<br>Pilih peran untuk lanjut.
                        @endif
                    </p>
                </div>

                <div class="left-bottom">
                    <div class="step-label">Langkah</div>

                    <div class="step-dots">
                        <div class="dot {{ $step == 1 ? 'active' : '' }}" id="dot1"></div>
                        <div class="dot {{ $step == 2 ? 'active' : '' }}" id="dot2"></div>
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN -->
            <div class="right-panel">

                @if ($step == 1)
                    <!-- STEP 1: FORM LOGIN -->
                    <div class="step-content" id="step1">
                        <h2>Selamat datang</h2>
                        <p class="sub">Masukkan kredensial Anda</p>

                        <form method="POST" action="{{ route('login.process') }}">
                            @csrf

                            <div class="form-group">
                                <label for="username">Email</label>

                                <input type="text"
                                    id="username"
                                    name="username"
                                    placeholder="Masukkan email"
                                    autocomplete="username"
                                    value="{{ old('username') }}">

                                <span class="field-error" id="err-username">
                                    @error('username')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>

                                <div class="input-wrap">
                                    <input type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Masukkan password"
                                        autocomplete="current-password">

                                    <span class="toggle-pw" onclick="togglePassword()" role="button"
                                        aria-label="Tampilkan password">
                                        <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>

                                        <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            style="display:none">
                                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                                            <line x1="1" y1="1" x2="23" y2="23" />
                                        </svg>
                                    </span>
                                </div>

                                <span class="field-error" id="err-password">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <button class="btn-next" id="btn-masuk" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 9.9-1" />
                                </svg>
                                Masuk
                            </button>
                        </form>
                    </div>
                @endif

                @if ($step == 2 && $loginUser)
                    <!-- STEP 2: PILIH ROLE -->
                    <div class="step-content" id="step2">
                        <div class="loading-bar">
                            <div class="loading-bar-fill animate" id="loading-fill"></div>
                        </div>

                        <div class="user-info-badge">
                            <div class="user-avatar" id="user-avatar">
                                {{ strtoupper(substr($loginUser['nama'], 0, 1)) }}
                            </div>

                            <div class="user-meta">
                                <strong id="user-name">{{ $loginUser['nama'] }}</strong>
                                <span>Akun terverifikasi ✓</span>
                            </div>
                        </div>

                        <h2>Masuk sebagai</h2>
                        <p class="sub">Pilih peran Anda untuk melanjutkan</p>

                        <form method="POST" action="{{ route('login.role.process') }}" id="roleForm">
                            @csrf

                            <span class="field-error" id="err-role" style="display:block;margin-bottom:10px">
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </span>

                            <div class="role-grid" id="role-grid">
                                @foreach ($loginUser['roles'] as $role)
                                    @php
                                        $meta = $roleMeta[$role] ?? [
                                            'label' => ucfirst($role),
                                            'desc' => '',
                                            'cls' => 'op',
                                        ];
                                    @endphp

                                    <label class="role-card" data-role="{{ $role }}">
                                        <input type="radio" name="role" value="{{ $role }}" class="role-radio" hidden>

                                        <div class="role-icon {{ $meta['cls'] }}">
                                            @if ($role === 'operator')
                                                ⚙
                                            @elseif ($role === 'dosen')
                                                🎓
                                            @elseif ($role === 'tendik')
                                                👥
                                            @elseif ($role === 'pimpinan')
                                                👑
                                            @endif
                                        </div>

                                        <div class="role-info">
                                            <strong>{{ $meta['label'] }}</strong>
                                            <span>{{ $meta['desc'] }}</span>
                                        </div>

                                        <svg class="role-arrow" xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </label>
                                @endforeach
                            </div>

                            <button class="btn-next disabled" id="btn-lanjut" disabled type="submit">
                                Lanjutkan
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </button>
                        </form>

                        <a href="{{ route('login') }}" class="back-link">
                            ← Kembali ke login
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
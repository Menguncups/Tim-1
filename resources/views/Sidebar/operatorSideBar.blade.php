<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('img/unriteknik.png') }}" alt="UNRI Teknik" class="brand-img">
    </div>

    <nav class="sidebar-nav">

        {{-- UTAMA --}}
        <div class="nav-group-label">Utama</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="{{ url('/operator/dashboard') }}"
                    class="nav-link {{ request()->is('operator/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/operator/daftar-pegawai') }}"
                    class="nav-link {{ request()->is('operator/daftar-pegawai') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    Daftar Pegawai
                </a>
            </li>

        </ul>

        {{-- VALIDASI --}}
        <div class="nav-group-label">Validasi</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center
                   {{ request()->is('operator/verifikasi*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#menuVerifikasi" role="button"
                    aria-expanded="{{ request()->is('operator/verifikasi*') ? 'true' : 'false' }}">
                    <span>
                        <i class="bi bi-patch-check-fill me-2"></i>
                        Validasi
                    </span>
                    <i class="bi bi-chevron-down small dropdown-icon"></i>
                </a>

                <div class="collapse {{ request()->is('operator/verifikasi*') ? 'show' : '' }}" id="menuVerifikasi">
                    <ul class="submenu-list">

                        <li>
                            <a href="{{ url('/operator/validasi/surtug') }}"
                                class="nav-link sub-link {{ request()->is('operator/validasi/surtug') ? 'active' : '' }}">
                                <i class="bi bi-envelope-paper-fill"></i>
                                Surat Tugas
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/operator/validasi/jabfung') }}"
                                class="nav-link sub-link {{ request()->is('operator/validasi/jabfung') ? 'active' : '' }}">
                                <i class="bi bi-person-vcard-fill"></i>
                                Jabfung
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/operator/validasi/panggol') }}"
                                class="nav-link sub-link {{ request()->is('operator/validasi/panggol') ? 'active' : '' }}">
                                <i class="bi bi-award-fill"></i>
                                Panggol
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        </ul>

    </nav>

    <div class="sidebar-footer">
        <a href="#" class="logout-link" id="btnLogout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </div>

</aside>

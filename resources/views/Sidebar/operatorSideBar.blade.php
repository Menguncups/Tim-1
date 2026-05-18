<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('img/teknikmerah.png') }}" alt="UNRI">
    </div>

    <ul class="nav flex-column pt-2 flex-grow-1">

        <li class="nav-item">
            <a href="{{ url('/operator/dashboard') }}"
                class="nav-link {{ request()->is('operator/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/operator/daftar-pegawai') }}"
                class="nav-link {{ request()->is('operator/daftar-pegawai') ? 'active' : '' }}">
                <i class="bi bi-people-fill me-2"></i>
                Daftar Pegawai
            </a>
        </li>

        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center
               {{ request()->is('operator/verifikasi') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#menuVerifikasi" role="button">

                <span>
                    <i class="bi bi-patch-check-fill me-2"></i>
                    Verifikasi
                </span>

                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>

            <div class="collapse {{ request()->is('operator/verifikasi*') ? 'show' : '' }}" id="menuVerifikasi">

                <ul class="nav flex-column sub-nav">

                    <li class="nav-item">
                        <a href="{{ url('/operator/verifikasi/surat-tugas') }}"
                            class="nav-link sub-link {{ request()->is('operator/verifikasi/surat-tugas') ? 'active' : '' }}">
                            <i class="bi bi-envelope-paper-fill"></i>
                            Surat Tugas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/operator/verifikasi/jabfung') }}"
                            class="nav-link sub-link {{ request()->is('operator/verifikasi/jabfung') ? 'active' : '' }}">
                            <i class="bi bi-person-vcard-fill"></i>
                            Jabfung
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/operator/verifikasi/panggol') }}"
                            class="nav-link sub-link {{ request()->is('operator/verifikasi/panggol') ? 'active' : '' }}">
                            <i class="bi bi-award-fill"></i>
                            Panggol
                        </a>
                    </li>

                </ul>

            </div>

        </li>

    </ul>

    <div class="sidebar-footer">
        <a href="#" class="logout-link" id="btnLogout">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login / Sign In</span>
        </a>
    </div>
</aside>

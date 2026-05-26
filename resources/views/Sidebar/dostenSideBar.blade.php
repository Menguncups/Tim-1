<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('img/unriteknik.png') }}" alt="UNRI Teknik" class="brand-img">
    </div>

    <nav class="sidebar-nav">

        {{-- UTAMA --}}
        <div class="nav-group-label">Utama</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="{{ url('/dosten/dashboard') }}"
                    class="nav-link {{ request()->is('dosten/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/dosten/data-diri') }}"
                    class="nav-link {{ request()->is('dosten/data-diri') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    Data Diri
                </a>
            </li>

        </ul>

        {{-- PENGAJUAN --}}
        <div class="nav-group-label">Pengajuan</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center
                   {{ request()->is('dosten/pengajuan*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#menuPengajuan" role="button"
                    aria-expanded="{{ request()->is('dosten/pengajuan*') ? 'true' : 'false' }}">
                    <span>
                        <i class="bi bi-send-fill me-2"></i>
                        Pengajuan
                    </span>
                    <i class="bi bi-chevron-down small dropdown-icon"></i>
                </a>

                <div class="collapse {{ request()->is('dosten/pengajuan*') ? 'show' : '' }}" id="menuPengajuan">
                    <ul class="submenu-list">

                        <li>
                            <a href="{{ url('/dosten/pengajuan/surtug') }}"
                                class="nav-link sub-link {{ request()->is('dosten/pengajuan/surtug') ? 'active' : '' }}">
                                <i class="bi bi-envelope-paper-fill"></i>
                                Surat Tugas
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/dosten/pengajuan/jabfung') }}"
                                class="nav-link sub-link {{ request()->is('dosten/pengajuan/jabfung') ? 'active' : '' }}">
                                <i class="bi bi-person-vcard-fill"></i>
                                Jabfung
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/dosten/pengajuan/panggol') }}"
                                class="nav-link sub-link {{ request()->is('dosten/pengajuan/panggol') ? 'active' : '' }}">
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
        @include('auth.logoutButton')
    </div>

</aside>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('img/unriteknik.png') }}" alt="UNRI Teknik" class="brand-img">
    </div>

    <nav class="sidebar-nav">

        {{-- UTAMA --}}
        <div class="nav-group-label">Utama</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="{{ url('/pimpinan/dashboard') }}"
                    class="nav-link {{ request()->is('pimpinan/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

        </ul>

        {{-- VERIFIKASI --}}
        <div class="nav-group-label">Verifikasi</div>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center
                   {{ request()->is('pimpinan/verifikasi*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#menuVerifikasi" role="button"
                    aria-expanded="{{ request()->is('pimpinan/verifikasi*') ? 'true' : 'false' }}">
                    <span>
                        <i class="bi bi-patch-check-fill me-2"></i>
                        Verifikasi
                    </span>
                    <i class="bi bi-chevron-down small dropdown-icon"></i>
                </a>

                <div class="collapse {{ request()->is('pimpinan/verifikasi*') ? 'show' : '' }}" id="menuVerifikasi">
                    <ul class="submenu-list">

                        <li>
                            <a href="{{ url('/pimpinan/verifikasi/surtug') }}"
                                class="nav-link sub-link {{ request()->is('pimpinan/verifikasi/surtug') ? 'active' : '' }}">
                                <i class="bi bi-envelope-paper-fill"></i>
                                Surat Tugas
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/pimpinan/verifikasi/jabfung') }}"
                                class="nav-link sub-link {{ request()->is('pimpinan/verifikasi/jabfung') ? 'active' : '' }}">
                                <i class="bi bi-person-vcard-fill"></i>
                                Jabfung
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/pimpinan/verifikasi/panggol') }}"
                                class="nav-link sub-link {{ request()->is('pimpinan/verifikasi/panggol') ? 'active' : '' }}">
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

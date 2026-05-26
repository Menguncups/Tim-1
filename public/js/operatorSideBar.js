// ================================================
// verifikasiSideBar.js
// ================================================

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("sidebarToggle");

    if (!sidebar || !toggle) return;

    // ── Mobile toggle (buka / tutup sidebar) ──────────────────────
    document.addEventListener("click", function (e) {
        if (toggle.contains(e.target)) {
            sidebar.classList.toggle("open");
        } else if (!sidebar.contains(e.target)) {
            sidebar.classList.remove("open");
        }
    });

    // ── Dropdown chevron: sinkronkan aria-expanded ──────────────────
    document
        .querySelectorAll('[data-bs-toggle="collapse"]')
        .forEach(function (trigger) {
            const targetId =
                trigger.getAttribute("href") ||
                trigger.getAttribute("data-bs-target");
            const collapseEl = document.querySelector(targetId);

            if (!collapseEl) return;

            collapseEl.addEventListener("show.bs.collapse", function () {
                trigger.setAttribute("aria-expanded", "true");
            });

            collapseEl.addEventListener("hide.bs.collapse", function () {
                trigger.setAttribute("aria-expanded", "false");
            });
        });

    // ── Active link: tandai link yang cocok dengan URL saat ini ──────
    const currentPath = window.location.pathname;

    document.querySelectorAll(".sidebar .nav-link").forEach(function (link) {
        const href = link.getAttribute("href");
        if (!href || href === "#") return;

        if (currentPath === href || currentPath.startsWith(href + "/")) {
            link.classList.add("active");

            // Buka parent collapse jika sub-link yang aktif
            const parentCollapse = link.closest(".collapse");
            if (parentCollapse) {
                parentCollapse.classList.add("show");

                const parentTrigger = document.querySelector(
                    `[data-bs-toggle="collapse"][href="#${parentCollapse.id}"]`,
                );
                if (parentTrigger) {
                    parentTrigger.classList.add("active");
                    parentTrigger.setAttribute("aria-expanded", "true");
                }
            }
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const logoutForms = document.querySelectorAll(".form-logout");

    logoutForms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            if (typeof Swal !== "undefined") {
                Swal.fire({
                    icon: "question",
                    title: "Yakin ingin logout?",
                    text: "Sesi login Anda akan diakhiri.",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Logout",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#b52a20",
                    cancelButtonColor: "#7a8099",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

                return;
            }

            const yakin = confirm("Yakin ingin logout?");

            if (yakin) {
                form.submit();
            }
        });
    });
});

fetch("operatorSideBar.html")
    .then((response) => response.text())
    .then((data) => {
        document.getElementById("sidebar-container").innerHTML = data;

        // ACTIVE SIDEBAR
        const currentPage = window.location.pathname.split("/").pop();

        document.querySelectorAll(".sidebar .nav-link").forEach((link) => {
            const href = link.getAttribute("href");

            if (href === currentPage) {
                link.classList.add("active");

                const collapse = link.closest(".collapse");

                if (collapse) {
                    collapse.classList.add("show");

                    const parentToggle = document.querySelector(
                        `[href="#${collapse.id}"]`,
                    );

                    if (parentToggle) {
                        parentToggle.classList.add("active");
                    }
                }
            }
        });
    });

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("sidebarToggle");

    if (!sidebar || !toggle) return;

    document.addEventListener("click", function (e) {
        if (toggle.contains(e.target)) {
            sidebar.classList.toggle("open");
        } else if (!sidebar.contains(e.target)) {
            sidebar.classList.remove("open");
        }
    });
});

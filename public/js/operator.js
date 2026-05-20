/* ================================================
   daftarPengguna.js
   Filter pill & DataTable untuk Daftar Pengguna
   ================================================ */

$(document).ready(function () {
    // ── 1. INISIALISASI DATATABLE ─────────────────────────────────
    const table = $("#tabelPengguna").DataTable({
        language: {
            search: "",
            searchPlaceholder: "Cari nama, NIP, email...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ pengguna",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" },
            emptyTable: "Tidak ada data pengguna",
            zeroRecords: "Data tidak ditemukan",
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, "asc"]],
        columnDefs: [
            { width: "55px", targets: 0 },
            { orderable: false, targets: [4, 6] },
            { searchable: false, targets: [5, 6] },
        ],
        // DOM: posisi elemen DataTables
        dom: '<"du-dt-top"lf>rt<"du-dt-bot"ip>',
        drawCallback: function () {
            // Reapply filter setelah redraw (misal setelah search)
            const activeTab = $(".du-pill.active").data("tab");
            if (activeTab && activeTab !== "semua") {
                filterByRole(activeTab);
            }
        },
    });

    // ── 2. FILTER PILL ────────────────────────────────────────────
    function filterByRole(tab) {
        if (tab === "semua") {
            // Tampilkan semua baris
            $.fn.dataTable.ext.search.pop();
            table.draw();
            return;
        }

        // Push custom search function
        $.fn.dataTable.ext.search.pop(); // buang filter sebelumnya
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const row = table.row(dataIndex).node();
            const roleAttr = $(row).data("role") || "";
            return roleAttr.split(" ").includes(tab);
        });
        table.draw();
    }

    $(".du-pill").on("click", function () {
        $(".du-pill").removeClass("active");
        $(this).addClass("active");

        const tab = $(this).data("tab");
        filterByRole(tab);
    });

    // ── 3. CUSTOM SEARCH INPUT STYLE ─────────────────────────────
    // Tambahkan ikon search di dalam input (setelah DataTable render)
    setTimeout(function () {
        const $input = $(".dataTables_filter input");
        $input.attr("placeholder", "Cari nama, NIP, email...");
    }, 100);
});

document.addEventListener("click", function (event) {
    const button = event.target.closest(".btn-detail-pegawai");

    if (!button) return;

    const foto = button.dataset.foto;

    const fotoEl = document.getElementById("detailFoto");
    const fotoFallback = document.getElementById("detailFotoFallback");

    if (foto) {
        fotoEl.src = foto;
        fotoEl.style.display = "block";
        fotoFallback.style.display = "none";
    } else {
        fotoEl.src = "";
        fotoEl.style.display = "none";
        fotoFallback.style.display = "flex";
    }

    document.getElementById("detailNama").textContent =
        button.dataset.nama || "-";
    document.getElementById("detailEmail").textContent =
        button.dataset.email || "-";
    document.getElementById("detailRole").textContent =
        button.dataset.role || "-";

    document.getElementById("detailNip").textContent =
        button.dataset.nip || "-";
    document.getElementById("detailNidn").textContent =
        button.dataset.nidn || "-";
    document.getElementById("detailJenisKelamin").textContent =
        button.dataset.jenisKelamin || "-";
    document.getElementById("detailTanggalLahir").textContent =
        button.dataset.tanggalLahir || "-";

    document.getElementById("detailNoHp").textContent =
        button.dataset.noHp || "-";
    document.getElementById("detailNoHpDarurat").textContent =
        button.dataset.noHpDarurat || "-";

    document.getElementById("detailHomebase").textContent =
        button.dataset.homebase || "-";
    document.getElementById("detailPangkatGolongan").textContent =
        button.dataset.pangkatGolongan || "-";
    document.getElementById("detailJabatanFungsional").textContent =
        button.dataset.jabatanFungsional || "-";
});

document.addEventListener("submit", function (event) {
    const form = event.target.closest(".form-hapus-pegawai");

    if (!form) return;

    event.preventDefault();

    const nama = form.dataset.nama || "pegawai ini";

    Swal.fire({
        icon: "warning",
        title: "Hapus Data?",
        text: `Data ${nama} akan dihapus permanen.`,
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#c0392b",
        cancelButtonColor: "#64748b",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

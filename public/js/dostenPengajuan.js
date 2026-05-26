$(document).ready(function () {
    const tableElement = $(".tabel-dosten").first();

    if (!tableElement.length) return;

    // Cegah DataTable diinisialisasi dua kali
    if ($.fn.DataTable.isDataTable(tableElement)) {
        return;
    }

    const statusColumn = Number(tableElement.data("status-column"));

    const table = tableElement.DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Belum ada pengajuan",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada pengajuan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "›",
                previous: "‹",
            },
        },
        dom:
            "<'du-dt-top'<'dataTables_length'l><'dataTables_filter'f>>" +
            "rt" +
            "<'row mt-3'<'col-md-6'i><'col-md-6'p>>",
    });

    $(".dataTables_filter input").attr("placeholder", "Cari data pengajuan...");

    $(".du-pill").on("click", function () {
        $(".du-pill").removeClass("active");
        $(this).addClass("active");

        const status = $(this).data("status");

        if (status === "semua") {
            table.column(statusColumn).search("").draw();
            return;
        }

        const labelMap = {
            menunggu: "Menunggu",
            diproses: "Diproses",
            diterima: "Diterima",
            ditolak: "Ditolak",
        };

        table
            .column(statusColumn)
            .search(labelMap[status] || "")
            .draw();
    });

    // DETAIL SURAT TUGAS
    $(document).on("click", ".btn-detail-surtug", function () {
        const button = $(this);

        $("#detailId").text(button.data("id") || "-");

        $("#detailNama").text(button.data("nama") || "-");
        $("#detailNamaPengusul").text(button.data("nama") || "-");

        $("#detailWaktu").text(button.data("waktu") || "-");
        $("#detailLama").text(button.data("lama") || "-");
        $("#detailPerihal").text(button.data("perihal") || "-");
        $("#detailTanggal").text(button.data("tanggal") || "-");
        $("#detailStatus").text(button.data("status") || "-");
        $("#detailCatatan").text(button.data("catatan") || "—");

        $("#detailBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");

        if (fileUrl) {
            $("#detailBerkasLink").attr("href", fileUrl);
        } else {
            $("#detailBerkasLink").attr("href", "#");
        }
    });

    // DETAIL JABFUNG
    $(document).on("click", ".btn-detail-jabfung", function () {
        const button = $(this);

        $("#detailJabfungId").text(button.data("id") || "-");

        $("#detailJabfungNamaJabatan").text(button.data("nama-jabatan") || "-");
        $("#detailJabfungNama").text(button.data("nama-jabatan") || "-");

        $("#detailJabfungTmtText").text("TMT: " + (button.data("tmt") || "-"));
        $("#detailJabfungTmt").text(button.data("tmt") || "-");

        $("#detailJabfungTanggal").text(button.data("tanggal") || "-");
        $("#detailJabfungStatus").text(button.data("status") || "-");
        $("#detailJabfungCatatan").text(button.data("catatan") || "—");

        $("#detailJabfungBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");

        if (fileUrl) {
            $("#detailJabfungBerkasLink").attr("href", fileUrl);
        } else {
            $("#detailJabfungBerkasLink").attr("href", "#");
        }
    });

    // DETAIL PANGGOL
    $(document).on("click", ".btn-detail-panggol", function () {
        const button = $(this);

        $("#detailPanggolId").text(button.data("id") || "-");

        $("#detailPanggolPangkatTitle").text(button.data("pangkat") || "-");
        $("#detailPanggolPangkat").text(button.data("pangkat") || "-");

        $("#detailPanggolGolonganText").text(
            "Golongan: " + (button.data("golongan") || "-"),
        );
        $("#detailPanggolGolongan").text(button.data("golongan") || "-");

        $("#detailPanggolTmt").text(button.data("tmt") || "-");
        $("#detailPanggolTanggal").text(button.data("tanggal") || "-");
        $("#detailPanggolStatus").text(button.data("status") || "-");
        $("#detailPanggolCatatan").text(button.data("catatan") || "—");

        $("#detailPanggolBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");

        if (fileUrl) {
            $("#detailPanggolBerkasLink").attr("href", fileUrl);
        } else {
            $("#detailPanggolBerkasLink").attr("href", "#");
        }
    });

    $(document).on("submit", ".form-hapus-surtug", function (event) {
        event.preventDefault();

        const form = this;
        const perihal = $(form).data("perihal") || "pengajuan ini";

        Swal.fire({
            title: "Hapus pengajuan?",
            text: `Pengajuan "${perihal}" akan dihapus permanen.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
            confirmButtonColor: "#c0392b",
            cancelButtonColor: "#7a8099",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

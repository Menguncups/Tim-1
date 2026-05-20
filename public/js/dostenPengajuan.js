$(document).ready(function () {
    const tableElement = $(".tabel-dosten");

    if (!tableElement.length) return;

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

        table.column(statusColumn).search(labelMap[status] || "").draw();
    });

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

        $("#detailBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");

        if (fileUrl) {
            $("#detailBerkasLink").attr("href", fileUrl);
        } else {
            $("#detailBerkasLink").attr("href", "#");
        }
    });
});
$(document).ready(function () {
    const table = $("#tabelPanggol").DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Belum ada pengajuan pangkat golongan",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada pengajuan pangkat golongan",
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

    $("#tabelPanggol_filter input").attr(
        "placeholder",
        "Cari nama, NIP, panggol..."
    );

    $(".du-pill").on("click", function () {
        $(".du-pill").removeClass("active");
        $(this).addClass("active");

        const status = $(this).data("status");

        if (status === "semua") {
            table.column(6).search("").draw();
            return;
        }

        const labelMap = {
            menunggu: "Menunggu",
            divalidasi: "Divalidasi",
            ditolak: "Ditolak",
        };

        table.column(6).search(labelMap[status] || "").draw();
    });
});
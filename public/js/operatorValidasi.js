$(document).ready(function () {
    const tableElement = $("#tabelJabfung, #tabelPanggol").first();

    if (!tableElement.length) return;

    if ($.fn.DataTable.isDataTable(tableElement)) return;

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

    $(".dataTables_filter input").attr("placeholder", "Cari pengajuan...");

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
            ditolak: "Ditolak",
        };

        table.column(statusColumn).search(labelMap[status] || "").draw();
    });

    $(".btn-detail-jabfung").on("click", function () {
        const button = $(this);

        $("#detailJabfungId").text(button.data("id") || "-");
        $("#detailJabfungNamaPegawai").text(button.data("nama") || "-");
        $("#detailJabfungEmail").text(button.data("email") || "-");
        $("#detailJabfungNip").text(button.data("nip") || "-");
        $("#detailJabfungHomebase").text(button.data("homebase") || "-");
        $("#detailJabfungNamaJabatan").text(button.data("nama-jabatan") || "-");
        $("#detailJabfungTmt").text(button.data("tmt") || "-");
        $("#detailJabfungTanggal").text(button.data("tanggal") || "-");
        $("#detailJabfungStatus").text(button.data("status") || "-");
        $("#detailJabfungCatatan").text(button.data("catatan") || "—");
        $("#detailJabfungBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");
        $("#detailJabfungBerkasLink").attr("href", fileUrl || "#");
    });

    $(".btn-detail-panggol").on("click", function () {
        const button = $(this);

        $("#detailPanggolId").text(button.data("id") || "-");
        $("#detailPanggolNamaPegawai").text(button.data("nama") || "-");
        $("#detailPanggolEmail").text(button.data("email") || "-");
        $("#detailPanggolNip").text(button.data("nip") || "-");
        $("#detailPanggolHomebase").text(button.data("homebase") || "-");
        $("#detailPanggolPangkat").text(button.data("pangkat") || "-");
        $("#detailPanggolGolongan").text(button.data("golongan") || "-");
        $("#detailPanggolTmt").text(button.data("tmt") || "-");
        $("#detailPanggolTanggal").text(button.data("tanggal") || "-");
        $("#detailPanggolStatus").text(button.data("status") || "-");
        $("#detailPanggolCatatan").text(button.data("catatan") || "—");
        $("#detailPanggolBerkas").text(button.data("berkas") || "-");

        const fileUrl = button.data("file-url");
        $("#detailPanggolBerkasLink").attr("href", fileUrl || "#");
    });

    $(".form-proses-jabfung, .form-proses-panggol").on("submit", function (event) {
        event.preventDefault();

        const form = this;
        const nama = $(form).data("nama") || "pengaju";

        Swal.fire({
            title: "Proses pengajuan?",
            text: `Pengajuan dari ${nama} akan diteruskan ke pimpinan.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, proses",
            cancelButtonText: "Batal",
            confirmButtonColor: "#27ae60",
            cancelButtonColor: "#7a8099",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $(".form-tolak-jabfung, .form-tolak-panggol").on("submit", function (event) {
        event.preventDefault();

        const form = this;
        const nama = $(form).data("nama") || "pengaju";

        Swal.fire({
            title: "Tolak pengajuan?",
            text: `Masukkan catatan penolakan untuk ${nama}.`,
            input: "textarea",
            inputPlaceholder: "Contoh: Berkas pendukung belum lengkap.",
            inputAttributes: {
                maxlength: 250,
            },
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, tolak",
            cancelButtonText: "Batal",
            confirmButtonColor: "#c0392b",
            cancelButtonColor: "#7a8099",
            inputValidator: (value) => {
                if (!value || value.trim() === "") {
                    return "Catatan penolakan wajib diisi.";
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $(form).find(".input-catatan-tolak").val(result.value.trim());
                form.submit();
            }
        });
    });
});
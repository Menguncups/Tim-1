$(document).ready(function () {
    const table = $("#tabelSurtug").DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Belum ada pengajuan surat tugas",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada pengajuan surat tugas",
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

    $("#tabelSurtug_filter input").attr(
        "placeholder",
        "Cari nama, NIP, surat..."
    );

    $(".du-pill").on("click", function () {
        $(".du-pill").removeClass("active");
        $(this).addClass("active");

        const status = $(this).data("status");

        if (status === "semua") {
            table.column(7).search("").draw();
            return;
        }

        const labelMap = {
            menunggu: "Menunggu",
            ditolak: "Ditolak",
        };

        table.column(7).search(labelMap[status] || "").draw();
    });

    $(".btn-detail-surtug").on("click", function () {
        const button = $(this);

        $("#detailId").text(button.data("id") || "-");

        $("#detailNama").text(button.data("nama") || "-");
        $("#detailNamaPegawai").text(button.data("nama") || "-");
        $("#detailEmail").text(button.data("email") || "-");
        $("#detailNip").text(button.data("nip") || "-");
        $("#detailHomebase").text(button.data("homebase") || "-");

        $("#detailNamaPengusul").text(button.data("nama-pengusul") || "-");
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

    $(".form-proses-surtug").on("submit", function (event) {
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

    $(".form-tolak-surtug").on("submit", function (event) {
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
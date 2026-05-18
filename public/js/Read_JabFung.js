$(document).ready(function () {

    $('#jabfungTable').DataTable({
        responsive: true,
        autoWidth: false,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "‹",
                next: "›"
            },
            zeroRecords: "Data tidak ditemukan"
        }
    });

});
$(document).ready(function () {

    $("#jabfungTable, #panggolTable").DataTable({
        language: {
            search: "",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total)",
            paginate: {
                first: "«",
                last: "»",
                next: "›",
                previous: "‹"
            },
            emptyTable: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
        },

        pageLength: 10,
        lengthMenu: [5,10,25,50],

        order:[[0,"asc"]],

        columnDefs:[
            {width:"55px",targets:0},
            {orderable:false,targets:[3,5]},
            {searchable:false,targets:[3,5]},
        ],

        dom:'<"du-dt-top"lf>rt<"du-dt-bot"ip>',
    });

    setTimeout(function () {
        $(".dataTables_filter input")
        .attr("placeholder","Cari data...");
    },100);

});
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Chart === "undefined") {
        return;
    }

    const data = window.dashboardOperatorData || {};

    const roleCanvas = document.getElementById("chartRole");
    const statusCanvas = document.getElementById("chartStatus");

    Chart.defaults.font = {
        family: "'Plus Jakarta Sans', sans-serif",
        size: 11,
    };

    Chart.defaults.color = "#7a8099";

    const colors = {
        red: "#b52a20",
        redLight: "#e85d4a",
        redDark: "#8c1e15",
        blue: "#1d4ed8",
        blueLight: "#60a5fa",
        green: "#16a34a",
        amber: "#d97706",
        purple: "#7c3aed",
        gray: "#c5c7d0",
    };

    if (roleCanvas) {
        new Chart(roleCanvas, {
            type: "bar",
            data: {
                labels: data.roleLabels || [],
                datasets: [
                    {
                        label: "Jumlah Pegawai",
                        data: data.roleValues || [],
                        backgroundColor: [
                            colors.blue,
                            colors.green,
                            colors.red,
                            colors.purple,
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.parsed.y} pegawai`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 10,
                            },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "#f0f2f7",
                        },
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: "doughnut",
            data: {
                labels: data.statusLabels || [],
                datasets: [
                    {
                        data: data.statusValues || [],
                        backgroundColor: [
                            colors.amber, // Menunggu
                            colors.blue,  // Diproses
                            colors.green, // Diterima
                            colors.red,   // Ditolak
                        ],
                        borderWidth: 3,
                        borderColor: "#fff",
                        hoverOffset: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "68%",
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                        labels: {
                            boxWidth: 10,
                            padding: 12,
                            font: {
                                size: 11,
                                weight: "600",
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.parsed} pengajuan`;
                            },
                        },
                    },
                },
            },
        });
    }
});
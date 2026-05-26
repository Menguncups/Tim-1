document.addEventListener("DOMContentLoaded", function () {
    if (typeof Chart === "undefined") {
        return;
    }

    const data = window.dashboardPimpinanData || {};

    Chart.defaults.font = {
        family: "'Plus Jakarta Sans', sans-serif",
        size: 11,
    };

    Chart.defaults.color = "#7a8099";
    Chart.defaults.plugins.legend.display = false;

    const colors = {
        red: "#b52a20",
        redLight: "#e85d4a",
        redDark: "#8c1e15",
        redDarker: "#5a1210",

        blue: "#1d4ed8",
        green: "#16a34a",
        amber: "#d97706",

        purple1: "#7c3aed",
        purple2: "#6d28d9",
        purple3: "#5b21b6",
        purple4: "#4c1d95",
        purple5: "#3b0764",

        gray: "#c5c7d0",
    };

    function makeBarChart(canvasId, labels, values, labelText, backgroundColor, horizontal = false) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            return;
        }

        new Chart(canvas, {
            type: "bar",
            data: {
                labels: labels || [],
                datasets: [
                    {
                        label: labelText,
                        data: values || [],
                        backgroundColor: backgroundColor,
                        borderRadius: 7,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: horizontal ? "y" : "x",
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = horizontal ? context.parsed.x : context.parsed.y;
                                return `${value} orang`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: horizontal ? true : false,
                            color: "#f0f2f7",
                        },
                        ticks: {
                            font: {
                                size: 10,
                            },
                            precision: 0,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: horizontal ? false : true,
                            color: "#f0f2f7",
                        },
                        ticks: {
                            font: {
                                size: 10,
                            },
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    function makeDoughnutChart(canvasId, labels, values, backgroundColor) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            return;
        }

        new Chart(canvas, {
            type: "doughnut",
            data: {
                labels: labels || [],
                datasets: [
                    {
                        data: values || [],
                        backgroundColor: backgroundColor,
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "70%",
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 11,
                            },
                            padding: 10,
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.parsed} orang`;
                            },
                        },
                    },
                },
            },
        });
    }

    makeBarChart(
        "chartJabfung",
        data.jabfungLabels,
        data.jabfungValues,
        "Jumlah Dosen",
        [
            colors.redLight,
            colors.red,
            colors.redDark,
            colors.redDarker,
            colors.gray,
        ]
    );

    makeBarChart(
        "chartGolongan",
        data.panggolLabels,
        data.panggolValues,
        "Jumlah Pegawai",
        colors.blue,
        true
    );

    makeDoughnutChart(
        "chartGender",
        data.genderLabels,
        data.genderValues,
        [
            colors.blue,
            colors.redLight,
        ]
    );

    makeBarChart(
        "chartUsia",
        data.usiaLabels,
        data.usiaValues,
        "Jumlah Pegawai",
        [
            colors.purple1,
            colors.purple2,
            colors.purple3,
            colors.purple4,
            colors.purple5,
        ]
    );
});
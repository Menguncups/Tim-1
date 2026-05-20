// Fallback logo FT UNRI: dipindahkan dari inline onerror HTML ke JavaScript eksternal.
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("img[data-logo-fallback]").forEach((img) => {
        img.addEventListener("error", () => {
            const fallbackType = img.dataset.logoFallback;

            if (fallbackType === "replace") {
                const fallback = document.createElement("span");
                fallback.className = "logo-fallback";
                fallback.textContent = "FT UNRI";
                img.replaceWith(fallback);
                return;
            }

            if (fallbackType === "hide") {
                img.style.display = "none";
            }
        });
    });
});

// =============================================
// Data profil — ganti dengan fetch dari database
// sesuai role/session user yang login
// =============================================
const profileData = JSON.parse(sessionStorage.getItem("datadiri") || "{}");

document.addEventListener("DOMContentLoaded", function () {
    function fmt(v) {
        return v && v.trim && v.trim() !== "" ? v : null;
    }

    const name = fmt(profileData.nama);
    const nip = fmt(profileData.nip);
    const jabfung = fmt(profileData.jabatan_fungsional);
    const homebase = fmt(profileData.homebase);
    const role = fmt(profileData.jenis_pegawai) || "Dosen Tetap";
    const foto = fmt(profileData.foto);

    document.getElementById("profileName").textContent = name || "—";
    document.getElementById("profileRole").textContent = role;
    document.getElementById("profileNip").textContent = nip || "—";
    document.getElementById("profileJabfung").textContent = jabfung || "—";
    document.getElementById("profileHomebase").textContent = homebase || "—";

    if (foto) {
        document.getElementById("profileAvatar").innerHTML =
            `<img src="${foto}" alt="Foto Profil">`;
    }
});

// =============================================
// Chart.js global defaults
// =============================================
Chart.defaults.font = { family: "'Plus Jakarta Sans', sans-serif", size: 11 };
Chart.defaults.color = "#7a8099";
Chart.defaults.plugins.legend.display = false;

// ---- Jabatan Fungsional Bar Chart ----
// Data & index aktif akan disesuaikan dari database
const jabfungLabels = ["Asisten Ahli", "Lektor", "Lektor Kepala", "Guru Besar"];
const jabfungColors = ["#e85d4a", "#b52a20", "#8c1e15", "#5a1210"];
const jabfungActive = 0; // ganti sesuai index jabatan aktif dosen dari database
const jabfungCount = [24, 52, 68, 24]; // ganti dengan data dari database

new Chart(document.getElementById("chartJabfung"), {
    type: "bar",
    data: {
        labels: jabfungLabels,
        datasets: [
            {
                data: jabfungCount,
                backgroundColor: jabfungColors.map((c, i) =>
                    i === jabfungActive ? c : c + "44",
                ),
                borderRadius: 8,
                borderSkipped: false,
                borderWidth: 0,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: (c) => `${c.parsed.y} dosen`,
                    title: (c) =>
                        c[0].label +
                        (c[0].dataIndex === jabfungActive
                            ? " ← posisi Anda"
                            : ""),
                },
            },
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { grid: { color: "#f0f2f7" }, ticks: { stepSize: 20 } },
        },
    },
});

// Legend jabfung
const lj = document.getElementById("legendJabfung");
jabfungLabels.forEach((l, i) => {
    lj.innerHTML += `<span class="legend-pill">
    <span class="legend-dot" style="background:${jabfungColors[i]}${i === jabfungActive ? "" : "44"}"></span>
    ${l}${i === jabfungActive ? ' <span style="color:var(--primary);font-weight:800">(Anda)</span>' : ""}
  </span>`;
});

// ---- Status Surat Tugas Doughnut ----
// Data akan disesuaikan dari database
const suratLabels = ["Disetujui", "Menunggu", "Ditolak", "Dalam Proses"];
const suratData = [5, 1, 1, 1]; // ganti dengan data dari database
const suratColors = ["#16a34a", "#d97706", "#dc2626", "#1d4ed8"];

new Chart(document.getElementById("chartSurat"), {
    type: "doughnut",
    data: {
        labels: suratLabels,
        datasets: [
            {
                data: suratData,
                backgroundColor: suratColors,
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
            tooltip: {
                callbacks: { label: (c) => ` ${c.label}: ${c.parsed} surat` },
            },
        },
    },
});

// Total center label plugin
const totalPlugin = {
    id: "centerText",
    afterDraw(chart) {
        if (chart.canvas.id !== "chartSurat") return;
        const {
            ctx,
            chartArea: { top, bottom, left, right },
        } = chart;
        const cx = (left + right) / 2,
            cy = (top + bottom) / 2;
        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
        ctx.save();
        ctx.font = "900 22px 'Plus Jakarta Sans', sans-serif";
        ctx.fillStyle = "#1e2235";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(total, cx, cy - 7);
        ctx.font = "600 11px 'Plus Jakarta Sans', sans-serif";
        ctx.fillStyle = "#7a8099";
        ctx.fillText("Total Surat", cx, cy + 12);
        ctx.restore();
    },
};
Chart.register(totalPlugin);

// Legend surat tugas
const ls = document.getElementById("legendSurat");
suratLabels.forEach((l, i) => {
    ls.innerHTML += `<span class="legend-pill">
    <span class="legend-dot" style="background:${suratColors[i]}"></span>
    ${l}: <b style="color:var(--text-main)">${suratData[i]}</b>
  </span>`;
});

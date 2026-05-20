document.addEventListener("DOMContentLoaded", function () {
    // Fallback logo jika gambar tidak ditemukan
    document.querySelectorAll("img[data-logo-fallback]").forEach(function (img) {
        img.addEventListener("error", function () {
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

    // Data sementara dashboard operator
    // Nanti bisa diganti dari controller Laravel
    const dashboardData = {
        totalPegawai: 124,
        totalDosen: 86,
        totalTendik: 38,
        pengajuanBaru: 8,
    };

    animateCounter("cnt-total", dashboardData.totalPegawai);
    animateCounter("cnt-dosen", dashboardData.totalDosen);
    animateCounter("cnt-tendik", dashboardData.totalTendik);
    animateCounter("cnt-pengajuan", dashboardData.pengajuanBaru);
});

function animateCounter(elementId, targetValue) {
    const element = document.getElementById(elementId);

    if (!element) {
        return;
    }

    let currentValue = 0;
    const duration = 800;
    const stepTime = 20;
    const totalSteps = duration / stepTime;
    const increment = targetValue / totalSteps;

    const counter = setInterval(function () {
        currentValue += increment;

        if (currentValue >= targetValue) {
            element.textContent = targetValue;
            clearInterval(counter);
        } else {
            element.textContent = Math.floor(currentValue);
        }
    }, stepTime);
}
// Fallback logo FT UNRI: dipindahkan dari inline onerror HTML ke JavaScript eksternal.
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('img[data-logo-fallback]').forEach((img) => {
    img.addEventListener('error', () => {
      const fallbackType = img.dataset.logoFallback;

      if (fallbackType === 'replace') {
        const fallback = document.createElement('span');
        fallback.className = 'logo-fallback';
        fallback.textContent = 'FT UNRI';
        img.replaceWith(fallback);
        return;
      }

      if (fallbackType === 'hide') {
        img.style.display = 'none';
      }
    });
  });
});

// Nilai dummy — ganti dengan data dari database
    function animateCount(el, end) { let v = 0; const step = end / 60; const t = setInterval(() => { v += step; el.textContent = Math.min(Math.round(v), end); if (v >= end) clearInterval(t) }, 1200 / 60) }
    setTimeout(() => {
      animateCount(document.getElementById('cnt-total'), 248);
      animateCount(document.getElementById('cnt-dosen'), 186);
      animateCount(document.getElementById('cnt-tendik'), 62);
      animateCount(document.getElementById('cnt-surat'), 34);
    }, 300);

    Chart.defaults.font = { family: "'Plus Jakarta Sans', sans-serif", size: 11 };
    Chart.defaults.color = '#7a8099';
    Chart.defaults.plugins.legend.display = false;

    new Chart(document.getElementById('chartJabfung'), { type: 'bar', data: { labels: ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar', 'Tanpa Jabatan'], datasets: [{ data: [24, 52, 68, 24, 18], backgroundColor: ['#e85d4a', '#b52a20', '#8c1e15', '#5a1210', '#c5c7d0'], borderRadius: 7, borderSkipped: false }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { tooltip: { callbacks: { label: c => `${c.parsed.y} dosen` } } }, scales: { x: { grid: { display: false }, ticks: { font: { size: 10 } } }, y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 20 } } } } });

    new Chart(document.getElementById('chartGolongan'), { type: 'bar', data: { labels: ['III/a-b', 'III/c-d', 'IV/a', 'IV/b', 'IV/c', 'IV/d-e'], datasets: [{ data: [18, 42, 64, 38, 22, 10], backgroundColor: '#1d4ed8', borderRadius: 6, borderSkipped: false }] }, options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { tooltip: { callbacks: { label: c => `${c.parsed.x} orang` } } }, scales: { x: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 20 } }, y: { grid: { display: false }, ticks: { font: { size: 10 } } } } } });

    new Chart(document.getElementById('chartGender'), { type: 'doughnut', data: { labels: ['Laki-laki', 'Perempuan'], datasets: [{ data: [124, 62], backgroundColor: ['#1d4ed8', '#e85d4a'], borderWidth: 0, hoverOffset: 4 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 11 }, padding: 10 } } } } });

    new Chart(document.getElementById('chartUsia'), { type: 'bar', data: { labels: ['< 30', '30–39', '40–49', '50–59', '≥ 60'], datasets: [{ data: [8, 42, 76, 48, 12], backgroundColor: ['#7c3aed', '#6d28d9', '#5b21b6', '#4c1d95', '#3b0764'], borderRadius: 6, borderSkipped: false }] }, options: { responsive: true, maintainAspectRatio: false, scales: { x: { grid: { display: false }, ticks: { font: { size: 10 } } }, y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 25, font: { size: 10 } } } } } });

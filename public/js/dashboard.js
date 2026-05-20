// dashboard.js
// Dipakai oleh dashboard dosen, tendik, pimpinan, dan operator.
// Script dibuat aman: bagian tertentu hanya berjalan kalau elemen HTML-nya ada.

(function () {
  'use strict';

  const $ = (id) => document.getElementById(id);

  function fmt(value) {
    return value && value.trim && value.trim() !== '' ? value : null;
  }

  function setText(id, value) {
    const el = $(id);
    if (el) el.textContent = value ?? '—';
  }

  function setupLogoFallback() {
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
  }

  function setupChartDefaults() {
    if (!window.Chart) return false;

    Chart.defaults.font = { family: "'Plus Jakarta Sans', sans-serif", size: 11 };
    Chart.defaults.color = '#7a8099';
    Chart.defaults.plugins.legend.display = false;
    return true;
  }

  function makeChart(canvasId, config) {
    const canvas = $(canvasId);
    if (!canvas || !window.Chart) return null;
    return new Chart(canvas, config);
  }

  function addLegend(containerId, labels, colors, data, activeIndex = null, activeText = '(Anda)') {
    const container = $(containerId);
    if (!container) return;

    container.innerHTML = '';
    labels.forEach((label, index) => {
      const muted = activeIndex !== null && index !== activeIndex ? '44' : '';
      const value = Array.isArray(data) ? `: <b style="color:var(--text-main)">${data[index]}</b>` : '';
      const active = activeIndex === index ? ` <span style="color:var(--primary);font-weight:800">${activeText}</span>` : '';

      container.innerHTML += `<span class="legend-pill">
        <span class="legend-dot" style="background:${colors[index]}${muted}"></span>
        ${label}${value}${active}
      </span>`;
    });
  }

  let centerTextRegistered = false;

  function registerCenterTextPlugin() {
    if (!window.Chart || centerTextRegistered) return;

    Chart.register({
      id: 'centerText',
      afterDraw(chart) {
        if (chart.canvas.id !== 'chartSurat') return;

        const { ctx, chartArea: { top, bottom, left, right } } = chart;
        const cx = (left + right) / 2;
        const cy = (top + bottom) / 2;
        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);

        ctx.save();
        ctx.font = "900 22px 'Plus Jakarta Sans', sans-serif";
        ctx.fillStyle = '#1e2235';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, cx, cy - 7);
        ctx.font = "600 11px 'Plus Jakarta Sans', sans-serif";
        ctx.fillStyle = '#7a8099';
        ctx.fillText('Total Surat', cx, cy + 12);
        ctx.restore();
      }
    });

    centerTextRegistered = true;
  }

  function initDosenProfile() {
    if (!$('profileNip')) return;

    const profileData = JSON.parse(sessionStorage.getItem('datadiri') || '{}');
    const name = fmt(profileData.nama);
    const nip = fmt(profileData.nip);
    const jabfung = fmt(profileData.jabatan_fungsional);
    const homebase = fmt(profileData.homebase);
    const role = fmt(profileData.jenis_pegawai) || 'Dosen Tetap';
    const foto = fmt(profileData.foto);

    setText('profileName', name || '—');
    setText('profileRole', role);
    setText('profileNip', nip || '—');
    setText('profileJabfung', jabfung || '—');
    setText('profileHomebase', homebase || '—');

    if (foto && $('profileAvatar')) {
      $('profileAvatar').innerHTML = `<img src="${foto}" alt="Foto Profil">`;
    }
  }

  function initTendikProfile() {
    if (!$('profileNik')) return;

    const profileData = JSON.parse(sessionStorage.getItem('datadiri') || '{}');
    const name = fmt(profileData.nama);
    const nik = fmt(profileData.nik);
    const golongan = fmt(profileData.golongan);
    const unit = fmt(profileData.unit_kerja);
    const role = fmt(profileData.jenis_pegawai) || 'Tenaga Kependidikan';
    const foto = fmt(profileData.foto);

    setText('profileName', name || '—');
    setText('profileRole', role);
    setText('profileNik', nik || '—');
    setText('profileGolongan', golongan || '—');
    setText('profileUnit', unit || '—');

    if (foto && $('profileAvatar')) {
      $('profileAvatar').innerHTML = `<img src="${foto}" alt="Foto Profil">`;
    }
  }

  function initDosenCharts() {
    if (!$('legendJabfung')) return;

    const jabfungLabels = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
    const jabfungColors = ['#e85d4a', '#b52a20', '#8c1e15', '#5a1210'];
    const jabfungActive = 0;
    const jabfungCount = [24, 52, 68, 24];

    makeChart('chartJabfung', {
      type: 'bar',
      data: {
        labels: jabfungLabels,
        datasets: [{
          data: jabfungCount,
          backgroundColor: jabfungColors.map((color, index) => index === jabfungActive ? color : color + '44'),
          borderRadius: 8,
          borderSkipped: false,
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: context => `${context.parsed.y} dosen`,
              title: context => context[0].label + (context[0].dataIndex === jabfungActive ? ' ← posisi Anda' : '')
            }
          }
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 } } },
          y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 20 } }
        }
      }
    });

    addLegend('legendJabfung', jabfungLabels, jabfungColors, null, jabfungActive);

    const suratLabels = ['Disetujui', 'Menunggu', 'Ditolak', 'Dalam Proses'];
    const suratData = [5, 1, 1, 1];
    const suratColors = ['#16a34a', '#d97706', '#dc2626', '#1d4ed8'];

    makeChart('chartSurat', {
      type: 'doughnut',
      data: {
        labels: suratLabels,
        datasets: [{
          data: suratData,
          backgroundColor: suratColors,
          borderWidth: 3,
          borderColor: '#fff',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          tooltip: { callbacks: { label: context => ` ${context.label}: ${context.parsed} surat` } }
        }
      }
    });

    addLegend('legendSurat', suratLabels, suratColors, suratData);
  }

  function initTendikCharts() {
    if (!$('legendGolongan')) return;

    const golonganLabels = ['Pengatur / II', 'Penata / III', 'Pembina / IV'];
    const golonganColors = ['#e85d4a', '#b52a20', '#8c1e15'];
    const golonganActive = 1;
    const golonganCount = [12, 34, 18];

    makeChart('chartGolongan', {
      type: 'bar',
      data: {
        labels: golonganLabels,
        datasets: [{
          data: golonganCount,
          backgroundColor: golonganColors.map((color, index) => index === golonganActive ? color : color + '44'),
          borderRadius: 8,
          borderSkipped: false,
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: context => `${context.parsed.y} tendik`,
              title: context => context[0].label + (context[0].dataIndex === golonganActive ? ' ← posisi Anda' : '')
            }
          }
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 } } },
          y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 10 } }
        }
      }
    });

    addLegend('legendGolongan', golonganLabels, golonganColors, null, golonganActive);

    const suratLabels = ['Disetujui', 'Menunggu', 'Ditolak', 'Dalam Proses'];
    const suratData = [3, 1, 0, 1];
    const suratColors = ['#16a34a', '#d97706', '#dc2626', '#1d4ed8'];

    makeChart('chartSurat', {
      type: 'doughnut',
      data: {
        labels: suratLabels,
        datasets: [{
          data: suratData,
          backgroundColor: suratColors,
          borderWidth: 3,
          borderColor: '#fff',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          tooltip: { callbacks: { label: context => ` ${context.label}: ${context.parsed} surat` } }
        }
      }
    });

    addLegend('legendSurat', suratLabels, suratColors, suratData);
  }

  function initPimpinanCharts() {
    if (!$('chartGender')) return;

    makeChart('chartJabfung', {
      type: 'bar',
      data: {
        labels: ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar', 'Tanpa Jabatan'],
        datasets: [{
          data: [24, 52, 68, 24, 18],
          backgroundColor: ['#e85d4a', '#b52a20', '#8c1e15', '#5a1210', '#c5c7d0'],
          borderRadius: 7,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { tooltip: { callbacks: { label: context => `${context.parsed.y} dosen` } } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 } } },
          y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 20 } }
        }
      }
    });

    makeChart('chartGolongan', {
      type: 'bar',
      data: {
        labels: ['III/a-b', 'III/c-d', 'IV/a', 'IV/b', 'IV/c', 'IV/d-e'],
        datasets: [{ data: [18, 42, 64, 38, 22, 10], backgroundColor: '#1d4ed8', borderRadius: 6, borderSkipped: false }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: { tooltip: { callbacks: { label: context => `${context.parsed.x} orang` } } },
        scales: {
          x: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 20 } },
          y: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
      }
    });

    makeChart('chartGender', {
      type: 'doughnut',
      data: {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [{ data: [124, 62], backgroundColor: ['#1d4ed8', '#e85d4a'], borderWidth: 0, hoverOffset: 4 }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 11 }, padding: 10 } } }
      }
    });

    makeChart('chartUsia', {
      type: 'bar',
      data: {
        labels: ['< 30', '30–39', '40–49', '50–59', '≥ 60'],
        datasets: [{ data: [8, 42, 76, 48, 12], backgroundColor: ['#7c3aed', '#6d28d9', '#5b21b6', '#4c1d95', '#3b0764'], borderRadius: 6, borderSkipped: false }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 } } },
          y: { grid: { color: '#f0f2f7' }, ticks: { stepSize: 25, font: { size: 10 } } }
        }
      }
    });
  }

  function animateCount(el, end) {
    if (!el) return;

    let value = 0;
    const step = end / 60;
    const timer = setInterval(() => {
      value += step;
      el.textContent = Math.min(Math.round(value), end);
      if (value >= end) clearInterval(timer);
    }, 1200 / 60);
  }

  function initCounters() {
    const counters = {
      'cnt-total': 248,
      'cnt-dosen': 186,
      'cnt-tendik': 62,
      'cnt-pengajuan': 34,
      'cnt-surat': 34,
    };

    setTimeout(() => {
      Object.entries(counters).forEach(([id, value]) => animateCount($(id), value));
    }, 300);
  }

  document.addEventListener('DOMContentLoaded', () => {
    setupLogoFallback();
    setupChartDefaults();
    registerCenterTextPlugin();

    initDosenProfile();
    initTendikProfile();
    initDosenCharts();
    initTendikCharts();
    initPimpinanCharts();
    initCounters();
  });
})();

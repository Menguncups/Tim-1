// ==========================================================================
// FILE FINAL REVISI PRODUCTION: CreateJabFung.js
// L A Y A N A N   P E N G A J U A N   J A B A T A N   F U N G S I O N A L
// F A K U L T A S   T E K N I K   U N I V E R S I T A S   R I A U
// ==========================================================================

const aturanPangkatJabfung = {
    "Asisten Ahli": ["III/b"],
    "Lektor": ["III/c", "III/d"],
    "Lektor Kepala": ["IV/a", "IV/b", "IV/c"],
    "Profesor": ["IV/d", "IV/e"]
};

const tingkatanJabfung = {
    "Asisten Ahli": 1,
    "Lektor": 2,
    "Lektor Kepala": 3,
    "Profesor": 4
};

const tingkatanPangkat = {
    "III/b": 2, "III/c": 3, "III/d": 4,
    "IV/a": 5, "IV/b": 6, "IV/c": 7, "IV/d": 8, "IV/e": 9
};


function ekstrakGolonganSistem(teks) {
    if (!teks) return "";
    const cocokan = teks.match(/(III|IV)\/[a-eA-E]/i);
    if (cocokan) {
        const bagian = cocokan[0].split('/');
        return `${bagian[0].toUpperCase()}/${bagian[1].toLowerCase()}`;
    }
    const cocokanAlternatif = teks.match(/(3|4)\s*[a-eA-E]/i);
    if (cocokanAlternatif) {
        const bersih = cocokanAlternatif[0].replace(/\s+/g, '').toLowerCase();
        const mapAlt = { 
            "3b": "III/b", "3c": "III/c", "3d": "III/d", 
            "4a": "IV/a", "4b": "IV/b", "4c": "IV/c", "4d": "IV/d", "4e": "IV/e" 
        };
        return mapAlt[bersih] || "";
    }
    return "";
}

function validasiFormatDanUkuranFileJabfung(elementId, errorId) {
    const fileInput = document.getElementById(elementId);
    const errorTarget = document.getElementById(errorId);
    
    if (!fileInput) return true;

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const tipeFile = file.type;
            const ukuranFile = file.size / 1024 / 1024;

            if (tipeFile !== "application/pdf") {
                errorTarget.innerHTML = '<small class="text-danger">Format file harus PDF!</small>';
                this.classList.add("is-invalid");
                this.value = "";
                return false;
            } else if (ukuranFile > 5) {
                errorTarget.innerHTML = '<small class="text-danger">Ukuran file maksimal 5MB!</small>';
                this.classList.add("is-invalid");
                this.value = "";
                return false;
            } else {
                errorTarget.innerHTML = '<small class="text-success"><i class="bi bi-check-circle-fill"></i> Berkas PDF siap.</small>';
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
                return true;
            }
        }
    });
}

function konfirmasiSimpanJabfung() {
    let statusValid = true;

    const namaJabatan = document.getElementById("nama_jabatan");
    const errorJabatan = document.getElementById("error_jabatan");
    if (!namaJabatan || !namaJabatan.value || namaJabatan.value.startsWith("--")) {
        if (errorJabatan) errorJabatan.textContent = "Silakan pilih jabatan fungsional baru!";
        namaJabatan.classList.add("is-invalid");
        statusValid = false;
    } else {
        if (errorJabatan) errorJabatan.textContent = "";
        namaJabatan.classList.remove("is-invalid");
    }

    const tmt = document.getElementById("tmt");
    const errorTmt = document.getElementById("error_tmt");
    if (!tmt || !tmt.value) {
        if (errorTmt) errorTmt.textContent = "Tanggal mulai berlaku (TMT) wajib diisi!";
        tmt.classList.add("is-invalid");
        statusValid = false;
    } else {
        if (errorTmt) errorTmt.textContent = "";
        tmt.classList.remove("is-invalid");
    }

    const berkasWajib = [
        { id: "dokumen_sk_cpns", err: "error_dokumen_sk_cpns" },
        { id: "dokumen_sk_pns", err: "error_dokumen_sk_pns" },
        { id: "dokumen_pak", err: "error_dokumen_pak" },
        { id: "dokumen_publikasi_ilmiah", err: "error_dokumen_publikasi_ilmiah" }
    ];

    berkasWajib.forEach(item => {
        const input = document.getElementById(item.id);
        const errEl = document.getElementById(item.err);
        if (!input || !input.files[0]) {
            if (errEl) errEl.innerHTML = '<small class="text-danger">File wajib diunggah!</small>';
            if (input) input.classList.add("is-invalid");
            statusValid = false;
        }
    });

    if (!statusValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Harap mengisi semua kolom bertanda (*) dan periksa kelayakan file dokumen Anda!',
            confirmButtonColor: '#b52a20'
        });
        return false;
    }

    const inputJabfungSekarang = document.getElementById("jabatan_sekarang");
    const inputPangkatSekarang = document.getElementById("pangkat_sekarang") || document.getElementById("golongan_sekarang");

    if (inputJabfungSekarang && inputPangkatSekarang && namaJabatan) {
        const jabfungSekarang = inputJabfungSekarang.value.trim();
        const pangkatSekarang = ekstrakGolonganSistem(inputPangkatSekarang.value);
        const jabfungBaruPilihan = namaJabatan.value.trim();

        const skorSekarang = tingkatanJabfung[jabfungSekarang] || 0;
        const skorBaru = tingkatanJabfung[jabfungBaruPilihan] || 0;
        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        if (skorBaru <= skorSekarang && skorSekarang > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Ditolak',
                text: 'Sistem menolak pengisian riwayat jabatan di bawah posisi aktif Anda saat ini!',
                confirmButtonColor: '#b52a20'
            });
            return false;
        }

        const daftarPangkatValid = aturanPangkatJabfung[jabfungBaruPilihan];
        if (daftarPangkatValid) {
            const pangkatMinValid = daftarPangkatValid[0];
            const skorPangkatMinValid = tingkatanPangkat[pangkatMinValid] || 0;

            const isPangkatSesuaiRentang = daftarPangkatValid.includes(pangkatSekarang);
            const isPangkatTepatSatuTingkatDiBawah = (skorPangkatSekarang === (skorPangkatMinValid - 1));

            if (!isPangkatSesuaiRentang && !isPangkatTepatSatuTingkatDiBawah) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pangkat Belum Memenuhi Syarat',
                    text: `Untuk mengajukan ke jabatan "${jabfungBaruPilihan}", pangkat/golongan Anda saat ini terlalu rendah.`,
                    confirmButtonColor: '#b52a20'
                });
                return false;
            }
        }
    }

    // Pop-up Konfirmasi
    Swal.fire({
        title: 'Konfirmasi Simpan',
        text: 'Apakah Anda yakin data riwayat jabatan fungsional yang dimasukkan sudah benar dan siap diajukan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754', 
        cancelButtonColor: '#6c757d',  
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true 
    }).then((result) => {
        if (result.isConfirmed) {
            
            Swal.fire({ 
                title: 'Memproses Data...', 
                text: 'Mohon tunggu, berkas pengajuan sedang diunggah ke server.',
                allowOutsideClick: false, 
                didOpen: () => { Swal.showLoading(); } 
            });
            
            const formHtml = document.getElementById("formJabfung");
            const formData = new FormData(formHtml);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // PERBAIKAN UTAMA: Menggunakan rute absolut /dosen/... agar tidak berlipat ganda
            fetch('/dosen/pengajuan/jabfung/store', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Berhasil', 
                        text: data.message,
                        confirmButtonColor: '#198754'
                    }).then(() => { 
                        // PERBAIKAN KEDUA: Dialihkan kembali ke rute indeks dosen yang benar
                        window.location.href = "/dosen/pengajuan/jabfung"; 
                    });
                } else {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Gagal', 
                        text: data.message,
                        confirmButtonColor: '#b52a20'
                    });
                }
            })
            .catch(() => { 
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: 'Gagal terhubung ke server. Pastikan ukuran file berkas tidak melebihi 5MB.',
                    confirmButtonColor: '#b52a20'
                }); 
            });
        }
    });
}

function konfirmasiBatalJabfung() {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Perubahan data yang belum disimpan akan hilang!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', 
        cancelButtonColor: '#6c757d',  
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/dosen/pengajuan/jabfung";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    validasiFormatDanUkuranFileJabfung("dokumen_sk_cpns", "error_dokumen_sk_cpns");
    validasiFormatDanUkuranFileJabfung("dokumen_sk_pns", "error_dokumen_sk_pns");
    validasiFormatDanUkuranFileJabfung("dokumen_pak", "error_dokumen_pak");
    validasiFormatDanUkuranFileJabfung("dokumen_publikasi_ilmiah", "error_dokumen_publikasi_ilmiah");

    function proteksiDropdownJabfung() {
        const dropdownJabfung = document.getElementById("nama_jabatan");
        const inputJabfungSekarang = document.getElementById("jabatan_sekarang");
        const inputPangkatSekarang = document.getElementById("pangkat_sekarang") || document.getElementById("golongan_sekarang") || document.getElementById("pangkat_golongan");

        if (!dropdownJabfung || !inputJabfungSekarang || !inputPangkatSekarang) return;

        const jabfungSekarang = inputJabfungSekarang.value.trim(); 
        const pangkatSekarang = ekstrakGolonganSistem(inputPangkatSekarang.value || inputPangkatSekarang.innerText);

        const pilihanOpsi = dropdownJabfung.options;
        const skorJabfungSekarang = tingkatanJabfung[jabfungSekarang] || 0;
        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        for (let i = 0; i < pilihanOpsi.length; i++) {
            const nilaiJabfung = pilihanOpsi[i].value.trim();
            if (nilaiJabfung === "" || nilaiJabfung.startsWith("--")) continue;

            const skorJabfungTarget = tingkatanJabfung[nilaiJabfung] || 0;
            const teksAsli = pilihanOpsi[i].text.split(" (")[0];

            pilihanOpsi[i].disabled = false;
            pilihanOpsi[i].text = teksAsli;

            if (skorJabfungTarget <= skorJabfungSekarang && skorJabfungSekarang > 0) {
                pilihanOpsi[i].disabled = true;
                pilihanOpsi[i].text = (nilaiJabfung === jabfungSekarang) ? `${teksAsli} (Jabatan Anda Saat Ini)` : `${teksAsli} (Tidak boleh memilih di bawah posisi saat ini)`;
                continue;
            }

            const pangkatValid = aturanPangkatJabfung[nilaiJabfung];
            if (pangkatValid) {
                const pangkatMinValid = pangkatValid[0];
                const skorPangkatMinValid = tingkatanPangkat[pangkatMinValid] || 0;

                const isPangkatSesuaiRentang = pangkatValid.includes(pangkatSekarang);
                const isPangkatTepatSatuTingkatDiBawah = (skorPangkatSekarang > 0 && skorPangkatSekarang === (skorPangkatMinValid - 1));

                if (!isPangkatSesuaiRentang && !isPangkatTepatSatuTingkatDiBawah) {
                    pilihanOpsi[i].disabled = true;
                    pilihanOpsi[i].text = `${teksAsli} (Pangkat Anda belum memenuhi syarat)`;
                }
            }
        }
    }

    proteksiDropdownJabfung();

    const inputTmt = document.getElementById("tmt");
    if (inputTmt) {
        inputTmt.addEventListener("click", function () {
            try { 
                this.showPicker(); 
            } catch (e) { 
                console.log("Browser tidak mendukung showPicker() otomatis."); 
            }
        });
    }

    const submitBtn = document.getElementById("submit");
    if (submitBtn) {
        submitBtn.addEventListener("click", function (e) {
            e.preventDefault(); 
            konfirmasiSimpanJabfung(); 
        });
    }

    const batalBtn = document.getElementById("btnBatal");
    if (batalBtn) {
        batalBtn.addEventListener("click", function (e) {
            e.preventDefault();
            konfirmasiBatalJabfung();
        });
    }
});
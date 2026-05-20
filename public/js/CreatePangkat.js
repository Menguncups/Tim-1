// ==========================================================================
// FILE PRODUKSI FINAL REVISI: CreatePangkat.js
// L A Y A N A N   P E N G A J U A N   P A N G K A T   G O L O N G A N
// F A K U L T A S   T E K N I K   U N I V E R S I T A S   R I A U
// ==========================================================================

const tingkatanPangkat = {
    "III/a": 1, "III/b": 2, "III/c": 3, "III/d": 4,
    "IV/a": 5, "IV/b": 6, "IV/c": 7, "IV/d": 8, "IV/e": 9
};

const aturanPangkatJabfung = {
    "Asisten Ahli": ["III/a", "III/b"],
    "Lektor": ["III/c", "III/d"],
    "Lektor Kepala": ["IV/a", "IV/b", "IV/c"],
    "Profesor": ["IV/d", "IV/e"]
};

/**
 * Mengekstrak kode golongan (Contoh: III/c atau IV/a) menggunakan Regular Expression.
 */
function ekstrakGolonganPintar(teks) {
    if (!teks) return "";
    const cocok = teks.match(/(III|IV)\/[a-e]/i);
    return cocok ? cocok[0].trim() : "";
}

/**
 * Mengelola validasi kelengkapan berkas, logika aturan pangkat, 
 * pop-up konfirmasi, hingga pengiriman AJAX FormData.
 */
function konfirmasiSimpan() {
    const inputs = [
        { id: "pangkat_baru", errorId: "error_pangkat", msg: "Silakan pilih pangkat/golongan baru!" },
        { id: "tmt", errorId: "error_tmt", msg: "Tanggal mulai berlaku (TMT) wajib diisi!" }
    ];

    const files = [
        { id: "dokumen_sk_cpns", errorId: "error_dokumen_sk_cpns" },
        { id: "dokumen_sk_pns", errorId: "error_dokumen_sk_pns" },
        { id: "dokumen_pak", errorId: "error_dokumen_pak" },
        { id: "dokumen_publikasi_ilmiah", errorId: "error_dokumen_publikasi_ilmiah" }
    ];

    let dataLengkap = true;

    // 1. Validasi Kolom Input Wajib (Teks & Select)
    inputs.forEach(input => {
        const el = document.getElementById(input.id);
        const errEl = document.getElementById(input.errorId);
        if (!el || !el.value.trim() || el.value.startsWith("--")) {
            if (errEl) errEl.textContent = input.msg;
            if (el) el.classList.add("is-error");
            dataLengkap = false;
        } else {
            if (errEl) errEl.textContent = "";
            if (el) el.classList.remove("is-error");
        }
    });

    // 2. Validasi Lampiran File PDF Wajib
    files.forEach(file => {
        const el = document.getElementById(file.id);
        const errEl = document.getElementById(file.errorId);
        if (!el || !el.files[0]) {
            if (errEl) errEl.innerHTML = '<small class="text-danger">File wajib diunggah!</small>';
            if (el) el.classList.add("is-error");
            dataLengkap = false;
        }
    });

    if (!dataLengkap) {
        Swal.fire({
            icon: 'warning',
            title: 'Data belum lengkap',
            text: 'Harap mengisi semua kolom bertanda (*) dan periksa kembali file dokumen Anda!',
            confirmButtonColor: '#b52a20'
        });
        return false; 
    }

    // 3. Validasi Logika Kenaikan Posisi (Aturan Kepegawaian)
    const inputPangkatSekarang = document.getElementById("pangkat_sekarang") || document.getElementById("golongan_sekarang");
    const dropdownPangkatBaru = document.getElementById("pangkat_baru");
    const inputJabatanSekarang = document.getElementById("jabatan_sekarang");

    if (inputPangkatSekarang && dropdownPangkatBaru) {
        const pangkatSekarang = ekstrakGolonganPintar(inputPangkatSekarang.value);
        const pangkatBaruPilihan = ekstrakGolonganPintar(dropdownPangkatBaru.value);
        const jabatanSekarang = inputJabatanSekarang ? inputJabatanSekarang.value.trim() : "";

        const skorSekarang = tingkatanPangkat[pangkatSekarang] || 0;
        const skorBaru = tingkatanPangkat[pangkatBaruPilihan] || 0;

        // Proteksi: Pangkat sama
        if (pangkatBaruPilihan === pangkatSekarang) {
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Ditolak',
                text: `Anda saat ini sudah berada di golongan ${pangkatSekarang}. Silakan pilih jenjang pangkat di atasnya!`,
                confirmButtonColor: '#b52a20'
            });
            dropdownPangkatBaru.classList.add("is-error");
            return false;
        }

        // Proteksi: Menurunkan pangkat
        if (skorBaru < skorSekarang) {
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Ditolak',
                text: 'Sistem menolak pengisian riwayat pangkat di bawah posisi aktif Anda saat ini!',
                confirmButtonColor: '#b52a20'
            });
            dropdownPangkatBaru.classList.add("is-error");
            return false;
        }

        // KUNCI UTAMA: Mencegah pelompatan jenjang (Wajib naik tepat 1 tingkat di atasnya)
        if (skorBaru > (skorSekarang + 1)) {
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Ditolak',
                text: 'Sistem menolak pelompatan jenjang pangkat. Anda harus memilih golongan satu tingkat di atas posisi aktif Anda!',
                confirmButtonColor: '#b52a20'
            });
            dropdownPangkatBaru.classList.add("is-error");
            return false;
        }

        // Validasi kecocokan pangkat terhadap Jabatan Fungsional aktif
        if (jabatanSekarang && aturanPangkatJabfung[jabatanSekarang]) {
            const listValid = aturanPangkatJabfung[jabatanSekarang];
            const pangkatMaxValid = listValid[listValid.length - 1]; 
            const skorPangkatMaxValid = tingkatanPangkat[pangkatMaxValid] || 0;

            const isPangkatValidDiJabfungSama = listValid.includes(pangkatBaruPilihan);
            const isNaikPangkatAntarJabfung = (skorBaru === (skorPangkatMaxValid + 1));

            if (!isPangkatValidDiJabfungSama && !isNaikPangkatAntarJabfung) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pangkat Tidak Sesuai',
                    text: `Sebagai ${jabatanSekarang}, Anda tidak dapat memilih golongan ${pangkatBaruPilihan}. Pangkat terlalu tinggi, Anda harus mengajukan kenaikan Jabatan Fungsional terlebih dahulu.`,
                    confirmButtonColor: '#b52a20'
                });
                dropdownPangkatBaru.classList.add("is-error");
                return false;
            }
        }
    }

    // 4. Pop-up Pertanyaan Konfirmasi Sebelum Menyimpan
    Swal.fire({
        title: 'Konfirmasi Simpan',
        text: 'Apakah Anda yakin data riwayat pangkat yang dimasukkan sudah benar dan siap diajukan?',
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
                title: 'Sedang Menyimpan...',
                text: 'Mohon tunggu berkas PDF Anda sedang diunggah ke server.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const formElement = document.getElementById("formPangkat"); 
            const formData = new FormData(formElement);

            if (dropdownPangkatBaru && dropdownPangkatBaru.value) {
                let splitData = dropdownPangkatBaru.value.split('-');
                if (splitData.length >= 2) {
                    formData.set('pangkat', splitData[0].trim());  
                    formData.set('golongan', splitData[1].trim()); 
                }
            }

            const csrfToken = document.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/dosen/pengajuan/panggol/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        confirmButtonColor: '#198754'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/dosen/pengajuan/panggol"; 
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: data.message || 'Terjadi kesalahan pada sistem backend.',
                        confirmButtonColor: '#b52a20'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Gagal terhubung ke server. Pastikan ukuran file berkas tidak melebihi 5MB.',
                    confirmButtonColor: '#b52a20'
                });
            });
        }
    });
}

function konfirmasiBatal() {
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
            window.location.href = "/dosen/pengajuan/panggol";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {

    // A. Proteksi Dinamis Pilihan Dropdown Pangkat Baru
    function proteksiDropdownPangkat() {
        const dropdownPangkat = document.getElementById("pangkat_baru");
        const inputPangkatSekarang = document.getElementById("pangkat_sekarang") || document.getElementById("golongan_sekarang");
        const inputJabatanSekarang = document.getElementById("jabatan_sekarang");

        if (!dropdownPangkat || !inputPangkatSekarang) return;

        const pangkatSekarang = ekstrakGolonganPintar(inputPangkatSekarang.value);
        const jabatanSekarang = inputJabatanSekarang ? inputJabatanSekarang.value.trim() : "";

        const pilihanOpsi = dropdownPangkat.options;
        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        for (let i = 0; i < pilihanOpsi.length; i++) {
            let valueRaw = pilihanOpsi[i].value.trim();
            if (valueRaw === "" || valueRaw.startsWith("--")) continue;

            let nilaiPangkat = ekstrakGolonganPintar(valueRaw);
            const skorPangkatTarget = tingkatanPangkat[nilaiPangkat] || 0;
            
            let teksAsli = pilihanOpsi[i].text.split(" (")[0]; 
            if(teksAsli.includes("-")) {
                let part = teksAsli.split("-");
                teksAsli = `${part[0].trim()} - ${part[1].trim()}`;
            }

            pilihanOpsi[i].disabled = false;
            pilihanOpsi[i].text = teksAsli;

            // 1. Kunci pangkat yang sama
            if (nilaiPangkat === pangkatSekarang) {
                pilihanOpsi[i].disabled = true;
                pilihanOpsi[i].text = `${teksAsli} (Pangkat Anda Saat Ini)`;
                continue;
            }

            // 2. Kunci pangkat di bawah aktif
            if (skorPangkatTarget < skorPangkatSekarang) {
                pilihanOpsi[i].disabled = true;
                pilihanOpsi[i].text = `${teksAsli} (Tidak boleh di bawah pangkat saat ini)`;
                continue;
            }

            // 3. KUNCI UTAMA DI DROPDOWN: Blokir opsi jika melompat tingkat (skor melebihi tingkatan + 1)
            if (skorPangkatTarget > (skorPangkatSekarang + 1)) {
                pilihanOpsi[i].disabled = true;
                pilihanOpsi[i].text = `${teksAsli} (Harus melewati jenjang pangkat sebelumnya)`;
                continue;
            }

            // 4. Aturan relasi Jabatan Fungsional
            if (jabatanSekarang && aturanPangkatJabfung[jabatanSekarang]) {
                const listPangkatValid = aturanPangkatJabfung[jabatanSekarang];
                const pangkatMaxValid = listPangkatValid[listPangkatValid.length - 1]; 
                const skorPangkatMaxValid = tingkatanPangkat[pangkatMaxValid] || 0;

                const isPangkatSesuai = listPangkatValid.includes(nilaiPangkat);
                const isNaikPangkatAntarJabfung = (skorPangkatTarget === (skorPangkatMaxValid + 1));

                if (!isPangkatSesuai && !isNaikPangkatAntarJabfung) {
                    pilihanOpsi[i].disabled = true;
                    pilihanOpsi[i].text = `${teksAsli} (Harus naik Jabatan Fungsional terlebih dahulu)`;
                }
            }
        }
    }

    proteksiDropdownPangkat();

    // B. Fitur Klik Kolom TMT Langsung Buka Kalender Bawaan
    const inputTmt = document.getElementById("tmt");
    if (inputTmt) {
        inputTmt.addEventListener("click", function () {
            try { 
                this.showPicker(); 
            } catch (e) { 
                console.log("showPicker() tidak didukung."); 
            }
        });
    }

    // C. Validasi Realtime via Blur & File Upload
    function pasangValidasiBlur(elementId, errorId, errorMsg) {
        const inputEl = document.getElementById(elementId);
        if (inputEl) {
            inputEl.addEventListener("blur", function () {
                const errEl = document.getElementById(errorId);
                if (this.value === "" || this.value.startsWith("--")) {
                    if (errEl) errEl.textContent = errorMsg;
                    this.classList.add("is-error");
                } else {
                    if (errEl) errEl.textContent = "";
                    this.classList.remove("is-error");
                }
            });
        }
    }
    pasangValidasiBlur("pangkat_baru", "error_pangkat", "Silakan pilih pangkat/golongan baru!");
    pasangValidasiBlur("tmt", "error_tmt", "Tanggal mulai berlaku (TMT) wajib diisi!");

    function pasangValidasiFile(elementId, errorId) {
        const fileInput = document.getElementById(elementId);
        if (fileInput) {
            fileInput.addEventListener("change", function () {
                const file = this.files[0];
                const errorEl = document.getElementById(errorId);
                if (!file) return;
                if (file.type !== "application/pdf") {
                    if (errorEl) errorEl.innerHTML = '<small class="text-danger">Format file salah! Harus PDF.</small>';
                    this.classList.add("is-error");
                    this.value = ""; 
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    if (errorEl) errorEl.innerHTML = '<small class="text-danger">Ukuran file terlalu besar! Maksimal 5MB.</small>';
                    this.classList.add("is-error");
                    this.value = ""; 
                    return;
                }
                this.classList.remove("is-error");
                if (errorEl) errorEl.innerHTML = '<small class="text-success"><i class="bi bi-check-circle-fill"></i> Berkas PDF siap.</small>';
            });
        }
    }
    pasangValidasiFile("dokumen_sk_cpns", "error_dokumen_sk_cpns");
    pasangValidasiFile("dokumen_sk_pns", "error_dokumen_sk_pns");
    pasangValidasiFile("dokumen_pak", "error_dokumen_pak");
    pasangValidasiFile("dokumen_publikasi_ilmiah", "error_dokumen_publikasi_ilmiah");

    // D. Pengikatan Aksi Tombol (Opsi 2 & Tombol Batal)
    const tombolSubmit = document.getElementById("submit");
    if (tombolSubmit) {
        tombolSubmit.addEventListener("click", function (e) {
            e.preventDefault(); 
            konfirmasiSimpan(); 
        });
    }

    const batalBtn = document.getElementById("btnBatal");
    if (batalBtn) {
        batalBtn.addEventListener("click", function (e) {
            e.preventDefault();
            konfirmasiBatal(); 
        });
    }
});
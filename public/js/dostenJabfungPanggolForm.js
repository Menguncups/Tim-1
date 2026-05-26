document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form-pengajuan");

    if (!form) {
        return;
    }

    const formType = form.dataset.formType;
    const submitBtn = document.querySelector(".btn-submit-pengajuan");

    const aturanPangkatJabfung = {
        "Asisten Ahli": ["III/a", "III/b"],
        "Lektor": ["III/c", "III/d"],
        "Lektor Kepala": ["IV/a", "IV/b", "IV/c"],
        "Guru Besar": ["IV/d", "IV/e"],
        "Profesor": ["IV/d", "IV/e"],
    };

    const tingkatanJabfung = {
        "Tenaga Pengajar": 0,
        "Asisten Ahli": 1,
        "Lektor": 2,
        "Lektor Kepala": 3,
        "Guru Besar": 4,
        "Profesor": 4,
    };

    const tingkatanPangkat = {
        "III/a": 1,
        "III/b": 2,
        "III/c": 3,
        "III/d": 4,
        "IV/a": 5,
        "IV/b": 6,
        "IV/c": 7,
        "IV/d": 8,
        "IV/e": 9,
    };

    function ekstrakGolongan(teks) {
        if (!teks) return "";

        const cocok = teks.match(/(III|IV)\/[a-eA-E]/i);

        if (cocok) {
            const bagian = cocok[0].split("/");
            return `${bagian[0].toUpperCase()}/${bagian[1].toLowerCase()}`;
        }

        const cocokAlternatif = teks.match(/(3|4)\s*[a-eA-E]/i);

        if (cocokAlternatif) {
            const bersih = cocokAlternatif[0].replace(/\s+/g, "").toLowerCase();

            const mapAlt = {
                "3a": "III/a",
                "3b": "III/b",
                "3c": "III/c",
                "3d": "III/d",
                "4a": "IV/a",
                "4b": "IV/b",
                "4c": "IV/c",
                "4d": "IV/d",
                "4e": "IV/e",
            };

            return mapAlt[bersih] || "";
        }

        return "";
    }

    function setError(input, errorId, message) {
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        error.textContent = message;

        if (message) {
            input.classList.add("is-error");
            input.classList.add("is-invalid");
        } else {
            input.classList.remove("is-error");
            input.classList.remove("is-invalid");
        }
    }

    function validateRequired(id, errorId, message) {
        const input = document.getElementById(id);

        if (!input || !input.value || input.value.trim() === "") {
            setError(input, errorId, message);
            return false;
        }

        setError(input, errorId, "");
        return true;
    }

    function validateFile() {
        const berkas = document.getElementById("berkas_pendukung");

        if (!berkas) {
            return true;
        }

        const file = berkas.files[0];

        if (!file) {
            setError(
                berkas,
                "error_berkas_pendukung",
                "Berkas pendukung wajib diunggah."
            );
            return false;
        }

        const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];

        if (!allowedTypes.includes(file.type)) {
            setError(
                berkas,
                "error_berkas_pendukung",
                "Berkas harus PDF, JPG, JPEG, atau PNG."
            );

            berkas.value = "";
            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            setError(
                berkas,
                "error_berkas_pendukung",
                "Ukuran berkas maksimal 2 MB."
            );

            berkas.value = "";
            return false;
        }

        setError(berkas, "error_berkas_pendukung", "");
        return true;
    }

    function splitPangkatGolongan() {
        const pangkatBaru = document.getElementById("pangkat_baru");
        const pangkatInput = document.getElementById("pangkat");
        const golonganInput = document.getElementById("golongan");

        if (!pangkatBaru || !pangkatBaru.value) {
            return false;
        }

        const parts = pangkatBaru.value.split("-");

        if (parts.length < 2) {
            return false;
        }

        if (pangkatInput) {
            pangkatInput.value = parts[0].trim();
        }

        if (golonganInput) {
            golonganInput.value = parts[1].trim();
        }

        return true;
    }

    function proteksiDropdownJabfung() {
        const dropdownJabfung = document.getElementById("nama_jabatan");
        const jabatanSekarangInput = document.getElementById("jabatan_sekarang");
        const pangkatSekarangInput = document.getElementById("pangkat_sekarang");

        if (!dropdownJabfung || !jabatanSekarangInput || !pangkatSekarangInput) {
            return;
        }

        const jabfungSekarang = jabatanSekarangInput.value.trim();
        const pangkatSekarang = ekstrakGolongan(pangkatSekarangInput.value);

        const skorJabfungSekarang = tingkatanJabfung[jabfungSekarang] || 0;
        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        Array.from(dropdownJabfung.options).forEach(function (option) {
            const jabfungTarget = option.value.trim();

            if (!jabfungTarget) {
                return;
            }

            const teksAsli = option.text.split(" (")[0];
            const skorJabfungTarget = tingkatanJabfung[jabfungTarget] || 0;

            option.disabled = false;
            option.text = teksAsli;

            if (skorJabfungTarget <= skorJabfungSekarang && skorJabfungSekarang > 0) {
                option.disabled = true;

                option.text =
                    jabfungTarget === jabfungSekarang
                        ? `${teksAsli} (Jabatan Anda Saat Ini)`
                        : `${teksAsli} (Tidak boleh di bawah posisi saat ini)`;

                return;
            }

            const pangkatValid = aturanPangkatJabfung[jabfungTarget];

            if (pangkatValid) {
                const pangkatMinValid = pangkatValid[0];
                const skorPangkatMinValid = tingkatanPangkat[pangkatMinValid] || 0;

                const isPangkatSesuaiRentang = pangkatValid.includes(pangkatSekarang);
                const isPangkatTepatSatuTingkatDiBawah =
                    skorPangkatSekarang > 0 &&
                    skorPangkatSekarang === skorPangkatMinValid - 1;

                if (!isPangkatSesuaiRentang && !isPangkatTepatSatuTingkatDiBawah) {
                    option.disabled = true;
                    option.text = `${teksAsli} (Pangkat belum memenuhi syarat)`;
                }
            }
        });
    }

    function proteksiDropdownPangkat() {
        const dropdownPangkat = document.getElementById("pangkat_baru");
        const pangkatSekarangInput = document.getElementById("pangkat_sekarang");
        const jabatanSekarangInput = document.getElementById("jabatan_sekarang");

        if (!dropdownPangkat || !pangkatSekarangInput) {
            return;
        }

        const pangkatSekarang = ekstrakGolongan(pangkatSekarangInput.value);
        const jabatanSekarang = jabatanSekarangInput
            ? jabatanSekarangInput.value.trim()
            : "";

        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        Array.from(dropdownPangkat.options).forEach(function (option) {
            const valueRaw = option.value.trim();

            if (!valueRaw) {
                return;
            }

            const teksAsli = option.text.split(" (")[0];
            const pangkatTarget = ekstrakGolongan(valueRaw);
            const skorTarget = tingkatanPangkat[pangkatTarget] || 0;

            option.disabled = false;
            option.text = teksAsli;

            if (pangkatTarget === pangkatSekarang) {
                option.disabled = true;
                option.text = `${teksAsli} (Pangkat Anda Saat Ini)`;
                return;
            }

            if (skorTarget < skorPangkatSekarang) {
                option.disabled = true;
                option.text = `${teksAsli} (Tidak boleh di bawah pangkat saat ini)`;
                return;
            }

            if (skorTarget > skorPangkatSekarang + 1) {
                option.disabled = true;
                option.text = `${teksAsli} (Harus naik bertahap)`;
                return;
            }

            if (jabatanSekarang && aturanPangkatJabfung[jabatanSekarang]) {
                const listValid = aturanPangkatJabfung[jabatanSekarang];
                const pangkatMaxValid = listValid[listValid.length - 1];
                const skorPangkatMaxValid = tingkatanPangkat[pangkatMaxValid] || 0;

                const isPangkatSesuai = listValid.includes(pangkatTarget);
                const isNaikPangkatAntarJabfung =
                    skorTarget === skorPangkatMaxValid + 1;

                if (!isPangkatSesuai && !isNaikPangkatAntarJabfung) {
                    option.disabled = true;
                    option.text = `${teksAsli} (Harus naik jabfung terlebih dahulu)`;
                }
            }
        });
    }

    function validateLogicJabfung() {
        const namaJabatan = document.getElementById("nama_jabatan");
        const jabatanSekarangInput = document.getElementById("jabatan_sekarang");
        const pangkatSekarangInput = document.getElementById("pangkat_sekarang");

        if (!namaJabatan || !jabatanSekarangInput || !pangkatSekarangInput) {
            return true;
        }

        const jabfungSekarang = jabatanSekarangInput.value.trim();
        const pangkatSekarang = ekstrakGolongan(pangkatSekarangInput.value);
        const jabfungBaru = namaJabatan.value.trim();

        const skorSekarang = tingkatanJabfung[jabfungSekarang] || 0;
        const skorBaru = tingkatanJabfung[jabfungBaru] || 0;
        const skorPangkatSekarang = tingkatanPangkat[pangkatSekarang] || 0;

        if (skorBaru <= skorSekarang && skorSekarang > 0) {
            Swal.fire({
                icon: "error",
                title: "Pengajuan Ditolak",
                text: "Tidak boleh mengajukan jabatan fungsional yang sama atau di bawah posisi aktif saat ini.",
                confirmButtonColor: "#b52a20",
            });

            return false;
        }

        const daftarPangkatValid = aturanPangkatJabfung[jabfungBaru];

        if (daftarPangkatValid) {
            const pangkatMinValid = daftarPangkatValid[0];
            const skorPangkatMinValid = tingkatanPangkat[pangkatMinValid] || 0;

            const isPangkatSesuaiRentang = daftarPangkatValid.includes(pangkatSekarang);
            const isPangkatTepatSatuTingkatDiBawah =
                skorPangkatSekarang === skorPangkatMinValid - 1;

            if (!isPangkatSesuaiRentang && !isPangkatTepatSatuTingkatDiBawah) {
                Swal.fire({
                    icon: "error",
                    title: "Pangkat Belum Memenuhi Syarat",
                    text: `Untuk mengajukan ke jabatan "${jabfungBaru}", pangkat/golongan Anda saat ini belum memenuhi syarat.`,
                    confirmButtonColor: "#b52a20",
                });

                return false;
            }
        }

        return true;
    }

    function validateLogicPanggol() {
        const pangkatBaru = document.getElementById("pangkat_baru");
        const pangkatSekarangInput = document.getElementById("pangkat_sekarang");
        const jabatanSekarangInput = document.getElementById("jabatan_sekarang");

        if (!pangkatBaru || !pangkatSekarangInput) {
            return true;
        }

        const pangkatSekarang = ekstrakGolongan(pangkatSekarangInput.value);
        const pangkatBaruPilihan = ekstrakGolongan(pangkatBaru.value);
        const jabatanSekarang = jabatanSekarangInput
            ? jabatanSekarangInput.value.trim()
            : "";

        const skorSekarang = tingkatanPangkat[pangkatSekarang] || 0;
        const skorBaru = tingkatanPangkat[pangkatBaruPilihan] || 0;

        if (pangkatBaruPilihan === pangkatSekarang) {
            Swal.fire({
                icon: "error",
                title: "Pengajuan Ditolak",
                text: `Anda saat ini sudah berada di golongan ${pangkatSekarang}. Pilih jenjang di atasnya.`,
                confirmButtonColor: "#b52a20",
            });

            return false;
        }

        if (skorBaru < skorSekarang) {
            Swal.fire({
                icon: "error",
                title: "Pengajuan Ditolak",
                text: "Tidak boleh mengajukan pangkat di bawah posisi aktif saat ini.",
                confirmButtonColor: "#b52a20",
            });

            return false;
        }

        if (skorBaru > skorSekarang + 1) {
            Swal.fire({
                icon: "error",
                title: "Pengajuan Ditolak",
                text: "Tidak boleh melompati jenjang pangkat. Pilih satu tingkat di atas posisi aktif.",
                confirmButtonColor: "#b52a20",
            });

            return false;
        }

        if (jabatanSekarang && aturanPangkatJabfung[jabatanSekarang]) {
            const listValid = aturanPangkatJabfung[jabatanSekarang];
            const pangkatMaxValid = listValid[listValid.length - 1];
            const skorPangkatMaxValid = tingkatanPangkat[pangkatMaxValid] || 0;

            const isPangkatValidDiJabfungSama = listValid.includes(pangkatBaruPilihan);
            const isNaikPangkatAntarJabfung =
                skorBaru === skorPangkatMaxValid + 1;

            if (!isPangkatValidDiJabfungSama && !isNaikPangkatAntarJabfung) {
                Swal.fire({
                    icon: "error",
                    title: "Pangkat Tidak Sesuai",
                    text: `Sebagai ${jabatanSekarang}, Anda tidak dapat memilih golongan ${pangkatBaruPilihan}. Ajukan kenaikan jabatan fungsional terlebih dahulu.`,
                    confirmButtonColor: "#b52a20",
                });

                return false;
            }
        }

        return true;
    }

    function validateForm() {
        let valid = true;

        if (formType === "jabfung") {
            if (
                !validateRequired(
                    "nama_jabatan",
                    "error_nama_jabatan",
                    "Silakan pilih jabatan fungsional baru."
                )
            ) {
                valid = false;
            }

            if (
                !validateRequired(
                    "tmt",
                    "error_tmt",
                    "Tanggal mulai berlaku atau TMT wajib diisi."
                )
            ) {
                valid = false;
            }
        }

        if (formType === "panggol") {
            if (
                !validateRequired(
                    "pangkat_baru",
                    "error_pangkat",
                    "Silakan pilih pangkat/golongan baru."
                )
            ) {
                valid = false;
            }

            if (
                !validateRequired(
                    "tmt",
                    "error_tmt",
                    "Tanggal mulai berlaku atau TMT wajib diisi."
                )
            ) {
                valid = false;
            }
        }

        if (!validateFile()) {
            valid = false;
        }

        if (!valid) {
            Swal.fire({
                icon: "warning",
                title: "Data Belum Lengkap",
                text: "Harap mengisi semua kolom wajib dan periksa kembali berkas pendukung.",
                confirmButtonColor: "#b52a20",
            });

            return false;
        }

        if (formType === "jabfung") {
            return validateLogicJabfung();
        }

        if (formType === "panggol") {
            splitPangkatGolongan();
            return validateLogicPanggol();
        }

        return true;
    }

    const berkas = document.getElementById("berkas_pendukung");

    if (berkas) {
        berkas.addEventListener("change", validateFile);
    }

    const tmt = document.getElementById("tmt");

    if (tmt) {
        tmt.addEventListener("click", function () {
            try {
                this.showPicker();
            } catch (e) {}
        });

        tmt.addEventListener("change", function () {
            setError(this, "error_tmt", "");
        });
    }

    const namaJabatan = document.getElementById("nama_jabatan");

    if (namaJabatan) {
        namaJabatan.addEventListener("change", function () {
            setError(this, "error_nama_jabatan", "");
        });
    }

    const pangkatBaru = document.getElementById("pangkat_baru");

    if (pangkatBaru) {
        pangkatBaru.addEventListener("change", function () {
            splitPangkatGolongan();
            setError(this, "error_pangkat", "");
        });
    }

    if (submitBtn && form) {
        submitBtn.addEventListener("click", function (event) {
            event.preventDefault();

            if (!validateForm()) {
                return;
            }

            Swal.fire({
                title: "Konfirmasi Simpan",
                text: "Apakah data pengajuan sudah benar dan siap diajukan?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    if (formType === "jabfung") {
        proteksiDropdownJabfung();
    }

    if (formType === "panggol") {
        proteksiDropdownPangkat();
    }
});
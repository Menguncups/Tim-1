document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formSuratTugas");
    const submitBtn = document.getElementById("btnSubmitSuratTugas");
    const resetBtn = document.getElementById("btnResetSuratTugas");

    const namaPengusul = document.getElementById("nama_pengusul");
    const waktuPelaksana = document.getElementById("waktu_pelaksana");
    const lamaPelaksanaan = document.getElementById("lama_pelaksanaan");
    const perihal = document.getElementById("perihal");
    const berkasPendukung = document.getElementById("berkas_pendukung");

    function setError(input, errorId, message) {
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        error.textContent = message;

        if (message) {
            input.classList.add("is-error");
        } else {
            input.classList.remove("is-error");
        }
    }

    function updateCounter(input, counterId, max) {
        const counter = document.getElementById(counterId);

        if (!input || !counter) return;

        counter.textContent = `${input.value.length}/${max}`;
    }

    function clearAllClientErrors() {
        document.querySelectorAll(".field-error").forEach(function (item) {
            item.textContent = "";
        });

        document.querySelectorAll(".is-error").forEach(function (item) {
            item.classList.remove("is-error");
        });
    }

    function initCounters() {
        if (namaPengusul) {
            updateCounter(namaPengusul, "counter_nama_pengusul", 50);
        }

        if (perihal) {
            updateCounter(perihal, "counter_perihal", 50);
        }
    }

    if (namaPengusul) {
        updateCounter(namaPengusul, "counter_nama_pengusul", 50);

        namaPengusul.addEventListener("input", function () {
            updateCounter(namaPengusul, "counter_nama_pengusul", 50);

            if (/[0-9]/.test(this.value)) {
                setError(
                    this,
                    "error_nama_pengusul",
                    "Nama tidak boleh mengandung angka.",
                );
            } else {
                setError(this, "error_nama_pengusul", "");
            }
        });
    }

    if (perihal) {
        updateCounter(perihal, "counter_perihal", 50);

        perihal.addEventListener("input", function () {
            updateCounter(perihal, "counter_perihal", 50);
            setError(this, "error_perihal", "");
        });
    }

    if (lamaPelaksanaan) {
        lamaPelaksanaan.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
            setError(this, "error_lama_pelaksanaan", "");
        });
    }

    if (waktuPelaksana) {
        waktuPelaksana.addEventListener("change", function () {
            setError(this, "error_waktu_pelaksana", "");
        });
    }

    if (berkasPendukung) {
        berkasPendukung.addEventListener("change", function () {
            validateFile(false);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            setTimeout(function () {
                clearAllClientErrors();
                initCounters();
            }, 0);
        });
    }

    function validateFile(showRequired = true) {
        if (!berkasPendukung) return true;

        const file = berkasPendukung.files[0];

        if (!file) {
            if (showRequired) {
                setError(
                    berkasPendukung,
                    "error_berkas_pendukung",
                    "Berkas pendukung wajib diunggah.",
                );
                return false;
            }

            setError(berkasPendukung, "error_berkas_pendukung", "");
            return true;
        }

        const allowedTypes = ["image/jpeg", "image/png", "application/pdf"];

        if (!allowedTypes.includes(file.type)) {
            setError(
                berkasPendukung,
                "error_berkas_pendukung",
                "Format file harus PDF, JPG, atau PNG.",
            );
            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            setError(
                berkasPendukung,
                "error_berkas_pendukung",
                "Ukuran file maksimal 2 MB.",
            );
            return false;
        }

        setError(berkasPendukung, "error_berkas_pendukung", "");
        return true;
    }

    function validateForm() {
        let valid = true;

        if (!namaPengusul || !namaPengusul.value.trim()) {
            setError(
                namaPengusul,
                "error_nama_pengusul",
                "Nama pengusul wajib diisi.",
            );
            valid = false;
        } else if (/[0-9]/.test(namaPengusul.value)) {
            setError(
                namaPengusul,
                "error_nama_pengusul",
                "Nama tidak boleh mengandung angka.",
            );
            valid = false;
        } else {
            setError(namaPengusul, "error_nama_pengusul", "");
        }

        if (!waktuPelaksana || !waktuPelaksana.value) {
            setError(
                waktuPelaksana,
                "error_waktu_pelaksana",
                "Waktu pelaksanaan wajib diisi.",
            );
            valid = false;
        } else {
            setError(waktuPelaksana, "error_waktu_pelaksana", "");
        }
        console.log("lama value:", lamaPelaksanaan);
        console.log("lama value str:", lamaPelaksanaan?.value);
        console.log("lama parsed:", parseInt(lamaPelaksanaan?.value));
        if (
            !lamaPelaksanaan ||
            lamaPelaksanaan.value === "" ||
            parseInt(lamaPelaksanaan.value) < 1
        ) {
            setError(
                lamaPelaksanaan,
                "error_lama_pelaksanaan",
                "Lama pelaksanaan wajib diisi.",
            );
            valid = false;
        } else {
            setError(lamaPelaksanaan, "error_lama_pelaksanaan", "");
        }

        if (!perihal || !perihal.value.trim()) {
            setError(perihal, "error_perihal", "Perihal wajib diisi.");
            valid = false;
        } else {
            setError(perihal, "error_perihal", "");
        }

        if (!validateFile(true)) {
            valid = false;
        }

        return valid;
    }

    if (submitBtn && form) {
        submitBtn.addEventListener("click", function () {
            if (!validateForm()) {
                Swal.fire({
                    icon: "warning",
                    title: "Data Belum Lengkap",
                    text: "Harap isi semua kolom wajib dengan benar.",
                    confirmButtonColor: "#b52a20",
                });

                document.querySelector(".is-error")?.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });

                return;
            }

            Swal.fire({
                icon: "question",
                title: "Kirim Pengajuan?",
                text: "Pastikan data pengajuan surat tugas sudah benar.",
                showCancelButton: true,
                confirmButtonText: "Ya, Kirim",
                cancelButtonText: "Batal",
                confirmButtonColor: "#b52a20",
                cancelButtonColor: "#6c757d",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    initCounters();
});

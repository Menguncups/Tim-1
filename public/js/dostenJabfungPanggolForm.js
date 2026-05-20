document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form-pengajuan");
    const submitBtn = document.querySelector(".btn-submit-pengajuan");
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

    function validateRequired(id, errorId, message) {
        const input = document.getElementById(id);

        if (!input || !input.value.trim()) {
            setError(input, errorId, message);
            return false;
        }

        setError(input, errorId, "");
        return true;
    }

    function validateFile(showRequired = true) {
        if (!berkasPendukung) return true;

        const file = berkasPendukung.files[0];

        if (!file) {
            if (showRequired) {
                setError(
                    berkasPendukung,
                    "error_berkas_pendukung",
                    "Berkas pendukung wajib diunggah."
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
                "Format file harus PDF, JPG, atau PNG."
            );
            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            setError(
                berkasPendukung,
                "error_berkas_pendukung",
                "Ukuran file maksimal 2 MB."
            );
            return false;
        }

        setError(berkasPendukung, "error_berkas_pendukung", "");
        return true;
    }

    if (berkasPendukung) {
        berkasPendukung.addEventListener("change", function () {
            validateFile(false);
        });
    }

    function validateForm() {
        let valid = true;

        const formType = form?.dataset.formType;

        if (formType === "jabfung") {
            if (!validateRequired("nama_jabatan", "error_nama_jabatan", "Nama jabatan wajib dipilih.")) {
                valid = false;
            }

            if (!validateRequired("tmt", "error_tmt", "TMT wajib diisi.")) {
                valid = false;
            }
        }

        if (formType === "panggol") {
            if (!validateRequired("pangkat", "error_pangkat", "Pangkat wajib dipilih.")) {
                valid = false;
            }

            if (!validateRequired("golongan", "error_golongan", "Golongan wajib dipilih.")) {
                valid = false;
            }

            if (!validateRequired("tmt", "error_tmt", "TMT wajib diisi.")) {
                valid = false;
            }
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
                text: "Pastikan data pengajuan sudah benar.",
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
});
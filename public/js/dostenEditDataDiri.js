document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formUpdateDataDiri");
    const inputFoto = document.getElementById("inputFoto");
    const imgPreview = document.getElementById("imgPreview");
    const previewFallback = document.getElementById("previewFallback");

    const noHp = document.getElementById("no_hp");
    const noHpDarurat = document.getElementById("no_hp_darurat");

    function setError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        error.textContent = message;

        if (message) {
            input.classList.add("is-error");
        } else {
            input.classList.remove("is-error");
        }
    }

    function onlyNumber(input, errorId) {
        if (!input) return;

        input.addEventListener("input", function () {
            const clean = this.value.replace(/[^0-9]/g, "");

            if (this.value !== clean) {
                this.value = clean;
                setError(this.id, errorId, "Gunakan angka saja.");
            } else {
                setError(this.id, errorId, "");
            }
        });
    }

    function validatePhone(inputId, errorId, required = true) {
        const input = document.getElementById(inputId);

        if (!input) return true;

        const value = input.value.trim();

        if (required && value === "") {
            setError(inputId, errorId, "Field ini wajib diisi.");
            return false;
        }

        if (!required && value === "") {
            setError(inputId, errorId, "");
            return true;
        }

        if (!/^[0-9]+$/.test(value)) {
            setError(inputId, errorId, "Gunakan angka saja.");
            return false;
        }

        if (value.length < 10 || value.length > 14) {
            setError(inputId, errorId, "Nomor harus 10 sampai 14 angka.");
            return false;
        }

        setError(inputId, errorId, "");
        return true;
    }

    onlyNumber(noHp, "error_no_hp");
    onlyNumber(noHpDarurat, "error_no_hp_darurat");

    if (inputFoto) {
        inputFoto.addEventListener("change", function () {
            const file = this.files[0];

            setError("inputFoto", "error_foto", "");

            if (!file) return;

            const allowedTypes = ["image/jpeg", "image/png"];

            if (!allowedTypes.includes(file.type)) {
                setError("inputFoto", "error_foto", "Foto harus JPG, JPEG, atau PNG.");
                this.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                setError("inputFoto", "error_foto", "Ukuran foto maksimal 2 MB.");
                this.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                if (imgPreview) {
                    imgPreview.src = e.target.result;
                    imgPreview.classList.remove("d-none");
                }

                if (previewFallback) {
                    previewFallback.style.display = "none";
                }
            };

            reader.readAsDataURL(file);
        });
    }

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const validNoHp = validatePhone("no_hp", "error_no_hp", true);
            const validNoHpDarurat = validatePhone(
                "no_hp_darurat",
                "error_no_hp_darurat",
                false
            );

            if (!validNoHp || !validNoHpDarurat) {
                Swal.fire({
                    icon: "warning",
                    title: "Data belum valid",
                    text: "Periksa kembali nomor handphone yang diisi.",
                    confirmButtonColor: "#b52a20",
                });

                return;
            }

            const updateUrl = form.dataset.updateUrl;
            const formData = new FormData(form);
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

            Swal.fire({
                icon: "question",
                title: "Simpan perubahan?",
                text: "Data diri Anda akan diperbarui.",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Batal",
                confirmButtonColor: "#b52a20",
                cancelButtonColor: "#7a8099",
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(updateUrl, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                })
                    .then(async (response) => {
                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                if (data.errors.no_hp) {
                                    setError("no_hp", "error_no_hp", data.errors.no_hp[0]);
                                }

                                if (data.errors.no_hp_darurat) {
                                    setError(
                                        "no_hp_darurat",
                                        "error_no_hp_darurat",
                                        data.errors.no_hp_darurat[0]
                                    );
                                }

                                if (data.errors.foto) {
                                    setError("inputFoto", "error_foto", data.errors.foto[0]);
                                }
                            }

                            throw new Error(data.message || "Gagal memperbarui data.");
                        }

                        return data;
                    })
                    .then((data) => {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: data.message,
                            confirmButtonColor: "#b52a20",
                        }).then(() => {
                            window.location.href =
                                data.redirect || form.dataset.redirectUrl;
                        });
                    })
                    .catch((error) => {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: error.message,
                            confirmButtonColor: "#b52a20",
                        });
                    });
            });
        });
    }
});
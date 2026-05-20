document.addEventListener("DOMContentLoaded", function () {
    const roleCards = document.querySelectorAll(".role-card");
    const roleCheckboxes = document.querySelectorAll(".role-checkbox");

    const sectionNidn = document.getElementById("section_nidn");
    const nidnInput = document.getElementById("nidn");
    const jabatanSelect = document.getElementById("jabatan_fungsional");

    const jabatanDosen = [
        "Tenaga Pengajar",
        "Asisten Ahli",
        "Lektor",
        "Lektor Kepala",
        "Guru Besar",
    ];

    const jabatanTendik = [
        "Pranata Komputer",
        "Arsiparis",
        "Pustakawan",
        "Pranata Laboratorium Pendidikan",
        "Analis SDM Aparatur",
        "Pranata Humas",
        "Pengelola Keuangan",
        "Administrasi Umum",
        "Teknisi",
    ];

    function getSelectedRoles() {
        return Array.from(roleCheckboxes)
            .filter((checkbox) => checkbox.checked)
            .map((checkbox) => checkbox.value);
    }

    function isValidRoleCombination(roles) {
        if (roles.length === 0) return false;
        if (roles.length === 1) return true;
        if (roles.length > 2) return false;

        const sortedRoles = roles.slice().sort().join(",");

        const dosenPimpinan = ["dosen", "pimpinan"].sort().join(",");
        const operatorTendik = ["operator", "tendik"].sort().join(",");

        return sortedRoles === dosenPimpinan || sortedRoles === operatorTendik;
    }

    function updateRoleCardStyle() {
        roleCards.forEach((card) => {
            const checkbox = card.querySelector(".role-checkbox");

            if (checkbox && checkbox.checked) {
                card.classList.add("selected");
            } else {
                card.classList.remove("selected");
            }
        });
    }

    function showSection(element) {
        if (!element) return;

        element.classList.remove("hidden");
        element.classList.add("visible");
    }

    function hideSection(element) {
        if (!element) return;

        element.classList.remove("visible");
        element.classList.add("hidden");

        element.querySelectorAll("input, select").forEach((input) => {
            input.value = "";
            input.classList.remove("is-error");
        });
    }

    function updateNidnVisibility() {
        const roles = getSelectedRoles();
        const butuhNidn = roles.includes("dosen") || roles.includes("pimpinan");

        if (butuhNidn) {
            showSection(sectionNidn);

            if (nidnInput) {
                nidnInput.required = true;
            }
        } else {
            hideSection(sectionNidn);

            if (nidnInput) {
                nidnInput.required = false;
                nidnInput.value = "";
            }

            const errorNidn = document.getElementById("error_nidn");
            if (errorNidn) {
                errorNidn.textContent = "";
            }
        }
    }

    function updateJabatanOptions() {
        if (!jabatanSelect) return;

        const roles = getSelectedRoles();
        const oldJabatan = jabatanSelect.dataset.old || "";

        const hasDosenPimpinan =
            roles.includes("dosen") || roles.includes("pimpinan");

        const hasOperatorTendik =
            roles.includes("operator") || roles.includes("tendik");

        jabatanSelect.innerHTML =
            '<option value="">-- Pilih Jabatan Fungsional --</option>';

        let options = [];

        if (hasDosenPimpinan) {
            options = jabatanDosen;
        } else if (hasOperatorTendik) {
            options = jabatanTendik;
        }

        options.forEach((jabatan) => {
            const option = document.createElement("option");
            option.value = jabatan;
            option.textContent = jabatan;

            if (oldJabatan === jabatan) {
                option.selected = true;
            }

            jabatanSelect.appendChild(option);
        });

        if (jabatanSelect.value !== "") {
            const errorJabatan = document.getElementById("error_jabatan");

            if (errorJabatan) {
                errorJabatan.textContent = "";
            }

            jabatanSelect.classList.remove("is-error");
        }
    }

    function setError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return false;

        if (input.value.trim() === "") {
            error.textContent = message;
            input.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        input.classList.remove("is-error");
        return true;
    }

    function setSelectError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return false;

        if (input.value === "") {
            error.textContent = message;
            input.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        input.classList.remove("is-error");
        return true;
    }

    function clearErrorWhenFilled(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        const clearError = function () {
            if (input.value.trim() !== "") {
                error.textContent = "";
                input.classList.remove("is-error");
            }
        };

        input.addEventListener("input", clearError);
        input.addEventListener("change", clearError);
    }

    function clearSelectErrorWhenSelected(selectId, errorId) {
        const select = document.getElementById(selectId);
        const error = document.getElementById(errorId);

        if (!select || !error) return;

        select.addEventListener("change", function () {
            if (this.value !== "") {
                error.textContent = "";
                this.classList.remove("is-error");
            }
        });
    }

    function setHanyaAngka(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        input.addEventListener("input", function () {
            const clean = this.value.replace(/[^0-9]/g, "");

            if (this.value !== clean) {
                this.value = clean;
                error.textContent = "Gunakan angka!";
                this.classList.add("is-error");
            } else {
                error.textContent = "";
                this.classList.remove("is-error");
            }
        });
    }

    function setAngkaTetap(inputId, errorId, jumlahDigit, namaField) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return;

        input.addEventListener("input", function () {
            let clean = this.value.replace(/[^0-9]/g, "");

            if (clean.length > jumlahDigit) {
                clean = clean.slice(0, jumlahDigit);
            }

            this.value = clean;

            if (this.value.length > 0 && this.value.length < jumlahDigit) {
                error.textContent = `${namaField} harus ${jumlahDigit} angka!`;
                this.classList.add("is-error");
            } else {
                error.textContent = "";
                this.classList.remove("is-error");
            }
        });
    }

    function validateFixedLengthNumber(
        inputId,
        errorId,
        jumlahDigit,
        namaField,
        emptyMessage,
    ) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);

        if (!input || !error) return false;

        if (input.value.trim() === "") {
            error.textContent = emptyMessage;
            input.classList.add("is-error");
            return false;
        }

        if (!/^[0-9]+$/.test(input.value)) {
            error.textContent = `${namaField} hanya boleh angka!`;
            input.classList.add("is-error");
            return false;
        }

        if (input.value.length !== jumlahDigit) {
            error.textContent = `${namaField} harus ${jumlahDigit} angka!`;
            input.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        input.classList.remove("is-error");
        return true;
    }

    function validateNama() {
        const nama = document.getElementById("nama");
        const error = document.getElementById("error_nama");

        if (!nama || !error) return false;

        if (nama.value.trim() === "") {
            error.textContent = "Nama tidak boleh kosong!";
            nama.classList.add("is-error");
            return false;
        }

        if (/[0-9]/.test(nama.value)) {
            error.textContent = "Nama tidak boleh mengandung angka!";
            nama.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        nama.classList.remove("is-error");
        return true;
    }

    function validateEmail() {
        const email = document.getElementById("email");
        const error = document.getElementById("error_email");

        if (!email || !error) return false;

        const emailValue = email.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailValue === "") {
            error.textContent = "Email tidak boleh kosong!";
            email.classList.add("is-error");
            return false;
        }

        if (!emailPattern.test(emailValue)) {
            error.textContent = "Format email tidak valid!";
            email.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        email.classList.remove("is-error");
        return true;
    }

    function validatePassword() {
        const password = document.getElementById("password");
        const error = document.getElementById("error_password");
        const formMode =
            document.getElementById("formData")?.dataset.mode || "create";

        if (!password || !error) return false;

        if (formMode === "edit" && password.value.trim() === "") {
            error.textContent = "";
            password.classList.remove("is-error");
            return true;
        }

        if (password.value.trim() === "") {
            error.textContent = "Password tidak boleh kosong!";
            password.classList.add("is-error");
            return false;
        }

        if (password.value.length < 8) {
            error.textContent = "Password minimal 8 karakter!";
            password.classList.add("is-error");
            return false;
        }

        error.textContent = "";
        password.classList.remove("is-error");
        return true;
    }

    function validateRole() {
        const roles = getSelectedRoles();
        const errorRole = document.getElementById("error_role");

        if (!errorRole) return false;

        if (!isValidRoleCombination(roles)) {
            errorRole.textContent =
                "Pilih 1 role, atau pasangan Dosen + Pimpinan / Operator + Tendik.";
            return false;
        }

        errorRole.textContent = "";
        return true;
    }

    function validateForm() {
        let valid = true;

        const roles = getSelectedRoles();

        if (!validateRole()) valid = false;
        if (!validateNama()) valid = false;
        if (!validateEmail()) valid = false;
        if (!validatePassword()) valid = false;

        if (
            !validateFixedLengthNumber(
                "nip",
                "error_nip",
                18,
                "NIP",
                "NIP tidak boleh kosong!",
            )
        ) {
            valid = false;
        }

        if (roles.includes("dosen") || roles.includes("pimpinan")) {
            if (
                !validateFixedLengthNumber(
                    "nidn",
                    "error_nidn",
                    10,
                    "NIDN",
                    "NIDN wajib diisi untuk Dosen atau Pimpinan!",
                )
            ) {
                valid = false;
            }
        }

        if (
            !setSelectError("jenis_kelamin", "error_jk", "Pilih jenis kelamin!")
        ) {
            valid = false;
        }

        if (
            !setError(
                "tanggal_lahir",
                "error_tgl",
                "Tanggal lahir tidak boleh kosong!",
            )
        ) {
            valid = false;
        }

        if (!setError("no_hp", "error_hp", "No HP tidak boleh kosong!")) {
            valid = false;
        }

        if (!setSelectError("homebase", "error_homebase", "Pilih homebase!")) {
            valid = false;
        }

        if (
            !setSelectError(
                "pangkat_golongan",
                "error_pangkat",
                "Pilih pangkat / golongan!",
            )
        ) {
            valid = false;
        }

        if (
            !setSelectError(
                "jabatan_fungsional",
                "error_jabatan",
                "Pilih jabatan fungsional!",
            )
        ) {
            valid = false;
        }

        return valid;
    }

    roleCards.forEach((card) => {
        card.addEventListener("click", function (event) {
            event.preventDefault();

            const checkbox = this.querySelector(".role-checkbox");
            if (!checkbox) return;

            checkbox.checked = !checkbox.checked;

            const roles = getSelectedRoles();

            if (roles.length > 2) {
                checkbox.checked = false;

                Swal.fire({
                    icon: "warning",
                    title: "Maksimal 2 role",
                    text: "Pegawai hanya boleh memiliki 1 role atau 2 role sesuai pasangan yang ditentukan.",
                    confirmButtonColor: "#b52a20",
                });
            }

            const newRoles = getSelectedRoles();

            if (newRoles.length === 2 && !isValidRoleCombination(newRoles)) {
                checkbox.checked = false;

                Swal.fire({
                    icon: "warning",
                    title: "Kombinasi role tidak valid",
                    text: "Pasangan role yang diperbolehkan hanya Dosen + Pimpinan atau Operator + Tendik.",
                    confirmButtonColor: "#b52a20",
                });
            }

            updateRoleCardStyle();
            updateNidnVisibility();
            updateJabatanOptions();
            validateRole();
        });
    });

    setAngkaTetap("nip", "error_nip", 18, "NIP");
    setAngkaTetap("nidn", "error_nidn", 10, "NIDN");

    setHanyaAngka("no_hp", "error_hp");
    setHanyaAngka("no_hp_darurat", "error_hp_darurat");

    clearErrorWhenFilled("tanggal_lahir", "error_tgl");
    clearErrorWhenFilled("no_hp", "error_hp");
    clearErrorWhenFilled("no_hp_darurat", "error_hp_darurat");

    clearSelectErrorWhenSelected("jenis_kelamin", "error_jk");
    clearSelectErrorWhenSelected("homebase", "error_homebase");
    clearSelectErrorWhenSelected("pangkat_golongan", "error_pangkat");
    clearSelectErrorWhenSelected("jabatan_fungsional", "error_jabatan");

    const nama = document.getElementById("nama");
    if (nama) {
        nama.addEventListener("input", validateNama);
    }

    const email = document.getElementById("email");
    if (email) {
        email.addEventListener("input", validateEmail);
    }

    const password = document.getElementById("password");
    if (password) {
        password.addEventListener("input", validatePassword);
    }

    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    }

    const fotoInput = document.getElementById("foto");
    const fotoPreview = document.getElementById("fotoPreview");
    const fotoPlaceholder = document.getElementById("fotoPlaceholder");
    const fotoHapus = document.getElementById("fotoHapus");
    const errorFoto = document.getElementById("error_foto");

    if (fotoInput) {
        fotoInput.addEventListener("change", function () {
            const file = this.files[0];

            if (!file) return;

            if (!["image/jpeg", "image/png"].includes(file.type)) {
                errorFoto.textContent = "File harus berformat JPG atau PNG!";
                fotoInput.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                errorFoto.textContent = "Ukuran file maksimal 2 MB!";
                fotoInput.value = "";
                return;
            }

            errorFoto.textContent = "";

            const reader = new FileReader();

            reader.onload = function (e) {
                fotoPreview.src = e.target.result;
                fotoPreview.style.display = "block";
                fotoPlaceholder.style.display = "none";
                fotoHapus.style.display = "inline-flex";
            };

            reader.readAsDataURL(file);
        });
    }

    if (fotoHapus) {
        fotoHapus.addEventListener("click", function () {
            fotoInput.value = "";
            fotoPreview.src = "";
            fotoPreview.style.display = "none";
            fotoPlaceholder.style.display = "flex";
            fotoHapus.style.display = "none";
            errorFoto.textContent = "";
        });
    }

    const submitBtn = document.getElementById("btnSimpan");

    if (submitBtn) {
        submitBtn.addEventListener("click", function () {
            if (!validateForm()) {
                Swal.fire({
                    icon: "warning",
                    title: "Data belum lengkap",
                    text: "Periksa kembali field yang wajib diisi.",
                    confirmButtonColor: "#b52a20",
                });

                return;
            }

            const roles = getSelectedRoles()
                .map((role) => role.charAt(0).toUpperCase() + role.slice(1))
                .join(" + ");

            Swal.fire({
                icon: "question",
                title: "Simpan Data?",
                text: `Role: ${roles}`,
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Batal",
                confirmButtonColor: "#b52a20",
                cancelButtonColor: "#7a8099",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById("formData");
                    console.log("Form action:", form.action);
                    console.log("Form method:", form.method);
                    form.submit();
                }
            });
        });
    }

    updateRoleCardStyle();
    updateNidnVisibility();
    updateJabatanOptions();
});

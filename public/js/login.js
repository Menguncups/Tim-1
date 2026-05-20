function togglePassword() {
    const input = document.getElementById("password");
    const eyeOn = document.getElementById("icon-eye");
    const eyeOff = document.getElementById("icon-eye-off");

    if (!input) return;

    const isHidden = input.type === "password";
    input.type = isHidden ? "text" : "password";

    if (eyeOn) eyeOn.style.display = isHidden ? "none" : "block";
    if (eyeOff) eyeOff.style.display = isHidden ? "block" : "none";

    input.focus();
}

document.addEventListener("DOMContentLoaded", function () {
    const logo = document.getElementById("leftLogo");
    const fallback = document.getElementById("logo-fallback");

    if (logo) {
        logo.addEventListener("error", function () {
            logo.style.display = "none";

            if (fallback) {
                fallback.style.display = "block";
            }
        });
    }

    const roleCards = document.querySelectorAll(".role-card");
    const btnLanjut = document.getElementById("btn-lanjut");

    roleCards.forEach(function (card) {
        card.addEventListener("click", function () {
            roleCards.forEach(function (item) {
                item.classList.remove("selected");
            });

            card.classList.add("selected");

            const radio = card.querySelector(".role-radio");

            if (radio) {
                radio.checked = true;
            }

            if (btnLanjut) {
                btnLanjut.disabled = false;
                btnLanjut.classList.remove("disabled");
            }
        });
    });
});
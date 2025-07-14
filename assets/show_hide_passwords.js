document.querySelectorAll(".toggle-password").forEach((btn) => {
  btn.addEventListener("click", function () {
    const inputId = this.getAttribute("aria-controls");
    const input = document.getElementById(inputId);
    const icon = this.querySelector("i");

    if (!input) return;

    const isVisible = input.type === "text";
    input.type = isVisible ? "password" : "text";

    icon.classList.toggle("bi-eye-slash", isVisible);
    icon.classList.toggle("bi-eye", !isVisible);

    this.setAttribute("aria-checked", !isVisible);
  });
});

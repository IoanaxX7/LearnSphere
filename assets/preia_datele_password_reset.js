window.addEventListener("load", () => {
  const form = document.getElementById("password_reset");
  const email = document.getElementById("email");
  const alertContainer = document.querySelector(".alerte_resetare_email");

  email.addEventListener("input", () => {
    email.classList.toggle("is-valid", email.checkValidity());
    email.classList.toggle("is-invalid", !email.checkValidity());
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    alertContainer.innerHTML = "";

    if (!email.checkValidity()) {
      email.classList.add("is-invalid");
      alertContainer.innerHTML = `<div class="alert alert-danger">Te rugăm să introduci un email valid.</div>`;
      return;
    }

    const payload = { email: email.value.trim() };

    try {
      const res = await fetch(form.action, {
        method: "POST",
        headers: { "Content-Type": "application/json; charset=UTF-8" },
        body: JSON.stringify(payload),
      });

      const raw = await res.text();  // get raw response
      console.log("RAW RESPONSE:", raw);

      if (!res.ok) {
        throw new Error(`Eroare server: ${res.status}`);
      }

      let data;
      try {
        data = JSON.parse(raw);
      } catch (jsonErr) {
        throw new Error("Răspuns invalid (nu este JSON)");
      }

      const type = data.success ? "success" : "danger";
      const msg = data.message || "A apărut o eroare.";
      alertContainer.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;

      if (data.success) {
        form.reset();
        email.classList.remove("is-valid", "is-invalid");
      }

    } catch (err) {
      console.error(err);
      alertContainer.innerHTML = `<div class="alert alert-danger">Eroare: ${err.message}</div>`;
    }
  });
});

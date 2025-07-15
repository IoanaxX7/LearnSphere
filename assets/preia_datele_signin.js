window.addEventListener("load", function () {
  const signup = document.getElementById("signup");
  const alertContainer = document.querySelector(".alert_signin");
  const formElements = signup.elements;

  const fields = {
    nume: document.getElementById("nume"),
    prenume: document.getElementById("prenume"),
    nume_utilizator: document.getElementById("nume_utilizator"),
    email: document.getElementById("email"),
    data_nasterii: document.getElementById("data_nasterii"),
    parola1: document.getElementById("parola1"),
    parola2: document.getElementById("parola2"),
  };

  // Live validation for username
  fields.nume_utilizator.addEventListener("keyup", () => {
    fields.nume_utilizator.classList.remove("is-valid", "is-invalid");
    fields.nume_utilizator.classList.add(
      fields.nume_utilizator.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  signup.addEventListener("submit", function (event) {
    event.preventDefault();

    clearAlerts();

    const formData = {};
    for (const key in fields) {
      formData[key] = fields[key].value;
    }

    const apiUrl = "actiuni/signin_process.php";

    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json; charset=UTF-8" },
      body: JSON.stringify(formData),
    })
      .then(async (response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return await response.json();
      })
      .then((data) => {
        if (JSON.stringify(data) === "[]") {
          showAlert(
            "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!",
            "warning"
          );
          return;
        }

        if (!data.succes) {
          handleValidationErrors(data.erori_validare);
        } else {
          if (data.mesaj_eroare_sql) {
            showAlert(data.mesaj_eroare_sql, "danger");
          }

          if (data.mesaj_succes_sql) {
            showAlert(data.mesaj_succes_sql, "success");
            resetForm();
          }

          if (data.succes_validare) {
            clearAlerts(); // in case another alert is already showing

            // Create the alert element manually
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-success";
            alertDiv.innerHTML = `Contul a fost creat cu succes! Redirecționare în <span id="countdown">5</span> secunde...`;
            alertContainer.appendChild(alertDiv);

            let countdown = 5;
            const countdownEl = alertDiv.querySelector("#countdown");

            const intervalId = setInterval(() => {
              countdown--;
              if (countdownEl) countdownEl.textContent = countdown;

              if (countdown <= 0) {
                clearInterval(intervalId);
                window.location.href = "login.php";
              }
            }, 1000);
          }
        }
      })
      .catch((error) => {
        console.error(error);
        showAlert(error.message, "danger");
      });
  });

  function showAlert(message, type = "info") {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = message;
    alertContainer.appendChild(alertDiv); // Append to bottom
  }

  function clearAlerts() {
    alertContainer.innerHTML = "";
  }

  function resetForm() {
    signup.reset();
    for (const element of formElements) {
      element.classList.remove("is-valid", "is-invalid");
    }
  }

  function handleValidationErrors(errors = {}) {
    const messages = [];

    for (const key in errors) {
      if (fields[key]) {
        fields[key].classList.remove("is-valid", "is-invalid");
        fields[key].classList.add("is-invalid");
        messages.push(errors[key]);
      }
    }

    if (messages.length) {
      showAlert(messages.join("<br>"), "danger");
    }
  }
});

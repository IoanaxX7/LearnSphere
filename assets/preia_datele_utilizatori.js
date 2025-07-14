window.addEventListener("load", function (e) {
  //Adauga utilizator
  const modal_adauga_utilizator = document.getElementById(
    "modal_adauga_utilizator"
  );
  const form_adauga_utilizator = document.getElementById(
    "form_adauga_utilizator"
  );
  const elemente_formular_modal_adauga_utilizator =
    form_adauga_utilizator.elements;

  const rol = document.getElementById("rol");
  const nume = document.getElementById("nume");
  const prenume = document.getElementById("prenume");
  const nume_utilizator = document.getElementById("nume_utilizator");
  const email = document.getElementById("email");
  const data_nasterii = document.getElementById("data_nasterii");
  const parola1 = document.getElementById("parola1");
  const parola2 = document.getElementById("parola2");

  nume_utilizator.addEventListener("keyup", (event) => {
    nume_utilizator.classList.remove("is-valid", "is-invalid");
    nume_utilizator.classList.add(
      nume_utilizator.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  form_adauga_utilizator.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          rol: rol.value,
          nume: nume.value,
          prenume: prenume.value,
          nume_utilizator: nume_utilizator.value,
          email: email.value,
          data_nasterii: data_nasterii.value,
          parola1: parola1.value,
          parola2: parola2.value,
        };

        const apiUrl = "../actiuni/adauga_utilizator.php";
        const fullUrl = `${apiUrl}`;

        try {
          const raspuns = await fetch(fullUrl, {
            method: "POST",
            headers: {
              "Content-Type": "application/json; charset=UTF-8",
            },
            body: JSON.stringify(date_formular),
          });

          if (!raspuns.ok) {
            throw new Error(`HTTP error! Status: ${raspuns.status}`);
          }

          const datele = await raspuns.json();

          const alert_adauga_utilizator = document.querySelector(
            ".alert_adauga_utilizator"
          );
          alert_adauga_utilizator.innerHTML = ""; // Clear old messages

          if (JSON.stringify(datele) !== "[]") {
            if (!datele.succes) {
              const mesaj = document.createElement("div");
              mesaj.className = "alert alert-danger";

              if (datele.erori_validare.rol) {
                rol.classList.remove("is-valid", "is-invalid");
                rol.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.rol + "<br>";
              }
              if (datele.erori_validare.nume) {
                nume.classList.remove("is-valid", "is-invalid");
                nume.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.nume + "<br>";
              }
              if (datele.erori_validare.prenume) {
                prenume.classList.remove("is-valid", "is-invalid");
                prenume.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.prenume + "<br>";
              }
              if (datele.erori_validare.nume_utilizator) {
                nume_utilizator.classList.remove("is-valid", "is-invalid");
                nume_utilizator.classList.add("is-invalid");
                mesaj.innerHTML +=
                  datele.erori_validare.nume_utilizator + "<br>";
              }
              if (datele.erori_validare.email) {
                email.classList.remove("is-valid", "is-invalid");
                email.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.email + "<br>";
              }
              if (datele.erori_validare.data_nasterii) {
                data_nasterii.classList.remove("is-valid", "is-invalid");
                data_nasterii.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.data_nasterii + "<br>";
              }
              if (datele.erori_validare.parola1) {
                parola1.classList.remove("is-valid", "is-invalid");
                parola1.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.parola1 + "<br>";
              }
              if (datele.erori_validare.parola2) {
                parola2.classList.remove("is-valid", "is-invalid");
                parola2.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.parola2 + "<br>";
              }

              alert_adauga_utilizator.appendChild(mesaj);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                alert_adauga_utilizator.appendChild(mesaj2);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                alert_adauga_utilizator.appendChild(mesaj3);

                form_adauga_utilizator.reset();
                Array.from(elemente_formular_modal_adauga_utilizator).forEach(
                  (element_formular_modal_adauga_utilizator) => {
                    element_formular_modal_adauga_utilizator.classList.remove(
                      "is-valid",
                      "is-invalid"
                    );
                  }
                );
              }

              if (datele.succes_validare) {
                const mesaj = document.createElement("div");
                mesaj.className = "alert alert-success";
                mesaj.innerHTML = datele.succes_validare;
                alert_adauga_utilizator.appendChild(mesaj);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            alert_adauga_utilizator.appendChild(mesaj);
          }
        } catch (error) {
          console.error(error);
          const alert_adauga_utilizator = document.querySelector(
            ".alert_adauga_utilizator"
          );
          alert_adauga_utilizator.innerHTML = "";
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          alert_adauga_utilizator.appendChild(mesaj);
        }
      }
      preiaDatele();
    },
    false
  );

  //Adauga datele utilizatorului in caseta modala
  document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", function () {
      const userId = this.getAttribute("id");
      document.getElementById("userID").value = userId;

      fetch("../actiuni/preia_utilizator.php?id=" + userId)
        .then((response) => response.json())
        .then((data) => {
          const modal = document.getElementById("modal_modifica_utilizator");

          modal.querySelector("#modifica_rol").value = data.rol;
          modal.querySelector("#modifica_nume").value = data.nume;
          modal.querySelector("#modifica_prenume").value = data.prenume;
          modal.querySelector("#modifica_nume_utilizator").value =
            data.username;
          modal.querySelector("#modifica_email").value = data.email;
          modal.querySelector("#modifica_data_nasterii").value =
            data.dataNasterii;
        })
        .catch((error) =>
          console.error("Eroare la încărcarea utilizatorului:", error)
        );
    });
  });

  //Modifica datele utilizatorului
  const form_modifica_utilizator = document.getElementById(
    "form_modifica_utilizator"
  );
  const modifica_rol = document.getElementById("modifica_rol");
  const edit_nume = document.getElementById("modifica_nume");
  const edit_prenume = document.getElementById("modifica_prenume");
  const edit_nume_utilizator = document.getElementById(
    "modifica_nume_utilizator"
  );
  const edit_email = document.getElementById("modifica_email");
  const edit_data_nasterii = document.getElementById("modifica_data_nasterii");
  const edit_parola1 = document.getElementById("modifica_parola1");
  const edit_parola2 = document.getElementById("modifica_parola2");

  form_modifica_utilizator.addEventListener("submit", function (event) {
    event.preventDefault();

    const userId = document.getElementById("userID").value;
    console.log("User ID:", userId);

    async function preiaDatele() {
      let data = {
        userId: userId,
        rol: modifica_rol.value,
        nume: edit_nume.value,
        prenume: edit_prenume.value,
        nume_utilizator: edit_nume_utilizator.value,
        email: edit_email.value,
        data_nasterii: edit_data_nasterii.value,
        parola1: edit_parola1.value,
        parola2: edit_parola2.value,
      };

      const apiUrl = "../actiuni/modifica_utilizator.php";

      try {
        const raspuns = await fetch(apiUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json; charset=UTF-8",
          },
          body: JSON.stringify(data),
        });

        if (!raspuns.ok) {
          throw new Error(`HTTP error! Status: ${raspuns.status}`);
        }

        const rezultat = await raspuns.json();
        console.log("Parsed JSON response:", rezultat);

        console.log("rezultat:", rezultat);
        if (rezultat.error) {
          showAlert(rezultat.error, "danger");
        } else if (rezultat.success) {

          showAlert(rezultat.success, "success");
        }
      } catch (error) {
        console.error("Fetch error:", error);
        showAlert(error.message, "danger");
      }
    }

    function showAlert(message, type) {
      const container = document.querySelector(".alert_modifica_utilizator");

      if (!container) {
        console.error("Alert container not found!");
        return;
      }

      const mesaj = document.createElement("div");
      mesaj.className = `alert alert-${type} alert-dismissible fade show mt-2`;
      mesaj.innerHTML =
        message +
        `<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

      container.appendChild(mesaj);
    }

    preiaDatele();
  });

  //Sterge utilizator
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const userID = this.id;

      if (confirm("Ești sigur că vrei să ștergi acest utilizator?")) {
        fetch("../actiuni/sterge_utilizator.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `userID=${userID}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              this.closest("tr").remove();
            } else {
              alert("Eroare: " + data.message);
            }
          });
      }
    });
  });
});

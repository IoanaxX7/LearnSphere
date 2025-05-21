window.addEventListener("load", function (e) {
  //Adauga utilizator
  const modal_adauga_utilizator = document.getElementById(
    "modal_adauga_utilizator"
  );
  const elemente_formular_modal_adauga_utilizator = document.getElementById(
    "modal_adauga_utilizator"
  ).elements;
  const form_adauga_utilizator = document.getElementById(
    "form_adauga_utilizator"
  ).elements;

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

  modal_adauga_utilizator.addEventListener(
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
          console.log(raspuns);

          if (!raspuns.ok) {
            throw new Error(`HTTP error! Status: ${raspuns.status}`);
          }

          const datele = await raspuns.json();

          while (document.contains(document.querySelector(".alert"))) {
            document.querySelector(".alert").remove();
          }

          if (JSON.stringify(datele) !== "[]") {
            if (!datele.succes) {
              const mesaj = document.createElement("div");
              mesaj.className = "alert alert-danger";
              if (datele.erori_validare.rol) {
                nume.classList.remove("is-valid", "is-invalid");
                nume.classList.add("is-invalid");
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
                mesaj.innerHTML += datele.erori_validare.parola1;
              }
              if (datele.erori_validare.parola2) {
                parola2.classList.remove("is-valid", "is-invalid");
                parola2.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.parola2;
              }
              const modal_body = document.querySelector(".modal-body");
              modal_body.insertBefore(mesaj, modal_body.firstChild);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                const modal_body = document.querySelector(".modal-body");
                modal_body.insertBefore(mesaj2, modal_body.firstChild);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                const modal_body = document.querySelector(".modal-body");
                modal_body.insertBefore(mesaj3, modal_body.firstChild);
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
                const modal_body = document.querySelector(".modal-body");
                modal_body.insertBefore(mesaj, modal_body.firstChild);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            const modal_body = document.querySelector(".modal-body");
            modal_body.insertBefore(mesaj, modal_body.firstChild);
          }
        } catch (error) {
          console.error(error);
          while (document.contains(document.querySelector(".alert"))) {
            document.querySelector(".alert").remove();
          }
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          const modal_body = document.querySelector(".modal-body");
          modal_body.insertBefore(mesaj, modal_body.firstChild);
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

        // Correctly parse the JSON response body
        const rezultat = await raspuns.json();
        console.log("Parsed JSON response:", rezultat);

        if (rezultat.error) {
          // Show error message from server
          showAlert(rezultat.error, "danger");
        } else if (rezultat.success) {
          // Show success message from server
          showAlert(rezultat.success, "success");
        }
      } catch (error) {
        console.error("Fetch error:", error);
        showAlert(error.message, "danger");
      }
    }

    // Helper function to show alert messages inside modal-body
    function showAlert(message, type) {
      // Remove existing alerts
      const existingAlerts = document.querySelectorAll(".alert");
      existingAlerts.forEach((alert) => alert.remove());

      const mesaj = document.createElement("div");
      mesaj.className = `alert alert-${type} alert-dismissible fade show`;
      mesaj.innerHTML =
        message +
        `<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
      const modal_body = document.querySelector(".modal-body");
      modal_body.insertBefore(mesaj, modal_body.firstChild);
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

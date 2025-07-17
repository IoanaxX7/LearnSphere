window.addEventListener("load", function (e) {
  //Adauga materie
  const modal_adauga_materie = document.getElementById(
    "modal_adauga_materie"
  );
  const form_adauga_materie = document.getElementById(
    "form_adauga_materie"
  );
  const elemente_formular_modal_adauga_materie =
    form_adauga_materie.elements;

  const nume_materie = document.getElementById("nume_adauga_materie");
  const descriere = document.getElementById("descriere_adauga_materie");

  nume_materie.addEventListener("keyup", (event) => {
    nume_materie.classList.remove("is-valid", "is-invalid");
    nume_materie.classList.add(
      nume_materie.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  form_adauga_materie.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          nume_materie: nume_materie.value,
          descriere: descriere.value,
        };

        const apiUrl = "../actiuni/adauga_materie.php";
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

          const alert_adauga_materie = document.querySelector(
            ".alert_adauga_materie"
          );
          alert_adauga_materie.innerHTML = ""; // Clear old messages

          if (JSON.stringify(datele) !== "[]") {
            if (!datele.succes) {
              const mesaj = document.createElement("div");
              mesaj.className = "alert alert-danger";

              if (datele.erori_validare.nume_materie) {
                nume_materie.classList.remove("is-valid", "is-invalid");
                nume_materie.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.nume_materie + "<br>";
              }

              alert_adauga_materie.appendChild(mesaj);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                alert_adauga_materie.appendChild(mesaj2);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                alert_adauga_materie.appendChild(mesaj3);

                form_adauga_materie.reset();
                Array.from(elemente_formular_modal_adauga_materie).forEach(
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
                alert_adauga_materie.appendChild(mesaj);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            alert_adauga_materie.appendChild(mesaj);
          }
        } catch (error) {
          console.error(error);
          const alert_adauga_materie = document.querySelector(
            ".alert_adauga_materie"
          );
          alert_adauga_materie.innerHTML = "";
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          alert_adauga_materie.appendChild(mesaj);
        }
      }
      preiaDatele();
    },
    false
  );

  //Adauga datele materiei in caseta modala
  document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", function () {
      const materieId = this.getAttribute("id");
      document.getElementById("materieId").value = materieId;

      fetch("../actiuni/preia_materie.php?id=" + materieId)
        .then((response) => response.json())
        .then((data) => {
          const modal = document.getElementById("modal_modifica_materie");

          modal.querySelector("#nume_modifica_materie").value = data.nume;
          modal.querySelector("#descriere_modifica_material").value = data.descriere;
        })
        .catch((error) =>
          console.error("Eroare la încărcarea materiei:", error)
        );
    });
  });

  //Modifica datele materiei
  const form_modifica_materie = document.getElementById(
    "form_modifica_materie"
  );
  const nume_modifica_materie = document.getElementById("nume_modifica_materie");
  const descriere_modifica_material = document.getElementById("descriere_modifica_material");

  form_modifica_materie.addEventListener("submit", function (event) {
    event.preventDefault();

    const materieId = document.getElementById("materieId").value;

    async function preiaDatele() {
      let data = {
        materieID: materieId,
        nume: nume_modifica_materie.value,
        descriere: descriere_modifica_material.value,
      };

      const apiUrl = "../actiuni/modifica_materie.php";

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
      const container = document.querySelector(".alert_modifica_materie");

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

  //Sterge materie
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const materieID = this.id;

      if (confirm("Ești sigur că vrei să ștergi această materie?")) {
        fetch("../actiuni/sterge_materie.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `materieID=${materieID}`,
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

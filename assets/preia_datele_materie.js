window.addEventListener("load", function (e) {
  const modal_adauga_materie = document.getElementById(
    "modal_adauga_materie"
  );
  const elemente_formular_modal_adauga_materie = document.getElementById(
    "modal_adauga_materie"
  ).elements;
  const form_adauga_materie = document.getElementById(
    "form_adauga_materie"
  );

  const nume_materie = document.getElementById("nume_materie");
  const descriere_material = document.getElementById("descriere_material");

  nume_materie.addEventListener("keyup", (event) => {
    nume_materie.classList.remove("is-valid", "is-invalid");
    nume_materie.classList.add(
      nume_materie.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  modal_adauga_materie.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          nume_materie: nume_materie.value,
          descriere: descriere_material.value,
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
              if (datele.erori_validare.nume_materie) {
                nume_materie.classList.remove("is-valid", "is-invalid");
                nume_materie.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.nume_materie + "<br>";
              }

              const alert_materie = document.querySelector(".alert_materie");
              alert_materie.insertBefore(mesaj, alert_materie.firstChild);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                const alert_materie = document.querySelector(".alert_materie");
                alert_materie.insertBefore(mesaj2, alert_materie.firstChild);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                const alert_materie = document.querySelector(".alert_materie");
                alert_materie.insertBefore(mesaj3, alert_materie.firstChild);
                form_adauga_materie.reset();
                if (elemente_formular_modal_adauga_materie) {
                  Array.from(elemente_formular_modal_adauga_materie).forEach(
                    (element) => {
                      element.classList.remove("is-valid", "is-invalid");
                    }
                  );
                }
              }
              if (datele.succes_validare) {
                const mesaj = document.createElement("div");
                mesaj.className = "alert alert-success";
                mesaj.innerHTML = datele.succes_validare;
                const alert_materie = document.querySelector(".alert_materie");
                alert_materie.insertBefore(mesaj, alert_materie.firstChild);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            const alert_materie = document.querySelector(".alert_materie");
            alert_materie.insertBefore(mesaj, alert_materie.firstChild);
          }
        } catch (error) {
          console.error(error);
          while (document.contains(document.querySelector(".alert"))) {
            document.querySelector(".alert").remove();
          }
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          const alert_materie = document.querySelector(".alert_materie");
          alert_materie.insertBefore(mesaj, alert_materie.firstChild);
        }
      }
      preiaDatele();
    },
    false
  );
});

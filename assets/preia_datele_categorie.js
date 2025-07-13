window.addEventListener("load", function (e) {
  const modal_adauga_categorie = document.getElementById(
    "modal_adauga_categorie"
  );
  const elemente_formular_modal_adauga_categorie = document.getElementById(
    "modal_adauga_categorie"
  ).elements;
  const form_adauga_categorie = document.getElementById(
    "form_adauga_categorie"
  );

  const nume_categorie = document.getElementById("nume_categorie");
  const materie = document.getElementById("materie");
  const descriere_categorie = document.getElementById("descriere_categorie");

  nume_categorie.addEventListener("keyup", (event) => {
    nume_categorie.classList.remove("is-valid", "is-invalid");
    nume_categorie.classList.add(
      nume_categorie.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  materie.addEventListener("keyup", (event) => {
    materie.classList.remove("is-valid", "is-invalid");
    materie.classList.add(
      materie.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  modal_adauga_categorie.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          nume_categorie: nume_categorie.value,
          materie: materie.value,
          descriere: descriere_categorie.value,
        };

        const apiUrl = "../actiuni/adauga_categorie.php";
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
              if (datele.erori_validare.nume_categorie) {
                nume_categorie.classList.remove("is-valid", "is-invalid");
                nume_categorie.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.nume_categorie + "<br>";
              }
              if (datele.erori_validare.materie) {
                materie.classList.remove("is-valid", "is-invalid");
                materie.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.materie + "<br>";
              }

              const alert_categorie = document.querySelector(".alert_categorie");
              alert_categorie.insertBefore(mesaj, alert_categorie.firstChild);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                const alert_categorie = document.querySelector(".alert_categorie");
                alert_categorie.insertBefore(mesaj2, alert_categorie.firstChild);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                const alert_categorie = document.querySelector(".alert_categorie");
                alert_categorie.insertBefore(mesaj3, alert_categorie.firstChild);
                form_adauga_categorie.reset();
                if (elemente_formular_modal_adauga_categorie) {
                  Array.from(elemente_formular_modal_adauga_categorie).forEach(
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
                const alert_categorie = document.querySelector(".alert_categorie");
                alert_categorie.insertBefore(mesaj, alert_categorie.firstChild);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            const alert_categorie = document.querySelector(".alert_categorie");
            alert_categorie.insertBefore(mesaj, alert_categorie.firstChild);
          }
        } catch (error) {
          console.error(error);
          while (document.contains(document.querySelector(".alert"))) {
            document.querySelector(".alert").remove();
          }
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          const alert_categorie = document.querySelector(".alert_categorie");
          alert_categorie.insertBefore(mesaj, alert_categorie.firstChild);
        }
      }
      preiaDatele();
    },
    false
  );
});

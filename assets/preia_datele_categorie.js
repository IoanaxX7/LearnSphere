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

  const nume_catgorie = document.getElementById("nume_catgorie");
  const descriere = document.getElementById("descriere");

  nume_catgorie.addEventListener("keyup", (event) => {
    nume_catgorie.classList.remove("is-valid", "is-invalid");
    nume_catgorie.classList.add(
      nume_catgorie.checkValidity() ? "is-valid" : "is-invalid"
    );
  });

  modal_adauga_categorie.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          nume_categorie: nume_catgorie.value,
          descriere: descriere.value,
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
              if (datele.erori_validare.nume_catgorie) {
                nume_catgorie.classList.remove("is-valid", "is-invalid");
                nume_catgorie.classList.add("is-invalid");
                mesaj.innerHTML += datele.erori_validare.nume_catgorie + "<br>";
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
});

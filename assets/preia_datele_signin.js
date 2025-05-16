window.addEventListener("load", function (e) {
  const signup = document.getElementById("signup");
  const elemente_formular_signup = document.getElementById("signup").elements;

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

  signup.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();
      async function preiaDatele() {
        let date_formular = {
          nume: nume.value,
          prenume: prenume.value,
          nume_utilizator: nume_utilizator.value,
          email: email.value,
          data_nasterii: data_nasterii.value,
          parola1: parola1.value,
          parola2: parola2.value,
        };

        const apiUrl = "actiuni/signin_process.php";
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
              const signin_body = document.querySelector(".signin-body");
              signin_body.insertBefore(mesaj, signin_body.firstChild);
            } else {
              if (datele.mesaj_eroare_sql) {
                const mesaj2 = document.createElement("div");
                mesaj2.className = "alert alert-danger";
                mesaj2.innerHTML = datele.mesaj_eroare_sql;
                const signin_body = document.querySelector(".signin-body");
                signin_body.insertBefore(mesaj2, signin_body.firstChild);
              } else if (datele.mesaj_succes_sql) {
                const mesaj3 = document.createElement("div");
                mesaj3.className = "alert alert-success";
                mesaj3.innerHTML = datele.mesaj_succes_sql;
                const signin_body = document.querySelector(".signin-body");
                signin_body.insertBefore(mesaj3, signin_body.firstChild);
                signup.reset();
                Array.from(elemente_formular_signup).forEach((element_formular_signup) => {
                  element_formular_signup.classList.remove("is-valid", "is-invalid");
                });
              }
              if (datele.succes_validare) {
                const mesaj = document.createElement("div");
                mesaj.className = "alert alert-success";
                mesaj.innerHTML = datele.succes_validare;
                const signin_body = document.querySelector(".signin-body");
                signin_body.insertBefore(mesaj, signin_body.firstChild);
              }
            }
          } else {
            const mesaj = document.createElement("div");
            mesaj.className = "alert alert-warning";
            mesaj.innerHTML =
              "Serverul nu a returnat date. Posibil nu s-a trimis o acțiune corectă!";
            const signin_body = document.querySelector(".signin-body");
            signin_body.insertBefore(mesaj, signin_body.firstChild);
          }
        } catch (error) {
          console.error(error);
          while (document.contains(document.querySelector(".alert"))) {
            document.querySelector(".alert").remove();
          }
          const mesaj = document.createElement("div");
          mesaj.className = "alert alert-danger";
          mesaj.innerHTML = error;
          const signin_body = document.querySelector(".signin-body");
          signin_body.insertBefore(mesaj, signin_body.firstChild);
        }
      }
      preiaDatele();
    },
    false
  );
});
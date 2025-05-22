window.addEventListener("load", function (e) {
  const userID = document.getElementById("userID").value;

  if (!userID) {
    console.error("ID-ul utilizatorului nu este setat.");
    return;
  }

  //Adauga datele utilizatorului in formular
  fetch("../actiuni/preia_utilizator.php?id=" + userID)
    .then((response) => response.json())
    .then((data) => {
      const sectiune = document.getElementById("date_personale");

      sectiune.querySelector("#nume").value = data.nume;
      sectiune.querySelector("#prenume").value = data.prenume;
      sectiune.querySelector("#nume_utilizator").value = data.username;
      sectiune.querySelector("#email").value = data.email;
      sectiune.querySelector("#data_nasterii").value = data.dataNasterii;
      sectiune.querySelector("#biografie").value = data.biografie;
    })
    .catch((error) =>
      console.error("Eroare la încărcarea utilizatorlui:", error)
    );

    //Modifica datele utilizator
  const date_personale = document.getElementById(
    "date_personale"
  );
  const nume = document.getElementById("nume");
  const prenume = document.getElementById("prenume");
  const nume_utilizator = document.getElementById("nume_utilizator");
  const poza_profil = document.getElementById("poza_profil");
  const email = document.getElementById("email");
  const data_nasterii = document.getElementById("data_nasterii");
  const biografie = document.getElementById("biografie");
  const parola1 = document.getElementById("parola1");
  const parola2 = document.getElementById("parola2");

  date_personale.addEventListener("submit", function (event) {
    event.preventDefault();

    async function preiaDatele() {
      let data = {
        nume: nume.value,
        prenume: prenume.value,
        nume_utilizator: nume_utilizator.value,
        poza_profil: poza_profil.files[0],
        email: email.value,
        data_nasterii: data_nasterii.value,
        biografie: biografie.value,
        parola1: parola1.value,
        parola2: parola2.value,
      };

      const apiUrl = "../actiuni/modifica_utilizator_setari.php";

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
      const settings = document.querySelector(".settings");
      settings.insertBefore(mesaj, settings.firstChild);
    }

    preiaDatele();
  });

  //Sterge utilizator
  document
    .getElementById("stergeUtilizator")
    .addEventListener("click", function () {
      const userID = document.getElementById("userID").value;

      if (!userID) {
        alert("ID-ul utilizatorului lipsește.");
        return;
      }

      if (confirm("Ești sigur că vrei să ștergi acest utilizator?")) {
        fetch("../actiuni/sterge_utilizator.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "userID=" + encodeURIComponent(userID),
        })
          .then((response) => response.text())
          .then((text) => {
            console.log("Raw response:", text);
            let data;
            try {
              data = JSON.parse(text);
            } catch (e) {
              alert("Eroare la interpretarea răspunsului serverului.");
              console.error("JSON parse error:", e);
              return;
            }

            if (data.success) {
              alert("Utilizatorul a fost șters cu succes.");
              window.location.href = "../login.php";
            } else {
              alert("Eroare: " + (data.message || "Ștergerea a eșuat."));
            }
          })
          .catch((error) => {
            console.error("Eroare la fetch:", error);
            alert("Eroare de rețea sau server.");
          });
      }
    });
});

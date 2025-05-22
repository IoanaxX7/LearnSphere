window.addEventListener("load", function (e) {
  const userID = document.getElementById("userID").value;

  if (!userID) {
    console.error("ID-ul utilizatorului nu este setat.");
    return;
  }

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

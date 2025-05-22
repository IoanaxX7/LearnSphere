window.addEventListener("load", function (e) {
  //Adauga datele utilizatorului in caseta modala
  document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", function () {
      const categorieID = this.getAttribute("id");
      document.getElementById("categorieID").value = categorieID;

      fetch("../actiuni/preia_categorie.php?id=" + categorieID)
        .then((response) => response.json())
        .then((data) => {
          const modal = document.getElementById("modal_modifica_categorie");

          modal.querySelector("#categorieID").value = data.categorieID;
          modal.querySelector("#modifica_nume_categorie").value = data.nume;
          modal.querySelector("#modifica_descriere").value = data.descriere;
        })
        .catch((error) =>
          console.error("Eroare la încărcarea categoriei:", error)
        );
    });
  });

  //Sterge utilizator
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const categorieID = this.id;

      if (confirm("Ești sigur că vrei să ștergi această categorie?")) {
        fetch("../actiuni/sterge_categorie.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `categorieID=${categorieID}`,
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

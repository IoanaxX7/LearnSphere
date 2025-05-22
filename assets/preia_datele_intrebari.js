window.addEventListener("load", function (e) {

  //Adauga datele materialului in caseta modala
  document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", function () {
      const intrebareID = this.getAttribute("id");
      document.getElementById("intrebareID").value = intrebareID;

      fetch("../actiuni/preia_intrebare.php?id=" + intrebareID)
        .then((response) => response.json())
        .then((data) => {
          const modal = document.getElementById("modal_modifica_intrebare");

          modal.querySelector("#interbare").value = data.intrebare;
          modal.querySelector("#categorie").value = data.categorieID;
          modal.querySelector("#detalii").value = data.detalii;
          modal.querySelector("#material").value = data.material;
        })
        .catch((error) =>
          console.error("Eroare la încărcarea întrebării:", error)
        );
    });
  });

  //Sterge material
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const intrebareID = this.id;

      if (confirm("Ești sigur că vrei să ștergi acestă întrebare?")) {
        fetch("../actiuni/sterge_intrebare.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `intrebareID=${intrebareID}`,
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

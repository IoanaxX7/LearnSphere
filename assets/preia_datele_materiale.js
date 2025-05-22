window.addEventListener("load", function (e) {

  //Adauga datele materialului in caseta modala
  document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", function () {
      const materialID = this.getAttribute("id");
      document.getElementById("materialID").value = materialID;

      fetch("../actiuni/preia_material.php?id=" + materialID)
        .then((response) => response.json())
        .then((data) => {
          const modal = document.getElementById("modal_modifica_material");

          modal.querySelector("#titlu").value = data.titlu;
          modal.querySelector("#categorie").value = data.categorieID;
          modal.querySelector("#descriere").value = data.descriere;
          modal.querySelector("#cuvinte_cheie").value =
            data.cuvinte_cheie;
          modal.querySelector("#material").value = data.material;
        })
        .catch((error) =>
          console.error("Eroare la încărcarea materialului:", error)
        );
    });
  });

  //Sterge material
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const materialID = this.id;

      if (confirm("Ești sigur că vrei să ștergi acest material?")) {
        fetch("../actiuni/sterge_material.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `materialID=${materialID}`,
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

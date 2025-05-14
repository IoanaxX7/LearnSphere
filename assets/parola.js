window.addEventListener("DOMContentLoaded", (event) => {
  const parola1 = document.getElementById("parola1");
  const parola2 = document.getElementById("parola2");

  parola1.addEventListener("keyup", (event) => {
    const password = document.getElementById("parola1").value;
    const putere_parola = document.getElementById("putere_parola");
    const litere_mici = document.getElementById("litere_mici");
    const litere_mari = document.getElementById("litere_mari");
    const cifre = document.getElementById("cifre");
    const caractere_speciale = document.getElementById("caractere_speciale");
    const lungime_parola = document.getElementById("lungime_parola");

    if (password.length == 0) {
      putere_parola.innerHTML = "";
      return;
    }

    var regex = new Array();
    regex.push("[a-z]");
    regex.push("[A-Z]");
    regex.push("[0-9]");
    regex.push("[$@$!%*#?&]");

    if (new RegExp(regex[0]).test(password)) {
      litere_mici.classList.remove("bi-x-lg");
      litere_mici.classList.add("bi-check");
      litere_mici.style.color = "#00A41E";
    } else {
      litere_mici.classList.remove("bi-check");
      litere_mici.classList.add("bi-x-lg");
      litere_mici.style.color = "#FF0004";
    }

    if (new RegExp(regex[1]).test(password)) {
      litere_mari.classList.remove("bi-x-lg");
      litere_mari.classList.add("bi-check");
      litere_mari.style.color = "#00A41E";
    } else {
      litere_mari.classList.remove("bi-check");
      litere_mari.classList.add("bi-x-lg");
      litere_mari.style.color = "#FF0004";
    }

    if (new RegExp(regex[2]).test(password)) {
      cifre.classList.remove("bi-x-lg");
      cifre.classList.add("bi-check");
      cifre.style.color = "#00A41E";
    } else {
      cifre.classList.remove("bi-check");
      cifre.classList.add("bi-x-lg");
      cifre.style.color = "#FF0004";
    }

    if (new RegExp(regex[3]).test(password)) {
      caractere_speciale.classList.remove("bi-x-lg");
      caractere_speciale.classList.add("bi-check");
      caractere_speciale.style.color = "#00A41E";
    } else {
      caractere_speciale.classList.remove("bi-check");
      caractere_speciale.classList.add("bi-x-lg");
      caractere_speciale.style.color = "#FF0004";
    }

    if (password.length > 8) {
      lungime_parola.classList.remove("bi-x-lg");
      lungime_parola.classList.add("bi-check");
      lungime_parola.style.color = "#00A41E";
    } else {
      lungime_parola.classList.remove("bi-check");
      lungime_parola.classList.add("bi-x-lg");
      lungime_parola.style.color = "#FF0004";
    }

    let passed = 0;

    for (var i = 0; i < regex.length; i++) {
      if (new RegExp(regex[i]).test(password)) {
        passed++;
      }
    }

    let color = "";
    let strength = "";
    let className = "";

    switch (passed) {
      case 0:
      case 1:
        strength = "Foarte slabă";
        className = "veryWeak";
        break;
      case 2:
        strength = "Slabă";
        className = "weak";
        break;
      case 3:
        strength = "Moderată";
        className = "moderate";
        break;
      case 4:
        strength = "Puternică";
        className = "strong";
        break;
      case 5:
        strength = "Foarte puternică";
        className = "veryStrong";
        break;
    }
    
    putere_parola.className = "";
    putere_parola.classList.add(className);
    putere_parola.innerHTML = strength;

  });
  document.querySelectorAll('input[type="password"]').forEach((item) => {
    item.addEventListener("keyup", (event) => {
      const valParola1 = document.getElementById("parola1").value;
      const valParola2 = document.getElementById("parola2").value;
      const potrivire_parole = document.getElementById("potrivire_parole");

      if (valParola1 == valParola2) {
        potrivire_parole.classList.remove("bi-x-lg");
        potrivire_parole.classList.add("bi-check");
        potrivire_parole.style.color = "#00A41E";
        potrivire_parole.textContent = "parolele se potrivesc";
      } else {
        potrivire_parole.classList.remove("bi-check");
        potrivire_parole.classList.add("bi-x-lg");
        potrivire_parole.style.color = "#FF0004";
        potrivire_parole.textContent = "parolele nu se potrivesc";
      }
    });
  });
});

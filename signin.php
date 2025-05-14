<?php

file_exists(__DIR__ . "/config.php") ?
    require_once __DIR__ . "/config.php" :
    die("Fisierul nu a fost gasit!");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Conectare</title>
    <?php
    file_exists(__DIR__ . "/module/head.php") ?
        require_once __DIR__ . "/module/head.php" :
        die("Fisierul head nu a fost gasit!");
    ?>
    <link rel="stylesheet" href="assets/stiluriForms.css">
    <script type="text/javascript" src="assets/parola.js"></script>
</head>

<body class="login-body">
    <section class="login-page">
        <div class="form-box">
            <div class="form-value">
                <form action="actiuni/signin_process.php" method="post">
                    <div class="buttons">
                        <button type="button" class="btn-title active" onclick="location.href='signin.php'">Sign Up</button>
                        <button type="button" class="btn-title" onclick="location.href='login.php'">Log in</button>
                    </div>
                    <div class="inputbox">
                        <label for="nume">Nume:</label>
                        <input type="text" id="nume" name="nume" required>
                    </div>
                    <div class="inputbox">
                        <label for="prenume">Prenume:</label>
                        <input type="text" id="prenume" name="prenume" required>
                    </div>
                    <div class="inputbox">
                        <label for="nume_utilizator">Nume utilizator:</label>
                        <input type="text" id="nume_utilizator" name="nume_utilizator" required>
                    </div>
                    <div class="inputbox">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required>
                    </div>
                    <div class="inputbox">
                        <label for="data_nasterii">Data nasterii:</label>
                        <input type="date" id="data_nasterii" name="data_nasterii" required>
                    </div>
                    <div class="inputbox">
                        <label for="parola1">Parola:</label>
                        <input type="password" id="parola1" name="parola1" required>
                    </div>
                    <div id="ajutorParola1" class="form-text">
                        <span id="litere_mici" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mica <br>
                        <span id="litere_mari" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mare <br>
                        <span id="cifre" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un numar <br>
                        <span id="caractere_speciale" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un caracter special <br>
                        <span id="lungime_parola" class="bi bi-x-lg" style="color: #FF0004;"></span> minim 9 cifre <br>
                        <span id="putere_parola" class=""></span>
                    </div>
                    <div class="inputbox">
                        <label for="parola2">Confirmati parola:</label>
                        <input type="password" id="parola2" name="parola2" required>
                    </div>
                    <div id="ajutorParola2" class="form-text">
                        <span id="potrivire_parole" class="bi bi-x-lg" style="color: #FF0004;"></span>
                    </div>
                    <button class="form-btn form-btn-signin">Sign in</button>
                </form>
            </div>
        </div>
    </section>
</body>
<script>
    function afisareMesaj() {
        var mesaj = document.getElementById('mesaj');
        mesaj.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Datele de autentificare sunt incorecte!<br>Încercați o noua conectare sau contactați administratorul.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
</script>

</html>
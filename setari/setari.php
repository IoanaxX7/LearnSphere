<?php

if (!file_exists(__DIR__ . "/../config.php")) {
    exit("Eroare! Fisierul de conectare nu a fost gasit.");
}

require_once(__DIR__ . "/../config.php");

if (empty($_SESSION["username"]) || !in_array($_SESSION["rol"], $Roluri)) {
    header("Location: logout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    file_exists(__DIR__ . "/../module/head.php") ?
        require_once __DIR__ . "/../module/head.php" :
        die("Fisierul head nu a fost gasit!");
    ?>
    <title>Setări</title>
    <link rel="stylesheet" href="../assets/stiluriForms.css">
</head>

<body>
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>

    <section class="login-page">
        <div class="form-box form-profil">
            <div class="form-value">
                <form action="" method="post" id="date_personale">
                    <div class="signin-body settings">
                        <h2 class="form-title">Date personale</h2>
                        <input type="hidden" id="userID" name="userID" <?php echo 'value="' . $_SESSION['userID'] . '"'; ?>>
                        <div class="inputbox">
                            <label for="nume">Nume:</label>
                            <input type="text" id="nume" name="nume">
                        </div>
                        <div class="inputbox">
                            <label for="prenume">Prenume:</label>
                            <input type="text" id="prenume" name="prenume" value="">
                        </div>
                        <div class="inputbox">
                            <label for="nume_utilizator">Nume utilizator:</label>
                            <input type="text" id="nume_utilizator" name="nume_utilizator" value="">
                        </div>
                        <div class="inputbox">
                            <label for="poza_profil">Poza de profil:</label>
                            <input type="file" id="poza_profil" class="poza_profil" name="poza_profil">
                        </div>
                        <div class="inputbox">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="">
                        </div>
                        <div class="inputbox">
                            <label for="data_nasterii">Data nașterii:</label>
                            <input type="date" id="data_nasterii" name="data_nasterii" value="">
                        </div>
                        <div class="inputbox">
                            <label for="biografie">Biografie:</label>
                            <input type="text" id="biografie" name="biografie" value="">
                        </div>
                        <div class="inputbox">
                            <label for="parola1">Parola:</label>
                            <input type="password" id="parola1" name="parola1">
                        </div>
                        <div id="ajutorParola1" class="form-text">
                            <span id="litere_mici" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mica <br>
                            <span id="litere_mari" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mare <br>
                            <span id="cifre" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un numar <br>
                            <span id="caractere_speciale" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un caracter special <br>
                            <span id="lungime_parola" class="bi bi-x-lg" style="color: #FF0004;"></span> minim 9 caractere <br>
                            <span id="putere_parola" class=""></span>
                        </div>
                        <div class="inputbox">
                            <label for="parola2">Confirmați parola:</label>
                            <input type="password" id="parola2" name="parola2">
                        </div>
                        <div id="ajutorParola2" class="form-text">
                            <span id="potrivire_parole" class="bi bi-x-lg" style="color: #FF0004;"></span>
                        </div>
                        <div style="margin-top: .5em;">
                            <div class="alerte"></div>
                        </div>
                        <button class="form-btn form-btn-signin">Modifică</button>
                        <button type="button" class="form-btn form-btn-delete" id="stergeUtilizator">Șterge utilizator</button>

                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="../assets/parola.js"></script>
    <script type="text/javascript" src="../assets/preia_datele_setari.js"></script>

</body>

</html>
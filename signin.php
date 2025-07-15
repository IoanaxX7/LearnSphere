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
    <script type="text/javascript" src="assets/preia_datele_signin.js"></script>
</head>

<body class="login-body">
    <section class="login-page">
        <div class="form-box">
            <div class="form-value">
                <form action="" method="post" id="signup">
                    <div class="signin-body">
                        <div class="buttons">
                            <button type="button" class="btn-title active" onclick="location.href='signin.php'">Înregistrare</button>
                            <button type="button" class="btn-title" onclick="location.href='login.php'">Autentificare</button>
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
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="inputbox">
                            <label for="data_nasterii">Data nașterii:</label>
                            <input type="date" id="data_nasterii" name="data_nasterii" required>
                        </div>
                        <div class="inputbox">
                            <div class="input-group" style="display: flex; align-items: center; width: 100%; position: relative;">
                                <label for="parola1">Parola:</label>
                                <div style="position: relative; flex: 1;">
                                    <input type="password" id="parola1" name="parola1" required style="width: 100%; padding-right: 35px;">
                                    <button type="button" class="toggle-password"
                                        aria-controls="parola1" aria-label="Afișează parola"
                                        style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); border: none; background: none; padding: 0; cursor: pointer;">
                                        <i class="bi bi-eye-slash" style="font-size: 1.2em; color: var(--txt);"></i>
                                    </button>
                                </div>
                            </div>
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
                            <div class="input-group" style="display: flex; align-items: center; width: 100%; position: relative;">
                                <label for="parola2">Confirmați parola:</label>
                                <div style="position: relative; flex: 1;">
                                    <input type="password" id="parola2" name="parola2" required style="width: 100%; padding-right: 35px;">
                                    <button type="button" class="toggle-password"
                                        aria-controls="parola2" aria-label="Afișează parola"
                                        style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); border: none; background: none; padding: 0; cursor: pointer;">
                                        <i class="bi bi-eye-slash" style="font-size: 1.2em; color: var(--txt);"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="ajutorParola2" class="form-text">
                            <span id="potrivire_parole" class="bi bi-x-lg" style="color: #FF0004;"></span>
                        </div>

                        <div class="alert-wrapper">
                            <div class="alert_signin"></div>
                        </div>

                        <button class="form-btn form-btn-signin">Înregistrare</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="assets/show_hide_passwords.js"></script>
</body>

</html>
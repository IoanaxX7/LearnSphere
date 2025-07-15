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
</head>

<body class="login-body">
    <section class="login-page">
        <div class="form-box">
            <div class="form-value">
                <form action="actiuni/login_process.php" method="post">
                    <div class="buttons">
                        <button type="button" class="btn-title" onclick="location.href='signin.php'">Înregistrare</button>
                        <button type="button" class="btn-title active" onclick="location.href='login.php'">Autentificare</button>
                    </div>
                    <div class="inputbox">
                        <label for="username">Nume utilizator:</label>
                        <input type="username" id="nume_utilizator" name="nume_utilizator" required>
                    </div>
                    <div class="inputbox">
                        <div class="input-group" style="display: flex; align-items: center; width: 100%; position: relative;">
                            <label for="parola" style="margin-right: 10px; white-space: nowrap;">Parola:</label>

                            <div style="position: relative; flex: 1;">
                                <input type="password" id="parola" name="parola" required style="width: 100%; padding-right: 35px;">

                                <button type="button" class="toggle-password"
                                    aria-controls="parola" aria-label="Afișează parola"
                                    style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); border: none; background: none; padding: 0; cursor: pointer;">
                                    <i class="bi bi-eye-slash" style="font-size: 1.2em; color: var(--txt);"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="forgot-password">
                        <a href="password_reset.php">Ai uitat parola?</a>
                    </div>
                    <button class="form-btn">Autentificare</button>
                </form>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="assets/show_hide_passwords.js"></script>
</body>

</html>
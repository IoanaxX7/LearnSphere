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
                        <button type="button" class="btn-title" onclick="location.href='signin.php'">Sign Up</button>
                        <button type="button" class="btn-title active" onclick="location.href='login.php'">Log in</button>
                    </div>
                    <div class="inputbox">
                        <label for="username">Nume utilizator:</label>
                        <input type="username" id="nume_utilizator" name="nume_utilizator" required>
                    </div>
                    <div class="inputbox">
                        <label for="password">Parola:</label>
                        <input type="password" id="parola" name="parola" required>
                    </div>
                    <div class="forgot-password">
                        <a href="password_reset.php">Ai uitat parola?</a>
                    </div>
                    <button class="form-btn">Log in</button>
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
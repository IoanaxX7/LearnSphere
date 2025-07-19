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
                 <form action="actiuni/password_reset_process.php" method="post" id="password_reset">
                     <div class="buttons">
                         <button type="button" class="btn-title" onclick="location.href='signin.php'">Înregistrare</button>
                         <button type="button" class="btn-title active" onclick="location.href='login.php'">Autentificare</button>
                     </div>
                     <div class="inputbox">
                         <label for="email">Email:</label>
                         <input type="email" id="email" name="email" required>
                     </div>
                     <div class="alerte_resetare_email"></div>
                     <button class="form-btn">Trimite email</button>
                 </form>
             </div>
         </div>
     </section>

     <script type="text/javascript" src="assets/preia_datele_password_reset.js"></script>
 </body>

 </html>
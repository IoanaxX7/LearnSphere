<?php

if (!file_exists(__DIR__ . "/../config.php")) {
    exit("Eroare! Fisierul de conectare nu a fost gasit.");
}

require_once(__DIR__ . "/../config.php");

if (empty($_SESSION["username"]) || !in_array($_SESSION["rol"], $Roluri)) {
    $_SESSION["rol"] = 3;
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
    <title>Încarcă un material</title>
    <link rel="stylesheet" href="../assets/stiluriForms.css">
</head>

<body>
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>

    <?php if ($_SESSION["rol"] == 3): ?>

        <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; margin-top: -3em;">
            <div class="alert text-center" role="alert" style="background-color: white; max-width: 700px; width: 90%; border-radius: 1.3em; padding-top: 1.5em; padding-bottom: 1.5em;">
                <h4 class="alert-heading mb-4">Conectează-te pentru a pune o întrebare</h4>
                <p class="fs-5">
                    Te rugăm să
                    <a href="../signin.php" class="fw-bold text-decoration-none">te înregistrezi</a>
                    sau să
                    <a href="../login.php" class="fw-bold text-decoration-none">te conectezi</a>
                    pentru a pune o întrebare.
                </p>
            </div>
        </div>

    <?php else: ?>
        <section class="login-page form-material">
            <div class="form-box">
                <div class="form-value">
                    <form action="../actiuni/adauga_material.php" enctype="multipart/form-data" method="post" class="material_form">
                        <h2 class="form-title">Încarcă un material</h2>
                        <div class="inputbox">
                            <label for="titlu">Titlu:</label>
                            <input type="text" id="titlu" name="titlu" required>
                        </div>
                        <div class="inputbox">
                            <label for="categorie" class="col-form-label">Categorie:</label>
                            <div>
                                <select name="categorie" id="categorie" class="form-control categorii" required>
                                    <option selected value> -- alegeți o opțiune -- </option>
                                    <?php
                                    try {
                                        $cerereSQL = $conexiune->query('SELECT * FROM categorii');
                                        while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                            echo '
                                        <option value="' . $rand["categorieID"] . '">' . $rand["nume"] . '</option>
                                        ';
                                        }
                                    } catch (PDOException $e) {
                                        exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Trebuie să alegi o categorie.</div>

                            </div>
                        </div>
                        <div class="adauga-categorie">
                            <a href="" data-bs-toggle="modal" data-bs-target="#modal_adauga_categorie">
                                Adaugă o categorie
                            </a>
                        </div>
                        <div class="inputbox">
                            <label for="descriere">Descriere (opțional):</label>
                            <input type="text" id="descriere" name="descriere">
                        </div>
                        <div class="inputbox">
                            <label for="cuvinte_cheie">Cuvinte cheie (opțional):</label>
                            <input type="text" id="cuvinte_cheie" name="cuvinte_cheie">
                        </div>
                        <div class="inputbox file">
                            <label for="material">Material:</label>
                            <input type="file" id="material" name="material" required>
                        </div>
                        <button class="form-btn">Postează</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Casetă modală - Adaugă o categorie-->
        <div class="modal fade" id="modal_adauga_categorie" class="modal_adauga_categorie" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="eticheta_adauga_categorie" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="eticheta_adauga_categorie">Adaugă
                            o categorie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                    </div>
                    <form method="post" action="" id="form_adauga_categorie">
                        <div class="modal-body">
                            <div class="row mt-3">
                                <label for="nume_catgorie" class="col-form-label">Nume categorie:</label>
                                <div>
                                    <input type="text" class="form-control" id="nume_catgorie" name="nume_catgorie"
                                        aria-describedby="ajutorNumeCategorie" required>
                                    <div class="invalid-feedback">Numele categoriei este obligatoriu.</div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label for="descriere" class="col-form-label">Descriere (optional):</label>
                                <div>
                                    <input type="text" class="form-control" id="descriere" name="descriere">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                                <button type="submit" class="btn btn-secondary">Adaugă</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="../assets/preia_datele_categorie.js"></script>
    <?php endif; ?>
</body>

</html>
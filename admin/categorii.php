<?php

if (!file_exists(__DIR__ . "/../config.php")) {
    exit("Eroare! Fisierul de conectare nu a fost gasit.");
}

require_once(__DIR__ . "/../config.php");

if (empty($_SESSION["username"]) || !in_array($_SESSION["rol"], [1])) {
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
    <title>Categorii</title>
</head>

<body>
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>



    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card">
                    <h2 class="card-header">Categorii</h2>
                    <div class="card-body">
                        <div style="display: flex;">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#modal_adauga_categorie">
                                Adaugă categorie
                            </button>
                            <form class="d-flex" role="search" style="margin-left: 1rem;">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search" style="border-color:gray">
                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                        <h4 class="card-title mt-4">Tabel categorii</h4>
                        <div class="mesaj" style="display: none; margin: 5px 0;"></div>

                        <table class="table mt-4 table-striped" id="tabel_utilizatori">
                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th>Nume categorie</th>
                                    <th>Descriere</th>
                                    <th>Data Postării</th>
                                    <th class="text-center">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $cerereSQL = $conexiune->query('SELECT * FROM categorii');
                                    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr>
                                            <td style="display: none;">' . $rand["categorieID"] . '</td>
                                            <td>' . $rand["nume"] . '</td>
                                            <td>' . $rand["descriere"] . '</td>
                                            <td>' . $rand["dataPostarii"] . '</td>
                                            <td class="text-center">
                                                <a id="' . $rand["categorieID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_categorie"
                                                style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                                <a id="' . $rand["categorieID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
                                            </td>';
                                    }
                                } catch (PDOException $e) {
                                    exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Casetă modală - Adaugă categorie-->
    <div class="modal fade" id="modal_adauga_categorie" data-bs-backdrop="static" data-bs-keyboard="false"
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


    <!-- Casetă modală - Modifica categorie-->
    <div class="modal fade" id="modal_modifica_categorie" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_modifica_categorie" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_modifica_categorie">Modifică
                        categoria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_modifica_categorie">
                    <div class="modal-body">
                        <input type="hidden" id="categorieID" name="categorieID" value="">
                        <div class="row mt-3">
                            <label for="modifica_nume_categorie" class="col-form-label">Nume categorie:</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_nume_categorie" name="modifica_nume_categorie" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_descriere" class="col-form-label">Descriere (optional):</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_descriere" name="modifica_descriere" value="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                            <button type="submit" class="btn btn-secondary">Modifică</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/preia_datele_categorii.js"></script>
</body>

</html>
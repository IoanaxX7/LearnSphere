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
    <title>Materiale</title>
</head>

<body>
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>



    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <h2 class="card-header">Întrebări</h2>
                    <div class="card-body">
                        <div style="display: flex;">
                            <form class="d-flex" role="search">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search" style="border-color:gray">
                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                        <h4 class="card-title mt-4">Tabel materiale</h4>
                        <div class="mesaj" style="display: none; margin: 5px 0;"></div>

                        <table class="table mt-4 table-striped" id="tabel_utilizatori">
                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th>Înterbare</th>
                                    <th>Utilizator</th>
                                    <th>Categorie</th>
                                    <th>Detalii</th>
                                    <th>Material</th>
                                    <th>Data Postării</th>
                                    <th class="text-center">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $cerereSQL = $conexiune->query('
                                        SELECT i.*, u.username AS username, c.nume
                                        FROM intrebari i
                                        JOIN users u ON i.userID = u.userID
                                        JOIN categorii c ON i.categorieID = c.categorieID
                                    ');

                                    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr>
                                            <td style="display: none;">' . $rand["intrebareID"] . '</td>
                                            <td>' . $rand["intrebare"] . '</td>
                                            <td>' . $rand["username"] . '</td>
                                            <td>' . $rand["nume"] . '</td>
                                            <td>' . $rand["detalii"] . '</td>
                                            <td>' . $rand["material"] . '</td>
                                            <td>' . $rand["dataPostarii"] . '</td>
                                            <td class="text-center">
                                                <a id="' . $rand["intrebareID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_intrebare"
                                                    style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                                <a id="' . $rand["intrebareID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
                                            </td>
                                        </tr>';
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

    <!-- Casetă modală - Modifica intrebare-->
    <div class="modal fade" id="modal_modifica_intrebare" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_modifica_intrebare" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_modifica_intrebare">Modifică întrebarea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_modifica_intrebare">
                    <div class="modal-body">
                        <input type="hidden" id="intrebareID" name="intrebareID" value="">
                        <div class="row mt-3">
                            <label for="interbare" class="col-form-label">Întrebare:</label>
                            <div>
                                <input type="text" class="form-control" id="interbare" name="interbare" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="categorie" class="col-form-label">Categorie:</label>
                            <div>
                                <select name="categorie" id="categorie" class="form-control categorii">
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
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="detalii" class="col-form-label">Detalii (opțional):</label>
                            <div>
                                <input type="text" class="form-control" id="detalii" name="detalii" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="material" class="col-form-label">Material (opțional):</label>
                            <div>
                                <input type="text" class="form-control" id="material" name="material" value="">
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

    <script type="text/javascript" src="../assets/preia_datele_intrebari.js"></script>
</body>

</html>
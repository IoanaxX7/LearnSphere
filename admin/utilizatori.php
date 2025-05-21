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
    <title>Utilizatori</title>
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
                    <h2 class="card-header">Utilizatori</h2>
                    <div class="card-body">
                        <div style="display: flex;">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#modal_adauga_utilizator">
                                Adaugă utilizator
                            </button>
                            <form class="d-flex" role="search" style="margin-left: 1rem;">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search" style="border-color:gray">
                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                        <h4 class="card-title mt-4">Tabel utilizatori</h4>
                        <div class="mesaj" style="display: none; margin: 5px 0;"></div>

                        <table class="table mt-4 table-striped" id="tabel_utilizatori">
                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th>Nume</th>
                                    <th>Prenume</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Tip utilizator</th>
                                    <th>Data nașterii</th>
                                    <th>Data înregistrării</th>
                                    <th class="text-center">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $cerereSQL = $conexiune->query('SELECT * FROM users');
                                    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr>
                                            <td style="display: none;">' . $rand["userID"] . '</td>
                                            <td>' . $rand["nume"] . '</td>
                                            <td>' . $rand["prenume"] . '</td>
                                            <td>' . $rand["username"] . '</td>
                                            <td>' . $rand["email"] . '</td>
                                            <td>' . $rand["rol"] . '</td>
                                            <td>' . $rand["dataNasterii"] . '</td>
                                            <td>' . $rand["dataInregistrarii"] . '</td>
                                            <td class="text-center">
                                                <a id="' . $rand["userID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_utilizator"
                                                style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                                <a id="' . $rand["userID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
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


    <!-- Casetă modală - Adaugă utilizator-->
    <div class="modal fade" id="modal_adauga_utilizator" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_adauga_utilizator" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_adauga_utilizator">Adaugă
                        utilizator</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_adauga_utilizator">
                    <div class="modal-body">
                        <div class="row">
                            <label for="rol" class="col-form-label">Tip utilizator:</label>
                            <div>
                                <select name="rol" id="rol" class="form-control" required>
                                    <option selected value> -- alegeți o opțiune -- </option>
                                    <option value="1">administrator</option>
                                    <option value="2">utilizator</option>
                                </select>
                                <div class="invalid-feedback">Trebuie să alegi un rol de utilizator.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="nume" class="col-form-label">Nume:</label>
                            <div>
                                <input type="text" class="form-control" id="nume" name="nume"
                                    aria-describedby="ajutorNume" required>
                                <div class="invalid-feedback">Numele este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="prenume" class="col-form-label">Prenume:</label>
                            <div>
                                <input type="text" class="form-control" id="prenume" name="prenume"
                                    aria-describedby="ajutorPrenume" required>
                                <div class="invalid-feedback">Prenumele este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="nume_utilizator" class="col-form-label">Nume utilizator:</label>
                            <div>
                                <input type="text" class="form-control" id="nume_utilizator" name="nume_utilizator"
                                    aria-describedby="ajutorNumeUtilizator" required>
                                <div class="invalid-feedback">Nume de utilizator este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="email" class="col-form-label">Email:</label>
                            <div>
                                <input type="text" class="form-control" id="email" name="email"
                                    aria-describedby="ajutorEmail" required>
                                <div class="invalid-feedback">Emailul este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="data_nasterii" class="col-form-label">Data nașterii:</label>
                            <div>
                                <input type="date" class="form-control" id="data_nasterii" name="data_nasterii"
                                    aria-describedby="ajutorDataNasterii" required>
                                <div class="invalid-feedback">Data nașterii este obligatorie.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="parola1" class="col-form-label">Parola:</label>
                            <div>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="parola1" name="parola1"
                                        aria-describedby="ajutorParola1" autocomplete="off" required>
                                    <button type="button" class="btn btn-secondary" id="afisare_parola" role="switch"
                                        aria-label="Afiseza parola" aria-checked="false"><i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div id="ajutorParola1" class="form-text">
                                    <span id="litere_mici" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mica <br>
                                    <span id="litere_mari" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mare <br>
                                    <span id="cifre" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un numar <br>
                                    <span id="caractere_speciale" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un caracter special <br>
                                    <span id="lungime_parola" class="bi bi-x-lg" style="color: #FF0004;"></span> minim 9 caractere <br>
                                    <span id="putere_parola" class=""></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <label for="parola2" class="col-form-label">Confirmați parola:</label>
                            <div>
                                <input type="password" class="form-control" id="parola2" name="parola2"
                                    aria-describedby="ajutorParola2" autocomplete="off" required>
                                <div id="ajutorParola2" class="form-text">
                                    <span id="potrivire_parole" class="bi bi-x-lg" style="color:#FF0004;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript" src="../assets/parola.js"></script>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                        <button type="submit" class="btn btn-secondary">Adaugă</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../assets/parola.js"></script>


    <!-- Casetă modală - Modifica utilizator-->
    <div class="modal fade" id="modal_modifica_utilizator" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_modifica_utilizator" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_modifica_utilizator">Modifică
                        utilizator</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_modifica_utilizator">
                    <div class="modal-body">
                       <input type="hidden" id="userID" name="userID"  value="">
                        <div class="row">
                            <label for="modifica_rol" class="col-form-label">Tip utilizator:</label>
                            <div>
                                <select name="modifica_rol" id="modifica_rol" class="form-control">
                                    <option selected value> -- alegeți o opțiune -- </option>
                                    <option value="1">administrator</option>
                                    <option value="2">utilizator</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_nume" class="col-form-label">Nume:</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_nume" name="modifica_nume"
                                    aria-describedby="ajutorNume" value="">
                                <div class="invalid-feedback">Numele este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_prenume" class="col-form-label">Prenume:</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_prenume" name="modifica_prenume"
                                    aria-describedby="ajutorPrenume" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_nume_utilizator" class="col-form-label">Nume utilizator:</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_nume_utilizator" name="modifica_nume_utilizator"
                                    aria-describedby="ajutorNumeUtilizator" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_email" class="col-form-label">Email:</label>
                            <div>
                                <input type="text" class="form-control" id="modifica_email" name="modifica_email"
                                    aria-describedby="ajutorEmail" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_data_nasterii" class="col-form-label">Data nașterii:</label>
                            <div>
                                <input type="date" class="form-control" id="modifica_data_nasterii" name="modifica_data_nasterii"
                                    aria-describedby="ajutorDataNasterii" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="modifica_parola1" class="col-form-label">Parola:</label>
                            <div>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="modifica_parola1" name="modifica_parola1"
                                        aria-describedby="ajutorParola1" autocomplete="off">
                                    <button type="button" class="btn btn-secondary" id="afisare_parola" role="switch"
                                        aria-label="Afiseza parola" aria-checked="false"><i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div id="ajutorParola1" class="form-text">
                                    <span id="litere_mici_parola_modificata" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mica <br>
                                    <span id="litere_mari_parola_modificata" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin o litera mare <br>
                                    <span id="cifre_parola_modificata" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un numar <br>
                                    <span id="caractere_speciale_parola_modificata" class="bi bi-x-lg" style="color: #FF0004;"></span> cel putin un caracter special <br>
                                    <span id="lungime_parola_modificata" class="bi bi-x-lg" style="color: #FF0004;"></span> minim 9 caractere <br>
                                    <span id="putere_parola_modificata" class=""></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <label for="modifica_parola2" class="col-form-label">Confirmați parola:</label>
                            <div>
                                <input type="password" class="form-control" id="modifica_parola2" name="modifica_parola2"
                                    aria-describedby="ajutorParola2" autocomplete="off">
                                <div id="ajutorParola2" class="form-text">
                                    <span id="potrivire_parole_modificate" class="bi bi-x-lg" style="color:#FF0004;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript" src="../assets/parola.js"></script>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                        <button type="submit" class="btn btn-secondary">Modifică</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/preia_datele_utilizatori.js"></script>
    <script type="text/javascript" src="../assets/modifica_parola.js"></script>
</body>

</html>
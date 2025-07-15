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
    <link rel="stylesheet" href="../assets/stiluriAdmin.css">
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
                            <form class="d-flex" role="search" method="GET" style="margin-left: 1rem;">
                                <input class="form-control searchBar" type="search" name="search" placeholder="Caută" aria-label="Search" style="border-color:gray"
                                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

                                <button class="btn btn-outline-secondary searchBtn" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-outline-danger ms-3">Resetează</a>
                            </form>


                        </div>
                        <h4 class="card-title mt-4">Tabel utilizatori</h4>
                        <div class="mesaj" style="display: none; margin: 5px 0;"></div>

                        <table class="table mt-4 table-striped" id="tabel_utilizatori">
                            <?php
                            function sortLink($label, $column, $currentSort, $currentOrder)
                            {
                                if ($currentSort === $column) {
                                    $icon = $currentOrder === 'ASC'
                                        ? '<i class="bi bi-caret-up-fill ms-1"></i>'
                                        : '<i class="bi bi-caret-down-fill ms-1"></i>';
                                } else {
                                    $icon = '<i class="bi bi-funnel-fill"></i>';
                                }

                                $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'desc' : 'asc';
                                $query = http_build_query(array_merge($_GET, ['sort' => $column, 'order' => $newOrder]));

                                return "<a href='?{$query}' class='sort-header' title='Sortează după {$label}'>{$label} {$icon}</a>";
                            }

                            ?>


                            <?php
                            $allowedSortColumns = ['username', 'nume', 'prenume', 'email', 'rol', 'dataNasterii', 'dataInregistrarii'];
                            $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'userID';
                            $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';
                            $nextOrder = $order === 'ASC' ? 'desc' : 'asc';
                            ?>

                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th><?= sortLink('Nume', 'nume', $sort, $order) ?></th>
                                    <th><?= sortLink('Prenume', 'prenume', $sort, $order) ?></th>
                                    <th><?= sortLink('Username', 'username', $sort, $order) ?></th>
                                    <th><?= sortLink('Email', 'email', $sort, $order) ?></th>
                                    <th><?= sortLink('Tip utilizator', 'rol', $sort, $order) ?></th>
                                    <th><?= sortLink('Data nașterii', 'dataNasterii', $sort, $order) ?></th>
                                    <th><?= sortLink('Data înregistrării', 'dataInregistrarii', $sort, $order) ?></th>
                                    <th class="text-center">Acțiuni</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                                $usersPerPage = 10;
                                $offset = ($page - 1) * $usersPerPage;

                                // Validate ORDER BY
                                $allowedSortColumns = ['username', 'nume', 'prenume', 'email', 'rol', 'dataNasterii', 'dataInregistrarii'];
                                $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'userID';
                                $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';

                                // Total users (search-aware)
                                if ($search !== '') {
                                    $stmtTotal = $conexiune->prepare("
                                        SELECT COUNT(*) FROM users
                                        WHERE username LIKE :search OR nume LIKE :search OR prenume LIKE :search OR email LIKE :search
                                    ");
                                    $stmtTotal->execute(['search' => "%$search%"]);
                                } else {
                                    $stmtTotal = $conexiune->query("SELECT COUNT(*) FROM users");
                                }
                                $totalUsers = $stmtTotal->fetchColumn();
                                $totalPages = ceil($totalUsers / $usersPerPage);
                                $page = max(1, min($page, $totalPages));
                                $startIndex = $offset + 1;
                                $endIndex = min($offset + $usersPerPage, $totalUsers);

                                // Fetch users (search-aware, with ORDER BY)
                                if ($search !== '') {
                                    $stmt = $conexiune->prepare("
                                        SELECT * FROM users
                                        WHERE username LIKE :search OR nume LIKE :search OR prenume LIKE :search OR email LIKE :search
                                        ORDER BY $sort $order
                                        LIMIT :limit OFFSET :offset
                                    ");
                                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                                    $stmt->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
                                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                    $stmt->execute();
                                } else {
                                    $stmt = $conexiune->prepare("
                                        SELECT * FROM users
                                        ORDER BY $sort $order
                                        LIMIT :limit OFFSET :offset
                                    ");
                                    $stmt->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
                                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                    $stmt->execute();
                                }

                                // Render users
                                while ($rand = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>
                                        <td style="display: none;">' . $rand["userID"] . '</td>
                                        <td>' . htmlspecialchars($rand["nume"]) . '</td>
                                        <td>' . htmlspecialchars($rand["prenume"]) . '</td>
                                        <td>' . htmlspecialchars($rand["username"]) . '</td>
                                        <td>' . htmlspecialchars($rand["email"]) . '</td>
                                        <td>' . htmlspecialchars($rand["rol"]) . '</td>
                                        <td>' . htmlspecialchars($rand["dataNasterii"]) . '</td>
                                        <td>' . htmlspecialchars($rand["dataInregistrarii"]) . '</td>
                                        <td class="text-center">
                                            <a id="' . $rand["userID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_utilizator"
                                            style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                            <a id="' . $rand["userID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>

                        </table>

                        <?php if ($totalUsers > 0): ?>
                            <p class="text-center mt-2">Afișare utilizatori <?= $startIndex ?>–<?= $endIndex ?> din <?= $totalUsers ?></p>
                        <?php else: ?>
                            <p class="text-center mt-2">Nu s-au găsit utilizatori.</p>
                        <?php endif; ?>

                        <?php if ($totalPages > 1): ?>
                            <?php
                            $queryString = http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY));
                            $urlBase = strtok($_SERVER["REQUEST_URI"], '?') . '?' . $queryString;
                            ?>
                            <nav>
                                <ul class="pagination justify-content-center mt-3" style="gap: 0.5rem;">

                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="btn btn-outline-secondary" href="<?= $urlBase . '&page=' . ($page - 1) ?>">‹</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php
                                    $range = 1;
                                    $startPage = max(1, $page - $range);
                                    $endPage = min($totalPages, $page + $range);

                                    if ($startPage > 2) {
                                        echo '<li><a class="btn btn-outline-secondary" href="' . $urlBase . '&page=1">1</a></li>';
                                        echo '<li><span class="btn btn-light disabled">...</span></li>';
                                    } elseif ($startPage == 2) {
                                        echo '<li><a class="btn btn-outline-secondary" href="' . $urlBase . '&page=1">1</a></li>';
                                    }

                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        if ($i == $page) {
                                            echo '<li><a class="btn btn-secondary text-white" style="pointer-events: none;">' . $i . '</a></li>';
                                        } else {
                                            echo '<li><a class="btn btn-outline-secondary" href="' . $urlBase . '&page=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }

                                    if ($endPage < $totalPages - 1) {
                                        echo '<li><span class="btn btn-light disabled">...</span></li>';
                                        echo '<li><a class="btn btn-outline-secondary" href="' . $urlBase . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                    } elseif ($endPage == $totalPages - 1) {
                                        echo '<li><a class="btn btn-outline-secondary" href="' . $urlBase . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                    }
                                    ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="btn btn-outline-secondary" href="<?= $urlBase . '&page=' . ($page + 1) ?>">›</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
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
                                    <button type="button" class="btn btn-secondary toggle-password" aria-controls="parola1" aria-label="Afișează parola">
                                        <i class="bi bi-eye-slash"></i>
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
                            <div class="input-group">
                                <input type="password" class="form-control" id="parola2" name="parola2"
                                    aria-describedby="ajutorParola2" autocomplete="off" required>
                                <button type="button" class="btn btn-secondary toggle-password" aria-controls="parola2" aria-label="Afișează parola">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div id="ajutorParola2" class="form-text">
                                <span id="potrivire_parole" class="bi bi-x-lg" style="color:#FF0004;"></span>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript" src="../assets/parola.js"></script>

                    <div class="row mt-3 px-3">
                        <div class="alert_adauga_utilizator alerte px-0" style="width: 465px; margin-left: .8em"></div>
                    </div>

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
                        <input type="hidden" id="userID" name="userID" value="">
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
                                    <button type="button" class="btn btn-secondary toggle-password" aria-controls="modifica_parola1" aria-label="Afișează parola">
                                        <i class="bi bi-eye-slash"></i>
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
                            <div class="input-group">
                                <input type="password" class="form-control" id="modifica_parola2" name="modifica_parola2"
                                    aria-describedby="ajutorParola2" autocomplete="off">
                                <button type="button" class="btn btn-secondary toggle-password" aria-controls="modifica_parola2" aria-label="Afișează parola">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div id="ajutorParola2" class="form-text">
                                <span id="potrivire_parole_modificate" class="bi bi-x-lg" style="color:#FF0004;"></span>
                            </div>
                        </div>

                    </div>
                    <script type="text/javascript" src="../assets/parola.js"></script>

                    <div class="row mt-3 px-3">
                        <div class="alert_modifica_utilizator alerte px-0" style="width: 465px; margin-left: .8em"></div>
                    </div>

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
    <script type="text/javascript" src="../assets/show_hide_passwords.js"></script>
</body>

</html>
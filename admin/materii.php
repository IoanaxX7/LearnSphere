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
    <title>Materii</title>
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
                    <h2 class="card-header">Materii</h2>
                    <div class="card-body">
                        <div style="display: flex;">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#modal_adauga_materie">
                                Adaugă materie
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
                        <h4 class="card-title mt-4">Tabel materii</h4>
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
                            $allowedSortColumns = ['nume', 'descriere', 'dataPostarii'];
                            $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'userID';
                            $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';
                            $nextOrder = $order === 'ASC' ? 'desc' : 'asc';
                            ?>

                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th><?= sortLink('Nume', 'nume', $sort, $order) ?></th>
                                    <th><?= sortLink('Descriere', 'descriere', $sort, $order) ?></th>
                                    <th><?= sortLink('Data postării', 'dataPostarii', $sort, $order) ?></th>
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
                                $allowedSortColumns = ['nume', 'descriere', 'dataPostarii'];
                                $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'nume';
                                $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';


                                // Total users (search-aware)
                                if ($search !== '') {
                                    $stmtTotal = $conexiune->prepare("
                                        SELECT COUNT(*) FROM materii
                                        WHERE nume LIKE :search OR descriere LIKE :search
                                    ");
                                    $stmtTotal->execute(['search' => "%$search%"]);
                                } else {
                                    $stmtTotal = $conexiune->query("SELECT COUNT(*) FROM materii");
                                }
                                $totalMaterii = $stmtTotal->fetchColumn();
                                $totalPages = ceil($totalMaterii / $usersPerPage);
                                $page = max(1, min($page, $totalPages));
                                $startIndex = $offset + 1;
                                $endIndex = min($offset + $usersPerPage, $totalMaterii);

                                // Fetch users (search-aware, with ORDER BY)
                                if ($search !== '') {
                                    $stmt = $conexiune->prepare("
                                        SELECT * FROM materii
                                        WHERE nume LIKE :search OR descriere LIKE :search
                                        ORDER BY $sort $order
                                        LIMIT :limit OFFSET :offset
                                    ");
                                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                                    $stmt->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
                                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                    $stmt->execute();
                                } else {
                                    $stmt = $conexiune->prepare("
                                        SELECT * FROM materii
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
                                        <td style="display: none;">' . $rand["materiiID"] . '</td>
                                        <td>' . htmlspecialchars($rand["nume"] ?? '') . '</td>
                                        <td>' . htmlspecialchars($rand["descriere"] ?? '') . '</td>
                                        <td>' . htmlspecialchars($rand["dataPostarii"] ?? '') . '</td>
                                        <td class="text-center">
                                            <a id="' . $rand["materiiID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_materie"
                                            style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                            <a id="' . $rand["materiiID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>

                        </table>

                        <?php if ($totalMaterii > 0): ?>
                            <p class="text-center mt-2">Afișare materii <?= $startIndex ?>–<?= $endIndex ?> din <?= $totalMaterii ?></p>
                        <?php else: ?>
                            <p class="text-center mt-2">Nu s-au găsit materii.</p>
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


    <!-- Casetă modală - Adaugă materie-->
    <div class="modal fade" id="modal_adauga_materie" class="modal_adauga_materie" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_adauga_materie" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_adauga_materie">Adaugă
                        o materie</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_adauga_materie">
                    <div class="modal-body">
                        <div class="row mt-3">
                            <label for="nume_adauga_materie" class="col-form-label">Nume materie:</label>
                            <div>
                                <input type="text" class="form-control" id="nume_adauga_materie" name="nume_adauga_materie"
                                    aria-describedby="ajutorNumeMaterial" required>
                                <div class="invalid-feedback">Numele materiei este obligatoriu.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="descriere_adauga_materie" class="col-form-label">Descriere (optional):</label>
                            <div style="margin-bottom: 1em;">
                                <input type="text" class="form-control" id="descriere_adauga_materie" name="descriere_adauga_materie">
                            </div>
                        </div>
                        <div class="alert_adauga_materie alerte"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                            <button type="submit" class="btn btn-secondary">Adaugă</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Casetă modală - Modifica materie-->
    <div class="modal fade" id="modal_modifica_materie" class="modal_modifica_materie" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_modifica_materie" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_modifica_materie">Modifică materie</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_modifica_materie">
                    <input type="hidden" id="materieId" name="materieId">
                    <div class="modal-body">
                        <div class="row mt-3">
                            <label for="nume_modifica_materie" class="col-form-label">Nume materie:</label>
                            <div>
                                <input type="text" class="form-control" id="nume_modifica_materie" name="nume_modifica_materie" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="descriere_modifica_material" class="col-form-label">Descriere (optional):</label>
                            <div style="margin-bottom: 1em;">
                                <input type="text" class="form-control" id="descriere_modifica_material" name="descriere_modifica_material">
                            </div>
                        </div>
                        <div class="alert_modifica_materie alerte"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                            <button type="submit" class="btn btn-secondary">Modifică</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/preia_datele_materie_admin.js"></script>
</body>

</html>
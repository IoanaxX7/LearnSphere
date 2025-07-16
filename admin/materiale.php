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
            <div class="col-lg-12">
                <div class="card">
                    <h2 class="card-header">Materiale</h2>
                    <div class="card-body">
                        <div style="display: flex;">
                            <form class="d-flex" role="search" method="GET">
                                <input class="form-control searchBar" type="search" name="search" placeholder="Caută" aria-label="Search" style="border-color:gray"
                                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

                                <button class="btn btn-outline-secondary searchBtn" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>

                                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-outline-danger ms-3">Resetează</a>
                            </form>
                        </div>
                        <h4 class="card-title mt-4">Tabel materiale</h4>
                        <div class="mesaj" style="display: none; margin: 5px 0;"></div>

                        <table class="table mt-4 table-striped" id="tabel_utilizatori">
                            <?php
                            function sortLink($label, $column, $currentSort, $currentOrder)
                            {
                                $currentOrder = strtoupper($currentOrder);
                                $isCurrent = ($currentSort === $column);
                                $icon = '<i class="bi bi-funnel-fill"></i>';

                                if ($isCurrent) {
                                    if ($currentOrder === 'ASC') {
                                        $icon = '<i class="bi bi-caret-up-fill ms-1"></i>';
                                    } else {
                                        $icon = '<i class="bi bi-caret-down-fill ms-1"></i>';
                                    }
                                }

                                $newOrder = ($isCurrent && $currentOrder === 'ASC') ? 'desc' : 'asc';

                                $query = http_build_query(array_merge($_GET, ['sort' => $column, 'order' => $newOrder]));

                                return "<a href='?{$query}' class='sort-header' title='Sortează după {$label}'>{$label} {$icon}</a>";
                            }

                            ?>

                            <?php
                            $allowedSortColumns = ['titlu', 'username', 'materieNume', 'categorieNume', 'descriere', 'cuvinteCheie', 'material', 'dataPostarii'];
                            $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'titlu';
                            $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';

                            $columnMap = [
                                'titlu' => 'materiale.titlu',
                                'username' => 'users.username',
                                'materieNume' => 'materii.nume',
                                'categorieNume' => 'categorii.nume',
                                'descriere' => 'materiale.descriere',
                                'cuvinteCheie' => 'materiale.cuvinteCheie',
                                'material' => 'materiale.material',
                                'dataPostarii' => 'materiale.dataPostarii'
                            ];

                            $sortColumn = $columnMap[$sort] ?? 'materiale.titlu';
                            $nextOrder = $order === 'ASC' ? 'desc' : 'asc';
                            ?>

                            <thead>
                                <tr class="table-info">
                                    <th style="display: none;">ID</th>
                                    <th><?= sortLink('Titlu', 'titlu', $sort, $order) ?></th>
                                    <th><?= sortLink('Username', 'username', $sort, $order) ?></th>
                                    <th><?= sortLink('Materie', 'materieNume', $sort, $order) ?></th>
                                    <th><?= sortLink('Categorie', 'categorieNume', $sort, $order) ?></th>
                                    <th><?= sortLink('Descriere', 'descriere', $sort, $order) ?></th>
                                    <th><?= sortLink('Cuvinte cheie', 'cuvinteCheie', $sort, $order) ?></th>
                                    <th><?= sortLink('Material', 'material', $sort, $order) ?></th>
                                    <th><?= sortLink('Data Postării', 'dataPostarii', $sort, $order) ?></th>
                                    <th class="text-center">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                                $materialePerPage = 5;
                                $offset = ($page - 1) * $materialePerPage;

                                $where = [];
                                $params = [];

                                if ($search !== '') {
                                    $where[] = "(
                                        materiale.titlu LIKE :search OR
                                        materiale.descriere LIKE :search OR
                                        materiale.cuvinteCheie LIKE :search OR
                                        materiale.material LIKE :search OR
                                        categorii.nume LIKE :search OR
                                        materii.nume LIKE :search OR
                                        users.username LIKE :search
                                    )";
                                    $params[':search'] = "%$search%";
                                }

                                $whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

                                $stmtTotal = $conexiune->prepare("
                                    SELECT COUNT(*) FROM materiale
                                    JOIN categorii ON materiale.categorieID = categorii.categorieID
                                    JOIN materii ON categorii.materieID = materii.materiiID
                                    JOIN users ON materiale.userID = users.userID
                                    $whereSQL
                                ");

                                $stmtTotal->execute($params);

                                $totalMateriale = $stmtTotal->fetchColumn();
                                $totalPages = ceil($totalMateriale / $materialePerPage);
                                $page = max(1, min($page, $totalPages));
                                $startIndex = $offset + 1;
                                $endIndex = min($offset + $materialePerPage, $totalMateriale);

                                $stmt = $conexiune->prepare("
                                    SELECT 
                                        materiale.*, 
                                        categorii.nume AS categorieNume, 
                                        materii.nume AS materieNume,
                                        users.username
                                    FROM materiale
                                    JOIN categorii ON materiale.categorieID = categorii.categorieID
                                    JOIN materii ON categorii.materieID = materii.materiiID
                                    JOIN users ON materiale.userID = users.userID
                                    $whereSQL
                                    ORDER BY $sortColumn $order
                                    LIMIT :limit OFFSET :offset
                                ");

                                foreach ($params as $key => $value) {
                                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                                }
                                $stmt->bindValue(':limit', $materialePerPage, PDO::PARAM_INT);
                                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                $stmt->execute();


                                while ($rand = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>
                                        <td style="display: none;">' . $rand["materialID"] . '</td>
                                        <td>' . htmlspecialchars($rand["titlu"]) . '</td>
                                        <td>' . htmlspecialchars($rand["username"]) . '</td>
                                        <td>' . htmlspecialchars($rand["materieNume"]) . '</td>
                                        <td>' . htmlspecialchars($rand["categorieNume"]) . '</td>
                                        <td>' . htmlspecialchars($rand["descriere"]) . '</td>
                                        <td>' . htmlspecialchars($rand["cuvinteCheie"]) . '</td>
                                        <td>' . htmlspecialchars($rand["material"]) . '</td>
                                        <td>' . htmlspecialchars($rand["dataPostarii"]) . '</td>
                                        <td class="text-center">
                                            <a id="' . $rand["materialID"] . '" class="edit" title="Modifică" data-bs-toggle="modal" data-bs-target="#modal_modifica_material"
                                                style="font-size: 1.2em; color: SlateBlue;"><i class="bi bi-pencil-fill"></i></a>
                                            <a id="' . $rand["materialID"] . '" class="delete" href="#" title="Șterge" style="font-size: 1.2em; color: Tomato;"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                        <?php if ($totalMateriale > 0): ?>
                            <p class="text-center mt-2">Afișare materiale <?= $startIndex ?>–<?= $endIndex ?> din <?= $totalMateriale ?></p>
                        <?php else: ?>
                            <p class="text-center mt-2">Nu s-au găsit materiale.</p>
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

    <!-- Casetă modală - Modifica material-->
    <div class="modal fade" id="modal_modifica_material" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="eticheta_modifica_material" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eticheta_modifica_material">Modifică materialul</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <form method="post" action="" id="form_modifica_material">
                    <div class="modal-body">
                        <input type="hidden" id="materialID" name="materialID" value="">
                        <div class="row mt-3">
                            <label for="titlu" class="col-form-label">Titlul:</label>
                            <div>
                                <input type="text" class="form-control" id="titlu" name="titlu" value="">
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
                            <label for="descriere" class="col-form-label">Descriere (optional):</label>
                            <div>
                                <input type="text" class="form-control" id="descriere" name="descriere" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="cuvinte_cheie" class="col-form-label">Cuvinte cheie (optional):</label>
                            <div>
                                <input type="text" class="form-control" id="cuvinte_cheie" name="cuvinte_cheie" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="material" class="col-form-label">Material:</label>
                            <div>
                                <input type="file" class="form-control" id="material" name="material" value="">
                            </div>
                        </div>

                        <div class="row mt-3 px-3">
                            <div class="alert_modifica_material alerte px-0" style="width: 465px; margin-left: .8em"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Închide</button>
                            <button type="submit" class="btn btn-secondary">Modifică</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/preia_datele_materiale.js"></script>
</body>

</html>
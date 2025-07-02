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
    <title>Materiale</title>
    <link rel="stylesheet" href="../assets/stiluriForms.css">
</head>

<body>
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>

    <section class="login-page">
        <div class="form-box form-cauta-categorie">
            <div class="form-value">
                <form action="" method="post" class="cauta-categorie">
                    <h2 class="form-title">Caută materiale după categorie</h2>
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
                        </div>
                    </div>
                    <button class="form-btn">Caută</button>
                </form>
            </div>
        </div>
    </section>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['categorie'])) {
        $categorieID = (int) $_POST['categorie'];
        try {
            $isLoggedIn = isset($_SESSION['userID']) && is_numeric($_SESSION['userID']) && $_SESSION['userID'] > 0;
            $sql = '
            SELECT 
                m.*, 
                u.username AS username, 
                u.pozaProfil, 
                c.nume AS categorieNume,
                mt.nume AS materieNume';

            if ($isLoggedIn) {
                $sql .= ',
                EXISTS (
                    SELECT 1 FROM likes l 
                    WHERE l.materialID = m.materialID AND l.userID = :currentUserID
                ) AS hasLiked,
                EXISTS (
                    SELECT 1 FROM dislikes d 
                    WHERE d.materialID = m.materialID AND d.userID = :currentUserID
                ) AS hasDisliked';
            } else {
                $sql .= ',
                false AS hasLiked,
                false AS hasDisliked';
            }

            $sql .= ',
                (
                    SELECT COUNT(*) FROM comentarii cm 
                    WHERE cm.materialID = m.materialID
                    ) AS commentCount
                FROM materiale m
                JOIN users u ON m.userID = u.userID
                JOIN categorii c ON m.categorieID = c.categorieID
                JOIN materii mt ON c.materieID = mt.materiiID
                WHERE m.categorieID = :categorieID
                ORDER BY m.dataPostarii DESC';

            $cerereSQL = $conexiune->prepare($sql);
            $params = ['categorieID' => $categorieID];
            if ($isLoggedIn) {
                $params['currentUserID'] = $_SESSION['userID'];
            }

            $cerereSQL->execute($params);

            while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="post">
                    <div class="post-header">
                        <div class="profile-pic">
                            <img src="../uploads/' . htmlspecialchars($rand["username"]) . '/' . htmlspecialchars($rand["pozaProfil"]) . '" alt="Poza de profil a lui ' . htmlspecialchars($rand["username"]) . '">
                        </div>
                        <div class="user-info">
                            <span class="username">' . htmlspecialchars($rand["username"]) . '</span>
                            <span class="time">' . Data($rand["dataPostarii"]) . '</span>
                        </div>
                    </div>
                    <div class="post-content">
                        <a href="../postari/postare_material.php?id=' . $rand['materialID'] . '">
                            <h4>' . htmlspecialchars($rand["titlu"]) . '</h4>
                        </a>
                        <h6 class="category">' . htmlspecialchars($rand["categorieNume"]) . ' • ' . htmlspecialchars($rand["materieNume"]) . '</h6>
                        <p>' . nl2br(htmlspecialchars($rand["descriere"])) . '</p>
                    </div>';

                if (!empty($rand["material"])) {
                    if ($isLoggedIn) {
                        $filePath = '../uploads/' . htmlspecialchars($rand["username"]) . '/' . htmlspecialchars($rand["material"]);
                        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        echo '<div class="material-preview">';
                        if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                            echo '<img src="' . $filePath . '" alt="Material Image">';
                        } elseif ($fileExt === 'pdf') {
                            echo '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="400px">';
                        } elseif (in_array($fileExt, ['mp4', 'webm'])) {
                            echo '<video controls width="100%">
                            <source src="' . $filePath . '" type="video/' . $fileExt . '">
                        </video>';
                        } else {
                            echo '<a class="download-link" href="' . $filePath . '" download>' . htmlspecialchars($rand["material"]) . ' (' . strtoupper($fileExt) . ')</a>';
                        }
                        echo '</div>';
                    } else {
                        $filePath = '../uploads/' . htmlspecialchars($rand["username"]) . '/' . htmlspecialchars($rand["material"]);
                        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        echo '<div class="material-preview">';
                        if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                            echo '<img src="' . $filePath . '" alt="Material Image">';
                        } elseif ($fileExt === 'pdf') {
                            echo '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="400px">';
                        } elseif (in_array($fileExt, ['mp4', 'webm'])) {
                            echo '<video controls width="100%">
                            <source src="' . $filePath . '" type="video/' . $fileExt . '">
                        </video>';
                        } else {
                            echo '<a class="download-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">' . htmlspecialchars($rand["material"]) . ' (' . strtoupper($fileExt) . ')</a>';
                        }
                        echo '</div>';
                    }
                }

                // Likes & Dislikes
                $stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE materialID = ?");
                $stmt->execute([$rand['materialID']]);
                $likeCount = $stmt->fetchColumn();

                $stmt = $conexiune->prepare("SELECT COUNT(*) FROM dislikes WHERE materialID = ?");
                $stmt->execute([$rand['materialID']]);
                $dislikeCount = $stmt->fetchColumn();

                echo '<div class="post-actions">';

                if ($isLoggedIn) {
                    $likeActive = $rand["hasLiked"] ? 'active' : '';
                    $dislikeActive = $rand["hasDisliked"] ? 'active' : '';
                    $likeIcon = $rand["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
                    $dislikeIcon = $rand["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

                    echo '
                    <button class="reaction-btn like ' . $likeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="like" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $rand['materialID'] . '">' . $likeCount . '</span>
                    </button>
                    <button class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="dislike" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $rand['materialID'] . '">' . $dislikeCount . '</span>
                    </button>
                    <a href="../postari/postare_material.php?id=' . $rand['materialID'] . '" title="Comentarii">
                        <button class="reaction-btn comment">
                            <i class="bi bi-chat-left-text"></i>
                            <span class="comment-count" id="comment-count-' . $rand['materialID'] . '">' . $rand['commentCount'] . '</span>
                        </button>
                    </a>';
                } else {

                    $likeIcon = $rand["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
                    $dislikeIcon = $rand["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

                    $modalTrigger = $isLoggedIn ? '' : ' data-bs-toggle="modal" data-bs-target="#loginModal"';

                    echo '
                    <button class="reaction-btn-logged-out like ' . ($rand["hasLiked"] ? 'active' : '') . '" data-id="' . $rand['materialID'] . '" data-reaction="like" title="Like"' . $modalTrigger . '>
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $rand['materialID'] . '">' . $likeCount . '</span>
                    </button>
                    <button class="reaction-btn-logged-out dislike ' . ($rand["hasDisliked"] ? 'active' : '') . '" data-id="' . $rand['materialID'] . '" data-reaction="dislike" title="Dislike"' . $modalTrigger . '>
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $rand['materialID'] . '">' . $dislikeCount . '</span>
                    </button>
                    <a href="../postari/postare_material.php?id=' . $rand['materialID'] . '" title="Comentarii">
                        <button  class="reaction-btn-logged-out comment">
                            <i class="bi bi-chat-left-text"></i>
                            <span class="comment-count" id="comment-count-' . $rand['materialID'] . '">' . $rand['commentCount'] . '</span>
                        </button>
                    </a>
                </div>';
                }

                echo '</div></div>';
            }
        } catch (PDOException $e) {
            exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
        }
    }
    ?>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header rounded-top-4">
                    <h5 class="modal-title" id="loginModalLabel">Conectează-te pentru a interacționa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Închide"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Te rugăm să <a href="../signin.php" class="text-decoration-none fw-bold">te înregistrezi</a> sau să <a href="../login.php" class="text-decoration-none fw-bold">te conectezi</a> pentru a aprecia, comenta sau respinge postările.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Închide</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/actiuni_material.js"></script>
</body>

</html>
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
    <title>Rezultate Materiale</title>
    <link rel="stylesheet" href="../assets/stiluriForms.css">
</head>

<body style="margin-top: 4em;">
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>


    <?php
    $isLoggedIn = isset($_SESSION['userID']);
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';

    function evidentiaza($text, $query)
    {
        $escapedQuery = preg_quote($query, '/');
        return preg_replace_callback(
            "/($escapedQuery)/i",
            function ($match) {
                return '<mark>' . $match[0] . '</mark>';
            },
            $text
        );
    }

    if (empty($query)) {
        echo "<p>Nu a fost introdus niciun termen de căutare.</p>";
        exit;
    }

    $searchTerm = '%' . $query . '%';

    echo '
    <div class="d-flex justify-content-center" style="margin-bottom: 3em">
        <div class="text-center bg-white shadow p-4" style="max-width: 600px; width: 100%; border-radius: 1.5rem;">
            <h2 class="mb-4">Rezultate pentru materiale: <em>' . htmlspecialchars($query) . '</em></h2>
            <div class="d-flex justify-content-center gap-3">
                <a href="cauta_materiale.php?q=' . urlencode($query) . '" class="btn btn-secondary">Vezi materiale</a>
                <a href="cauta_intrebari.php?q=' . urlencode($query) . '" class="btn btn-outline-secondary">Vezi întrebări</a>
            </div>
        </div>
    </div>';



    $cerereSQL = $conexiune->prepare("
        SELECT m.*, c.nume AS categorieNume, ma.nume AS materieNume, u.username, u.pozaProfil,
            (SELECT COUNT(*) FROM comentarii cm WHERE cm.materialID = m.materialID) AS commentCount,
            EXISTS(SELECT 1 FROM likes l WHERE l.materialID = m.materialID AND l.userID = :userID) AS hasLiked,
            EXISTS(SELECT 1 FROM dislikes d WHERE d.materialID = m.materialID AND d.userID = :userID) AS hasDisliked
        FROM materiale m
        JOIN categorii c ON m.categorieID = c.categorieID
        JOIN materii ma ON c.materieID = ma.materiiID
        JOIN users u ON m.userID = u.userID
        WHERE m.titlu LIKE :term OR m.descriere LIKE :term OR m.cuvinteCheie LIKE :term 
            OR c.nume LIKE :term OR ma.nume LIKE :term
        ORDER BY m.dataPostarii DESC
    ");

    $cerereSQL->execute([
        'term' => $searchTerm,
        'userID' => $isLoggedIn ? $_SESSION['userID'] : 0
    ]);

    echo '<div class="row">';
    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
        echo '
            <div class="col-12 col-lg-6 mb-4">
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
                            <h4>' . evidentiaza(htmlspecialchars($rand["titlu"]), $query) . '</h4>
                        </a>
                        <h6 class="category">' . evidentiaza(htmlspecialchars($rand["categorieNume"]), $query) . ' • '
            . evidentiaza(htmlspecialchars($rand["materieNume"]), $query) . '</h6>
                        <p>' . nl2br(evidentiaza(htmlspecialchars($rand["descriere"]), $query)) . '</p>
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
                    <button data-type="material" class="reaction-btn like ' . $likeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="like" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $rand['materialID'] . '">' . $likeCount . '</span>
                    </button>
                    <button data-type="material" class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="dislike" title="Dislike">
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
                    </a>';
        }

        echo '</div></div></div>';
    }
    echo '</div>';
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
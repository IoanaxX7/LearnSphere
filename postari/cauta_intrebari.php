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
<html lang="ro">

<head>
    <?php
    file_exists(__DIR__ . "/../module/head.php") ?
        require_once __DIR__ . "/../module/head.php" :
        die("Fisierul head nu a fost gasit!");
    ?>
    <title>Rezultate Întrebări</title>
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
    <div class="d-flex justify-content-center" style="margin-bottom: 5em">
        <div class="text-center bg-white shadow p-4" style="max-width: 600px; width: 100%; border-radius: 1.5rem;">
            <h2 class="mb-4">Rezultate pentru întrebări: <em>' . htmlspecialchars($query) . '</em></h2>
            <div class="d-flex justify-content-center gap-3">
                <a href="cauta_materiale.php?q=' . urlencode($query) . '" class="btn btn-outline-secondary">Vezi materiale</a>
                <a href="cauta_intrebari.php?q=' . urlencode($query) . '" class="btn btn-secondary">Vezi întrebări</a>
            </div>
        </div>
    </div>';

    $isLoggedIn = isset($_SESSION['userID']) && is_numeric($_SESSION['userID']) && $_SESSION['userID'] > 0;

    $sql = '
        SELECT 
            i.*, 
            u.username AS username, 
            u.pozaProfil,
            c.nume AS categorieNume,
            m.nume AS materieNume';

    if ($isLoggedIn) {
        $sql .= ',
            EXISTS (
                SELECT 1 FROM likes l 
                WHERE l.intrebareID = i.intrebareID AND l.userID = :currentUserID
            ) AS hasLiked,
            EXISTS (
                SELECT 1 FROM dislikes d 
                WHERE d.intrebareID = i.intrebareID AND d.userID = :currentUserID
            ) AS hasDisliked';
    } else {
        $sql .= ',
            false AS hasLiked,
            false AS hasDisliked';
    }

    $sql .= ',
        (
            SELECT COUNT(*) FROM comentarii cm 
            WHERE cm.intrebareID = i.intrebareID
        ) AS commentCount
        FROM intrebari i
        JOIN categorii c ON i.categorieID = c.categorieID
        JOIN materii m ON c.materieID = m.materiiID
        JOIN users u ON i.userID = u.userID
        WHERE i.intrebare LIKE :term OR c.nume LIKE :term OR m.nume LIKE :term
        ORDER BY i.dataPostarii DESC';


    $cerereSQL = $conexiune->prepare($sql);

    $params = ['term' => '%' . $query . '%'];

    if ($isLoggedIn) {
        $params['currentUserID'] = $_SESSION['userID'];
    }

    $cerereSQL->execute($params);

    echo '<div class="row mx-md-2 mx-lg-4">';
    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
        echo '
            <div class="col-md-6 col-lg-4">
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
                        <a href="../postari/postare_intrebare.php?id=' . $rand['intrebareID'] . '">
                            <h4>' . evidentiaza(htmlspecialchars($rand["intrebare"]), $query) . '</h4>
                        </a>
                        <h6 class="category">' . evidentiaza(htmlspecialchars($rand["categorieNume"]), $query) . ' • '
                             . evidentiaza(htmlspecialchars($rand["materieNume"]), $query) . '</h6>
                        <p>' . nl2br(evidentiaza(htmlspecialchars($rand["detalii"]), $query)) . '</p>
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
        $stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE intrebareID = ?");
        $stmt->execute([$rand['intrebareID']]);
        $likeCount = $stmt->fetchColumn();

        $stmt = $conexiune->prepare(query: "SELECT COUNT(*) FROM dislikes WHERE intrebareID = ?");
        $stmt->execute([$rand['intrebareID']]);
        $dislikeCount = $stmt->fetchColumn();

        echo '<div class="post-actions">';

        if ($isLoggedIn) {
            $likeActive = $rand["hasLiked"] ? 'active' : '';
            $dislikeActive = $rand["hasDisliked"] ? 'active' : '';
            $likeIcon = $rand["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
            $dislikeIcon = $rand["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

            echo '
                <button data-type="question" class="reaction-btn like ' . $likeActive . '" data-id="' . $rand['intrebareID'] . '" data-reaction="like" title="Like">
                    <i class="bi ' . $likeIcon . '"></i>
                    <span class="like-count" id="like-count-' . $rand['intrebareID'] . '">' . $likeCount . '</span>
                </button>
                <button data-type="question" class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $rand['intrebareID'] . '" data-reaction="dislike" title="Dislike">
                    <i class="bi ' . $dislikeIcon . '"></i>
                    <span class="dislike-count" id="dislike-count-' . $rand['intrebareID'] . '">' . $dislikeCount . '</span>
                </button>
                <a href="../postari/postare_intrebare.php?id=' . $rand['intrebareID'] . '" title="Comentarii">
                    <button class="reaction-btn comment">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="comment-count" id="comment-count-' . $rand['intrebareID'] . '">' . $rand['commentCount'] . '</span>
                    </button>
                </a>';
        } else {

            $likeIcon = $rand["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
            $dislikeIcon = $rand["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

            $modalTrigger = $isLoggedIn ? '' : ' data-bs-toggle="modal" data-bs-target="#loginModal"';

            echo '
                <button class="reaction-btn-logged-out like ' . ($rand["hasLiked"] ? 'active' : '') . '" data-id="' . $rand['intrebareID'] . '" data-reaction="like" title="Like"' . $modalTrigger . '>
                    <i class="bi ' . $likeIcon . '"></i>
                    <span class="like-count" id="like-count-' . $rand['intrebareID'] . '">' . $likeCount . '</span>
                </button>
                <button class="reaction-btn-logged-out dislike ' . ($rand["hasDisliked"] ? 'active' : '') . '" data-id="' . $rand['intrebareID'] . '" data-reaction="dislike" title="Dislike"' . $modalTrigger . '>
                    <i class="bi ' . $dislikeIcon . '"></i>
                    <span class="dislike-count" id="dislike-count-' . $rand['intrebareID'] . '">' . $dislikeCount . '</span>
                </button>
                <a href="../postari/postare_intrebare.php?id=' . $rand['intrebareID'] . '" title="Comentarii">
                    <button  class="reaction-btn-logged-out comment">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="comment-count" id="comment-count-' . $rand['intrebareID'] . '">' . $rand['commentCount'] . '</span>
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
<?php

if (!file_exists(__DIR__ . "/../config.php")) {
    exit("Eroare! Fisierul de conectare nu a fost gasit.");
}

require_once(__DIR__ . "/../config.php");

if (empty($_SESSION["username"]) || !in_array($_SESSION["rol"], $Roluri)) {
    $_SESSION["rol"] = 3;
    $_SESSION["userID"] = 0;
}

$currentUserID  = $_SESSION['userID'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    file_exists(__DIR__ . "/../module/head.php") ?
        require_once __DIR__ . "/../module/head.php" :
        die("Fisierul head nu a fost gasit!");
    ?>
    <title>Întrebări</title>
</head>

<body style="margin-bottom: 3em;">
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        exit('ID întrebări invalid.');
    }

    $intrebareID = (int)$_GET['id'];

    try {
        $stmt = $conexiune->prepare('
    SELECT i.*, 
           u.username, 
           u.pozaProfil, 
           c.nume AS categorieNume, 
           mt.nume AS numeMaterie,
           EXISTS (
               SELECT 1 FROM likes l 
               WHERE l.intrebareID = i.intrebareID AND l.userID = :currentUserID
           ) AS hasLiked,
           EXISTS (
               SELECT 1 FROM dislikes d 
               WHERE d.intrebareID = i.intrebareID AND d.userID = :currentUserID
           ) AS hasDisliked,
           (
               SELECT COUNT(*) FROM comentarii cm 
               WHERE cm.intrebareID = i.intrebareID
           ) AS commentCount
    FROM intrebari i
    JOIN users u ON i.userID = u.userID
    JOIN categorii c ON i.categorieID = c.categorieID
    JOIN materii mt ON c.materieID = mt.materiiID
    WHERE i.intrebareID = :intrebareID
');


        $stmt->execute([
            ':currentUserID' => $currentUserID,
            ':intrebareID' => $intrebareID
        ]);



        $intrebare = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$intrebare) {
            exit('Întrebarea nu a fost găsită.');
        }

        // Show post
        echo '<div class="post" style="max-width: 800px;">
        <div class="post-header">
            <img src="../uploads/' . htmlspecialchars($intrebare["username"]) . '/' . htmlspecialchars($intrebare["pozaProfil"]) . '" class="profile-pic">
            <div class="user-info">
                <span class="username">' . htmlspecialchars($intrebare["username"]) . '</span>
                <span class="time">' . Data($intrebare["dataPostarii"]) . '</span>
            </div>
        </div>

        <div class="post-content">
            <h2>' . htmlspecialchars($intrebare["intrebare"]) . '</h2>
            <h6 class="category">' . htmlspecialchars($intrebare["categorieNume"]) . ' • ' . htmlspecialchars($intrebare["numeMaterie"]) . '</h6>
            <p>' . nl2br(htmlspecialchars($intrebare["detalii"])) . '</p>';

        if (!empty($intrebare["material"])) {
            $filePath = '../uploads/' . htmlspecialchars($intrebare["username"]) . '/' . htmlspecialchars($intrebare["material"]);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            echo '<div class="material-preview">';
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo '<img src="' . $filePath . '" alt="Material Image">';
            } elseif ($ext === 'pdf') {
                echo '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="400px">';
            } elseif (in_array($ext, ['mp4', 'webm'])) {
                echo '<video controls width="100%"><source src="' . $filePath . '" type="video/' . $ext . '"></video>';
            } elseif ($currentUserID) {
                echo '<a class="download-link" href="' . $filePath . '" download>' . htmlspecialchars($intrebare["material"]) . ' (' . strtoupper($ext) . ')</a>';
            } else {
                echo '<a class="download-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">' . htmlspecialchars($intrebare["material"]) . ' (' . strtoupper($ext) . ')</a>';
            }
            echo '</div>';
        }

        // Get like count
        $stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE intrebareID = ?");
        $stmt->execute([$intrebare['intrebareID']]);
        $likeCount = $stmt->fetchColumn();

        // Get dislike count
        $stmt = $conexiune->prepare("SELECT COUNT(*) FROM dislikes WHERE intrebareID = ?");
        $stmt->execute([$intrebare['intrebareID']]);
        $dislikeCount = $stmt->fetchColumn();

        $likeActive = $intrebare["hasLiked"] ? 'active' : '';
        $dislikeActive = $intrebare["hasDisliked"] ? 'active' : '';

        $likeIcon = $intrebare["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
        $dislikeIcon = $intrebare["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

        if ($currentUserID) {
            echo '</div>
                <div class="post-actions">
                    <button data-type="question" class="reaction-btn like ' . $likeActive . '" data-id="' . $intrebare['intrebareID'] . '" data-reaction="like" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $intrebare['intrebareID'] . '">' . $likeCount . '</span>
                    </button>
                    <button  data-type="question" class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $intrebare['intrebareID'] . '" data-reaction="dislike" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $intrebare['intrebareID'] . '">' . $dislikeCount . '</span>
                    </button>
                    <button data-type="question" class="reaction-btn comment" data-id="' . $intrebare['intrebareID'] . '" title="Comments">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="comment-count" id="comment-count-' . $intrebare['intrebareID'] . '">' . $intrebare['commentCount'] . '</span>
                    </button>
                </div>
            </div>';
        } else {
            echo '</div>
                <div class="post-actions">
                    <button data-type="question" class="reaction-btn like ' . $likeActive . '" data-bs-toggle="modal" data-bs-target="#loginModal" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $intrebare['intrebareID'] . '">' . $likeCount . '</span>
                    </button>
                    <button  data-type="question" class="reaction-btn dislike ' . $dislikeActive . '" data-bs-toggle="modal" data-bs-target="#loginModal" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $intrebare['intrebareID'] . '">' . $dislikeCount . '</span>
                    </button>
                    <button data-type="question" class="reaction-btn comment" data-bs-toggle="modal" data-bs-target="#loginModal" title="Comments">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="comment-count" id="comment-count-' . $intrebare['intrebareID'] . '">' . $intrebare['commentCount'] . '</span>
                    </button>
                </div>
            </div>';
        }

        echo '</div>';
    } catch (PDOException $e) {
        exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
    }
    ?>


    <script type="text/javascript" src="../assets/actiuni_material.js"></script>

    <?php

    // Comentarii

    echo '<div class="comments-container">
    <h3>Comentarii</h3>';

    if ($currentUserID !== 0) {
        echo '<form id="comment-form" class="comment-form">
        <input type="hidden" name="intrebareID" id="intrebareID" value="' . $intrebareID . '">
        <textarea maxlength="255" name="comentariu" id="comentariu" required placeholder="Adaugă un comentariu..."></textarea>
        <button type="submit">Trimite</button>
    </form>
    <div class="comments">';
    } else {
        echo '<p><a href="../login.php" class="text-decoration-none fw-bold">Autentifică-te</a> pentru a comenta.</p>';
    }

    $stmtComments = $conexiune->prepare('
    SELECT cm.*, u.username, u.pozaProfil,
        EXISTS (SELECT 1 FROM likes l WHERE l.comentariuID = cm.comentariuID AND l.userID = :currentUserID) AS hasLiked,
        EXISTS (SELECT 1 FROM dislikes d WHERE d.comentariuID = cm.comentariuID AND d.userID = :currentUserID) AS hasDisliked,
        (SELECT COUNT(*) FROM likes l WHERE l.comentariuID = cm.comentariuID) AS likeCount,
        (SELECT COUNT(*) FROM dislikes d WHERE d.comentariuID = cm.comentariuID) AS dislikeCount
    FROM comentarii cm
    JOIN users u ON cm.userID = u.userID
    WHERE cm.intrebareID = :intrebareID
    ORDER BY cm.dataPostarii DESC
');
    $stmtComments->execute([':intrebareID' => $intrebareID, ':currentUserID' => $_SESSION['userID']]);
    $comentarii = $stmtComments->fetchAll(PDO::FETCH_ASSOC);


    foreach ($comentarii as $comentariu) {
        echo '
    <div class="comment">
        <div class="comment-header">
            <img src="../uploads/' . htmlspecialchars($comentariu["username"]) . '/' . htmlspecialchars($comentariu["pozaProfil"]) . '" class="comment-avatar" alt="Profile picture">
            <div class="comment-meta">
                <span class="comment-username">' . htmlspecialchars($comentariu["username"]) . '</span>
                <span class="comment-time">' . date("d M Y, H:i", strtotime($comentariu["dataPostarii"])) . '</span>
            </div>
        </div>
        <div class="comment-text">' . nl2br(htmlspecialchars($comentariu["comentariu"])) . '</div>';
        // Get like count
        $stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE comentariuID = ?");
        $stmt->execute([$comentariu['comentariuID']]);
        $likeCount = $stmt->fetchColumn();

        // Get dislike count
        $stmt = $conexiune->prepare("SELECT COUNT(*) FROM dislikes WHERE comentariuID = ?");
        $stmt->execute([$comentariu['comentariuID']]);
        $dislikeCount = $stmt->fetchColumn();

        $likeActive = $comentariu["hasLiked"] ? 'active' : '';
        $dislikeActive = $comentariu["hasDisliked"] ? 'active' : '';

        $likeIcon = $comentariu["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
        $dislikeIcon = $comentariu["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

        if ($currentUserID) {
            echo '
                <div class="post-actions">
                    <button data-type="comment" class="reaction-btn like ' . $likeActive . '" data-id="' . $comentariu['comentariuID'] . '" data-reaction="like" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $comentariu['comentariuID'] . '">' . $likeCount . '</span>
                    </button>
                    <button data-type="comment" class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $comentariu['comentariuID'] . '" data-reaction="dislike" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $comentariu['comentariuID'] . '">' . $dislikeCount . '</span>
                    </button>
                </div>';
        } else {
            echo '
                <div class="post-actions">
                    <button data-type="comment" class="reaction-btn like ' . $likeActive . '" data-bs-toggle="modal" data-bs-target="#loginModal" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $comentariu['comentariuID'] . '">' . $likeCount . '</span>
                    </button>
                    <button data-type="comment" class="reaction-btn dislike ' . $dislikeActive . '" data-bs-toggle="modal" data-bs-target="#loginModal" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $comentariu['comentariuID'] . '">' . $dislikeCount . '</span>
                    </button>
                </div>';
        }
        echo '</div>';
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
    <script type="text/javascript" src="../assets/preia_comentariu.js"></script>
</body>

</html>
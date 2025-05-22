<?php

if (!file_exists(__DIR__ . "/config.php")) {
    exit("Eroare! Fisierul de conectare nu a fost gasit.");
}

require_once(__DIR__ . "/config.php");

if (empty($_SESSION["username"]) || !in_array($_SESSION["rol"], $Roluri)) {
    header("Location: logout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    file_exists(__DIR__ . "/module/head.php") ?
        require_once __DIR__ . "/module/head.php" :
        die("Fisierul head nu a fost gasit!");
    ?>
    <title>LearnSphere</title>
</head>

<body>
    <?php
    file_exists(__DIR__ . "/module/meniu.php") ?
        require_once __DIR__ . "/module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>

    <?php
    try {
        $cerereSQL = $conexiune->prepare('
    SELECT 
        m.*, 
        u.username AS username, 
        u.pozaProfil, 
        c.nume,
        EXISTS (
            SELECT 1 FROM likes l 
            WHERE l.materialID = m.materialID AND l.userID = :currentUserID
        ) AS hasLiked,
        EXISTS (
            SELECT 1 FROM dislikes d 
            WHERE d.materialID = m.materialID AND d.userID = :currentUserID
        ) AS hasDisliked,
        (
            SELECT COUNT(*) FROM comentarii cm 
            WHERE cm.materialID = m.materialID
        ) AS commentCount
    FROM materiale m
    JOIN users u ON m.userID = u.userID
    JOIN categorii c ON m.categorieID = c.categorieID
    ORDER BY m.dataPostarii DESC
    LIMIT 10
');

        $cerereSQL->execute(['currentUserID' => $_SESSION['userID']]);

        while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
            echo '
                <div class="post">
                    <div class="post-header">
                        <div class="profile-pic">
                            <img src="uploads/' . htmlspecialchars($rand["username"]) . '/' . htmlspecialchars($rand["pozaProfil"]) . '" alt="Profile picture of ' . htmlspecialchars($rand["username"]) . '">
                        </div>
                        <div class="user-info">
                            <span class="username">' . htmlspecialchars($rand["username"]) . '</span>
                            <span class="time">' . date("d M Y, H:i", strtotime($rand["dataPostarii"])) . '</span>
                        </div>
                    </div>
                    <div class="post-content">
                        <a href="postari/postare_material.php?id=' . $rand['materialID'] . '">
                            <h4>' . htmlspecialchars($rand["titlu"]) . '</h4>
                        </a>
                        <h6 class="category">' . htmlspecialchars($rand["nume"]) . '</h6>
                        <p>' . nl2br(htmlspecialchars($rand["descriere"])) . '</p>
                    </div>';

            if (!empty($rand["material"])) {
                $filePath = 'uploads/' . htmlspecialchars($rand["username"]) . '/' . htmlspecialchars($rand["material"]);
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
            }

            // Get like count
            $stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE materialID = ?");
            $stmt->execute([$rand['materialID']]);
            $likeCount = $stmt->fetchColumn();

            // Get dislike count
            $stmt = $conexiune->prepare("SELECT COUNT(*) FROM dislikes WHERE materialID = ?");
            $stmt->execute([$rand['materialID']]);
            $dislikeCount = $stmt->fetchColumn();

            $likeActive = $rand["hasLiked"] ? 'active' : '';
            $dislikeActive = $rand["hasDisliked"] ? 'active' : '';

            $likeIcon = $rand["hasLiked"] ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up';
            $dislikeIcon = $rand["hasDisliked"] ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down';

            echo '</div>
                <div class="post-actions">
                    <button class="reaction-btn like ' . $likeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="like" title="Like">
                        <i class="bi ' . $likeIcon . '"></i>
                        <span class="like-count" id="like-count-' . $rand['materialID'] . '">' . $likeCount . '</span>
                    </button>
                    <button class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $rand['materialID'] . '" data-reaction="dislike" title="Dislike">
                        <i class="bi ' . $dislikeIcon . '"></i>
                        <span class="dislike-count" id="dislike-count-' . $rand['materialID'] . '">' . $dislikeCount . '</span>
                    </button>
                    <button class="reaction-btn comment" data-id="' . $rand['materialID'] . '" title="Comments">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="comment-count" id="comment-count-' . $rand['materialID'] . '">' . $rand['commentCount'] . '</span>
                    </button>
                </div>
            </div>';
        }
    } catch (PDOException $e) {
        exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
    }
    ?>

    <script type="text/javascript" src="assets/actiuni.js"></script>

</body>

</html>
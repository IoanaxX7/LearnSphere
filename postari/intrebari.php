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
    <title>Întrebări</title>
    <link rel="stylesheet" href="../assets/stiluriForms.css">
</head>

<body style="margin-top: -3em;">
    <?php
    file_exists(__DIR__ . "/../module/meniu.php") ?
        require_once __DIR__ . "/../module/meniu.php" :
        die("Fisierul meniu nu a fost gasit!");
    ?>


    <?php
    $selectedMaterie = $_POST['materie'] ?? '';
    $selectedCategorie = $_POST['categorie'] ?? '';
    ?>

    <section class="login-page">
        <div class="form-box form-cauta-categorie">
            <div class="form-value">
                <form action="" method="post" class="cauta-categorie" id="filterForm">
                    <h2 class="form-title">Caută întrebări</h2>

                    <div class="inputbox">
                        <label for="materie" class="col-form-label">Materia:</label>
                        <div>
                            <select name="materie" id="materie" class="form-control categorii" required>
                                <option value=""> -- alegeți o opțiune -- </option>
                                <?php
                                try {
                                    $cerereSQL = $conexiune->query('SELECT * FROM materii');
                                    while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($rand["materiiID"] == $selectedMaterie) ? 'selected' : '';
                                        echo '<option value="' . $rand["materiiID"] . '" ' . $selected . '>' . $rand["nume"] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage());
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <small id="categorieCount" style="color: #666;"></small>

                    <div class="inputbox">
                        <label for="categorie" class="col-form-label">Categoria:</label>
                        <div>
                            <select name="categorie" id="categorie" class="form-control categorii" required disabled>
                                <option value=""> -- selectați o materie mai întâi -- </option>
                            </select>
                        </div>
                    </div>

                    <button type="button" id="clearSelections" class="form-btn">Resetează selecțiile</button>
                    <button class="form-btn" id="cautaBtn" style="margin-top: .5em">Caută</button>
                </form>
            </div>
        </div>
    </section>



    <script>
        const materieSelect = document.getElementById('materie');
        const categorieSelect = document.getElementById('categorie');
        const categorieCount = document.getElementById('categorieCount');
        const clearBtn = document.getElementById('clearSelections');
        const cautaBtn = document.getElementById('cautaBtn');
        const filterForm = document.getElementById('filterForm');

        const allCategories = <?php
                                $data = [];
                                $cerereSQL = $conexiune->query('SELECT * FROM categorii');
                                while ($rand = $cerereSQL->fetch(PDO::FETCH_ASSOC)) {
                                    $materieID = $rand["materieID"];
                                    $data[$materieID][] = [
                                        'value' => $rand["categorieID"],
                                        'label' => $rand["nume"]
                                    ];
                                }
                                echo json_encode($data);
                                ?>;

        const previousMaterie = "<?php echo htmlspecialchars($selectedMaterie); ?>";
        const previousCategorie = "<?php echo htmlspecialchars($selectedCategorie); ?>";

        function populateCategories(materieID, selectedCategorieID = null) {
            categorieSelect.innerHTML = '<option value=""> -- alegeți o opțiune -- </option>';
            categorieSelect.disabled = true;
            categorieCount.textContent = '';

            if (!materieID || !allCategories[materieID]) return;

            const items = allCategories[materieID];
            items.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.value;
                opt.textContent = cat.label;
                if (cat.value === selectedCategorieID) {
                    opt.selected = true;
                }
                categorieSelect.appendChild(opt);
            });

            categorieSelect.disabled = false;
            categorieCount.textContent = `Categorie disponibile: ${items.length}`;
        }

        function handleMaterieChange(forceUpdate = false) {
            const currentMaterie = materieSelect.value;

            if (!forceUpdate && !currentMaterie) {
                categorieSelect.disabled = true;
                categorieSelect.innerHTML = '<option value=""> -- selectați o materie mai întâi -- </option>';
                categorieCount.textContent = '';
                return;
            }

            populateCategories(currentMaterie);
            if (!forceUpdate) {
                categorieSelect.selectedIndex = 0;
            }
        }

        materieSelect.addEventListener('change', () => {
            handleMaterieChange();
            localStorage.setItem('selectedMaterie', materieSelect.value);
            localStorage.removeItem('selectedCategorie');
        });

        categorieSelect.addEventListener('change', () => {
            localStorage.setItem('selectedCategorie', categorieSelect.value);
        });

        clearBtn.addEventListener('click', () => {
            materieSelect.selectedIndex = 0;
            categorieSelect.innerHTML = '<option value=""> -- selectați o materie mai întâi -- </option>';
            categorieSelect.disabled = true;
            categorieCount.textContent = '';

            localStorage.removeItem('selectedMaterie');
            localStorage.removeItem('selectedCategorie');

            const postsContainer = document.getElementById('postsContainer');
            if (postsContainer) {
                postsContainer.innerHTML = '';
            }
        });

        cautaBtn.addEventListener('click', (e) => {
            e.preventDefault();

            if (!categorieSelect.value) {
                alert('Vă rugăm să selectați o categorie înainte de a căuta.');
                categorieSelect.focus();
                return;
            }

            const formData = new FormData(filterForm);
            fetch(filterForm.action, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newPosts = doc.getElementById('postsContainer');

                    if (!newPosts) {
                        console.error('⚠️ Eroare: Nu s-a găsit #postsContainer în răspunsul HTML!');
                        console.warn('Răspunsul complet HTML primit:', html);
                        return;
                    }

                    const postsContainer = document.getElementById('postsContainer');
                    postsContainer.innerHTML = newPosts.innerHTML;

                    if (!postsContainer.textContent.trim()) {
                        postsContainer.innerHTML = '<div style="text-align: center;"><div class="no-results"><h3>Niciun rezultat găsit.</h3></div></div>';
                    }

                    window.scrollTo({
                        top: postsContainer.offsetTop,
                        behavior: 'smooth'
                    });
                })

                .catch(error => console.error('Eroare la trimiterea formularului:', error));
        });

        window.addEventListener('DOMContentLoaded', () => {
            const savedMaterie = localStorage.getItem('selectedMaterie') || previousMaterie;
            const savedCategorie = localStorage.getItem('selectedCategorie') || previousCategorie;

            if (savedMaterie) {
                materieSelect.value = savedMaterie;
                populateCategories(savedMaterie, savedCategorie);
            }
        });
    </script>







    <div id="postsContainer">
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
                        WHERE l.intrebareID = m.intrebareID AND l.userID = :currentUserID
                    ) AS hasLiked,
                    EXISTS (
                        SELECT 1 FROM dislikes d 
                        WHERE d.intrebareID = m.intrebareID AND d.userID = :currentUserID
                    ) AS hasDisliked';
                } else {
                    $sql .= ',
                    false AS hasLiked,
                    false AS hasDisliked';
                }

                $sql .= ',
                (
                    SELECT COUNT(*) FROM comentarii cm 
                    WHERE cm.intrebareID = m.intrebareID
                ) AS commentCount
                FROM intrebari m
                JOIN users u ON m.userID = u.userID
                JOIN categorii c ON m.categorieID = c.categorieID
                JOIN materii mt ON c.materieID = mt.materiiID
                WHERE c.categorieID = :categorieID
                ORDER BY m.dataPostarii DESC';

                $cerereSQL = $conexiune->prepare($sql);
                $params = $isLoggedIn
                    ? ['currentUserID' => $_SESSION['userID'], 'categorieID' => $categorieID]
                    : ['categorieID' => $categorieID];
                $cerereSQL->execute($params);

                if ($cerereSQL->rowCount() === 0) {
                    echo '<div style="text-align: center;">
                        <div class="no-results">
                            <h3>Niciun rezultat găsit.</h3>
                        </div>
                    </div>';
                } else {
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
                                <a href="../postari/postare_intrebare.php?id=' . $rand['intrebareID'] . '">
                                    <h4>' . htmlspecialchars($rand["intrebare"]) . '</h4>
                                </a>
                                <h6 class="category">' . htmlspecialchars($rand["categorieNume"]) . ' • ' . htmlspecialchars($rand["materieNume"]) . '</h6>
                                <p>' . nl2br(htmlspecialchars($rand["detalii"])) . '</p>
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
                                <button class="reaction-btn like ' . $likeActive . '" data-id="' . $rand['intrebareID'] . '" data-reaction="like" title="Like">
                                    <i class="bi ' . $likeIcon . '"></i>
                                    <span class="like-count" id="like-count-' . $rand['intrebareID'] . '">' . $likeCount . '</span>
                                </button>
                                <button class="reaction-btn dislike ' . $dislikeActive . '" data-id="' . $rand['intrebareID'] . '" data-reaction="dislike" title="Dislike">
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
                            </a>
                        </div>';
                        }

                        echo '</div></div>';
                    }
                }
            } catch (PDOException $e) {
                exit("Eroare la afișarea datelor din baza de date.<br/>" . $e->getMessage() . "<br/>");
            }
        }
        ?>
    </div>
    <script type="text/javascript" src="../assets/actiuni_material.js"></script>


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
</body>

</html>
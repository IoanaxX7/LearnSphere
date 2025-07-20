<?php
include('../config.php');
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION["userID"])) {
    echo json_encode(["error" => "Utilizator neautentificat."]);
    exit;
}

if (!isset($_FILES["poza_profil"]) || $_FILES["poza_profil"]["error"] !== UPLOAD_ERR_OK) {
    echo json_encode(["error" => "Fișierul nu a fost încărcat corect."]);
    exit;
}

try {
    $sql = "SELECT username, pozaProfil FROM users WHERE userID = :userID";
    $stmt = $conexiune->prepare($sql);
    $stmt->execute([":userID" => $_SESSION["userID"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["error" => "Utilizatorul nu a fost găsit."]);
        exit;
    }

    $username = $user["username"];
    $oldPoza = $user["pozaProfil"];

    $upload_dir = __DIR__ . "/../uploads/" . $username . "/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $file_info = pathinfo($_FILES["poza_profil"]["name"]);
    $extension = strtolower($file_info["extension"]);

    if (!in_array($extension, $allowed_extensions)) {
        echo json_encode(["error" => "Format fișier invalid. Sunt permise: jpg, jpeg, png, gif, webp."]);
        exit;
    }

    $file_name = uniqid("pfp_", true) . "." . $extension;
    $target_path = $upload_dir . $file_name;

    if (!move_uploaded_file($_FILES["poza_profil"]["tmp_name"], $target_path)) {
        echo json_encode(["error" => "Eroare la mutarea fișierului."]);
        exit;
    }

    if (!empty($oldPoza)) {
        $oldPath = $upload_dir . $oldPoza;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    $sql_update = "UPDATE users SET pozaProfil = :poza WHERE userID = :userID";
    $stmt_update = $conexiune->prepare($sql_update);
    $stmt_update->execute([
        ":poza" => $file_name,
        ":userID" => $_SESSION["userID"]
    ]);

    $_SESSION['pozaProfil'] = $file_name;

    echo json_encode(["success" => "Poza de profil a fost actualizată."]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Eroare PDO: " . $e->getMessage()]);
}
?>

<?php
include('../config.php'); // include your database connection

header('Content-Type: application/json');

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    echo json_encode([
        "success" => false,
        "message" => "Trebuie să fii autentificat pentru a comenta."
    ]);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['userID'];
    $materialID = isset($_POST['materialID']) ? intval($_POST['materialID']) : 0;
    $comentariu = trim($_POST['comentariu']);

    // Validate comment
    if ($materialID <= 0 || $comentariu === '' || strlen($comentariu) > 255) {
        echo json_encode([
            "success" => false,
            "message" => "Comentariul este invalid sau prea lung."
        ]);
        exit;
    }

    // Insert comment into DB
    $stmt = $conexiune->prepare('
        INSERT INTO comentarii (userID, materialID, comentariu, dataPostarii)
        VALUES (?, ?, ?, NOW())
    ');
    $stmt->execute([$userID, $materialID, $comentariu]);

    // Get user info for frontend
    $stmtUser = $conexiune->prepare("SELECT username, pozaProfil FROM users WHERE userID = ?");
    $stmtUser->execute([$userID]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "username" => $user["username"],
        "pozaProfil" => $user["pozaProfil"],
        "comentariu" => htmlspecialchars($comentariu),
        "timestamp" => date("d M Y, H:i")
    ]);
    exit;
}

echo json_encode(["success" => false, "message" => "Metodă invalidă."]);

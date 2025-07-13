<?php
require_once __DIR__ . "/../config.php";
header('Content-Type: application/json');

if (!isset($_GET['materieID']) || !is_numeric($_GET['materieID'])) {
    http_response_code(400);
    echo json_encode(["error" => "Lipseste ID-ul materiei."]);
    exit;
}

$materieID = intval($_GET['materieID']);

try {
    $stmt = $conexiune->prepare("SELECT categorieID, nume FROM categorii WHERE materieID = ?");
    $stmt->execute([$materieID]);
    $categorii = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categorii);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>

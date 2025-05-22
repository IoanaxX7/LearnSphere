<?php
include('../config.php');

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conexiune->prepare("SELECT * FROM materiale WHERE materialID = ?");
        $stmt->execute([$id]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($material) {
            echo json_encode($material);
        } else {
            echo json_encode(["error" => "Materialul nu a fost găsit."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Eroare la baza de date: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "ID nu a fost găsit"]);
}

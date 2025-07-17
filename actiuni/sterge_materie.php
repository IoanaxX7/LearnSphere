<?php
include('../config.php');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["materieID"])) {
    $materieID = intval($_POST["materieID"]);

    try {
        $stmt = $conexiune->prepare("SELECT nume FROM materii WHERE materiiID = :materieID");
        $stmt->bindParam(":materieID", $materieID, PDO::PARAM_INT);
        $stmt->execute();

        $materie = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$materie) {
            echo json_encode(["success" => false, "message" => "Materia nu a fost găsită."]);
            exit;
        }

        $stmt = $conexiune->prepare("DELETE FROM materii WHERE materiiID = :materieID");
        $stmt->bindParam(":materieID", $materieID, PDO::PARAM_INT);
        $stmt->execute();

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Date invalide."]);
}

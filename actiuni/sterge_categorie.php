<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["categorieID"])) {
    $id = intval($_POST["categorieID"]);

    try {
        $stmt = $conexiune->prepare("DELETE FROM categorii WHERE categorieID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Date invalide."]);
}
?>

<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["intrebareID"])) {
    $id = intval($_POST["intrebareID"]);

    try {
        $stmt = $conexiune->prepare("DELETE FROM intrebari WHERE intrebareID = :id");
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

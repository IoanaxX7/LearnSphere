<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["materialID"])) {
    $id = intval($_POST["materialID"]);

    try {
        $stmt = $conexiune->prepare("
            SELECT u.username, m.material 
            FROM materiale m 
            JOIN users u ON m.userID = u.userID 
            WHERE m.materialID = :id
        ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['username']) && isset($result['material'])) {
            $username = $result['username'];
            $filename = $result['material'];

            $filePath = __DIR__ . '/../uploads/' . $username . '/' . $filename;

            $stmt = $conexiune->prepare("DELETE FROM materiale WHERE materialID = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Material not found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Date invalide."]);
}
?>

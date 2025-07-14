<?php
include('../config.php');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userID"])) {
    $id = intval($_POST["userID"]);

    try {
        // Step 1: Get the username first
        $stmt = $conexiune->prepare("SELECT username FROM users WHERE userID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "Utilizatorul nu a fost găsit."]);
            exit;
        }

        $username = $user['username'];
        $folderPath = __DIR__ . "/../uploads/$username";

        // Step 2: Delete the user from the DB
        $stmt = $conexiune->prepare("DELETE FROM users WHERE userID = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        // Step 3: If deleted, remove their folder
        if ($stmt->rowCount()) {
            // Function to recursively delete a folder
            function deleteFolder($folder) {
                if (!is_dir($folder)) return;
                $files = array_diff(scandir($folder), ['.', '..']);
                foreach ($files as $file) {
                    $fullPath = "$folder/$file";
                    is_dir($fullPath) ? deleteFolder($fullPath) : unlink($fullPath);
                }
                rmdir($folder);
            }

            if (is_dir($folderPath)) {
                deleteFolder($folderPath);
            }

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Eroare la ștergerea utilizatorului."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Date invalide."]);
}

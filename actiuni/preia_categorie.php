<?php
include('../config.php');

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conexiune->prepare("SELECT * FROM categorii WHERE categorieID = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(["error" => "Categorie nu a fost găsită."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Eroare la baza de date: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "ID nu a fost găsit."]);
}

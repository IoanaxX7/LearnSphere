<?php
include('../config.php');
header('Content-Type: application/json');

$rawInput = file_get_contents("php://input");

file_put_contents("debug_log.txt", $rawInput);

$date_intrare = json_decode($rawInput, true);

if (!$date_intrare) {
    echo json_encode([
        "error" => "JSON decoding failed or input empty.",
        "raw_input" => $rawInput,
        "json_last_error" => json_last_error_msg()
    ]);
    exit;
}

if (!isset($date_intrare['materieID'])) {
    echo json_encode(["error" => "Missing materieID in input.", "input" => $date_intrare]);
    exit;
}

$materieID = $date_intrare['materieID'];
$nume = $date_intrare['nume'];
$descriere = $date_intrare['descriere'];

try {
    $sql1 = "SELECT COUNT(*) FROM materii WHERE nume = :nume AND materiiID <> :materieID";
    $cerereSQL1 = $conexiune->prepare($sql1);
    $cerereSQL1->execute([":nume" => $nume, ":materieID" => $materieID]);
    $nr_randuri = $cerereSQL1->fetchColumn();

    if ($nr_randuri > 0) {
        echo json_encode(["error" => "Materia există deja."]);
        exit;
    }

    $sql2 = "UPDATE materii SET nume=:nume, descriere=:descriere WHERE materiiID=:materieID";
    $cerereSQL2 = $conexiune->prepare($sql2);
    $cerereSQL2->execute([
        ":nume" => $nume,
        ":descriere" => $descriere,
        ":materieID" => $materieID
    ]);

    $mesaj = "Datele au fost actualizate.";

    echo json_encode(["success" => $mesaj]);
    exit;
} catch (PDOException $e) {
    echo json_encode(["error" => "Eroare la baza de date: " . $e->getMessage()]);
    exit;
}

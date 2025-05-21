<?php
include('../config.php');
header('Content-Type: application/json');

$rawInput = file_get_contents("php://input");

// TEMPORARY DEBUGGING — log to a file
file_put_contents("debug_log.txt", $rawInput);

$date_intrare = json_decode($rawInput, true);

if (!$date_intrare) {
    echo json_encode(["error" => "JSON decoding failed or input empty.", "raw_input" => $inputJSON]);
    exit;
}

if (!isset($date_intrare['userId'])) {
    echo json_encode(["error" => "Missing userId in input.", "input" => $date_intrare]);
    exit;
}



// Read JSON from fetch body
$date_intrare = json_decode(file_get_contents("php://input"), true);

if (!$date_intrare || !isset($date_intrare['userID'])) {
    echo json_encode(["error" => "Datele nu au fost primite corect."]);
    exit;
}

$id = $date_intrare['userId'];
$rol = $date_intrare['rol'];
$nume = $date_intrare['nume'];
$prenume = $date_intrare['prenume'];
$nume_utilizator = htmlspecialchars($date_intrare['nume_utilizator']);
$email = $date_intrare['email'];
$data_nasterii = $date_intrare['data_nasterii'];
$parola1 = $date_intrare['parola1'];
$parola2 = $date_intrare['parola2'];

try {
    // Check for username conflict
    $sql1 = "SELECT COUNT(*) FROM users WHERE username = :nume_utilizator AND userID <> :id";
    $cerereSQL1 = $conexiune->prepare($sql1);
    $cerereSQL1->execute([":nume_utilizator" => $nume_utilizator, ":id" => $id]);
    $nr_randuri = $cerereSQL1->fetchColumn();

    if ($nr_randuri > 0) {
        echo json_encode(["error" => "Utilizatorul există deja."]);
        exit;
    }

    // Update user data
    $sql2 = "UPDATE users SET username=:nume_utilizator, rol=:rol, nume=:nume, prenume=:prenume, email=:email, dataNasterii=:dataNasterii WHERE userID=:id";
    $cerereSQL2 = $conexiune->prepare($sql2);
    $cerereSQL2->execute([
        ":nume_utilizator" => $nume_utilizator,
        ":rol" => $rol,
        ":nume" => $nume,
        ":prenume" => $prenume,
        ":email" => $email,
        ":dataNasterii" => $data_nasterii,
        ":id" => $id
    ]);

    $mesaj = "Datele au fost actualizate.";

    // Update password if needed
    if (!empty($parola1)) {
        if ($parola1 !== $parola2) {
            echo json_encode(["error" => "Parolele nu se potrivesc."]);
            exit;
        }
        if (
            strlen($parola1) < 9 ||
            !preg_match("#[A-Z]+#", $parola1) ||
            !preg_match("#[a-z]+#", $parola1) ||
            !preg_match("#[0-9]+#", $parola1)
        ) {
            echo json_encode(["error" => "Parola nu respectă cerințele de securitate."]);
            exit;
        }

        $sql3 = "UPDATE users SET parola = :parola WHERE userID = :id";
        $cerereSQL3 = $conexiune->prepare($sql3);
        $cerereSQL3->execute([
            ":parola" => password_hash($parola1, PASSWORD_DEFAULT),
            ":id" => $id
        ]);

        $mesaj = " Parola a fost schimbată.";
    }

    echo json_encode(["success" => $mesaj]);
    exit;
} catch (PDOException $e) {
    echo json_encode(["error" => "Eroare la baza de date: " . $e->getMessage()]);
    exit;
}

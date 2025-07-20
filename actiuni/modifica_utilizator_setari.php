<?php
include('../config.php');
header('Content-Type: application/json; charset=utf-8');

$mesaj = '';

if (!empty($_SESSION["userID"])) {
    try {
        $sql = "SELECT * FROM users WHERE userID = :userID";
        $declaratie = $conexiune->prepare($sql);
        if ($declaratie->execute(array(":userID" => $_SESSION["userID"]))) {
            $rand = $declaratie->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $mesaj = 'Eroare! Ceva nu a funcționat cum trebuie.<br/>' . $e->getMessage();
        echo json_encode(['error' => $mesaj]);
        exit();
    }
} else {
    $mesaj = 'ID utilizator nu este setat.';
    echo json_encode(['error' => $mesaj]);
    exit();
}

if (htmlspecialchars($_SERVER["REQUEST_METHOD"]) == "POST") {
    // Citește datele brute JSON
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    $nume_utilizator = htmlspecialchars($data["nume_utilizator"] ?? "");
    $nume = htmlspecialchars($data["nume"] ?? "");
    $prenume = htmlspecialchars($data["prenume"] ?? "");
    $email = htmlspecialchars($data["email"] ?? "");
    $data_nasterii = htmlspecialchars($data["data_nasterii"] ?? "");
    $biografie = htmlspecialchars($data["biografie"] ?? "");
    $parola1 = htmlspecialchars($data["parola1"] ?? "");
    $parola2 = htmlspecialchars($data["parola2"] ?? "");

    if (!empty($nume_utilizator)) {
        try {
            $sql1 = "SELECT count(*) FROM users WHERE username=:nume_utilizator AND userID<>:userID";
            $cerereSQL1 = $conexiune->prepare($sql1);
            $cerereSQL1->execute([
                ":nume_utilizator" => $nume_utilizator,
                ":userID" => $_SESSION["userID"]
            ]);
            $nr_randuri = $cerereSQL1->fetchColumn();

            if ($nr_randuri == 0) {
                $sql2 = 'UPDATE users SET username=:nume_utilizator WHERE userID=:userID';
                $cerereSQL2 = $conexiune->prepare($sql2);
                if ($cerereSQL2->execute([
                    ":nume_utilizator" => $nume_utilizator,
                    ":userID" => $_SESSION["userID"]
                ])) {
                    $mesaj = "Datele utilizatorului au fost modificate.";

                    if (!empty($parola1)) {
                        if ($nume_utilizator == $parola1) {
                            $mesaj .= "<br>Eroare! Numele de utilizator și parola nu pot fi identice.";
                        } else if (stripos($parola1, $nume_utilizator)) {
                            $mesaj .= "<br>Eroare! Parola nu poate contine username-ul utilizatorului.";
                        } else if ((strlen($parola1) < 9) || (!preg_match("#[A-Z]+#", $parola1)) || (!preg_match("#[a-z]+#", $parola1)) || (!preg_match("#[0-9]+#", $parola1))) {
                            $mesaj .= "<br>Eroare! Parola trebuie să conțină minim: 9 caractere, o literă mare, o literă mică și un număr.";
                        } else if ($parola2 != $parola1) {
                            $mesaj .= "<br>Eroare! Cele două parole nu se potrivesc.";
                        } else {
                            $sql3 = "UPDATE users SET parola=:parola WHERE userID=:userID";
                            $cerereSQL3 = $conexiune->prepare($sql3);
                            if ($cerereSQL3->execute([
                                ":parola" => password_hash($parola1, PASSWORD_DEFAULT),
                                ":userID" => $_SESSION["userID"]
                            ])) {
                                $mesaj .= "<br>Parola a fost modificată.";
                            } else {
                                $mesaj .= "<br>Eroare la modificarea parolei utilizatorului.";
                            }
                        }
                    }

                    $campuri = [
                        'nume' => $nume,
                        'prenume' => $prenume,
                        'email' => $email,
                        'dataNasterii' => $data_nasterii,
                        'biografie' => $biografie
                    ];

                    foreach ($campuri as $cheie => $valoare) {
                        if (!empty($valoare)) {
                            $sql = "UPDATE users SET $cheie=:$cheie WHERE userID=:userID";
                            $stmt = $conexiune->prepare($sql);
                            if (!$stmt->execute([":$cheie" => $valoare, ":userID" => $_SESSION["userID"]])) {
                                $mesaj .= "<br>Eroare la modificarea $cheie.";
                            }
                        }
                    }
                } else {
                    $mesaj = "Eroare la modificarea datelor utilizatorului.";
                }
            } else {
                $mesaj = "Eroare! Utilizatorul există deja în baza de date.";
            }

            echo json_encode(['success' => $mesaj]);
            exit();
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Eroare PDO: ' . $e->getMessage()]);
            exit();
        }
    } else {
        $mesaj = 'Nu ați completat toate datele obligatorii.';
        echo json_encode(['error' => $mesaj]);
        exit();
    }
}

$conexiune = null;

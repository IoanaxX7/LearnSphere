<?php
include('../config.php');

header('Content-Type: application/json');

if (isset($_POST)) {
    $erori_validare = [];
    $date_iesire = [];
    $mesaj_succes_sql = "";
    $mesaj_eroare_sql = "";

    $date_intrare = json_decode(file_get_contents("php://input"), true);
    $nume_materie = htmlspecialchars($date_intrare['nume_materie']);
    $descriere = $date_intrare['descriere'];

    if (empty($nume_materie)) {
        $erori_validare['nume_categorie'] = 'Numele materiei este obligatoriu.';
    }
    
    if (!empty($erori_validare)) {
        $date_iesire['succes'] = false;
        $date_iesire['erori_validare'] = $erori_validare;
    } else {
        $date_iesire['succes'] = true;
        try {
            $sql1 = 'SELECT nume FROM materii WHERE nume = :nume_materie';
            $cerereSQL1 = $conexiune->prepare($sql1);
            $cerereSQL1->execute(array(':nume_materie' => $nume_materie));
            $rezultat1 = $cerereSQL1->fetchAll(PDO::FETCH_ASSOC);
            if (count($rezultat1) == 0) {
                $sql2 = 'INSERT INTO materii(nume, descriere, dataPostarii)
                VALUES(:nume_materie, :descriere, curdate())';
                $cerereSQL2 = $conexiune->prepare($sql2);
                if (
                    $cerereSQL2->execute(
                        array(
                            ':nume_materie' => $nume_materie,
                            ':descriere' => $descriere,
                        )
                    )
                ) {
                    $mesaj_succes_sql .= 'Materia a fost adăugată!';
                } else {
                    $mesaj_eroare_sql = 'Eroare la adăugarea materiei!';
                }
            } else {
                $mesaj_eroare_sql = 'Numele materiei există deja.';
            }
        } catch (PDOException $e) {
            $mesaj_eroare_sql = 'Eroare! ' . $e->getMessage();
        }
        $date_iesire['mesaj_succes_sql'] = $mesaj_succes_sql;
        $date_iesire['mesaj_eroare_sql'] = $mesaj_eroare_sql;
    }

    echo json_encode($date_iesire);
}
<?php
include('../config.php');

if (isset($_POST)) {
    $erori_validare = [];
    $date_iesire = [];
    $mesaj_succes_sql = "";
    $mesaj_eroare_sql = "";

    $date_intrare = json_decode(file_get_contents("php://input"), true);
    $nume_categorie = htmlspecialchars($date_intrare['nume_categorie']);
    $descriere = $date_intrare['descriere'];

    if (empty($nume_categorie)) {
        $erori_validare['nume_categorie'] = 'Numele categoriei este obligatoriu.';
    }
    
    if (!empty($erori_validare)) {
        $date_iesire['succes'] = false;
        $date_iesire['erori_validare'] = $erori_validare;
    } else {
        $date_iesire['succes'] = true;
        $date_iesire['succes_validare'] = 'Datele au fost validate cu succes!';
        try {
            $sql1 = 'SELECT nume FROM categorii WHERE nume = :nume_categorie';
            $cerereSQL1 = $conexiune->prepare($sql1);
            $cerereSQL1->execute(array(':nume_categorie' => $nume_categorie));
            $rezultat1 = $cerereSQL1->fetchAll(PDO::FETCH_ASSOC);
            if (count($rezultat1) == 0) {
                $sql2 = 'INSERT INTO categorii(nume, descriere, dataPostarii)
                VALUES(:nume_categorie, :descriere, curdate())';
                $cerereSQL2 = $conexiune->prepare($sql2);
                if (
                    $cerereSQL2->execute(
                        array(
                            ':nume_categorie' => $nume_categorie,
                            ':descriere' => $descriere,
                        )
                    )
                ) {
                    $mesaj_succes_sql .= 'Categoria a fost adăugat în baza de date!';
                } else {
                    $mesaj_eroare_sql = 'Eroare la adăugarea categoriei în baza de date!';
                }
            } else {
                $mesaj_eroare_sql = 'Eroare! Numele categoriei există deja în baza de date.';
            }
        } catch (PDOException $e) {
            $mesaj_eroare_sql = 'Eroare! ' . $e->getMessage();
        }
        $date_iesire['mesaj_succes_sql'] = $mesaj_succes_sql;
        $date_iesire['mesaj_eroare_sql'] = $mesaj_eroare_sql;
    }

    echo json_encode($date_iesire);
}
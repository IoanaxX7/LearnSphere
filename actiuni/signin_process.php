<?php
include('../config.php');

if (isset($_POST)) {
    $erori_validare = [];
    $date_iesire = [];
    $mesaj_succes_sql = "";
    $mesaj_eroare_sql = "";

    $date_intrare = json_decode(file_get_contents("php://input"), true);
    $nume = $date_intrare['nume'];
    $prenume = $date_intrare['prenume'];
    $nume_utilizator = htmlspecialchars($date_intrare['nume_utilizator']);
    $email = $date_intrare['email'];
    $data_nasterii = $date_intrare['data_nasterii'];
    $parola1 = $date_intrare['parola1'];
    $parola2 = $date_intrare['parola2'];

    if (empty($nume)) {
        $erori_validare['nume'] = 'Numele este obligatoriu.';
    }
    if (empty($prenume)) {
        $erori_validare['prenume'] = 'Prenumele este obligatoriu.';
    }
    if (empty($nume_utilizator)) {
        $erori_validare['nume_utilizator'] = 'Numele de utilizator este obligatoriu.';
    }
    if (empty($email)) {
        $erori_validare['email'] = 'Emailul este obligatoriu.';
    }
    if (empty($data_nasterii)) {
        $erori_validare['data_nasterii'] = 'Data nasterii este obligatorie.';
    }
    if (empty($parola1)) {
        $erori_validare['parola1'] = 'Parola este obligatorie.';
    } else {
        if (
            (strlen($parola1) < 9) || (!preg_match("#[A-Z]+#", $parola1)) ||
            (!preg_match("#[a-z]+#", $parola1)) || (!preg_match("#[0-9]+#", $parola1))
        ) {
            $erori_validare['parola1'] = 'Parola trebuie să conțină minim 9 caractere, o literă mare, o litera mică și un numar!';
        } else if ($parola1 != $parola2) {
            $erori_validare['parola2'] = 'Parolele trebuie să coincidă.';
        }
    }
    if (!empty($erori_validare)) {
        $date_iesire['succes'] = false;
        $date_iesire['erori_validare'] = $erori_validare;
    } else {
        $date_iesire['succes'] = true;
        try {
            $sql1 = 'SELECT username FROM users WHERE username = :username';
            $cerereSQL1 = $conexiune->prepare($sql1);
            $cerereSQL1->execute(array(':username' => $nume_utilizator));
            $rezultat1 = $cerereSQL1->fetchAll(PDO::FETCH_ASSOC);
            if (count($rezultat1) == 0) {
                $sql2 = 'INSERT INTO users(username, rol, parola, pozaProfil, nume, prenume, email, dataNasterii, dataInregistrarii, darkMode)
                VALUES(:username, :rol, :parola, :pozaProfil, :nume, :prenume, :email, :dataNasterii, curdate(), :darkMode)';
                $cerereSQL2 = $conexiune->prepare($sql2);
                if (
                    $cerereSQL2->execute(
                        array(
                            ':username' => $nume_utilizator,
                            ':rol' => '2',
                            ':parola' => password_hash($parola1, PASSWORD_DEFAULT),
                            ':pozaProfil' => 'default.jpg',
                            ':nume' => $nume,
                            ':prenume' => $prenume,
                            ':email' => $email,
                            ':dataNasterii' => $data_nasterii,
                            ':darkMode' => '0'

                        )
                    )
                ) {
                    $mesaj_succes_sql .= 'Contul a fost creat!';
                    $date_iesire['succes_validare'] = 'Contul a fost creat cu succes! Redirecționare în 5 secunde...';
                } else {
                    $mesaj_eroare_sql = 'Eroare la crearea contului!';
                }
            } else {
                $mesaj_eroare_sql = 'Numele de utilizator există deja.';
            }
        } catch (PDOException $e) {
            $mesaj_eroare_sql = 'Eroare! ' . $e->getMessage();
        }
        $date_iesire['mesaj_succes_sql'] = $mesaj_succes_sql;
        $date_iesire['mesaj_eroare_sql'] = $mesaj_eroare_sql;
    }

    echo json_encode($date_iesire);
}
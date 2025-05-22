<?php
include('../config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erori_validare = [];
    $date_iesire = [];

    $titlu = $_POST['titlu'] ?? null;
    $categorie = $_POST['categorie'] ?? null;
    $descriere = $_POST['descriere'] ?? '';
    $cuvinte_cheie = $_POST['cuvinte_cheie'] ?? '';
    $file = $_FILES['material'] ?? null;

    // Validare
    if (empty($titlu)) {
        $erori_validare['titlu'] = 'Titlul este obligatoriu.';
    }

    if (empty($categorie)) {
        $erori_validare['categorie'] = 'Categoria este obligatorie.';
    }

    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $erori_validare['material'] = 'Materialul este obligatoriu.';
    }

    if (!empty($erori_validare)) {
        echo json_encode([
            'succes' => false,
            'erori_validare' => $erori_validare
        ]);
        exit;
    }

    // Pregătește folderul de upload
    $username = $_SESSION['username'] ?? 'guest';
    $upload_dir = "../uploads/" . $username . "/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($file['name']);
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        try {
            $sql = 'INSERT INTO materiale (userID, titlu, categorieID, descriere, cuvinteCheie, material, dataPostarii)
                    VALUES (:userID, :titlu, :categorie, :descriere, :cuvinte_cheie, :material, NOW())';

            $stmt = $conexiune->prepare($sql);
            $executat = $stmt->execute([
                ':userID' => $_SESSION['userID'],
                ':titlu' => $titlu,
                ':categorie' => $categorie,
                ':descriere' => $descriere,
                ':cuvinte_cheie' => $cuvinte_cheie,
                ':material' => $file_name
            ]);

            if ($executat) {
                $date_iesire['succes'] = true;
                $date_iesire['mesaj'] = 'Materialul a fost adăugat cu succes.';
                header('Location: ../postari/materiale.php');
                exit;
            } else {
                $date_iesire['succes'] = false;
                $date_iesire['mesaj'] = 'Eroare la inserarea în baza de date.';
            }
        } catch (PDOException $e) {
            $date_iesire['succes'] = false;
            $date_iesire['mesaj'] = 'Eroare BD: ' . $e->getMessage();
        }
    } else {
        $date_iesire['succes'] = false;
        $date_iesire['mesaj'] = 'Eroare la mutarea fișierului.';
    }

    header('Location: ../postari/form_material.php');
    exit;
}

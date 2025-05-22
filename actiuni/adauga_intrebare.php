<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erori_validare = [];

    $intrebare = $_POST['intrebare'] ?? null;
    $categorie = $_POST['categorie'] ?? null;
    $detalii = $_POST['detalii'] ?? '';
    $file = $_FILES['material'] ?? null;

    if (empty($intrebare)) {
        $erori_validare['intrebare'] = 'Întrebarea este obligatorie.';
    }

    if (empty($categorie)) {
        $erori_validare['categorie'] = 'Categoria este obligatorie.';
    }

    if (!empty($erori_validare)) {
        $_SESSION['errors'] = $erori_validare;
        header('Location: ../postari/form_intrebare.php');
        exit;
    }

    $file_name = null;

    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $username = $_SESSION['username'] ?? 'guest';
        $upload_dir = "../uploads/" . $username . "/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($file['name']);
        $target_path = $upload_dir . $file_name;

        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            $_SESSION['file_error'] = 'Eroare la mutarea fișierului.';
            header('Location: ../postari/form_intrebare.php');
            exit;
        }
    }

    try {
        $sql = 'INSERT INTO intrebari (userID, intrebare, categorieID, detalii, material, dataPostarii)
                VALUES (:userID, :intrebare, :categorie, :detalii, :material, NOW())';

        $stmt = $conexiune->prepare($sql);
        $executat = $stmt->execute([
            ':userID' => $_SESSION['userID'],
            ':intrebare' => $intrebare,
            ':categorie' => $categorie,
            ':detalii' => $detalii,
            ':material' => $file_name 
        ]);

        if ($executat) {
            header('Location: ../postari/intrebari.php');
            exit;
        } else {
            $_SESSION['db_error'] = 'Eroare la inserarea în baza de date.';
            header('Location: ../postari/form_intrebare.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['db_error'] = 'Eroare BD: ' . $e->getMessage();
        header('Location: ../postari/form_intrebare.php');
        exit;
    }
}
?>

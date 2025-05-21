<?php
include('../config.php');

if (isset($_POST['nume_utilizator']) && isset($_POST['parola'])) {
    $username = $_POST['nume_utilizator'];
    $password = $_POST['parola'];

    $sql = "SELECT * FROM users WHERE username = :username";
    $cerereSQL = $conexiune->prepare($sql);
    $cerereSQL->execute(array(":username" => $username));
    $user = $cerereSQL->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['parola'])) {
        $_SESSION['username'] = $username;
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['pozaProfil'] = $user['pozaProfil'];

        $userDir = __DIR__ . "/../uploads/$username";
        if (!file_exists($userDir)) {
            mkdir($userDir, 0755, true);
        }
        
        $defaultImage = __DIR__ . '/../uploads/default.jpg';
        $destinationImage = $userDir . "/default.jpg";
        copy($defaultImage, $destinationImage);

        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../login.php?error=1");
        exit();
    }
}

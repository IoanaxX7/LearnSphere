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
        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../login.php?error=1");
        exit();
    }
}
<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

set_time_limit(0);

if(error_reporting() !== E_ALL){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

$Roluri = array(1, 2);

$Server = "localhost";
$NumeDB = "learnsphere";
$dsn = "mysql:host=$Server;dbname=$NumeDB;charset=utf8mb4";
$UserDB = "databaseAdmin";
$ParolaDB = "5K1LL-155U3+L+RAT10";
$optiuni = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try{
    $conexiune = new PDO($dsn, $UserDB, $ParolaDB, $optiuni);
} catch (PDOException $e) {
    exit ("Eroare! Nu s-a putut realiza conexiunea la baza de date.<br>" . $e->getMessage());
}

function url()
{
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'] . '/LearnSphere'
    );
}
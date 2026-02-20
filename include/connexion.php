<?php
include('config.php');

function connexion()
{
    $pdo = new PDO('mysql:host=' . SERVER . ';dbname=' . BDD . ';charset=utf8', USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    if ($pdo) {
        return $pdo;
    } else {
        echo '<p>Connexion à la base de données impossible !</p>';
        exit;
    }
}

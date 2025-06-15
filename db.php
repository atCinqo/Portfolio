<?php
$host = 'localhost';
$dbname = 'u926477285_portfolio';
$user = 'u926477285_tom_allano';
$pass = 'aLtOm!100805'; // mot de passe vide par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
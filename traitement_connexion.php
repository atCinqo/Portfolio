<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Stocke les infos en session même si pas approuvé
        $_SESSION['user'] = [
            'id' => $user['id'],
            'prenom' => $user['prenom'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'],
            'is_approved' => $user['is_approved']
        ];

        // Redirige toujours vers index.php
        header("Location: index.php");
        exit;

    } else {
        header("Location: connexion.php?erreur=Email ou mot de passe incorrect.");
        exit;
    }
} else {
    header("Location: connexion.php");
    exit;
}
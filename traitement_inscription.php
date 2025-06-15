<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm']; // Ajoute cette ligne

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Email invalide, on revient sur inscription avec message d'erreur
        $_SESSION['error'] = "L'adresse email n'est pas valide.";
        header('Location: inscription.php');
        exit;
    }

    // Vérifie que les mots de passe correspondent
    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header('Location: inscription.php');
        exit;
    }

    // Ici tu peux ajouter d'autres validations (taille mdp, etc.)

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Par défaut, rôle utilisateur et pas approuvé
    $role = 'utilisateur';
    $is_approved = 0;

    // Prépare et execute l'insertion
    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, is_approved) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $email, $passwordHash, $role, $is_approved]);

    // Récupérer l'id
    $userId = $pdo->lastInsertId();

    // Crée session utilisateur (en attente de validation)
    $_SESSION['user'] = [
        'id' => $userId,
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'role' => $role,
        'is_approved' => $is_approved
    ];

    // Redirige vers index ou autre
    header('Location: index.php');
    exit;
}

<?php
session_start();
require_once 'db.php';

// Vérifie que seul le concepteur peut agir
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concepteur') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    // Récupère le rôle actuel de l'utilisateur ciblé
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $targetUser = $stmt->fetch();

    if (!$targetUser) {
        header('Location: approbation_admin.php');
        exit;
    }

    if ($action === 'approuver' || $action === 'changer_role') {
        $role = $_POST['role'];
        // Si la personne ciblée est déjà concepteur ET qu'on veut la rétrograder, on bloque
        if ($targetUser['role'] === 'concepteur' && $role !== 'concepteur') {
            header('Location: approbation_admin.php?erreur=concepteur');
            exit;
        }
        // Sinon, on autorise la modification (promotion ou changement entre autres rôles)
        $stmt = $pdo->prepare("UPDATE users SET is_approved = 1, role = ? WHERE id = ?");
        $stmt->execute([$role, $user_id]);
    } elseif ($action === 'supprimer') {
        // Empêche de supprimer un concepteur
        if ($targetUser['role'] === 'concepteur') {
            header('Location: approbation_admin.php?erreur=concepteur');
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    header('Location: approbation_admin.php');
    exit;
}
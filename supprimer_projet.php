<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concepteur') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Optionnel : supprimer aussi l'image sur le disque si nécessaire

    $stmt = $pdo->prepare("DELETE FROM projets WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: galerie.php');
exit;
<?php
session_start();
session_destroy(); // Supprime toutes les données de la session

// Redirige vers la page d'accueil après déconnexion
header('Location: index.php');
exit;
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection | Tom ALLANO</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
     <?php include 'navbar.php'; ?>
    

<div class="galerie"><h2>inscription</h2></div>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red; text-align:center; margin-bottom:16px;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}
?>

<form action="traitement_inscription.php" method="POST" class="form-ajout-projet">
    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" id="prenom" placeholder="Votre prénom" required>

    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" placeholder="Votre nom" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" placeholder="Votre email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" placeholder="Mot de passe" required>

    <label for="password_confirm">Confirmer le mot de passe :</label>
    <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required>

    <button type="submit">S'inscrire</button>
    <p class="inscription-link">
        Déjà un compte ?
        <a href="connexion.php"><strong>Se connecter</strong></a>
    </p>
</form>

<?php include 'footer.php'; ?>
</body>
</html>


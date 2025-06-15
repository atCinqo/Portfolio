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
    

<div class="galerie"><h2>connexion</h2></div>

<?php
    if (isset($_GET['erreur'])) {
        echo '<p style="color:red;">' . htmlspecialchars($_GET['erreur']) . '</p>';
    }
    ?>

   <form action="traitement_connexion.php" method="POST" class="form-ajout-projet">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" placeholder="Email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" placeholder="Mot de passe" required>

    <button type="submit">Se connecter</button>
    <p class="inscription-link">
        Pas encore de compte ?
        <a href="inscription.php"><strong>Créer un compte</strong></a>
    </p>
</form>

<?php include 'footer.php'; ?>
</body>
</html>
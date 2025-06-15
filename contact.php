<?php
// Traitement du formulaire de contact
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom === '' || $email === '' || $message === '') {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {
        // Ici tu pourrais envoyer un mail ou enregistrer le message en BDD
        // mail('ton@email.com', 'Nouveau message de contact', $message);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact | Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="galerie"><h2>Contactez-moi</h2></div>
<p style="margin-bottom:18px; font-size:1.1em; text-align:center;">
        Je suis ouvert à de nouveaux projets collaboratifs, partenariats <br>
         ou opportunités professionnelles. N'hésitez pas à me contacter !
    </p>
<div class="contact-container">
    
    <div class="contact-socials">
        <a href="../uploads/ALLANO_Tom_CV.pdf" class="download-link" download><i class="fa-solid fa-download"></i>Télécharger mon CV</a>
        <a href="mailto:tomallano83@gmail.com" title="Mail"><i class="fa-brands fa-solid fa-at"></i> tomallano83@gmail.com</a>
        <a href="https://www.linkedin.com/in/tomallano" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i> LinkedIn</a>
        <a href="https://www.instagram.com/atcinqo" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i> Instagram</a>
        <a href="https://www.tiktok.com/@cinqo1_" target="_blank" title="TikTok"><i class="fa-brands fa-tiktok"></i> TikTok</a>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
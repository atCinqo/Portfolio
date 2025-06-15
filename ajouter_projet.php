<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concepteur') {
    header('Location: indexport.php');
    exit;
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $annee = $_POST['annee'] ?? '';
    $competence = $_POST['competence'] ?? '';
    $description = $_POST['description'] ?? '';
    $description_detaillee = $_POST['description_detaillee'] ?? '';
    $type = $_POST['type'] ?? '';
    $imagePath = '';
    $critique = $_POST['critique'] ?? '';

    // Gérer l'upload de l'image principale
    $uploadDir = '../uploads/';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $originalName = basename($_FILES['image']['name']);
        $fileName = uniqid() . '_' . $originalName;
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $imagePath = $fileName;
        }
    }

    // Gérer les images secondaires
    $imagesSecondaires = [];
    if (!empty($_FILES['images_secondaires']['name'][0])) {
        foreach ($_FILES['images_secondaires']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images_secondaires']['error'][$key] === 0) {
                $originalName = basename($_FILES['images_secondaires']['name'][$key]);
                $fileName = uniqid() . '_' . $originalName;
                $uploadPath = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $imagesSecondaires[] = $fileName;
                }
            }
        }
    }
    $imagesSecondairesJson = json_encode($imagesSecondaires);

    // Insérer dans la base (ajoute bien description_detaillee dans ta table projets)
    $critique = $_POST['critique'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO projets (titre, annee, competence, description, description_detaillee, image, images_secondaires, type, critique) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $annee, $competence, $description, $description_detaillee, $imagePath, $imagesSecondairesJson, $type, $critique]);

    header('Location: galerie.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="favicon.png">
    
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="galerie"><h2>ajouter un projet</h2></div>
   
    <form method="POST" enctype="multipart/form-data" class="form-ajout-projet">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required>

        <label for="annee">Année :</label>
        <input type="text" name="annee" id="annee" min="1900" max="2100" required placeholder="Ex : 2024">

        <label for="competence">Compétences</label>
        <select name="competence" id="type" required>
            <option value="">Sélectionner une compétence</option>
            <option value="Concevoir" <?= (isset($_GET['competence']) && $_GET['competence']=='Concevoir') ? 'selected' : '' ?>>Concevoir</option>
    <option value="entreprendre" <?= (isset($_GET['competence']) && $_GET['competence']=='entreprendre') ? 'selected' : '' ?>>Entreprendre</option>
    <option value="exprimer" <?= (isset($_GET['competence']) && $_GET['competence']=='exprimer') ? 'selected' : '' ?>>Exprimer</option>
    <option value="comprendre" <?= (isset($_GET['competence']) && $_GET['competence']=='comprendre') ? 'selected' : '' ?>>Comprendre</option>
    <option value="developper" <?= (isset($_GET['competence']) && $_GET['competence']=='developper') ? 'selected' : '' ?>>Développer</option>
        </select>

        <label for="type">Type de projet :</label>
        <select name="type" id="type" required>
            <option value="">Sélectionner un type</option>
            <option value="site web">Site web</option>
            <option value="vidéo">Vidéo</option>
            <option value="graphisme">Graphisme</option>
            <option value="communication">Communication</option>
            <!-- Ajoute d'autres types si besoin -->
        </select>
        <label for="critique">Apprentissage critique</label>
        <select name="critique" id="critique" required>
    <option value="">Sélectionner un apprentissage critique</option>
    <option value="AC11.01" <?= (isset($projet['critique']) && $projet['critique']=='AC11.01') ? 'selected' : '' ?>>AC11.01</option>
    <option value="AC11.02" <?= (isset($projet['critique']) && $projet['critique']=='AC11.02') ? 'selected' : '' ?>>AC11.02</option>
    <option value="AC11.03" <?= (isset($projet['critique']) && $projet['critique']=='AC11.03') ? 'selected' : '' ?>>AC11.03</option>
    <option value="AC11.04" <?= (isset($projet['critique']) && $projet['critique']=='AC11.04') ? 'selected' : '' ?>>AC11.04</option>
    <option value="AC11.05" <?= (isset($projet['critique']) && $projet['critique']=='AC11.05') ? 'selected' : '' ?>>AC11.05</option>
    <option value="AC11.06" <?= (isset($projet['critique']) && $projet['critique']=='AC11.06') ? 'selected' : '' ?>>AC11.06</option>
    <option value="AC12.01" <?= (isset($projet['critique']) && $projet['critique']=='AC12.01') ? 'selected' : '' ?>>AC12.01</option>
    <option value="AC12.02" <?= (isset($projet['critique']) && $projet['critique']=='AC12.02') ? 'selected' : '' ?>>AC12.02</option>
    <option value="AC12.03" <?= (isset($projet['critique']) && $projet['critique']=='AC12.03') ? 'selected' : '' ?>>AC12.03</option>
    <option value="AC12.04" <?= (isset($projet['critique']) && $projet['critique']=='AC12.04') ? 'selected' : '' ?>>AC12.04</option>
    <option value="AC13.01" <?= (isset($projet['critique']) && $projet['critique']=='AC13.01') ? 'selected' : '' ?>>AC13.01</option>
    <option value="AC13.02" <?= (isset($projet['critique']) && $projet['critique']=='AC13.02') ? 'selected' : '' ?>>AC13.02</option>
    <option value="AC13.03" <?= (isset($projet['critique']) && $projet['critique']=='AC13.03') ? 'selected' : '' ?>>AC13.03</option>
    <option value="AC13.04" <?= (isset($projet['critique']) && $projet['critique']=='AC13.04') ? 'selected' : '' ?>>AC13.04</option>
    <option value="AC13.05" <?= (isset($projet['critique']) && $projet['critique']=='AC13.05') ? 'selected' : '' ?>>AC13.05</option>
    <option value="AC13.06" <?= (isset($projet['critique']) && $projet['critique']=='AC13.06') ? 'selected' : '' ?>>AC13.06</option>
    <option value="AC14.01" <?= (isset($projet['critique']) && $projet['critique']=='AC14.01') ? 'selected' : '' ?>>AC14.01</option>
    <option value="AC14.02" <?= (isset($projet['critique']) && $projet['critique']=='AC14.02') ? 'selected' : '' ?>>AC14.02</option>
    <option value="AC14.03" <?= (isset($projet['critique']) && $projet['critique']=='AC14.03') ? 'selected' : '' ?>>AC14.03</option>
    <option value="AC14.04" <?= (isset($projet['critique']) && $projet['critique']=='AC14.04') ? 'selected' : '' ?>>AC14.04</option>
    <option value="AC14.05" <?= (isset($projet['critique']) && $projet['critique']=='AC14.05') ? 'selected' : '' ?>>AC14.05</option>
    <option value="AC14.06" <?= (isset($projet['critique']) && $projet['critique']=='AC14.06') ? 'selected' : '' ?>>AC14.06</option>
    <option value="AC15.01" <?= (isset($projet['critique']) && $projet['critique']=='AC15.01') ? 'selected' : '' ?>>AC15.01</option>
    <option value="AC15.02" <?= (isset($projet['critique']) && $projet['critique']=='AC15.02') ? 'selected' : '' ?>>AC15.02</option>
    <option value="AC15.03" <?= (isset($projet['critique']) && $projet['critique']=='AC15.03') ? 'selected' : '' ?>>AC15.03</option>
    <option value="AC15.04" <?= (isset($projet['critique']) && $projet['critique']=='AC15.04') ? 'selected' : '' ?>>AC15.04</option>
    <option value="AC15.05" <?= (isset($projet['critique']) && $projet['critique']=='AC15.05') ? 'selected' : '' ?>>AC15.05</option>
    <option value="AC15.06" <?= (isset($projet['critique']) && $projet['critique']=='AC15.06') ? 'selected' : '' ?>>AC15.06</option>
    <option value="AC15.07" <?= (isset($projet['critique']) && $projet['critique']=='AC15.07') ? 'selected' : '' ?>>AC15.07</option>
</select>
      

        <label for="description">Description :</label>
        <textarea name="description" id="description" required placeholder="Décris ton projet ici..."></textarea>

        <label for="image" class="custom-file-label">Image principale :</label>
        <input type="file" name="image" id="image" accept="image/*" required class="custom-file-input">

        <label for="images_secondaires" class="custom-file-label">Images secondaires :</label>
        <input type="file" name="images_secondaires[]" id="images_secondaires" accept="image/*" multiple class="custom-file-input">

        <label for="description_detaillee">Description détaillée du projet :</label>
        <textarea name="description_detaillee" id="description_detaillee" rows="8" required placeholder="Décris en détail le projet, les outils utilisés, l'apprentissage critique, les étapes, etc."></textarea>
        
        <button type="submit">Ajouter</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
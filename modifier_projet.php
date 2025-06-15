<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concepteur') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Projet non spécifié.";
    exit;
}

// Récupération du projet
$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$id]);
$projet = $stmt->fetch();

if (!$projet) {
    echo "Projet introuvable.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $description_detaillee = $_POST['description_detaillee'] ?? '';
    $type = $_POST['type'] ?? '';
    $critique = $_POST['critique'] ?? '';
    $annee = $_POST['annee'] ?? '';
    $competence = $_POST['competence'] ?? '';

    $imageName = $projet['image']; // par défaut, l'ancienne image
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $imageName = time() . '_' . basename($image['name']);
        $uploadPath = '../uploads/' . $imageName;
        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
            if (!empty($projet['image']) && file_exists('../uploads/' . $projet['image'])) {
                unlink('../uploads/' . $projet['image']);
            }
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            exit;
        }
    }

    // Traitement des images secondaires
    $imagesSecondaires = [];
    if (!empty($_FILES['images_secondaires']['name'][0])) {
        foreach ($_FILES['images_secondaires']['tmp_name'] as $key => $tmpName) {
            $fileName = time() . '_' . basename($_FILES['images_secondaires']['name'][$key]);
            $uploadPath = '../uploads/' . $fileName;
            if (move_uploaded_file($tmpName, $uploadPath)) {
                $imagesSecondaires[] = $fileName;
            }
        }

        // Optionnel : supprimer les anciennes si besoin
        $oldImages = json_decode($projet['images_secondaires'], true) ?? [];
        foreach ($oldImages as $oldImage) {
            if (file_exists('../uploads/' . $oldImage)) {
                unlink('../uploads/' . $oldImage);
            }
        }
    } else {
        // Si pas de nouvelles images secondaires => conserver les anciennes
        $imagesSecondaires = json_decode($projet['images_secondaires'], true) ?? [];
    }

    $imagesSecondairesJson = json_encode($imagesSecondaires);

    // Mise à jour
    $stmt = $pdo->prepare("UPDATE projets SET titre=?, annee=?, competence=?, description=?, description_detaillee=?, image=?, images_secondaires=?, type=?, critique=? WHERE id=?");
    $stmt->execute([
        $titre, $annee, $competence, $description, $description_detaillee,
        $imageName, $imagesSecondairesJson, $type, $critique, $id
    ]);

    header("Location: galerie.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Projet</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="form-container">
    <div class="galerie"><h2>Modifier le projet</h2></div>

    <form method="POST" enctype="multipart/form-data" class="form-ajout-projet">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>

        <label for="annee">Année :</label>
        <input type="text" name="annee" id="annee" value="<?= htmlspecialchars($projet['annee']) ?>" required>

        <label class ="" for="competence">Compétences :</label>
        <select name="competence" id="type" required>
            <?php
            $competences = ['Concevoir', 'entreprendre', 'exprimer', 'comprendre', 'developper'];
            foreach ($competences as $c) {
                echo "<option value=\"$c\" " . ($projet['competence'] === $c ? 'selected' : '') . ">$c</option>";
            }
            ?>
        </select>

        <label for="type">Type de projet :</label>
        <select name="type" id="type" required>
            <?php
            $types = ['Site web', 'Vidéo', 'Graphisme', 'Photo', 'Communication'];
            foreach ($types as $t) {
                echo "<option value=\"$t\" " . ($projet['type'] === $t ? 'selected' : '') . ">$t</option>";
            }
            ?>
        </select>

        <label for="critique">Apprentissage critique :</label>
        <select name="critique" id="critique" required>
            <?php
            $ac_list = [
                'AC11.01','AC11.02','AC11.03','AC11.04','AC11.05','AC11.06',
                'AC12.01','AC12.02','AC12.03','AC12.04',
                'AC13.01','AC13.02','AC13.03','AC13.04','AC13.05','AC13.06',
                'AC14.01','AC14.02','AC14.03','AC14.04','AC14.05','AC14.06',
                'AC15.01','AC15.02','AC15.03','AC15.04','AC15.05','AC15.06','AC15.07'
            ];
            foreach ($ac_list as $ac) {
                echo "<option value=\"$ac\" " . ($projet['critique'] === $ac ? 'selected' : '') . ">$ac</option>";
            }
            ?>
        </select>

        <label for="description">Description :</label>
        <textarea name="description" id="description" rows="6" required><?= htmlspecialchars($projet['description']) ?></textarea>

        <label for="image">Nouvelle image principale :</label>
        <input type="file" name="image" id="image" accept="image/*">
        <?php if ($projet['image']): ?>
            <div class="image-preview">
                <p>Image actuelle :</p>
                <img src="../uploads/<?= htmlspecialchars($projet['image']) ?>" alt="image projet" width="200">
            </div>
        <?php endif; ?>

        <label for="images_secondaires" class="custom-file-label">Nouvelles images secondaires :</label>
        <input type="file" name="images_secondaires[]" id="images_secondaires" accept="image/*" multiple class="custom-file-input">

        <?php if (!empty($projet['images_secondaires'])): ?>
            <div class="image-preview">
                <p>Images secondaires actuelles :</p>
                <?php
                $images = json_decode($projet['images_secondaires'], true);
                foreach ($images as $img) {
                    echo "<img src=\"../uploads/" . htmlspecialchars($img) . "\" width=\"150\" style=\"margin:5px;\">";
                }
                ?>
            </div>
        <?php endif; ?>

        <label for="description_detaillee">Description détaillée :</label>
        <textarea name="description_detaillee" id="description_detaillee" rows="8" required><?= htmlspecialchars($projet['description_detaillee']) ?></textarea>

        <button type="submit">Enregistrer les modifications</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

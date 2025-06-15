<?php
session_start();
require_once 'db.php';

// Récupérer tous les projets approuvés
$stmt = $pdo->query("SELECT * FROM projets");
$projets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie | Tom ALLANO</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
   <?php include 'navbar.php'; ?>




<?php
require_once 'db.php';

$where = [];
$params = [];

if (!empty($_GET['type'])) {
    $where[] = "type = ?";
    $params[] = $_GET['type'];
}
if (!empty($_GET['annee'])) {
    $where[] = "annee = ?";
    $params[] = $_GET['annee'];
}
if (!empty($_GET['competence'])) {
    $where[] = "competence = ?";
    $params[] = $_GET['competence'];
}
if (!empty($_GET['critique'])) {
    $where[] = "critique = ?";
    $params[] = $_GET['critique'];
}

$sql = "SELECT * FROM projets";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY date_creation DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projets = $stmt->fetchAll();
?>

<div class="galerie"><h2>galerie</h2></div>

<form method="GET" class="filtre-projets" style="display:flex; gap:18px; margin-bottom:32px; align-items:center;">
    <label for="type">Type de projet :</label>
    <select name="type" id="type">
        <option value="">Tous</option>
        <option value="site web" <?= (isset($_GET['type']) && $_GET['type']=='site web') ? 'selected' : '' ?>>Site web</option>
        <option value="vidéo" <?= (isset($_GET['type']) && $_GET['type']=='vidéo') ? 'selected' : '' ?>>Vidéo</option>
        <option value="graphisme" <?= (isset($_GET['type']) && $_GET['type']=='graphisme') ? 'selected' : '' ?>>Graphisme</option>
        <option value="communication" <?= (isset($_GET['type']) && $_GET['type']=='communication') ? 'selected' : '' ?>>Communication</option>
        <!-- Ajoute d'autres types selon ta base -->
    </select>

    <label for="annee">Année :</label>
    <select name="annee" id="annee">
        <option value="">Toutes</option>
        <?php
        // Génère dynamiquement la liste des années présentes dans la base
        $stmt = $pdo->query("SELECT DISTINCT annee FROM projets ORDER BY annee DESC");
        while ($row = $stmt->fetch()) {
            $selected = (isset($_GET['annee']) && $_GET['annee'] == $row['annee']) ? 'selected' : '';
            echo "<option value=\"{$row['annee']}\" $selected>{$row['annee']}</option>";
        }
        ?>
    </select>

    <label for="competence">Compétence :</label>
    <select name="competence" id="competence">
        <option value="">Toutes</option>
        <option value="Concevoir" <?= (isset($_GET['competence']) && $_GET['competence']=='Concevoir') ? 'selected' : '' ?>>Concevoir</option>
        <option value="entreprendre" <?= (isset($_GET['competence']) && $_GET['competence']=='entreprendre') ? 'selected' : '' ?>>Entreprendre</option>
        <option value="exprimer" <?= (isset($_GET['competence']) && $_GET['competence']=='exprimer') ? 'selected' : '' ?>>Exprimer</option>
        <option value="comprendre" <?= (isset($_GET['competence']) && $_GET['competence']=='comprendre') ? 'selected' : '' ?>>Comprendre</option>
        <option value="developper" <?= (isset($_GET['competence']) && $_GET['competence']=='developper') ? 'selected' : '' ?>>Développer</option>
    </select>

    <label for="critique">Apprentissage critique :</label>
    <select name="critique" id="critique">
        <option value="">Tous</option>
        <?php
        $stmtCritique = $pdo->query("SELECT DISTINCT critique FROM projets WHERE critique IS NOT NULL AND critique != '' ORDER BY critique ASC");
        while ($row = $stmtCritique->fetch()) {
            $val = htmlspecialchars($row['critique']);
            $selected = (isset($_GET['critique']) && $_GET['critique'] == $row['critique']) ? 'selected' : '';
            echo "<option value=\"$val\" $selected>$val</option>";
        }
        ?>
    </select>
    <button type="submit">Filtrer</button>
</form>

<section class="sectgal">
    <?php foreach ($projets as $projet): ?>
        <div class="divgal">
            <a href="projet.php?id=<?= $projet['id'] ?>" class="projet-link">
                <img src="../uploads/<?= htmlspecialchars($projet['image'] ?? '') ?>" alt="<?= htmlspecialchars($projet['titre'] ?? '') ?>" class="imggal">
            </a>
            <p class="titre-projet"><?= htmlspecialchars($projet['titre'] ?? '') ?></p>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'concepteur'): ?>
                <div class="projet-actions">
                    <a href="modifier_projet.php?id=<?= $projet['id'] ?>" class="btn-modifier">Modifier</a>
                    <form action="supprimer_projet.php" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $projet['id'] ?>">
                        <button type="submit" class="btn-supprimer">Supprimer</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'concepteur'): ?>
        <div class="divgal no-shadow">
            <a href="ajouter_projet.php" class="projet-link">
                <div class="ajout-projet-plus">+</div>
            </a>
        </div>
    <?php endif; ?>
</section>



    <?php include 'footer.php'; ?>
</body>
</html>

<style>
/* Responsive pour la barre de filtres */
.filtre-projets {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
  justify-content: center;
  margin-left: auto;
  margin-right: auto;
}

.filtre-projets label {
  min-width: 120px;
  text-align: left;
  font-size: 1em;
}

.filtre-projets select,
.filtre-projets button {
  min-width: 140px;
  font-size: 1em;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: #fff;
  color: #111;
}

@media (max-width: 900px) {
  .filtre-projets {
    gap: 8px;
    max-width: 100%;
  }
  .filtre-projets label,
  .filtre-projets select,
  .filtre-projets button {
    min-width: 100px;
    font-size: 0.98em;
  }
}

@media (max-width: 600px) {
  .filtre-projets {
    flex-direction: column;
    align-items: stretch;
    gap: 0;
    margin-left: auto;
    margin-right: auto;
  }
  .filtre-projets label,
  .filtre-projets select,
  .filtre-projets button {
    width: 100%;
    min-width: 0;
    margin-bottom: 10px;
    font-size: 1em;
  }
  .filtre-projets button {
    margin-bottom: 0;
  }
}
</style>
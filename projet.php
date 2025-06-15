<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Projet non spécifié.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$id]);
$projet = $stmt->fetch();

if (!$projet) {
    echo "Projet introuvable.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['concepteur', 'evaluateur'])) {
    $contenu = trim($_POST['contenu'] ?? '');
    if ($contenu !== '') {
        $stmt = $pdo->prepare("INSERT INTO commentaires (projet_id, user_id, contenu, date_commentaire) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$projet['id'], $_SESSION['user']['id'], $contenu]);
        header("Location: projet.php?id=" . $projet['id']);
        exit;
    }
}

// Récupérer les commentaires du projet
$stmt = $pdo->prepare("SELECT c.*, u.prenom, u.nom FROM commentaires c JOIN users u ON c.user_id = u.id WHERE c.projet_id = ? ORDER BY c.date_commentaire DESC");
$stmt->execute([$projet['id']]);
$commentaires = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($projet['titre']) ?> | Détails du projet</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
<?php include 'navbar.php'; ?>


<div class="galerie"><h2><?= htmlspecialchars($projet['titre']) ?></h2></div>

<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'concepteur'): ?>
    <div class="projet-actions" style="margin-bottom:24px;">
        <a href="modifier_projet.php?id=<?= $projet['id'] ?>" class="btn-modifier">Modifier</a>
        <form action="supprimer_projet.php" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
            <input type="hidden" name="id" value="<?= $projet['id'] ?>">
            <button type="submit" class="btn-supprimer">Supprimer</button>
        </form>
    </div>
<?php endif; ?>

<div class="projet-detail">

    <div class="projet-main-info">
        <a href="../uploads/<?= htmlspecialchars($projet['image']) ?>" class="lightbox-link">
            <img src="../uploads/<?= htmlspecialchars($projet['image']) ?>" alt="<?= htmlspecialchars($projet['titre']) ?>" class="detail-image">
        </a>
        <div class="projet-desc">
            <p class="projet-description"><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
        </div>
    </div>

    <div class="projet-meta">
        <span><strong>Date de publication :</strong> <?= date('d/m/Y', strtotime($projet['date_creation'])) ?></span>
        <span><strong>Année :</strong> <?= htmlspecialchars($projet['annee']) ?></span>
        <span><strong>Type :</strong> <?= htmlspecialchars($projet['type']) ?></span>
        <span><strong>Apprentissage critique :</strong> <?= htmlspecialchars($projet['critique'] ?? '') ?></span>
    </div>

    <?php
    $imagesSecondaires = json_decode($projet['images_secondaires'] ?? '', true);
    if ($imagesSecondaires && is_array($imagesSecondaires)): ?>
        <div class="projet-images-secondaires">
            <?php foreach ($imagesSecondaires as $img): ?>
                <a href="../uploads/<?= htmlspecialchars($img) ?>" class="lightbox-link">
                    <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="Image secondaire" class="img-secondaire">
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- GROS PARAGRAPHE EXPLICATIF EN BAS -->
     <div class="h3projet">
     <span><h3><strong>Compétence lié :</strong></h3> <?= htmlspecialchars($projet['competence']) ?></span>
     </div>
    <div class="projet-description-detaillee" style="font-size:1.18em; margin:32px 0 28px 0; color:#fff;">
        <?= nl2br(htmlspecialchars($projet['description_detaillee'] ?? "Ici, tu peux détailler les outils utilisés, l'apprentissage critique, les étapes, etc.")) ?>
    </div>

    <a href="galerie.php" class="btn-retour">← Retour à la galerie</a>
</div> <!-- fin .projet-detail -->

<?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['concepteur', 'evaluateur'])): ?>
<div class="commentaires-section">
    <h2 class="commentaires-title">Commentaires</h2>
    <?php foreach ($commentaires as $com): ?>
        <div class="commentaire">
            <?php if (isset($editCommentId) && $editCommentId == $com['id']): ?>
    <form method="POST" class="form-edit-commentaire">
        <textarea name="new_content" required><?= htmlspecialchars($editCommentContent) ?></textarea>
        <input type="hidden" name="comment_id" value="<?= $com['id'] ?>">
        <button type="submit">Enregistrer</button>
    </form>
<?php else: ?>
    <div class="comment-content"><?= nl2br(htmlspecialchars($com['contenu'])) ?></div>
<?php endif; ?>
            <div class="comment-meta">
                <span class="comment-author"><?= htmlspecialchars($com['prenom'] . ' ' . $com['nom']) ?></span>
                <span class="comment-date"><?= date('d/m/Y H:i', strtotime($com['date_commentaire'])) ?></span>
            </div>
            <?php
            $canEdit = false;
            if (isset($_SESSION['user'])) {
                if ($_SESSION['user']['role'] === 'concepteur') {
                    $canEdit = true;
                } elseif (
                    $_SESSION['user']['role'] === 'evaluateur' &&
                    $_SESSION['user']['id'] == $com['user_id']
                ) {
                    $canEdit = true;
                }
            }
            ?>
            <?php if ($canEdit): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="comment_id" value="<?= $com['id'] ?>">
                    <input type="hidden" name="edit_content" value="1">
                    <button type="submit" class="btn-modifier-commentaire">Modifier</button>
                </form>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce commentaire ?');">
                    <input type="hidden" name="delete_comment" value="<?= $com['id'] ?>">
                    <button type="submit" class="btn-supprimer-commentaire">Supprimer</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <form method="POST" class="form-ajout-commentaire">
        <textarea name="contenu" placeholder="Écrire un commentaire..." required></textarea>
        <button type="submit">Envoyer</button>
    </form>
</div>
<?php endif; ?>

<?php
// Modifier un commentaire
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['comment_id'], $_POST['edit_content'])
    && isset($_SESSION['user'])
) {
    $comment_id = (int)$_POST['comment_id'];
    // On va chercher le commentaire pour vérifier l'auteur
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();
    if ($comment) {
        $canEdit = false;
        if ($_SESSION['user']['role'] === 'concepteur') {
            $canEdit = true;
        } elseif ($_SESSION['user']['role'] === 'evaluateur' && $_SESSION['user']['id'] == $comment['user_id']) {
            $canEdit = true;
        }
        if ($canEdit) {
            // Affiche le formulaire de modification (à faire dans la boucle d'affichage)
            $editCommentId = $comment_id;
            $editCommentContent = $comment['contenu'];
        }
    }
}

// Enregistrer la modification
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['comment_id'], $_POST['new_content'])
    && isset($_SESSION['user'])
) {
    $comment_id = (int)$_POST['comment_id'];
    $new_content = trim($_POST['new_content']);
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();
    if ($comment) {
        $canEdit = false;
        if ($_SESSION['user']['role'] === 'concepteur') {
            $canEdit = true;
        } elseif ($_SESSION['user']['role'] === 'evaluateur' && $_SESSION['user']['id'] == $comment['user_id']) {
            $canEdit = true;
        }
        if ($canEdit && $new_content !== '') {
            $stmt = $pdo->prepare("UPDATE commentaires SET contenu = ? WHERE id = ?");
            $stmt->execute([$new_content, $comment_id]);
            header("Location: projet.php?id=" . $projet['id']);
            exit;
        }
    }
}

// Supprimer un commentaire
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['delete_comment'])
    && isset($_SESSION['user'])
) {
    $comment_id = (int)$_POST['delete_comment'];
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();
    if ($comment) {
        $canDelete = false;
        if ($_SESSION['user']['role'] === 'concepteur') {
            $canDelete = true;
        } elseif ($_SESSION['user']['role'] === 'evaluateur' && $_SESSION['user']['id'] == $comment['user_id']) {
            $canDelete = true;
        }
        if ($canDelete) {
            $stmt = $pdo->prepare("DELETE FROM commentaires WHERE id = ?");
            $stmt->execute([$comment_id]);
            header("Location: projet.php?id=" . $projet['id']);
            exit;
        }
    }
}
?>

<?php include 'footer.php'; ?>
<script>
document.querySelectorAll('.lightbox-link').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const src = this.getAttribute('href');
    const overlay = document.createElement('div');
    overlay.className = 'lightbox-overlay';
    overlay.innerHTML = `<img src="${src}" alt="Image grand format">`;
    overlay.onclick = () => overlay.remove();
    document.body.appendChild(overlay);
  });
});
</script>
</body>
</html>

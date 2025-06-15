<?php
session_start();

// Vérifie que le concepteur est connecté
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concepteur') {
    header('Location: indexport.php');
    exit;
}

require_once 'db.php';

// ➤ C'est ici que tu ajoutes l'étape 2 :
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page admin | Tom ALLANO</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <?php include 'navbar.php'; ?>
<div class="galerie"><h2>rôles</h2></div>

    
        <div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="admin-th">Prénom</th>
                <th class="admin-th">Nom</th>
                <th class="admin-th">Email</th>
                <th class="admin-th">Choisir un rôle</th>
                <th class="admin-th">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr class="admin-tr">
                <td class="admin-td"><?= htmlspecialchars($user['prenom']) ?></td>
                <td class="admin-td"><?= htmlspecialchars($user['nom']) ?></td>
                <td class="admin-td"><?= htmlspecialchars($user['email']) ?></td>
                <td class="admin-td">
                    <?php if ($user['role'] !== 'concepteur'): ?>
                        <form action="traitement_approbation.php" method="POST" class="admin-form-role">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="role" required class="admin-select">
                                <option value="utilisateur" <?= $user['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="evaluateur" <?= $user['role'] === 'evaluateur' ? 'selected' : '' ?>>Évaluateur</option>
                                <option value="concepteur" <?= $user['role'] === 'concepteur' ? 'selected' : '' ?>>Concepteur</option>
                            </select>
                            <?php if ($user['is_approved'] == 0): ?>
                                <button type="submit" name="action" value="approuver" class="admin-btn">Approuver</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="changer_role" class="admin-btn">Modifier</button>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <span style="color:#fff; font-weight:bold;">Concepteur</span>
                    <?php endif; ?>
                </td>
                <td class="admin-td">
                    <?php if ($user['role'] !== 'concepteur'): ?>
                        <form action="traitement_approbation.php" method="POST" class="admin-form-delete" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="action" value="supprimer" class="admin-btn admin-btn-delete">Supprimer</button>
                        </form>
                    <?php else: ?>
                        <span style="color:#aaa;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p><a href="index.php" class="admin-link-retour">Retour à l'accueil</a></p>
    <?php include 'footer.php'; ?>
    </body>

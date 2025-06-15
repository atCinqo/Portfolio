<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
    .sidenav {
  height: 100%;
  width: 250px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: -250px;
  background-color:rgb(0, 0, 0);
  padding-top: 60px;
  transition: left 0.5s ease;
}

/* Sidenav menu links */
.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #111;
}

.sidenav ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

/* Active class */
.sidenav.active {
  left: 0;
}

/* Close btn */
.sidenav .close {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
}

/* Par défaut, le burger est caché */
.burger-icon {
  display: none;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 32px;
  height: 32px;
  cursor: pointer;
  z-index: 1001;
}
.burger-icon span {
  display: block;
  height: 4px;
  width: 26px;
  background:rgb(255, 255, 255);
  margin: 4px 0;
  border-radius: 2px;
}

/* Affiche le burger uniquement sur mobile */
@media (max-width: 768px) {
  .burger-icon {
    display: flex;
  }
  .navtext {
    display: none !important;
  }
  .sidenav {
    display: block;
  }
}

@media (min-width: 769px) {
  .burger-icon {
    display: none !important;
  }
  #openBtn {
    display: none !important;
  }
  .navtext {
    display: flex !important;
  }
  .sidenav {
    display: none !important;
  }
}
</style>
<body>
     <header>
    <div class="logodiv"> 
        <a href="index.php"><img src="../uploads/logo_blanc.png" alt="logo" class="logo"></a>
    </div>
    <div>
        
    </div>
    <div class="navbar">
        <div id="mySidenav" class="sidenav">
            <a id="closeBtn" href="#" class="close">×</a>
            <ul>
                <?php if (isset($_SESSION['user'])): ?>
                    <li style="color: #111; padding-left: 10px; font-family: 'Poppins';">
                        Bonjour, <strong><?= htmlspecialchars($_SESSION['user']['prenom']) ?></strong>
                    </li>
                <?php endif; ?>

                <li><a href="prés.php">à propos</a></li>
                <li><a href="comp.php">compétences</a></li>
                <li><a href="galerie.php">galerie</a></li>
                <li><a href="contact.php">contact</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'concepteur'): ?>
                        <li><a href="approbation_admin.php">admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">se déconnecter</a></li>
                <?php else: ?>
                    <li><a href="connexion.php">se connecter</a></li>
                <?php endif; ?>
            </ul>
            </div>

            <a href="#" id="openBtn">
            <span class="burger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
            </a>
        <ul class="navtext" id="nav-menu">

            <?php if (isset($_SESSION['user'])): ?>
                <li style="color: white; padding-left: 10px; font-family: 'Poppins';">
                    Bonjour, <strong><?= htmlspecialchars($_SESSION['user']['prenom']) ?></strong>
                </li>
            <?php endif; ?>

            <li><a class="linknav" href="prés.php">à propos</a></li>
            <li><a class="linknav" href="comp.php">compétences</a></li>
            <li><a class="linknav" href="galerie.php">galerie</a></li>
            <li><a class="linknav" href="contact.php">contact</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'concepteur'): ?>
                    <li><a class="linknav" href="approbation_admin.php">admin</a></li>
                <?php endif; ?>
                <li><a class="linknav" href="logout.php">se déconnecter</a></li>
            <?php else: ?>
                <li><a class="linknav" href="connexion.php">se connecter</a></li>
            <?php endif; ?>

        </ul>   
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ouvre le menu
    document.getElementById('openBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('mySidenav').classList.add('active');
    });
    // Ferme le menu
    document.getElementById('closeBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('mySidenav').classList.remove('active');
    });
});
</script>
</body>
</html>
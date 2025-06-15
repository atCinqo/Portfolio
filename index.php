<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Tom ALLANO</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="allsect">
        <section class="sectpres">
            <div class="pres">
                <div class="salut"><h1> salut !</h1></div>
                <div class="je-suis"><h1>je suis</h1></div>
                <div class="nom"><a href="#qui" class="toma"> <h1>tom allano</h1></a></div>
            </div>
        </section>
        <section class="sectqui" id="qui">
            <div class="textmoi">
                <p class="moi">
                    Je m'appelle Tom Allano, étudiant en <strong> BUT Métiers du Multimédia et de l’Internet à Toulon </strong>.
                     Passionné par la création numérique, le cinéma et le sport, j’ai toujours su que je voulais exercer un métier créatif. <br><br>
                     Aujourd’hui, j’ai enfin l’opportunité de m’exprimer pleinement à travers différents supports : création de posts Instagram, affiches, logos, photos et vidéos. <br><br>

                    <strong>Curieux et polyvalent</strong>, je m’efforce d’apprendre en continu, que ce soit dans le cadre de mes <strong>projets académiques ou personnels</strong>.
                    J’aime explorer les possibilités offertes par les différents outils de création et transformer mes idées en réalisations concrètes et visuelles. <br><br>

                    Chaque nouveau projet est pour moi une occasion <strong>d’apprendre, de progresser et de repousser mes limites créatives.</strong> <br><br>

                    <a href="./prés.php"> <strong> <i> En apprendre plus sur moi → </i></strong></a>

                </p>
            </div>
            <div class="picme">
                <a href="./prés.php"><img src="../uploads/moi.JPG" alt="photo de moi" class="pic"></a>
            </div>
        </section>
        <section class="homelast">
           <div class="apercu">
                <div class="titlepage">
                    <h2>aperçu</h2>
                    <div class="lienglr">
                   <a href="/galerie.php"> <strong>galerie complète →</strong> </a>
                 </div>
                    
                </div>
                
                <div class="container">
                    
                        <input type="radio" name="slider" id="item-1" checked>
                        <input type="radio" name="slider" id="item-2">
                        <input type="radio" name="slider" id="item-3">
                    
                    <div class="cards">
                        <label class="card" for="item-1" id="song-1">
                        <img src="../uploads/PS_ALLANO_Tom.jpg" alt="song">
                        </label>
                        <label class="card" for="item-2" id="song-2">
                       <img src="../uploads/credit_card.png" alt="song">
                        </label>
                        <label class="card" for="item-3" id="song-3">
                        <img src="../uploads/affiche_capri.jpg" alt="song">
                        </label>
                    </div>
                    <div class="player">
                        <div class="upper-part">
                        <div class="play-icon">
                            <svg width="20" height="20" fill="rgb(255,255,255)" stroke="rgb(255,255,255)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="feather feather-play" viewBox="0 0 24 24">
                            <defs/>
                            <path d="M5 3l14 9-14 9V3z"/>
                            </svg>
                        </div>
                        <div class="info-area" id="test">
                            <label class="song-info" id="song-info-1">
                                <!-- mettre lien -->
                                <a href="#">
                            <div class="title">Chimère</div>
                            <div class="sub-line">
                                <div class="subtitle">Photoshop</div>
                                <div class="time">10.08</div>
                            </div>
                            </a>
                            </label>
                            <label class="song-info" id="song-info-2">
                                <!-- mettre lien -->
                                <a href="#">
                            <div class="title">Banque fictive</div>
                            <div class="sub-line">
                                <div class="subtitle">Illustrator</div>
                                <div class="time">5.1</div>
                            </div>
                            </a>
                            </label>
                            <label class="song-info" id="song-info-3">
                                <!-- mettre lien -->
                                <a href="#">   
                            <div class="title">Affiche Capri</div>
                            <div class="sub-line">
                                <div class="subtitle">Illustrator</div>
                                <div class="time">4.05</div>
                            </div>
                            </a>
                            </label>
                        </div>
                        </div>
                        <div class="progress-bar">
                        <span class="progress"></span>
                        </div>
                    </div>
                 </div>
                 
           </div>
           <div>

           </div>
        </section>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
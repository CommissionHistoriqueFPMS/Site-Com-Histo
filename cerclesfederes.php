<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Cercles Fédéré - Commission Historique F.P.Ms</title>
    <?php include('include-php/header.php');?>
</head>
<body>
<?php include('include-php/navbar.php');?>

<div class="header" style="background-image:url(image/headers/st-waudru.jpg);">
    <h1 class="header-text">
        <strong>Annecdotes sur le Cercle</strong>
    </h1>
</div>
<div style="height:3rem"></div>

<div class="container">
    <div class="horizontal-display">
        <div class="sidebar">
            <div class="sidebar-content">
                <!-- Contenu du sommaire va ici -->
                <div class="sommaire-title" >Sommaire</div>
                <ul style="text-align: justify">
                    <li><a onclick="scrollToSection('cerclefedere')">Cercles Fédérés</a></li>
                    <ul>
                        <li><a onclick="scrollToSection('bar')">Bar</a></li>
                        <li><a onclick="scrollToSection('cap')">Centrale d'Achats Polytech</a></li>
                        <li><a onclick="scrollToSection('culturel')">Cercle Curturel</a></li>
                        <li><a onclick="scrollToSection('magellan')">Cercle Magellan</a></li>
                        <li><a onclick="scrollToSection('monsmines')">Mons-Mines</a></li>
                        <li><a onclick="scrollToSection('mutu')">Mutuelle d'Edition</a></li>
                        <li><a onclick="scrollToSection('peyresq')">Peyresq</a></li>
                        <li><a onclick="scrollToSection('cpv')">Cercle Photo-Vidéo</a></li>
                        <li><a onclick="scrollToSection('radio')">Radio Extra</a></li>
                        <li><a onclick="scrollToSection('scientifique')">Scientifique</a></li>
                        <li><a onclick="scrollToSection('sono')">Sono-Danse-Musique</a></li>
                        <li><a onclick="scrollToSection('sports')">Cercle des Sports</a></li>
                    </ul>
                </ul>
            </div>
        </div>

        <div class="content">
            <div class="article-title" id="cerclefedere">Cercles Fédérés</div>
            <div class="main-article-content">
                <!-- Contenu principal va ici -->
                <?php include("article/cerclefederes/bar.php") ?>
                <?php include("article/cerclefederes/cap.php") ?>
                <?php include("article/cerclefederes/culturel.php") ?>
                <?php include("article/cerclefederes/magellan.php") ?>
                <?php include("article/cerclefederes/monsmines.php") ?>
                <?php include("article/cerclefederes/mutu.php") ?>
                <?php include("article/cerclefederes/peyresq.php") ?>
                <?php include("article/cerclefederes/cpv.php") ?>
                <?php include("article/cerclefederes/radio.php") ?>
                <?php include("article/cerclefederes/scientifique.php") ?>
                <?php include("article/cerclefederes/sono.php") ?>
                <?php include("article/cerclefederes/sports.php") ?>
            </div>
        </div>
    </div>
</div>

<?php include('include-php/footer.php');?>

<script src="javascript/main-scripts.js"></script>
</body>
</html>
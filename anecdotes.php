<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Annecdotes - Commission Historique F.P.Ms</title>
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
                <li><a onclick="scrollToSection('vieetudiante')">Vie Étudiante</a></li>
                <ul>
                    <li><a onclick="scrollToSection('cortege1931')">Cortège des bleus 1931</a></li>
                    <li><a onclick="scrollToSection('saint1946')">Le grand saint 1946</a></li>
                    <li><a onclick="scrollToSection('cite1960')">Les kots à la cité 1960</a></li>
                    <li><a onclick="scrollToSection('cafe1970')">Les cafés montois 1970</a></li>
                </ul>
                <li><a onclick="scrollToSection('blagueestudiantine')">Blague Estudiantine</a></li>
                <ul>
                    <li><a onclick="scrollToSection('horse1949')">Pimp my horse 1949</a></li>
                    <li><a onclick="scrollToSection('doudou1957')">Le vol du doudou 1957</a></li>
                    <li><a onclick="scrollToSection('lune')">La Pierre de Lune 1970</a></li>
                    <li><a onclick="scrollToSection('diversion74_79')">Quelques diversions 1974 – 1979</a></li>
                    <li><a onclick="scrollToSection('cité1977')">La cité s’illumine 1977</a></li>
                </ul>
            </ul>
            </div>
        </div>

        <div class="content">
            <!-- Contenu principal va ici -->
            <?php include("article/anecdotes/vieetudiante.php") ?>
            <?php include("article/anecdotes/blagueestudiantine.php") ?>
        </div>
    </div>
</div>

<?php include('include-php/footer.php');?>

<script src="javascript/main-scripts.js"></script>

</body>
</html>
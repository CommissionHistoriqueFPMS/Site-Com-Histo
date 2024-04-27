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
        <strong>Vie Étudiante</strong>
    </h1>
</div>
<div style="height:3rem"></div>
<?php
$articlesDir = "article/vieetudiante/*.php";
?>
<div class="container">
    <div class="horizontal-display">
        <div class="sidebar">
            <div class="sidebar-content">
                <!-- Contenu du sommaire va ici -->
                <div class="sommaire-title" >Sommaire</div>
                <ul style="text-align: justify">
                    <li><a onclick="scrollToSection('vieetudiante')">Vie Étudiante</a></li>
                    <ul>
                        <?php
                        // Fonction pour extraire les sections d'un article et les ajouter au sommaire
                        $articles = glob($articlesDir);
                        foreach ($articles as $article) {
                            generateSidebarFromArticle($article);
                        }
                        ?>
                    </ul>
                </ul>
            </div>
        </div>

        <div class="content">
            <!-- Contenu principal va ici -->
            <<div class="article-title" id="vieetudiante">Vie Étudiante</div>
            <div class="main-article-content">
                <p>
                    Tout bon étudiant, comme son folklore, se caractérise par des habitudes de vie bien particulières,
                    avec comme grands évènments...
                </p>
                <br>
                <?php
                $articles = glob($articlesDir);
                foreach ($articles as $article) {
                    include $article;
                    echo "<br>";
                }
                ?>
            </div>
            <br>
        </div>
    </div>
</div>

<?php include('include-php/footer.php');?>

<script src="javascript/main-scripts.js"></script>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Blagues Estudiantines - Commission Historique F.P.Ms</title>
    <?php include('include-php/header.php');?>
</head>
<body>
<?php include('include-php/navbar.php');?>
<div class="header" style="background-image:url(image/headers/st-waudru.jpg);">
    <h1 class="header-text">
        <strong>Blagues Estudiantines</strong>
    </h1>
</div>
<div style="height:3rem"></div>
<?php
$articlesDir = "article/blagueestudiantine/*.php";
?>
<div class="container">
    <div class="horizontal-display">
        <div class="sidebar">
            <div class="sidebar-content">
            <!-- Contenu du sommaire va ici -->
            <div class="sommaire-title" >Sommaire</div>
            <ul style="text-align: justify">
                <li><a onclick="scrollToSection('blagueestudiantine')">Blagues Estudiantines</a></li>
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
            <div class="article-title" id="blagueestudiantine">Blagues Estudiantines</div>
            <div class="main-article-content">
                <p>
                    Depuis la lointaine création de la faculté, les étudiants ont toujours cherché un moyen d’occupation durant
                    leur moments de temps libre en dehors des cours (..et surtout des guindailles).
                </p>
                <p>
                    C’est là qu’interviennent les blagues estudiantines.
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
        </div>
    </div>
</div>

<?php include('include-php/footer.php');?>

<script src="javascript/main-scripts.js"></script>

</body>
</html>
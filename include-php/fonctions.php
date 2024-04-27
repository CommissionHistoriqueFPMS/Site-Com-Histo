<?php
function generateSidebarFromArticle($articlePath) {
    $html = file_get_contents($articlePath);
    $doc = new DOMDocument();
    $doc->loadHTML($html);

    $sections = $doc->getElementsByTagName('div');
    foreach ($sections as $section) {
        if ($section->getAttribute('class') === 'article-subtitle') {
            $id = $section->getAttribute('id');
            $title = $section->nodeValue;
            echo "<li><a onclick=\"scrollToSection('$id')\">$title</a></li>";
        }
    }
}


function generatePage($pageName, $themesName, $themesPath, $themeEntete) {
    echo "<!DOCTYPE html>
    <html lang=\"fr\">
    <head>
        <title>$pageName - Commission Historique F.P.Ms</title>";
    include('include-php/header.php');
    echo"</head>
    <body>";

    include('include-php/navbar.php');

    echo "
    <div class=\"header\" style=\"background-image:url(image/headers/st-waudru.jpg);\">
    <h1 class=\"header-text\">
        <strong>$pageName</strong>
    </h1>
    </div>
    <div style=\"height:3rem\"></div>
    <div class=\"container\">
        <div class=\"horizontal-display\">
            <div class=\"sidebar\">
                <div class=\"sidebar-content\">
                    <div class=\"sommaire-title\" >Sommaire</div>
                        <ul style=\"text-align: justify\">";

    for ($i = 0; $i < count($themesName); ++$i) {
        echo "<li><a onclick=\"scrollToSection('$themesPath[$i]')\">$themesName[$i]</a></li>
              <ul>";
        // Fonction pour extraire les sections d"un article et les ajouter au sommaire
        $articleDir = "article/$themesPath[$i]/*.php";
        $articles = glob($articleDir);
        foreach ($articles as $article) {
            generateSidebarFromArticle($article);
        }
        echo "</ul>";
    }

    echo "</ul>
        </div>
    </div>
    <div class=\"content\">";
    for ($i = 0; $i < count($themesName); ++$i) {
        echo "<div class=\"article-title\" id=\"$themesPath[$i]\">$themesName[$i]</div>
                <div class=\"main-article-content\">
                $themeEntete[$i]";
        // Fonction pour extraire les sections d"un article et les ajouter au sommaire
        $articleDir = "article/$themesPath[$i]/*.php";
        $articles = glob($articleDir);
        foreach ($articles as $article) {
            include $article;
            echo "<br>";
        }
        echo "</div></div>";
    }
    echo '</div></div>';
    include('include-php/footer.php');
    echo '</body></html>';
}
?>
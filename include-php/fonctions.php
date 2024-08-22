<?php
function generateSidebarFromArticle($articlePath) {
    // Obtenir le contenu du fichier
    $fileContents = file_get_contents($articlePath);

    // Extraire l'ID et le titre à l'aide de l'expression régulière
    preg_match('/\$id\s*=\s*"([^"]+)";/i', $fileContents, $matchesId);
    preg_match('/\$title\s*=\s*"([^"]+)";/i', $fileContents, $matchesTitle);


    // Vérifier si les variables ont été trouvées
    if (isset($matchesId[1]) && isset($matchesTitle[1])) {
        $id = $matchesId[1];
        $title = $matchesTitle[1];

        // Générer le lien dans la barre latérale
        echo "<li><a onclick=\"scrollToSection('$id')\">$title</a></li>";
    } else {
        // Afficher une erreur si les variables ne sont pas trouvées
        echo "Les variables \$id et \$title n'ont pas été trouvées dans le fichier.";
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
        // Fonction pour extraire les sections d"un articles et les ajouter au sommaire
        $articleDir = "articles/$themesPath[$i]/*.php";
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
        // Fonction pour extraire les sections d"un articles et les ajouter au sommaire
        $articleDir = "articles/$themesPath[$i]/*.php";
        $articles = glob($articleDir);
        foreach ($articles as $article) {
            include $article;
            echo "</div><div class='clear'></div><br>";
        }
        echo "</div></div>";
    }
    echo '</div></div>';
    include('include-php/footer.php');
    echo '</body></html>';
}


function baseArticle($articleName, $articleId) {
    echo "
    <meta charset=\"UTF-8\"> <!-- Important afin d'afficher le \"é\" correctement dans le sommaire -->
        <div class=\"article-subtitle\" id=\"$articleId\">$articleName</div>
        <div class=\"article-content\">
        ";
}

function addImage($imagePath, $width = 500, $orientation = "center", $additionnalCSS = "") {
    if ($orientation[0] == "r" or $orientation[0] == "R") {
        $orientation = "img-right";
    } elseif ($orientation[0] == "l" or $orientation[0] == "L") {
        $orientation = "img-left";
    } else {
        echo "<div style = 'display: flex; flex-direction: column; align-items: center;'>";
        $orientation = "img-center";
    }
    echo "<img class='" . $orientation . "' src='" . $imagePath . "' alt='" . $imagePath  .
        "' style='width: " .  $width . "px; " . $additionnalCSS . "''>";
    if ($orientation == "img-center") {
        echo "</div>";
    }
}

function addSource($text, $url = "") {
    if ($url != "") {
        echo '<div class="sources"><a href="' . $url . '">' . $text . '</a></div>';
    } else {
        echo '<div class="sources"><i>' . $text . '</i></div>';
    }
}
?>

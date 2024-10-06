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

function defaultArticle() {
    echo '<img src="image/workinprogress.png">';
    echo '<br>Article encore en cours de construction...';
}


function generateTable($headers, $contents) {
    $num_cols = sizeof($headers);
    $num_rows = sizeof($contents);

    echo '<div class="table-container">';
    echo "<table><thead><tr>";
    for ($i = 0; $i < $num_cols; ++$i) {
        echo "<th>" . $headers[$i] ."</th>";
    }
    echo "</tr></thead><tbody>";

    for ($i = 0; $i < $num_rows; ++$i) {
        echo "<tr>";
        for ($j = 0; $j < $num_cols; ++$j) {
            echo "<td>" . $contents[$i][$j] ."</td>";
        }
        echo "</tr>";
    }

    echo "</tbody></table></div>";
}

function createAlbum($directory) {
    $images = glob("$directory/*.*");
    echo ' <style>       
         .image-container {
             display: flex; 
             flex-direction: column; 
             align-items: center; 
             position: relative; 
             float:left; 
             max-width: 450px; 
             width: auto; 
             max-height: 500px; 
             height: auto;
         }
        .image-container .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .image-container:hover .overlay {
            opacity: 1;
        }

        .overlay-text {
            font-size: 24px;
            text-align: center;
        } 
        .img-album {
        max-width: 450px; width: auto; max-height: 500px; height: auto;
        }
        
        @media (max-width: 825px) {
            .image-container {
            max-width: 90%;
            }
            .img-album {
            max-width: 100%;
            }
        }
        </style>';
    foreach ($images as $image) {
        $promo = substr($image, -7, 3);
        echo '
        <div class="image-container" style = "">
            <img src=" ' . $image . '" alt="' . $promo . '" class="img-album" style="">
            <div class="overlay" style="float:none;">
                <div class="overlay-text"  style="float:none;">' . $promo . '</div>
            </div>
        </div>
        ';
//        addImage($image, 450, "left", "float:none; max-height: 500px;max-width:450px; width:auto; height:auto;");
    }
}
?>

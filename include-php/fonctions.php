<?php
function generateSidebarFromArticle($articlePath) {
    // Obtenir le contenu du fichier
    $fileContents = file_get_contents($articlePath);

    // Extraire l'ID et le titre à l'aide de l'expression régulière
    preg_match('/\$id\s*=\s*"([^"]+)";/i', $fileContents, $matchesId);
    preg_match('/\$title\s*=\s*"([^"]+)";/i', $fileContents, $matchesTitle);


    // Vérifier si les variables ont été trouvées
    if (isset($matchesId[1]) && isset($matchesTitle[1])) {
        // Échapper guillemets et apostrophes, sinon un titre qui en contient casse le href
        $id = htmlspecialchars($matchesId[1], ENT_QUOTES);
        $title = htmlspecialchars($matchesTitle[1], ENT_QUOTES);

        // Générer le lien dans la barre latérale
        // Vrai href au lieu d'un onclick : le lien devient partageable et navigable au clavier
        // data-spy est lu par main-scripts.js pour surligner la section en cours de lecture
        echo "<li><a href=\"#$id\" data-spy=\"$id\">$title</a></li>";
    } else {
        // Signaler l'erreur en commentaire HTML, invisible pour les visiteurs
        echo "<!-- " . basename($articlePath) . " : les variables \$id et \$title n'ont pas été trouvées -->";
    }
}

// $sousTitre et $heroImage sont optionnels : les appels existants à 4 arguments fonctionnent toujours
function generatePage($pageName, $themesName, $themesPath, $themeEntete, $sousTitre = "", $heroImage = "image/headers/st-waudru.jpg") {
    // Une page à thème unique n'a pas besoin de titres de thème, ils feraient doublon avec le <h1>
    $multi = count($themesName) > 1;

    echo "<!DOCTYPE html>
    <html lang=\"fr\">
    <head>
        <title>$pageName - Commission Historique F.P.Ms</title>";
    include('include-php/header.php');

    // has-hero déclenche la navbar transparente posée par-dessus l'image d'en-tête
    echo"</head>
    <body class=\"has-hero\">";

    include('include-php/navbar.php');

    echo "
    <header class=\"header\" style=\"background-image:url($heroImage);\">
        <div class=\"header__inner\">
            <h1 class=\"header-text\">$pageName</h1>";

    // N'écrire la balise que si le sous-titre existe, sinon un <p> vide laisse une marge
    if ($sousTitre !== "") {
        echo "<p class=\"header__sub\">$sousTitre</p>";
    }

    echo "  </div>
    </header>

    <div class=\"page\">
        <div class=\"page__inner\">";

    // <details open> : le <summary> est masqué en CSS au-dessus de 900px, donc impossible de
    // refermer le sommaire sur grand écran. Repliable sur mobile sans une ligne de JavaScript.
    echo "
            <details class=\"sommaire\" open>
                <summary class=\"sommaire__bouton\">Sommaire</summary>
                <div class=\"sommaire__sticky\">
                    <p class=\"sommaire__titre\">Sommaire</p>
                    <ul class=\"sommaire__liste\">";

    for ($i = 0; $i < count($themesName); ++$i) {
        // En multi-thèmes seulement : un niveau de regroupement au-dessus des articles
        if ($multi) {
            echo "<li><a href=\"#$themesPath[$i]\" data-spy=\"$themesPath[$i]\">$themesName[$i]</a>
                  <ul>";
        }

        // Fonction pour extraire les sections d"un articles et les ajouter au sommaire
        $articleDir = "articles/$themesPath[$i]/*.php";
        $articles = glob($articleDir);
        foreach ($articles as $article) {
            generateSidebarFromArticle($article);
        }

        if ($multi) {
            echo "</ul></li>";
        }
    }

    echo "      </ul>
                </div>
            </details>

            <div class=\"page__contenu\">";

    for ($i = 0; $i < count($themesName); ++$i) {
        echo "<section class=\"theme\" id=\"$themesPath[$i]\">";

        if ($multi) {
            echo "<h2 class=\"theme__titre\">$themesName[$i]</h2>";
        }

        // main-article-content est nécessaire ici : c'est elle qui active l'habillage des images
        // trim() attrape aussi les chapôs qui ne contiennent que des retours à la ligne
        if (trim($themeEntete[$i]) !== "") {
            echo "<div class=\"theme__entete main-article-content\">$themeEntete[$i]</div>";
        }

        // Fonction pour extraire les sections d"un articles et les ajouter au sommaire
        $articleDir = "articles/$themesPath[$i]/*.php";
        $articles = glob($articleDir);

        // Sans ça un dossier vide produit une section blanche, sans qu'on sache si c'est un bug
        if (empty($articles)) {
            echo "<p class=\"theme__vide\">Aucun article publié pour le moment.</p>";
        }

        foreach ($articles as $article) {
            include $article;
            // Ferme les balises ouvertes par baseArticle(). Le clear annule les float des images
            // et doit rester DANS le corps de la fiche pour les annuler.
            echo "<div class='clear'></div></div></article>";
        }

        echo "</section>";
    }

    echo "      </div>
        </div>
    </div>";

    // Hors de .page : il est en position fixed, mais l'extraire de la grille évite qu'il en devienne un élément
    echo "<button class=\"haut\" type=\"button\" aria-label=\"Revenir en haut de la page\"></button>";

    include('include-php/footer.php');
    echo '</body></html>';
}


// Signature inchangée : aucun fichier de articles/ n'a besoin d'être modifié
function baseArticle($articleName, $articleId) {
    echo "
    <meta charset=\"UTF-8\"> <!-- Important afin d'afficher le \"é\" correctement dans le sommaire -->
    <article class=\"fiche\">
        <!-- L'id est sur le <h3> et pas sur le <a> : c'est le titre qu'on veut voir arriver
             en haut de l'écran, et le CSS lui met un scroll-margin-top pour qu'il ne passe
             pas sous la navbar. Le <a> donne un lien direct vers l'anecdote. -->
        <h3 class=\"fiche__titre\" id=\"$articleId\">
            <a class=\"fiche__lien\" href=\"#$articleId\">$articleName</a>
        </h3>
        <!-- article-content est gardée pour ne pas casser les anciennes règles CSS.
             Cette div est fermée par generatePage(), pas ici. -->
        <div class=\"fiche__corps article-content\">
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
        $promo = "img";
        $lastBackslash = strrpos($image, '/');
        $lastDot = strrpos($image, '.');
        if ($lastBackslash !== false && $lastDot !== false && $lastBackslash < $lastDot) {
            $promo = substr($image, $lastBackslash + 1, $lastDot - $lastBackslash - 1);
        }
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
function addChant($fichier, $air = "", $titre = "") {
    echo "<div class='chant'>";

    if ($titre !== "") {
        echo "<p class='chant-titre'>" . htmlspecialchars($titre) . "</p>";
    }
    if ($air !== "") {
        echo "<p class='chant-air'>Air : " . htmlspecialchars($air) . "</p>";
    }

    $chemin = "articles/chants/" . $fichier;
    if (!file_exists($chemin)) {
        echo "<p><em>Chant introuvable : " . htmlspecialchars($fichier) . "</em></p></div>";
        return;
    }

    $couplets = preg_split("/\n\s*\n/", trim(file_get_contents($chemin)));
    foreach ($couplets as $couplet) {
        echo "<p>" . nl2br(htmlspecialchars(trim($couplet))) . "</p>";
    }
    echo "</div>";
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="image/logo.png">
<!-- Icones -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<!-- Plugins -->
<link href="css/style.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
?>
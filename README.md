# Site de la Commission Historique F.P.Ms
 
Site web de la **Commission Historique** de la Faculté Polytechnique de Mons, dédié à la préservation et à la mise en valeur du folklore estudiantin montois : anecdotes, blagues des Mines, histoire de la faculté et des cercles.
 
🔗 En ligne : [historique.fede.fpms.ac.be](https://historique.fede.fpms.ac.be/)
## Structure
 
```
├── index.php            # Page d'accueil
├── *.php                # Pages de thèmes (faculté, cercles, anecdotes…)
├── include-php/         # Header, navbar, footer et fonctions communes
├── articles/            # Contenu, un sous-dossier par thème
├── image/               # Images, un sous-dossier par thème
├── css/ · javascript/   # Styles et scripts
└── Dockerfile
```
 
Chaque page de thème génère automatiquement son sommaire et son contenu à partir des articles présents dans le sous-dossier correspondant de `articles/`.
 
## Ajouter un article
 
Créer un fichier `.php` dans le sous-dossier de thème voulu (ex. `articles/blagueestudiantine/`) :
 
```php
<?php
$title = "Titre de l'article";
$id    = "identifiant-unique";
baseArticle($title, $id);
?>
 
<p>Le contenu de l'article…</p>
```
## Fonctions disponibles
 
Toutes ces fonctions sont définies dans `include-php/fonctions.php` et peuvent être appelées depuis n'importe quel article.
 
### `baseArticle($articleName, $articleId)`
 
À appeler **en premier** dans chaque article. Génère le sous-titre de l'article et ouvre le conteneur de contenu.
 
- `$articleName` — le titre affiché de l'article.
- `$articleId` — un identifiant **unique, sans accent ni espace** (sert d'ancre pour le sommaire).
```php
<?php
$title = "Titre de l'article";
$id    = "identifiant-unique";
baseArticle($title, $id);
?>
```
 
### `addImage($imagePath, $width = 500, $orientation = "center", $additionnalCSS = "")`
 
Insère une image dans l'article.
 
- `$imagePath` — chemin **web** de l'image, avec un `/` initial (ex. `/image/vieetudiante/fest.jpg`).
- `$width` — largeur en pixels (500 par défaut).
- `$orientation` — `"center"` (défaut), `"left"` ou `"right"`. Seule la première lettre compte : `"l"`, `"r"`, etc. En `left`/`right`, le texte s'enroule autour de l'image ; en `center`, l'image est isolée et centrée.
- `$additionnalCSS` — CSS en ligne ajouté à l'image, pour un cas particulier (ex. un cadre sur **une seule** image). Terminer la chaîne par `;`.
```php
<?php addImage("/image/vieetudiante/fest.jpg", 400, "right"); ?>
<?php addImage("/image/vieetudiante/1999.jpg", 500, "center",
               "border: 3px solid var(--derive-main-color); border-radius: 8px;"); ?>
```
 
### `addChant($fichier, $air = "", $titre = "")`
 
Affiche un chant dans un encadré `.chant`, à partir d'un **fichier texte** (les paroles sont des données, pas du code).
 
- `$fichier` — le **nom du fichier** `.txt` uniquement (voir la note ci-dessous).
- `$air` — l'air sur lequel se chante le chant (optionnel). Affiché en « Air : … ».
- `$titre` — le titre du chant (optionnel), affiché au-dessus de l'air.
Les fichiers de chants se rangent dans **`articles/chants/`**. Dans le `.txt`, on écrit les paroles au naturel : un retour à la ligne = un vers, une **ligne vide** = un nouveau couplet. Aucun HTML à mettre.
 
```php
<?php addChant("mons-ulb-1988.txt", "les Champs-Élysées"); ?>
<?php addChant("betail-montois.txt",
               "Le jouet extraordinaire (C. François)", "Le bétail montois"); ?>
```
 
> **Note sur le chemin** — la fonction préfixe automatiquement `articles/chants/`, donc on ne passe **que le nom du fichier**, sans dossier ni `/` initial. C'est différent d'`addImage()`, qui attend un chemin *web* complet avec `/` initial : `addChant()` lit un fichier sur le disque (via `file_exists()`), pas une ressource web.
 
### `addSource($text, $url = "")`
 
Ajoute une mention de source alignée à droite en bas de l'article.
 
- `$text` — le texte de la source.
- `$url` — lien optionnel. Avec URL, le texte devient cliquable ; sans URL, il s'affiche en italique.
```php
<?php addSource("Site Com'Histo", "https://historiquefpms.wordpress.com/"); ?>
```
 
### `generateTable($headers, $contents)`
 
Génère un tableau HTML responsive (avec défilement horizontal sur mobile).
 
- `$headers` — tableau des en-têtes de colonnes.
- `$contents` — tableau de lignes, chaque ligne étant un tableau de cellules.
```php
<?php
generateTable(
    ["Année", "Président", "Trésorier"],
    [
        ["2025-2026", "Nom Prénom", "Nom Prénom"],
        ["2024-2025", "Nom Prénom", "Nom Prénom"],
    ]
);
?>
```
 
### `createAlbum($directory)`
 
Construit une galerie d'images à partir de **tous** les fichiers d'un dossier. Au survol, le **nom du fichier** (sans extension) s'affiche en surimpression — pratique pour légender par promo ou par année.
 
- `$directory` — chemin du dossier contenant les images.
```php
<?php createAlbum("image/albums/revue2013"); ?>
```
 
### `defaultArticle()`
 
Affiche un visuel « en construction ». À utiliser comme contenu temporaire d'un article pas encore rédigé.
 
```php
<?php defaultArticle(); ?>
```
 
### Fonctions internes
 
Ces fonctions sont utilisées automatiquement par le système et n'ont pas à être appelées à la main :
 
- **`generatePage($pageName, $themesName, $themesPath, $themeEntete)`** — assemble une page de thème complète (en-tête, sommaire, articles, pied de page). C'est ce que chaque page `.php` à la racine appelle.
- **`generateSidebarFromArticle($articlePath)`** — lit le `$id` et le `$title` d'un article pour construire automatiquement l'entrée correspondante dans le sommaire.
 
 
## Contribuer
 
Les contributions sont les bienvenues (corrections, nouveaux témoignages, photos). Ouvrir une *issue* ou une *pull request*.
 
## Contact
 
- Facebook : [Commission Historique FPMs](https://www.facebook.com/CommissionHistoriqueFPMs)
- Instagram : [@commission_historique_fpms](https://www.instagram.com/commission_historique_fpms/)
- Mail : [historique.fpms@gmail.com](mailto:historique.fpms@gmail.com)
---
 
© Commission Historique F.P.Ms

# Site de la Commission Historique F.P.Ms

Site web de la **Commission Historique** de la Faculté Polytechnique de Mons, dédié à la préservation et à la mise en valeur du folklore estudiantin montois : anecdotes, blagues des Mines, histoire de la faculté et des cercles.

🔗 En ligne : [historique.fede.fpms.ac.be](https://historique.fede.fpms.ac.be/)

## Structure

```
├── index.php            # Page d'accueil (héros + blocs image/texte)
├── *.php                # Pages de thèmes (faculté, cercles, anecdotes…)
├── include-php/         # Header, navbar, footer et fonctions communes
├── articles/            # Contenu, un sous-dossier par thème
│   └── chants/          # Paroles en .txt, lues par addChant()
├── image/               # Images, un sous-dossier par thème
├── css/ · javascript/   # Styles et scripts
├── .github/workflows/   # CI : build et push de l'image Docker
└── Dockerfile
```

Chaque page de thème génère automatiquement son sommaire et son contenu à partir des articles présents dans le sous-dossier correspondant de `articles/`. Ajouter un fichier dans ce dossier suffit : il n'y a rien à déclarer ailleurs.

L'ordre d'affichage des articles suit l'ordre alphabétique des noms de fichiers (`glob()`). Pour réordonner une page, préfixer les fichiers : `01-…`, `02-…`

## Développement local

```bash
php -S 0.0.0.0:8000
```

Puis ouvrir `http://localhost:8000`. PHP 7.4 minimum, aucune dépendance ni base de données.

Avant de committer une modification de `include-php/fonctions.php` :

```bash
php -l include-php/fonctions.php
```

## Déploiement

Un push sur `main` déclenche le workflow GitHub Actions, qui construit l'image Docker et la pousse sur Docker Hub sous le tag `c-<sha>`.

Les branches de travail (`testing`, `ReviewDA`…) ne déclenchent pas de build. On y développe, puis on ouvre une *pull request* vers `main`.

> ⚠️ L'image contient une copie du dépôt. Tout fichier écrit dans `/var/www/html` par le site lui-même est perdu au prochain déploiement.

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

Les variables `$title` et `$id` doivent être déclarées **exactement sous cette forme**, sur une seule ligne, avec des guillemets doubles : le sommaire est construit en lisant le fichier avec une expression régulière avant de l'exécuter. Un `$title = 'Titre';` en apostrophes simples ne serait pas détecté et l'article n'apparaîtrait pas dans le sommaire.

`$id` sert d'ancre dans l'URL (`fac.php#mon-id`), donc : sans accent, sans espace, unique dans la page.

## Ajouter une page de thème

1. Créer le dossier `articles/monTheme/`.
2. Créer `monTheme.php` à la racine :

```php
<?php
include("./include-php/fonctions.php");

generatePage(
    "Mon Thème",                    // titre affiché
    ["Mon Thème"],                  // libellés des thèmes
    ["monTheme"],                   // dossiers dans articles/
    [""],                           // chapô HTML de chaque thème
    "Une phrase de sous-titre.",    // optionnel
    "image/headers/mon-image.jpg"   // optionnel
);
?>
```

3. Ajouter le lien dans `include-php/navbar.php`.

Les trois premiers tableaux sont **parallèles** : l'indice `[0]` désigne le même thème dans les trois. Une page peut donc regrouper plusieurs thèmes, auquel cas un niveau de titres intermédiaire apparaît automatiquement.

## Fonctions disponibles

Toutes ces fonctions sont définies dans `include-php/fonctions.php` et peuvent être appelées depuis n'importe quel article.

### `baseArticle($articleName, $articleId)`

À appeler **en premier** dans chaque article. Génère le titre de l'article et ouvre le conteneur de contenu.

- `$articleName` — le titre affiché de l'article.
- `$articleId` — un identifiant **unique, sans accent ni espace** (sert d'ancre pour le sommaire).

```php
<?php
$title = "Titre de l'article";
$id    = "identifiant-unique";
baseArticle($title, $id);
?>
```

Le titre généré est un lien vers sa propre ancre : un clic droit dessus permet de copier le lien direct de l'anecdote.

### `addImage($imagePath, $width = 500, $orientation = "center", $additionnalCSS = "")`

Insère une image dans l'article.

- `$imagePath` — chemin **web** de l'image, avec un `/` initial (ex. `/image/vieetudiante/fest.jpg`).
- `$width` — largeur en pixels (500 par défaut). Sur mobile, le CSS force la pleine largeur.
- `$orientation` — `"center"` (défaut), `"left"` ou `"right"`. Seule la première lettre compte : `"l"`, `"r"`, etc. En `left`/`right`, le texte s'enroule autour de l'image ; en `center`, l'image est isolée et centrée.
- `$additionnalCSS` — CSS en ligne ajouté à l'image, pour un cas particulier (ex. un cadre sur **une seule** image). Terminer la chaîne par `;`.

```php
<?php addImage("/image/vieetudiante/fest.jpg", 400, "right"); ?>
<?php addImage("/image/vieetudiante/1999.jpg", 500, "center",
               "border: 3px solid var(--derive-main-color); border-radius: 8px;"); ?>
```

Toutes les images d'article s'agrandissent au clic (voir « Zoom » plus bas). Rien à ajouter pour en bénéficier.

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

Ajoute une mention de source en bas de l'article.

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

Construit une galerie d'images à partir de **tous** les fichiers d'un dossier. Au survol, le **nom du fichier** (sans extension) s'affiche en surimpression — pratique pour légender par promo ou par année. Ce nom sert aussi de légende dans la vue agrandie.

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

- **`generatePage($pageName, $themesName, $themesPath, $themeEntete, $sousTitre = "", $heroImage = "…")`** — assemble une page de thème complète (en-tête illustré, sommaire, articles, pied de page). C'est ce que chaque page `.php` à la racine appelle.
- **`generateSidebarFromArticle($articlePath)`** — lit le `$id` et le `$title` d'un article pour construire automatiquement l'entrée correspondante dans le sommaire.

> ⚠️ `baseArticle()` **ouvre** des balises que `generatePage()` **ferme**. Si vous ajoutez une balise ouvrante dans l'une, il faut ajouter la fermeture correspondante dans la boucle `foreach` de l'autre — sinon toutes les pages du site cassent d'un coup.

## Interface

### Mise en page

La page d'accueil ouvre sur un héros plein écran (`.hero`), suivi d'un chapô et de blocs image/texte alternés (`.duo`). Les pages de thème sont bâties sur une grille à deux colonnes : un rail de sommaire collant à gauche, une colonne de fiches d'articles à droite. Chaque article occupe sa propre carte blanche.

Sous 900 px, la grille repasse en une colonne et le sommaire devient un dépliant en haut de page.

### Sommaire

Le sommaire est un `<details open>` dont le `<summary>` est masqué en CSS au-delà de 900 px : il devient donc impossible à refermer sur grand écran, sans une ligne de JavaScript. Le surlignage de la section en cours de lecture est assuré par un `IntersectionObserver` dans `main-scripts.js`, qui pose la classe `is-actif` sur le lien correspondant.

### Zoom sur les images

Toutes les images de `.fiche__corps`, `.theme__entete` et de l'accueil sont cliquables et s'ouvrent en plein écran. Navigation aux flèches, fermeture par `Échap` ou clic sur le fond. Le tout est branché par délégation dans `main-scripts.js` : aucun article n'a besoin d'être modifié.

### Jetons CSS

Les couleurs, rayons, ombres et espacements sont centralisés en variables CSS en fin de `style.css`. Ne pas coder de valeur en dur ailleurs :

| Variable | Rôle |
|---|---|
| `--main-color` · `--derive-main-color` | bordeaux de l'identité |
| `--fond` · `--surface` · `--surface-2` | fond de page, cartes, encadrés |
| `--filet` | toutes les bordures 1 px |
| `--encre` · `--encre-douce` | texte principal et secondaire |
| `--r-carte` · `--r-elem` · `--r-bouton` | les trois seuls rayons |
| `--ombre-1` · `--ombre-2` | les deux seuls niveaux d'ombre |

## À faire

- `revue.php` référence le dossier `articles/revuedesmines/`, qui n'existe pas : la page s'affiche vide.
- Les polices sont servies en `.ttf` ; les convertir en `.woff2` diviserait leur poids par trois environ.

## Contribuer

Les contributions sont les bienvenues (corrections, nouveaux témoignages, photos). Ouvrir une *issue* ou une *pull request* vers `main`.

## Contact

- Facebook : [Commission Historique FPMs](https://www.facebook.com/CommissionHistoriqueFPMs)
- Instagram : [@commission_historique_fpms](https://www.instagram.com/commission_historique_fpms/)
- Mail : [historique.fpms@gmail.com](mailto:historique.fpms@gmail.com)

---

© Commission Historique F.P.Ms

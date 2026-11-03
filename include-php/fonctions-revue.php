<?php
/* =============================================================================
   Page « Revue des Mines » : lecture des données et rendu.

   generatePage() n'est pas utilisée ici : son gabarit sommaire + fiches ne
   convient pas à un catalogue de vidéos. Cette page a sa propre mise en page
   (css/revue.css) et son propre lecteur (javascript/revue.js).
   ============================================================================= */


/* -----------------------------------------------------------------------------
   Données
   -------------------------------------------------------------------------- */

/* Extrait l'identifiant de vidéo et/ou de playlist d'un code d'intégration,
   d'une URL ou d'un identifiant nu. Renvoie null si rien d'exploitable. */
function revueSourceYoutube($source) {
    $source = trim($source);

    if (preg_match('/^[\w-]{11}$/', $source)) {
        return ['video' => $source, 'liste' => null];
    }

    // Code d'intégration : seul l'attribut src nous intéresse
    if (preg_match('/src\s*=\s*["\']([^"\']+)/i', $source, $m)) {
        $source = $m[1];
    }
    $source = html_entity_decode($source, ENT_QUOTES);

    $video = null;
    $liste = null;
    $motif = '~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?(?:[^#]*&)?v=|shorts/|live/)|youtu\.be/)([\w-]{11})~';
    if (preg_match($motif, $source, $m) && $m[1] !== 'videoseries') {
        $video = $m[1];
    }
    if (preg_match('/[?&]list=([\w-]+)/', $source, $m)) {
        $liste = $m[1];
    }

    return ($video || $liste) ? ['video' => $video, 'liste' => $liste] : null;
}

/* youtube-nocookie : aucun cookie déposé tant que le visiteur ne lance pas la vidéo */
function revueUrlIntegration($yt) {
    $base = 'https://www.youtube-nocookie.com/embed/';
    $params = 'autoplay=1&rel=0';
    if ($yt['video']) {
        return $base . $yt['video'] . '?' . $params . ($yt['liste'] ? '&list=' . $yt['liste'] : '');
    }
    return $base . 'videoseries?list=' . $yt['liste'] . '&' . $params;
}

/* Lien direct vers YouTube : cible des cartes quand JavaScript est désactivé */
function revueUrlYoutube($yt) {
    if ($yt['video']) {
        return 'https://www.youtube.com/watch?v=' . $yt['video'] . ($yt['liste'] ? '&list=' . $yt['liste'] : '');
    }
    return 'https://www.youtube.com/playlist?list=' . $yt['liste'];
}

/* Année de tri : le premier nombre à 4 chiffres ("2024-2025" → 2024), 0 sinon */
function revueAnneeTri($annee) {
    return preg_match('/\d{4}/', (string) $annee, $m) ? (int) $m[0] : 0;
}

/* Valeurs spéciales de 'sortie'. Ce sont les deux plus grands entiers : une revue
   ainsi marquée est toujours « dans le futur », et une vraie date passe devant. */
const REVUE_SANS_DATE = PHP_INT_MAX - 1;   // annoncée, date encore inconnue
const REVUE_CACHEE    = PHP_INT_MAX;       // date illisible : n'apparaît nulle part

/* Date de sortie "AAAA-MM-JJ HH:MM", heure de Bruxelles, quel que soit le fuseau
   du serveur, ou 'bientôt' sans date. Renvoie un timestamp, REVUE_SANS_DATE,
   null si absente, false si illisible. */
function revueSortie($texte) {
    $texte = trim((string) $texte);
    if ($texte === '') return null;
    // strtolower ne touche que l'ASCII : le « ô » est remplacé avant (pas de mbstring requis)
    if (in_array(strtolower(str_replace(['ô', 'Ô'], 'o', $texte)), ['bientot', 'coming soon', 'soon'], true)) {
        return REVUE_SANS_DATE;
    }
    $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $texte, new DateTimeZone('Europe/Brussels'))
         ?: DateTimeImmutable::createFromFormat('!Y-m-d', $texte, new DateTimeZone('Europe/Brussels'));
    return $date ? $date->getTimestamp() : false;
}

/* « samedi 5 décembre 2026 à 20h » — sans dépendre de l'extension intl */
function revueDateEnClair($timestamp) {
    $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $mois  = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet',
              'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $d = (new DateTimeImmutable('@' . $timestamp))->setTimezone(new DateTimeZone('Europe/Brussels'));
    $heure = $d->format('i') === '00' ? $d->format('G\h') : $d->format('G\hi');
    return $jours[$d->format('w')] . ' ' . $d->format('j') . ' ' . $mois[$d->format('n') - 1]
         . ' ' . $d->format('Y') . ' à ' . $heure;
}

/* Charge le fichier de données et renvoie les revues valides, normalisées et
   triées de la plus récente à la plus ancienne. Les entrées inutilisables sont
   écartées et signalées en commentaire HTML, comme pour les articles. */
function revueCharger($fichier) {
    $brutes = is_file($fichier) ? include $fichier : [];
    if (!is_array($brutes)) return [];

    $revues = [];
    $slugs  = [];

    foreach ($brutes as $n => $brute) {
        $annee = trim((string) ($brute['annee'] ?? ''));
        $titre = trim((string) ($brute['titre'] ?? ''));

        $parties = [];
        foreach ((array) ($brute['videos'] ?? []) as $libelle => $source) {
            $yt = revueSourceYoutube((string) $source);
            if (!$yt) {
                echo "<!-- revues.php, entrée $n : vidéo « " . htmlspecialchars(is_int($libelle) ? "n°" . ($libelle + 1) : $libelle) . " » non reconnue -->";
                continue;
            }
            $parties[] = [
                'libelle' => is_int($libelle) ? 'Partie ' . (count($parties) + 1) : (string) $libelle,
                'embed'   => revueUrlIntegration($yt),
                'lien'    => revueUrlYoutube($yt),
                'video'   => $yt['video'],
            ];
        }

        $sortie = revueSortie($brute['sortie'] ?? '');
        if ($sortie === false) {
            // Une date mal écrite garde la revue cachée : mieux vaut un oubli qu'une fuite
            echo "<!-- revues.php, entrée $n : date de sortie illisible (AAAA-MM-JJ HH:MM ou 'bientôt'), revue cachée -->";
            $sortie = REVUE_CACHEE;
        }

        // Sans vidéo, seule une revue annoncée (avec une 'sortie') a un sens
        if ($titre === '' || (!$parties && $sortie === null)) {
            echo "<!-- revues.php, entrée $n : titre ou vidéo manquant, revue ignorée -->";
            continue;
        }

        // Identifiant d'ancre stable et unique : revue-2025, revue-2025-2…
        $slug = 'revue-' . (trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($annee ?: $titre)), '-') ?: $n);
        $base = $slug;
        for ($i = 2; isset($slugs[$slug]); ++$i) $slug = "$base-$i";
        $slugs[$slug] = true;

        // Miniature : l'affiche locale si fournie, sinon celle de la première vidéo
        $premiere = null;
        foreach ($parties as $p) { if ($p['video']) { $premiere = $p['video']; break; } }

        $revues[] = [
            'slug'      => $slug,
            'annee'     => $annee,
            'tri'       => revueAnneeTri($annee),
            'titre'     => $titre,
            'resume'    => trim((string) ($brute['resume'] ?? '')),
            'parties'   => $parties,
            'miniature' => $brute['affiche'] ?? ($premiere ? "https://i.ytimg.com/vi/$premiere/hqdefault.jpg" : ''),
            'grande'    => $brute['affiche'] ?? ($premiere ? "https://i.ytimg.com/vi/$premiere/maxresdefault.jpg" : ''),
            'affiche'   => $brute['affiche'] ?? '',
            'sortie'    => $sortie,
        ];
    }

    usort($revues, fn($a, $b) => $b['tri'] <=> $a['tri']);
    return $revues;
}

/* Sépare les revues visibles de la prochaine à sortir (la plus proche).
   Les revues à venir n'apparaissent nulle part ailleurs : ni carte, ni lecteur. */
function revueSeparerSorties($revues, $maintenant) {
    $publiees  = [];
    $prochaine = null;
    foreach ($revues as $r) {
        $sortie = $r['sortie'] ?? 0;
        if ($sortie <= $maintenant) {
            if ($r['parties']) { $publiees[] = $r; continue; }
            // Date passée mais vidéo pas encore en ligne : reste annoncée, sans compte à rebours
            $r['sortie'] = $sortie = REVUE_SANS_DATE;
        }
        if ($sortie !== REVUE_CACHEE && (!$prochaine || $sortie < $prochaine['sortie'])) {
            $prochaine = $r;
        }
    }
    return [$publiees, $prochaine];
}

/* Regroupe par décennie, dans l'ordre déjà trié : ['Années 2020' => [...], ...] */
function revueParDecennie($revues) {
    $rangees = [];
    foreach ($revues as $r) {
        $cle = $r['tri'] ? 'Années ' . intdiv($r['tri'], 10) * 10 : 'Autres revues';
        $rangees[$cle][] = $r;
    }
    return $rangees;
}


/* -----------------------------------------------------------------------------
   Rendu
   -------------------------------------------------------------------------- */

function revueNbParties($r) {
    $n = count($r['parties']);
    return $n > 1 ? "$n parties" : '';
}

function revueAfficheEnTete($r) {
    $e = fn($t) => htmlspecialchars($t, ENT_QUOTES);

    // Deux couches : si maxresdefault n'existe pas (404), hqdefault apparaît dessous
    $fond = $r['grande'] ? "url('" . $e($r['grande']) . "'), url('" . $e($r['miniature']) . "')" : 'none';
    $meta = array_filter([$r['annee'], revueNbParties($r)]);

    echo '
    <header class="affiche">
        <div class="affiche__fond" style="background-image:' . $fond . ';"></div>
        <div class="affiche__inner">
            <p class="affiche__surtitre">Dernière revue</p>
            <h1 class="affiche__titre">' . $e($r['titre']) . '</h1>
            <p class="affiche__meta">' . $e(implode(' · ', $meta)) . '</p>';

    if ($r['resume'] !== '') {
        echo '<p class="affiche__resume">' . $e($r['resume']) . '</p>';
    }

    echo '
            <div class="affiche__actions">
                <a class="bouton bouton--plein" href="' . $e($r['parties'][0]['lien']) . '" data-revue="' . $r['slug'] . '">
                    <i class="fas fa-play" aria-hidden="true"></i> Regarder
                </a>
                <a class="bouton bouton--voile" href="#catalogue">Toutes les revues</a>
            </div>
        </div>
    </header>';
}

/* Tête d'affiche d'une revue pas encore sortie : compte à rebours si la date est
   connue, simple annonce sinon. N'utilise que des données sans lien avec la vidéo :
   surtout pas la miniature YouTube, qui contient son identifiant. */
function revueAfficheAVenir($r, $maintenant, $avecCatalogue) {
    $datee   = $r['sortie'] !== REVUE_SANS_DATE;
    $restant = $datee ? max($r['sortie'] - $maintenant, 0) : 0;
    $e = fn($t) => htmlspecialchars($t, ENT_QUOTES);
    // Pas de style en ligne sans affiche : il écraserait le dégradé de repli du CSS
    $fond = $r['affiche'] !== '' ? ' style="background-image:url(\'' . $e($r['affiche']) . '\');"' : '';

    // Valeurs initiales calculées ici : rien ne clignote avant que le script démarre
    $cases = [
        'j' => [intdiv($restant, 86400),       'jours'],
        'h' => [intdiv($restant % 86400, 3600), 'heures'],
        'm' => [intdiv($restant % 3600, 60),    'minutes'],
        's' => [$restant % 60,                  'secondes'],
    ];

    echo '
    <header class="affiche affiche--a-venir">
        <div class="affiche__fond"' . $fond . '></div>
        <div class="affiche__inner">
            <p class="affiche__surtitre">Coming soon</p>
            <h1 class="affiche__titre">' . $e($r['titre']) . '</h1>
            <p class="affiche__meta">' . ($datee ? 'Sortie le ' . $e(revueDateEnClair($r['sortie'])) : 'Date de sortie bientôt annoncée') . '</p>';

    if ($r['resume'] !== '') {
        echo '<p class="affiche__resume">' . $e($r['resume']) . '</p>';
    }

    // data-restant vient du serveur : l'horloge du visiteur peut être fausse
    if ($datee) {
    echo '  <div class="decompte" data-restant="' . $restant . '" role="timer" aria-label="Temps restant avant la sortie">';
    foreach ($cases as $unite => [$valeur, $libelle]) {
        echo '<span class="decompte__case">
                  <span class="decompte__nombre" data-unite="' . $unite . '">' . str_pad($valeur, 2, '0', STR_PAD_LEFT) . '</span>
                  <span class="decompte__unite">' . $libelle . '</span>
              </span>';
    }
    echo '  </div>';
    }

    if ($avecCatalogue) {
        echo '<div class="affiche__actions"><a class="bouton bouton--voile" href="#catalogue">Revoir les précédentes</a></div>';
    }

    echo '
        </div>
    </header>';
}

function revueCarte($r) {
    $e = fn($t) => htmlspecialchars($t, ENT_QUOTES);
    $parties = revueNbParties($r);

    echo '
    <li class="carte">
        <a class="carte__lien" href="' . $e($r['parties'][0]['lien']) . '" data-revue="' . $r['slug'] . '">
            <span class="carte__vignette">';

    if ($r['miniature'] !== '') {
        echo '<img src="' . $e($r['miniature']) . '" alt="" loading="lazy" onerror="this.remove()">';
    }

    // Repli visible derrière l'image : l'année en grand si la miniature manque
    echo '      <span class="carte__repli" aria-hidden="true">' . $e($r['annee']) . '</span>
                <span class="carte__play" aria-hidden="true"><i class="fas fa-play"></i></span>'
             . ($parties ? '<span class="carte__badge">' . $parties . '</span>' : '') . '
            </span>
            <span class="carte__legende">
                <span class="carte__annee">' . $e($r['annee']) . '</span>
                <span class="carte__titre">' . $e($r['titre']) . '</span>
            </span>
        </a>
    </li>';
}

function revueRangee($nom, $revues, $index) {
    $id = "rangee-$index";
    echo '
    <section class="rangee" aria-labelledby="' . $id . '">
        <h2 class="rangee__titre" id="' . $id . '">' . htmlspecialchars($nom) . '</h2>
        <div class="rangee__cadre">
            <button class="rangee__fleche rangee__fleche--prec" type="button" aria-label="Revues précédentes" hidden></button>
            <ul class="rangee__piste">';
    foreach ($revues as $r) revueCarte($r);
    echo '  </ul>
            <button class="rangee__fleche rangee__fleche--suiv" type="button" aria-label="Revues suivantes" hidden></button>
        </div>
    </section>';
}

/* Données strictement nécessaires au lecteur, lues par revue.js */
function revueDonneesLecteur($revues) {
    $donnees = [];
    foreach ($revues as $r) {
        $donnees[$r['slug']] = [
            'annee'   => $r['annee'],
            'titre'   => $r['titre'],
            'resume'  => $r['resume'],
            'parties' => array_map(fn($p) => ['libelle' => $p['libelle'], 'embed' => $p['embed']], $r['parties']),
        ];
    }
    // JSON_HEX_TAG : un « </script> » dans un résumé ne peut pas fermer la balise
    return json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
}

function generateRevuePage($fichierDonnees) {
    echo '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <title>Revue des Mines - Commission Historique F.P.Ms</title>';
    include('include-php/header.php');
    echo '  <link href="css/revue.css" rel="stylesheet">
        <script src="/javascript/revue.js" defer></script>
    </head>
    <body class="has-hero page-revue">';

    include('include-php/navbar.php');

    // Chargé ici et pas plus haut : les avertissements en commentaire HTML
    // doivent tomber dans le <body>, pas avant le <!DOCTYPE>
    [$revues, $prochaine] = revueSeparerSorties(revueCharger($fichierDonnees), time());

    echo '<main class="revue">';

    if ($prochaine) {
        revueAfficheAVenir($prochaine, time(), (bool) $revues);
    }

    if (!$revues && !$prochaine) {
        echo '<div class="revue__vide"><h1>Revue des Mines</h1><p>Aucune revue publiée pour le moment.</p></div>';
    } elseif ($revues) {
        // Pendant le compte à rebours, la tête d'affiche est déjà prise
        if (!$prochaine) revueAfficheEnTete($revues[0]);

        echo '<div class="catalogue" id="catalogue">';
        $i = 0;
        foreach (revueParDecennie($revues) as $nom => $groupe) {
            revueRangee($nom, $groupe, $i++);
        }
        echo '</div>';

        echo '
        <dialog class="lecteur" aria-labelledby="lecteur-titre">
            <div class="lecteur__ecran"></div>
            <div class="lecteur__infos">
                <button class="lecteur__fermer" type="button" aria-label="Fermer">&times;</button>
                <p class="lecteur__annee"></p>
                <h2 class="lecteur__titre" id="lecteur-titre"></h2>
                <div class="lecteur__parties" role="group" aria-label="Parties"></div>
                <p class="lecteur__resume"></p>
            </div>
        </dialog>
        <script type="application/json" id="revue-donnees">' . revueDonneesLecteur($revues) . '</script>';
    }

    echo '</main>';

    include('include-php/footer.php');
    echo '</body></html>';
}
?>
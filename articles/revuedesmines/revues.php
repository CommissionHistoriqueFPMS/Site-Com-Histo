<?php
/* =============================================================================
   Revues des Mines — données de la page revue.php

   Une entrée par revue. L'ordre n'a pas d'importance : la page trie par année,
   la plus récente passe en tête d'affiche et chaque décennie forme une rangée.

   'videos' accepte, au choix, pour chaque partie :
     - le code d'intégration YouTube tel quel (Partager > Intégrer > Copier) ;
     - l'URL de la vidéo (youtube.com/watch?v=…, youtu.be/…) ;
     - l'URL d'une playlist (…?list=…) ;
     - l'identifiant seul (11 caractères).
   Seul l'identifiant est extrait : le code collé n'est jamais affiché tel quel.

   Une seule vidéo : pas besoin de libellé.
       'videos' => ['<iframe … src="https://www.youtube.com/embed/XXXXXXXXXXX" …></iframe>'],
   Plusieurs parties : le libellé devient le nom du bouton.
       'videos' => [
           'Acte I'  => '<iframe …></iframe>',
           'Acte II' => 'https://youtu.be/XXXXXXXXXXX',
       ],

   Champs optionnels :
     'resume'  => texte court affiché sous le titre ;
     'affiche' => image locale qui remplace la miniature YouTube (ex. image/revue/2025.jpg) ;
     'sortie'  => '2026-12-05 20:00' (heure de Bruxelles) ou 'bientôt' si la date n'est
                  pas encore connue. Tant que la revue n'est pas sortie, elle n'apparaît
                  qu'en tête d'affiche « Coming soon » (avec compte à rebours si datée) :
                  titre, résumé et affiche visibles, vidéo jamais envoyée au navigateur.
                  'videos' peut alors rester vide : la revue reste annoncée tant qu'elle
                  n'a pas de vidéo, même une fois la date passée.

   Les apostrophes du texte s'échappent : 'L\'Odyssée'. Les guillemets doubles
   du code YouTube, eux, passent sans rien faire.
   ============================================================================= */

return [

    // [
    //     'annee'  => '2028',
    //     'titre'  => 'Titre de la revue',
    //     'resume' => 'Une ou deux phrases sur le thème et l\'intrigue.',
    //     'sortie' => '2026-12-05 20:00',   // ou 'bientôt'
    //     'videos' => [
    //         '<iframe width="560" height="315" src="https://www.youtube.com/embed/XXXXXXXXXXX" …></iframe>',
    //     ],
    // ],
    [
        'annee'  => '2023',
        'titre'  => 'Imayini',
        'resume' => '',
        'sortie' => 'bientôt',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/dG32TwFu1Xg?si=V7CW6-mvHPBOZT7t" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '2018',
        'titre'  => 'Entre Ciel et Pierre',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/K-6k_7PerA0?si=a7W2Lr839sVsTk7o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '2013',
        'titre'  => 'La Croix et la Bière',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/pl8jM3beGCg?si=PYPAX8UlM_sZI4cL" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '2008',
        'titre'  => 'Le Bouclier Gouverne',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/V4XLKUWanZE?si=R2JwvL1-UzaUQDA0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '2003',
        'titre'  => 'Ca ne sent pire guère...',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/QmVlNeNywdw?si=-NosdB0A9EHVB8RH" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    
    [
        'annee'  => '1998',
        'titre'  => 'Prométhée moi la Lune',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/tPvGgbY1FlA?si=z8mjL4T8BQWb3F8C" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '1992',
        'titre'  => 'Sous les pavés, le désert',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/5MCgB1f_zb4?si=kMEAobycCjJ0thuY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
    [
        'annee'  => '1987',
        'titre'  => 'Le domaine des gueux',
        'resume' => '',
        'videos' => ['Enregistrement complet' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/NHIDzaDSiKU?si=2jwBegrc-TaynVuk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
    ],
];
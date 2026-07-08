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
 
Fonctions utiles disponibles : `addImage()`, `addSource()`, `generateTable()`. L'article est repris automatiquement dans la page du thème.
 
## Contribuer
 
Les contributions sont les bienvenues (corrections, nouveaux témoignages, photos). Ouvrir une *issue* ou une *pull request*.
 
## Contact
 
- Facebook : [Commission Historique FPMs](https://www.facebook.com/CommissionHistoriqueFPMs)
- Instagram : [@commission_historique_fpms](https://www.instagram.com/commission_historique_fpms/)
- Mail : [historique.fpms@gmail.com](mailto:historique.fpms@gmail.com)
---
 
© Commission Historique F.P.Ms

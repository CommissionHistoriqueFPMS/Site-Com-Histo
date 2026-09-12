<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Accueil - Commission Historique F.P.Ms</title>
    <?php include('include-php/header.php');?>
    <script src="/javascript/reveal.js"></script>
</head>
<body class="has-hero">
<?php include('include-php/navbar.php');?>

<header class="hero">
    <div class="hero__media" style="background-image:url('image/headers/st-waudru.jpg');"></div>
    <div class="hero__inner">
        <h1 class="hero__title">Commission Historique F.P.Ms</h1>
        <hr class="hero__rule">
        <p class="hero__sub">Le folklore estudiantin montois, recueilli témoignage par témoignage.</p>
        <a href="#accueil" class="hero__cta">Nous découvrir</a>
    </div>
    <span class="hero__scroll" aria-hidden="true"></span>
</header>

<section class="accueil" id="accueil">
    <div class="accueil__inner">

        <div class="accueil__intro">
            <p>
                Petite terre de défense du folklore estudiantin, la Commission est pour nous l’occasion de relater
                les diverses frasques et anecdotes qui jalonnent l’histoire estudiantine de Mons, et principalement
                celles des étudiants de la Faculté Polytechnique.
            </p>
        </div>

        <article class="duo">
            <div class="duo__media">
                <img src="image/vieetudiante/1931cortege.webp" alt="Cortège estudiantin, 1931">
            </div>
            <div class="duo__texte">
                <h2 class="duo__titre">Ce que nous conservons</h2>
                <p>
                    Nous rassemblons des témoignages écrits, oraux et matériels que nous publions ici au fil de
                    nos trouvailles. Objets, photographies, chants, coupures de presse : tout ce qui documente
                    le patrimoine de la Faculté Polytechnique et de ses étudiants a sa place dans le fonds.
                </p>
                <p>
                    Nos collections sont exposées dans notre local, au onzième étage de la cité Pierre Houzeau
                    de Lehaie, où sont également stockées les archives.
                </p>
            </div>
        </article>

        <article class="duo duo--inverse">
            <div class="duo__media">
                <img src="image/vieetudiante/1960kot.webp" alt="Kot étudiant, 1960">
            </div>
            <div class="duo__texte">
                <h2 class="duo__titre">Qui nous sommes</h2>
                <p>
                    La Commission Historique est un petit groupe d’étudiants baptisés qui veulent en savoir
                    plus sur le folklore dans lequel ils vivent. Ses responsables sont élus chaque année pour
                    un mandat annuel.
                </p>
                <p>
                    Si tu es étudiant à la Faculté Polytechnique ou membre actif du cercle Polytech Mons,
                    viens renforcer nos rangs ou donne un coup de main plus ponctuel lors des expositions
                    et des ouvertures!
                </p>
            </div>
        </article>

        <article class="duo">
            <div class="duo__media">
                <img src="image/headers/145-cortège.jpg" alt="Cortège du 145e anniversaire">
            </div>
            <div class="duo__texte">
                <h2 class="duo__titre">Contribuer au fonds</h2>
                <p>
                    Si, lors d’un bon tri dans le grenier, tu retombes sur des trésors datant de tes années
                    à la Polytech (ou de celles de tes parents) partage-les nous, ne fût-ce qu’en photo.
                </p>
                <p>
                    Documents, objets, souvenirs ou simples anecdotes : prends contact, on en discutera
                    autour d’une excellente bière.
                </p>
                <a href="contact.php" class="big-button duo__bouton">Contactez-nous !</a>
            </div>
        </article>

    </div>
</section>

<?php include('include-php/footer.php');?>


</body>
</html>

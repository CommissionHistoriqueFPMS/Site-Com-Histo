function scrollToSection(sectionId) {
    var section = document.getElementById(sectionId);
    if (section) {
        var offset = 125; // Ajustez cette valeur selon vos besoins
        var offsetTop = section.offsetTop - offset;
        window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // Récupérer tous les éléments avec la classe articles-subtitle
    var titles = document.querySelectorAll(".article-subtitle, .article-title");

    // Boucle à travers tous les éléments avec la classe articles-subtitle
    titles.forEach(function(title) {
        // Ajouter un gestionnaire d'événements pour le clic sur chaque titre
        title.addEventListener("click", function() {
            // Récupérer l'élément du contenu correspondant
            var content = this.nextElementSibling;

            if (!content.classList.contains("hidden"))
                content.style.overflow = "hidden";

            // Basculer la classe 'hidden' pour afficher ou cacher le contenu
            content.classList.toggle("hidden");

            // Récupérer la hauteur du contenu
            var contentHeight = content.scrollHeight;

            content.style.maxHeight = contentHeight + "px";
            //if (content.classList.contains("main-article-content"))
                //contentHeight = 10000;

            // Modifier la hauteur maximale du contenu pour l'animation
            if (content.classList.contains("hidden")) {
                sleep(10).then(() => content.style.maxHeight = "0");
            }

            if (!content.classList.contains("hidden"))
                sleep(300).then(() => content.style.overflow = "");
        });
    });
        /* ---------- Zoom sur les images d'article ---------- */
    (() => {
        const images = [...document.querySelectorAll(
            '.fiche__corps img, .theme__entete img, .accueil__inner img'
        )].filter(img => !img.closest('a'));   // ne pas voler le clic d'une image déjà liée

        if (!images.length) return;

        images.forEach(img => {
            img.classList.add('zoomable');
            img.tabIndex = 0;                              // focusable au clavier
            img.setAttribute('role', 'button');
            img.setAttribute('aria-label', 'Agrandir l’image');
        });

        // L'overlay est construit une seule fois, à la première ouverture
        let zoom, figure, image, legende, precedent, suivant, declencheur, index = 0;

        const construire = () => {
            zoom = document.createElement('div');
            zoom.className = 'zoom';
            zoom.setAttribute('role', 'dialog');
            zoom.setAttribute('aria-modal', 'true');
            zoom.innerHTML = `
                <button class="zoom__fermer" type="button" aria-label="Fermer"></button>
                <button class="zoom__nav zoom__nav--prec" type="button" aria-label="Image précédente"></button>
                <button class="zoom__nav zoom__nav--suiv" type="button" aria-label="Image suivante"></button>
                <figure class="zoom__figure">
                    <img class="zoom__img" alt="">
                    <figcaption class="zoom__legende"></figcaption>
                </figure>`;
            document.body.appendChild(zoom);

            figure    = zoom.querySelector('.zoom__figure');
            image     = zoom.querySelector('.zoom__img');
            legende   = zoom.querySelector('.zoom__legende');
            precedent = zoom.querySelector('.zoom__nav--prec');
            suivant   = zoom.querySelector('.zoom__nav--suiv');

            zoom.addEventListener('click', e => {
                // Clic sur le fond ou la croix : on ferme. Clic sur l'image : on garde.
                if (e.target === zoom || e.target.closest('.zoom__fermer')) fermer();
            });
            precedent.addEventListener('click', () => afficher(index - 1));
            suivant.addEventListener('click',  () => afficher(index + 1));
        };

        // addImage() met le chemin du fichier dans l'alt : inutile en légende.
        // On n'affiche que les alt "humains", comme les années des albums photo.
        const legendeUtile = txt =>
            txt && !txt.includes('/') && !/\.(jpe?g|png|webp|gif|svg)$/i.test(txt);

        const afficher = i => {
            index = (i + images.length) % images.length;   // boucle aux extrémités
            const src = images[index];
            image.src = src.currentSrc || src.src;
            image.alt = src.alt || '';

            const txt = legendeUtile(src.alt) ? src.alt : '';
            legende.textContent = txt;
            legende.hidden = !txt;
        };

        const ouvrir = img => {
            if (!zoom) construire();
            declencheur = img;                             // pour rendre le focus à la fermeture

            const plusieurs = images.length > 1;
            precedent.hidden = suivant.hidden = !plusieurs;

            afficher(images.indexOf(img));
            document.body.classList.add('zoom-ouvert');    // bloque le défilement de la page
            zoom.classList.add('is-ouvert');
            zoom.querySelector('.zoom__fermer').focus();
        };

        const fermer = () => {
            zoom.classList.remove('is-ouvert');
            document.body.classList.remove('zoom-ouvert');
            image.src = '';                                // libère la mémoire
            if (declencheur) declencheur.focus();
        };

        images.forEach(img => {
            img.addEventListener('click', () => ouvrir(img));
            img.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); ouvrir(img); }
            });
        });

        document.addEventListener('keydown', e => {
            if (!zoom || !zoom.classList.contains('is-ouvert')) return;
            if (e.key === 'Escape')     fermer();
            if (e.key === 'ArrowLeft')  afficher(index - 1);
            if (e.key === 'ArrowRight') afficher(index + 1);
        });
    })();
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
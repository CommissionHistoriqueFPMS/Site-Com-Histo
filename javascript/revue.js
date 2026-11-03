/* =============================================================================
   Page « Revue des Mines » — lecteur et rangées défilantes.

   Sans JavaScript, chaque carte reste un lien vers la vidéo sur YouTube.
   Avec : le clic ouvre le lecteur (<dialog>) et l'URL prend l'ancre de la
   revue (revue.php#revue-2025), ce qui rend chaque revue partageable.
============================================================================= */

(() => {
    "use strict";

    const SEUIL_BORD = 4;      // px de tolérance pour masquer une flèche en bout de rangée
    const PAS_DEFILE = 0.85;   // fraction de la largeur visible parcourue par clic de flèche

    /* ------------------------------------------------------------ Rangées */

    const initRangee = cadre => {
        const piste = cadre.querySelector(".rangee__piste");
        const prec  = cadre.querySelector(".rangee__fleche--prec");
        const suiv  = cadre.querySelector(".rangee__fleche--suiv");

        const maj = () => {
            prec.hidden = piste.scrollLeft <= SEUIL_BORD;
            suiv.hidden = piste.scrollLeft + piste.clientWidth >= piste.scrollWidth - SEUIL_BORD;
        };
        const defiler = sens => piste.scrollBy({ left: sens * piste.clientWidth * PAS_DEFILE, behavior: "smooth" });

        prec.addEventListener("click", () => defiler(-1));
        suiv.addEventListener("click", () => defiler(1));
        piste.addEventListener("scroll", maj, { passive: true });
        window.addEventListener("resize", maj, { passive: true });
        maj();
    };

    /* ---------------------------------------------------- Compte à rebours */

    /* Le serveur fournit le nombre de secondes restantes : on décompte à partir
       de là avec l'horloge monotone du navigateur, insensible à une heure système
       fausse ou modifiée. À zéro, on recharge : c'est le serveur qui dévoile. */
    const initDecompte = bloc => {
        const restantInitial = Number(bloc.dataset.restant);
        const depart  = performance.now();
        const nombres = Object.fromEntries(
            [...bloc.querySelectorAll("[data-unite]")].map(n => [n.dataset.unite, n]));
        const deux = n => String(n).padStart(2, "0");

        const tic = () => {
            // +1 s de marge : recharger une fraction de seconde trop tôt réafficherait le décompte
            const restant = restantInitial - Math.floor((performance.now() - depart) / 1000);
            if (restant < -1) { location.reload(); return; }

            const r = Math.max(restant, 0);
            nombres.j.textContent = deux(Math.floor(r / 86400));
            nombres.h.textContent = deux(Math.floor(r % 86400 / 3600));
            nombres.m.textContent = deux(Math.floor(r % 3600 / 60));
            nombres.s.textContent = deux(r % 60);
            setTimeout(tic, 1000 - (performance.now() - depart) % 1000);
        };
        tic();
    };

    /* ------------------------------------------------------------ Lecteur */

    const initLecteur = () => {
        const source = document.getElementById("revue-donnees");
        const dialog = document.querySelector(".lecteur");
        if (!source || !dialog) return;

        const revues  = JSON.parse(source.textContent);
        const ecran   = dialog.querySelector(".lecteur__ecran");
        const annee   = dialog.querySelector(".lecteur__annee");
        const titre   = dialog.querySelector(".lecteur__titre");
        const resume  = dialog.querySelector(".lecteur__resume");
        const parties = dialog.querySelector(".lecteur__parties");

        let courante = null;

        const lire = index => {
            const partie = courante.parties[index];
            const iframe = document.createElement("iframe");
            iframe.src   = partie.embed;
            iframe.title = `${courante.titre} — ${partie.libelle}`;
            iframe.allow = "autoplay; encrypted-media; picture-in-picture; fullscreen";
            iframe.allowFullscreen = true;
            ecran.replaceChildren(iframe);

            parties.querySelectorAll("button").forEach((b, i) =>
                b.setAttribute("aria-pressed", String(i === index)));
        };

        const ouvrir = slug => {
            courante = revues[slug];
            if (!courante) return false;

            annee.textContent  = courante.annee;
            titre.textContent  = courante.titre;
            resume.textContent = courante.resume;

            // Un seul bouton n'aurait aucun intérêt : pas de sélecteur pour une vidéo unique
            parties.replaceChildren(...(courante.parties.length < 2 ? [] :
                courante.parties.map((p, i) => {
                    const b = document.createElement("button");
                    b.type = "button";
                    b.className = "lecteur__partie";
                    b.textContent = p.libelle;
                    b.addEventListener("click", () => lire(i));
                    return b;
                })));

            lire(0);
            if (!dialog.open) dialog.showModal();
            // replaceState : ouvrir dix revues n'empile pas dix entrées d'historique
            history.replaceState(null, "", "#" + slug);
            return true;
        };

        // Retirer l'iframe coupe le son ; le dialog rend lui-même le focus à la carte
        dialog.addEventListener("close", () => {
            ecran.replaceChildren();
            courante = null;
            history.replaceState(null, "", location.pathname + location.search);
        });

        dialog.querySelector(".lecteur__fermer").addEventListener("click", () => dialog.close());

        // Clic sur le fond assombri : la cible est le <dialog> lui-même
        dialog.addEventListener("click", e => { if (e.target === dialog) dialog.close(); });

        document.addEventListener("click", e => {
            const lien = e.target.closest("[data-revue]");
            // Ctrl/Cmd-clic, clic molette : laisser ouvrir YouTube dans un onglet
            if (!lien || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
            if (ouvrir(lien.dataset.revue)) e.preventDefault();
        });

        const depuisAncre = () => {
            const slug = decodeURIComponent(location.hash.slice(1));
            if (slug && revues[slug] && courante !== revues[slug]) ouvrir(slug);
        };
        window.addEventListener("hashchange", depuisAncre);
        depuisAncre();
    };

    document.querySelectorAll(".decompte").forEach(initDecompte);
    document.querySelectorAll(".rangee__cadre").forEach(initRangee);
    initLecteur();
})();
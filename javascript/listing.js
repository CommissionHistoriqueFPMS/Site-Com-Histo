/* =============================================================================
   Listing des anciens comités — page listingcomite.php

   Remplacement direct de javascript/listing.js : aucun changement de balisage
   nécessaire, les identifiants et les classes attendus sont inchangés.

   Corrections par rapport à la version précédente :
     1. le scroll visait .container, disparu lors de la refonte → l'exception
        coupait la fin du gestionnaire de clic, donc l'URL n'était plus mise à jour ;
     2. un slug inconnu dans le hash faisait planter le rendu du tableau et
        interrompait l'initialisation, laissant la recherche inerte ;
     3. l'onglet stocké dans le hash n'était jamais restauré au chargement ;
     4. le champ de recherche était focus même en ouvrant l'onglet « Parcourir » ;
     5. chaque changement de comité empilait une entrée d'historique ;
     6. la recherche et les slugs utilisaient deux normalisations divergentes.
============================================================================= */

(() => {
    "use strict";

    const SOURCE           = "/articles/listingcomite/listing.json";
    const ONGLET_DEFAUT    = "lc-tbrowse";
    const ANCRE_WIDGET     = "1listing-interactif";  // <h3> posé par baseArticle()
    const MARGE_SCROLL     = 200;
    const CARACTERES_MIN   = 2;
    const RESULTATS_MAX    = 40;
    const LONGUEUR_MIN_NOM = 4;
    const ROLES_IGNORES    = ["Thème(s)"];
    const SEPARATEURS      = /[+/,]| et /;

    /* ---------------------------------------------------------------- Texte */

    const sansAccent = texte => texte.normalize("NFD").replace(/\p{Diacritic}/gu, "");

    /* Doit rester identique à l'ancienne implémentation : les liens déjà
       partagés contiennent ces slugs. */
    const slug = nom => sansAccent(nom)
        .toLowerCase()
        .replace(/['’`]/g, "")
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");

    /* Même base que le slug, sans le remplacement par des tirets : « O'Neil »
       et « ONeil » se retrouvent désormais l'un l'autre. */
    const cleRecherche = texte => sansAccent(texte).toLowerCase().replace(/['’`]/g, "");

    const echapper = valeur => String(valeur ?? "").replace(/[&<>"']/g, caractere => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
    }[caractere]));

    /* ------------------------------------------------------- État dans l'URL */

    function lireEtat() {
        let brut = location.hash.slice(1);
        try { brut = decodeURIComponent(brut); } catch { /* hash malformé */ }
        const [onglet = "", comite = ""] = brut.split("|");
        return { onglet, comite };
    }

    function ecrireEtat(onglet, comite) {
        /* replaceState et non location.hash : sinon chaque comité consulté
           ajoute une entrée et le bouton Retour ne quitte plus la page. */
        history.replaceState(null, "", `${location.pathname}${location.search}#${onglet}|${comite}`);
    }

    /* ------------------------------------------------------------- Onglets */

    const onglets = [...document.querySelectorAll(".lc-tab")];

    function ongletActif() {
        const actif = onglets.find(onglet => onglet.classList.contains("on"));
        return actif ? actif.id : ONGLET_DEFAUT;
    }

    function activerOnglet(id, { focus = false } = {}) {
        for (const onglet of onglets) {
            const estActif = onglet.id === id;   // comparaison exacte, plus de includes()

            onglet.classList.remove("lc-hidden");
            onglet.classList.toggle("on", estActif);
            onglet.setAttribute("aria-pressed", estActif ? "true" : "false");

            for (const panneau of document.querySelectorAll(`[data-displaytab="${onglet.id}"]`)) {
                panneau.classList.toggle("lc-hidden", !estActif);
            }

            if (estActif && focus) {
                const champ = document.querySelector(`[data-displayfocus="${onglet.id}"]`);
                if (champ) champ.focus();
            }
        }
    }

    function remonterAuWidget() {
        const ancre = document.getElementById(ANCRE_WIDGET) || document.getElementById("lc-sub");
        if (!ancre) return;
        /* getBoundingClientRect plutôt qu'offsetTop : celui-ci est relatif au
           parent positionné, pas au document. */
        const cible = ancre.getBoundingClientRect().top + window.scrollY - MARGE_SCROLL;
        window.scrollTo({ behavior: "instant", top: Math.max(0, cible) });
    }

    /* -------------------------------------------------- Parcourir par cercle */

    function initParcourir(donnees) {
        const select = document.getElementById("lc-pick");
        const table  = document.getElementById("lc-table");
        const meta   = document.getElementById("lc-cmeta");

        /* slug → nom réel : évite de relire le DOM pour retrouver la clé du JSON.
           En cas de collision de slugs, le premier cercle rencontré l'emporte. */
        const parSlug = new Map();

        for (const nom of Object.keys(donnees).sort((a, b) => a.localeCompare(b, "fr"))) {
            const cle = slug(nom);
            if (parSlug.has(cle)) continue;
            parSlug.set(cle, nom);

            const option = document.createElement("option");
            option.value = cle;
            option.textContent = nom;
            select.appendChild(option);
        }

        function rendre(cle) {
            const nom = parSlug.get(cle);
            if (!nom) return;

            const cercle = donnees[nom];
            const roles  = [];
            for (const mandat of cercle.recs) {
                for (const role of Object.keys(mandat.r)) {
                    if (!roles.includes(role)) roles.push(role);
                }
            }

            const entete = ["N°", "Année", ...roles]
                .map(titre => `<th>${echapper(titre)}</th>`).join("");

            const lignes = cercle.recs.map(mandat =>
                `<tr><td class="y">${echapper(mandat.n)}</td><td class="y">${echapper(mandat.a)}</td>` +
                roles.map(role => `<td>${echapper(mandat.r[role] || "")}</td>`).join("") +
                "</tr>"
            ).join("");

            table.innerHTML = `<thead><tr>${entete}</tr></thead><tbody>${lignes}</tbody>`;
            meta.textContent = cercle.recs.length + " mandats" +
                (cercle.c ? " · création " + cercle.c : "");
        }

        return { select, rendre, connait: cle => parSlug.has(cle) };
    }

    /* ------------------------------------------------- Rechercher une personne */

    function indexerPersonnes(donnees) {
        const index = new Map();

        for (const [cercle, entree] of Object.entries(donnees)) {
            for (const mandat of entree.recs) {
                for (const [role, valeur] of Object.entries(mandat.r)) {
                    if (ROLES_IGNORES.includes(role)) continue;

                    for (const brut of String(valeur).split(SEPARATEURS)) {
                        const nom = brut.replace(/\(.*?\)/g, "").trim();
                        if (nom.length < LONGUEUR_MIN_NOM) continue;
                        if (!index.has(nom)) index.set(nom, []);
                        index.get(nom).push({ cercle, role, annee: mandat.a });
                    }
                }
            }
        }

        /* Clés de recherche et tris calculés une seule fois, pas à chaque frappe. */
        return [...index.entries()]
            .map(([nom, mandats]) => ({
                nom,
                cle: cleRecherche(nom),
                mandats: mandats.sort((a, b) => b.annee.localeCompare(a.annee))
            }))
            .sort((a, b) => a.nom.localeCompare(b.nom, "fr"));
    }

    function initRecherche(personnes) {
        const champ      = document.getElementById("lc-q");
        const resultats  = document.getElementById("lc-results");

        const carte = personne =>
            `<div class="lc-card"><h3>${echapper(personne.nom)}</h3>` +
            personne.mandats.map(mandat =>
                `<div class="lc-line"><span class="lc-role">${echapper(mandat.role)}</span>` +
                ` — ${echapper(mandat.cercle)} <em>(${echapper(mandat.annee)})</em></div>`
            ).join("") +
            "</div>";

        champ.addEventListener("input", () => {
            const requete = cleRecherche(champ.value.trim());

            if (requete.length < CARACTERES_MIN) {
                resultats.innerHTML = `<p class="lc-hint">Tapez au moins ${CARACTERES_MIN} lettres.</p>`;
                return;
            }

            const trouves = personnes.filter(personne => personne.cle.includes(requete));

            if (!trouves.length) {
                resultats.innerHTML = `<p class="lc-hint">Aucun nom ne correspond. Essayez une autre orthographe, ou seulement le début du nom.</p>`;
                return;
            }

            const reste = trouves.length - RESULTATS_MAX;
            resultats.innerHTML = trouves.slice(0, RESULTATS_MAX).map(carte).join("") +
                (reste > 0
                    ? `<p class="lc-hint">${reste} autre${reste > 1 ? "s" : ""} résultat${reste > 1 ? "s" : ""} — précisez votre recherche.</p>`
                    : "");
        });
    }

    /* ---------------------------------------------------------- Démarrage */

    function init(donnees) {
        const etat = lireEtat();

        const nbCercles = Object.keys(donnees).length;
        const nbMandats = Object.values(donnees).reduce((total, e) => total + e.recs.length, 0);
        document.getElementById("lc-sub").textContent =
            `${nbCercles} cercles & organismes · ${nbMandats} mandats répertoriés · archives depuis 1898`;

        const parcourir = initParcourir(donnees);
        initRecherche(indexerPersonnes(donnees));

        /* Restauration : le comité et l'onglet, chacun validé avant usage. */
        if (etat.comite && parcourir.connait(etat.comite)) {
            parcourir.select.value = etat.comite;
        }
        parcourir.rendre(parcourir.select.value);

        const ongletDemande = onglets.some(onglet => onglet.id === etat.onglet)
            ? etat.onglet
            : ONGLET_DEFAUT;
        activerOnglet(ongletDemande);   // sans focus : au chargement, il ferait sauter la page

        for (const onglet of onglets) {
            onglet.addEventListener("click", () => {
                activerOnglet(onglet.id, { focus: true });
                remonterAuWidget();
                ecrireEtat(onglet.id, parcourir.select.value);
            });
        }

        parcourir.select.addEventListener("change", () => {
            parcourir.rendre(parcourir.select.value);
            ecrireEtat(ongletActif(), parcourir.select.value);
        });
    }

    fetch(SOURCE)
        .then(reponse => {
            if (!reponse.ok) throw new Error(`HTTP ${reponse.status}`);
            return reponse.json();
        })
        .then(init)
        .catch(erreur => {
            document.getElementById("lc-sub").textContent =
                "Les données du listing n'ont pas pu être chargées. Rechargez la page ; " +
                "si le problème persiste, signalez-le à la Commission Historique.";
            console.error("[listing]", erreur);
        });
})();

/* =============================================================================
   Formulaire « Suggérer une modification »

   Indépendant du chargement des données : le bloc existe dans la page dès le
   départ (include-php/suggestion-listing.php).
============================================================================= */

(() => {
    "use strict";

    const bouton     = document.getElementById("lc-suggest-toggle");
    const panneau    = document.getElementById("lc-suggest");
    const formulaire = document.getElementById("lc-suggest-form");
    if (!bouton || !panneau || !formulaire) return;

    /* Le <select> stocke un slug : on reprend le libellé affiché. */
    function cercleAffiche() {
        const select = document.getElementById("lc-pick");
        const option = select && select.selectedOptions[0];
        return option ? option.textContent : "";
    }

    bouton.addEventListener("click", () => {
        panneau.classList.toggle("lc-hidden");
        const ouvert = !panneau.classList.contains("lc-hidden");

        bouton.setAttribute("aria-expanded", ouvert ? "true" : "false");
        bouton.classList.toggle("on", ouvert);
        if (!ouvert) return;

        /* Pré-remplissage modifiable, pas imposé. */
        const cercle = document.getElementById("lc-s-cercle");
        if (cercle && !cercle.value) cercle.value = cercleAffiche();

        panneau.scrollIntoView({ behavior: "smooth", block: "nearest" });
    });

    formulaire.addEventListener("submit", () => {
        const cercle = (document.getElementById("lc-s-cercle").value || "").trim();

        formulaire.querySelector("[name='_subject']").value =
            "Listing des comités — suggestion de modification" + (cercle ? " · " + cercle : "");

        /* Retour sur la page, onglet et comité compris. */
        formulaire.querySelector("[name='_next']").value =
            location.origin + location.pathname + "?merci=listing" + location.hash;
    });

    if (new URLSearchParams(location.search).get("merci") === "listing") {
        document.getElementById("lc-suggest-ok").classList.remove("lc-hidden");
        history.replaceState(null, "", location.pathname + location.hash);
    }
})();
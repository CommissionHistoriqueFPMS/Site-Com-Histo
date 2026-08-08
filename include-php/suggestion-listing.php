<!-- Bouton + formulaire "Suggérer une modification" (listing des comités) -->
<div class="lc-actions">
    <button type="button" class="lc-btn" id="lc-suggest-toggle"
            aria-expanded="false" aria-controls="lc-suggest">
        <i class="fas fa-pen"></i> Suggérer une modification
    </button>
    <span class="lc-actions-hint">Une erreur, un oubli, un nom mal orthographié&nbsp;? Dites-le nous.</span>
</div>

<p class="lc-ok lc-hidden" id="lc-suggest-ok">
    Merci&nbsp;! Votre suggestion a bien été envoyée à la Commission Historique.
</p>

<div class="lc-suggest lc-hidden" id="lc-suggest">
    <form class="contact-form" id="lc-suggest-form"
          action="https://formsubmit.co/historique.fpms@gmail.com" method="POST">

        <!-- Réglages FormSubmit (champs cachés) -->
        <input type="hidden" name="_subject" value="Listing des comités — suggestion de modification">
        <input type="hidden" name="_template" value="table">
        <input type="hidden" name="_captcha" value="true">
        <input type="hidden" name="_next" value="https://historique.fede.fpms.ac.be/listingcomite.php?merci=listing">
        <!-- Piège à robots : si rempli, le message est ignoré -->
        <input type="text" name="_honey" class="hp">

        <label for="lc-s-cercle">Cercle / organisme concerné</label>
        <input type="text" id="lc-s-cercle" name="Cercle" required>

        <label for="lc-s-annee">Année ou n° de comité <span class="lc-opt">(optionnel)</span></label>
        <input type="text" id="lc-s-annee" name="Année ou comité" placeholder="ex. 2013-2014, comité 175…">

        <label for="lc-s-modif">Modification proposée</label>
        <textarea id="lc-s-modif" name="Modification proposée" required
                  placeholder="Décrivez ce qui doit être corrigé ou ajouté (rôle, nom, orthographe, année manquante…)."></textarea>

        <label for="lc-s-source">Comment le savez-vous&nbsp;? <span class="lc-opt">(optionnel)</span></label>
        <input type="text" id="lc-s-source" name="Source" placeholder="Témoignage, photo, revue, archive…">

        <label for="lc-s-nom">Votre nom</label>
        <input type="text" id="lc-s-nom" name="Nom" required>

        <label for="lc-s-mail">Votre e-mail <span class="lc-opt">(pour qu'on puisse vous répondre)</span></label>
        <input type="email" id="lc-s-mail" name="email" required>

        <button type="submit">Envoyer la suggestion</button>
    </form>
</div>
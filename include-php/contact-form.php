<form class="contact-form" action="https://formsubmit.co/historique.fpms@gmail.com" method="POST">
 
    <!-- Réglages FormSubmit (champs cachés) -->
    <input type="hidden" name="_subject" value="Nouveau message — site Com'Histo">
    <input type="hidden" name="_template" value="table">
    <input type="hidden" name="_captcha" value="false">
    <!-- Piège à robots : si rempli, le message est ignoré -->
    <input type="text" name="_honey" class="hp">
 
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required>
 
    <label for="email">Votre e-mail (pour qu'on puisse vous répondre)</label>
    <input type="email" id="email" name="email" required>
 
    <label for="sujet">Sujet</label>
    <input type="text" id="sujet" name="sujet">
 
    <label for="message">Message</label>
    <textarea id="message" name="message" required></textarea>
 
    <button type="submit">Envoyer</button>
</form>
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
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
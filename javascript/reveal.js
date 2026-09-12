/* Apparition des blocs au défilement.
   Un seul rôle : poser .is-visible sur les éléments listés dans CIBLES quand ils
   entrent dans l'écran. Tout l'aspect visuel (opacité, translation, durée) est
   dans style.css, section « Apparition des blocs au défilement ». */
 
(() => {
    const CIBLES = '.accueil__intro, .duo';
    const MARGE  = '0px 0px -12% 0px';   // déclenche un peu avant le bas de l'écran
 
    const animer = 'IntersectionObserver' in window
                && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 
    // Posée immédiatement (le script est dans le <head>, avant le rendu du body) :
    // sans cette classe, le CSS laisse les blocs visibles. Pas de page blanche si
    // le JS ne tourne pas, et pas de clignotement au chargement.
    if (animer) document.documentElement.classList.add('js-reveal');
 
    document.addEventListener('DOMContentLoaded', () => {
        if (!animer) return;
 
        const blocs = document.querySelectorAll(CIBLES);
        if (!blocs.length) return;   // les autres pages n'ont pas ces blocs
 
        const observateur = new IntersectionObserver((entrees, obs) => {
            entrees.forEach(entree => {
                if (!entree.isIntersecting) return;
                entree.target.classList.add('is-visible');
                obs.unobserve(entree.target);   // une seule fois, pas de réanimation
            });
        }, { rootMargin: MARGE, threshold: 0 });
 
        blocs.forEach(bloc => observateur.observe(bloc));
    });
})();
 
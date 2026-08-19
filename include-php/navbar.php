<nav class="navbar">
    <div class="navbar-top">
        <a href="index.php" class="navbar-brand">
            <img src="/image/logo.png" alt="logo Commission Historique">
            <span>Commission Historique F.P.Ms</span>
        </a>
        <button class="navbar-burger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="navbar-menu">
        <ul class="navbar-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="fac.php">La Faculté</a></li>
            <li class="nav-dropdown">
                <span class="nav-dropdown-label">Cercle F.P.Ms <span class="dropdown-arrow">&#9660;</span></span>
                <div class="nav-dropdown-content">
                    <a href="fedefetes.php">Fédé et Fêtes</a>
                    <a href="cerclesfederes.php">Cercles Fédérés</a>
                    <a href="regio.php">Régionales</a>
                    <a href="revue.php">Revue des Mines</a>
                    <a href="listingcomite.php">Listing des Comités</a>
                </div>
            </li>
            <li><a href="cerclesmons.php">Cercles Montois</a></li>
            <li class="nav-dropdown">
                <span class="nav-dropdown-label">Anecdotes Diverses <span class="dropdown-arrow">&#9660;</span></span>
                <div class="nav-dropdown-content">
                    <a href="blagueestudiantine.php">Blagues Estudiantines</a>
                    <a href="vieetudiante.php">Vie Étudiante</a>
                    <a href="autresanecdotes.php">Autres anecdotes</a>
                </div>
            </li>
        </ul>
        <a href="contact.php" class="navbar-cta">Contact</a>
    </div>
</nav>
<div class="navbar-spacer"></div>

<script>
    const burger = document.querySelector('.navbar-burger');
    const menu   = document.querySelector('.navbar-menu');
    burger.addEventListener('click', () => menu.classList.toggle('open'));

    document.querySelectorAll('.nav-dropdown-label').forEach(label => {
        label.addEventListener('click', (e) => {
            e.stopPropagation();
            label.parentElement.classList.toggle('open');
        });
    });
    const navbar = document.querySelector('.navbar');
    const majNavbar = () => navbar.classList.toggle('is-scrolled', window.scrollY > 40);
    majNavbar();
    window.addEventListener('scroll', majNavbar, { passive: true });
</script>
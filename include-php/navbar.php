<style>
    /** ------- NAVBAR --------- **/

    .navbar {
        position: fixed;
        width: 100%;
        z-index: 9;

        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: var(--main-color);
        color: #eee;

        border-bottom: 3px solid var(--derive-main-color);
    }

    .navbar-brand {
        height: 4.5rem;
        margin: .5rem;
        padding: .5rem;
    }

    .navbar-brand:hover {
        background-color : var(--derive-main-color);
        border-radius: 0.5em;
    }

    .navbar-links ul {
        margin-left: 0;
        padding-left: 5vw;
        display: flex;
    }

    .navbar-links li{
        list-style: none;
    }

    .navbar-links li a {
        text-decoration: none;
        color: var(--text-color);
        padding: 1rem;
        display: block;

        font-family: 'Roboto', serif;
        font-weight: bold;
        font-size: x-large;
    }

    .navbar-links li:hover{
        background-color: var(--derive-main-color);
        border-radius: 0.5em;
    }

    .navbar-camenbert{
        position:absolute;
        top: 1rem;
        right: 1rem;
        display: none;
        flex-direction: column;
        justify-content: space-between;
        width: 4rem;
        height: 4rem;
        padding: .3rem;
        align-items: center;
    }

    .navbar-camenbert:hover{
        background-color: var(--derive-main-color);
        border-radius: 0.5em;
    }

    .navbar-camenbert .bar {
        height: .5rem;
        width: 90%;
        background-color: var(--text-color);
        border-radius: 10px;
    }

    @media (max-width: 825px) {
        .navbar-camenbert {
            display: flex;
        }

        .navbar-links {
            display: none;
            width: 100%;
        }

        .navbar-links ul {
            flex-direction: column;
            width: 100%;
        }

        .navbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar-links li {
            /*text-align: center;*/
        }

        .navbar-brand {
        }

        .navbar-links li a {
            padding: .5rem 1rem;
        }

        .navbar-links.active {
            display: flex;
        }
    }

    /* Ajoutez du CSS pour masquer initialement le dropdown */
    .dropdown-toggle .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Ajoutez du CSS pour styliser le dropdown */
    .dropdown-toggle .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-toggle .horizontal-display a {
        padding-right: 5px;
    }

    .dropdown-toggle .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown-arrow {
        transform: translateY(15%);
        font-size: 0.8em; /* Ajustez la taille du texte selon vos besoins */
    }

</style>

<nav class="navbar">
    <div class="navbar-brand ">
        <a href="index.php"><img src="/image/logo.png" style="height:100%;" alt="logo"></a>
    </div>
    <a href="#" class="navbar-camenbert">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </a>
    <div class="navbar-links">
        <ul>
            <li><a href ="index.php">Accueil</a></li>
            <li><a href ="fac.php">La Faculté</a></li>
            <li class="dropdown-toggle">
                <a>
                    <div class="horizontal-display">
                        Cercle F.P.Ms <div style="width: 5px"></div>
                        <div class="dropdown-arrow">&#9660;</div> <!-- Triangle vers le bas -->
                    </div>
                </a>
                <div class="dropdown-content" style="display: none;">
                    <a href="fedefetes.php">Fédé et Fêtes</a>
                    <a href="cerclesfederes.php">Cercles Fédérés</a>
                    <a href="regio.php">Régionales</a>
                    <a href="revue.php">Revue des Mines</a>
                    <a href="listingcomite.php">Listing des Comités</a>
                    <!-- <a href="#">Commissions</a> -->
                    <!-- Ajoutez d'autres liens du dropdown ici -->
                </div>
            </li>
            <li><a href ="cerclesmons.php">Cercles Montois</a></li>
            <li class="dropdown-toggle">
                <a>
                    <div class="horizontal-display" >
                        Anecdotes Diverses <div style="width: 5px"></div>
                        <div class="dropdown-arrow">&#9660;</div> <!-- Triangle vers le bas -->
                    </div>
                </a>
                <div class="dropdown-content" style="display: none;">
                    <a href="blagueestudiantine.php">Blagues Estudiantines</a>
                    <a href="vieetudiante.php">Vie Étudiante</a>
                    <a href="autresannecdotes.php">Autres Annecdotes</a>
                    <!-- Ajoutez d'autres liens du dropdown ici -->
                </div>
            </li>
            <li><a href ="contact.php">Contact</a></li>
            <div style="width: 2rem"></div>
        </ul>
    </div>
</nav>
<div style="height: 6rem"></div>

<script>
    const toggleButton = document.getElementsByClassName("navbar-camenbert")[0];
    const navbarLinks = document.getElementsByClassName("navbar-links")[0];
    const dropdowns = document.querySelectorAll('.dropdown-toggle');

    toggleButton.addEventListener('click', () => {
        navbarLinks.classList.toggle('active');
    });

    // Ajoutez ces événements pour gérer les dropdowns au survol
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('mouseenter', () => {
            dropdown.querySelector('.dropdown-content').style.display = 'block';
        });

        dropdown.addEventListener('mouseleave', () => {
            dropdown.querySelector('.dropdown-content').style.display = 'none';
        });

        dropdown.addEventListener('click', () => {
            const dropdownContent = dropdown.querySelector('.dropdown-content');
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
    });
</script>


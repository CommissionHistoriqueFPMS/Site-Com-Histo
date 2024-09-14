<?php
$title ="Listing des Doyens";
$id = "listing-doyens";
baseArticle($title,$id);
?>

<?php
generateTable(["Année", "Nom", "Titre"],
    [
        ["2022-today", "Véronique Felheim", "Doyenne"],
        ["2018-2022", "Christine Renotte", "Doyenne"],
        ["2014-2018", "Pierre Dehombreux", "Doyen"],
        ["2009-2014", "Paul Lybaert", "Doyen"],
        ["2006-2009", "Calogero Conti", "Recteur"],
        ["1994-2006", "Serge Boucher", "Recteur"],
        ["1986-1994", "Christian Bouquegneau", "Recteur"],
        ["1974-1986", "René Emile Baland", "Recteur"],
        ["1970-1974", "Jean Baland", "Recteur"],
        ["1954-1970", "Pierre Houzeau de Lehaie", "Recteur"],
        ["1952-1954", "Pierre Houzeau de Lehaie", "Administrateur"],
        ["1946-1952", "A.J. Jadot", "Administrateur"],
        ["1934-1946", "Jules Yernaux", "Administrateur-Directeur"],
        ["1923-1934", "Jules Yernaux", "Administrateur"],
        ["1918-1923", "Armand Halleux", "Administrateur"],
        ["1890-1918", "Auguste Macquet", "Directeur"],
        ["1888-1890", "M. Lambert", "Directeur Intérimaire"],
        ["1866-1888", "Adolphe Devillez", "Directeur"],
        ["1837-1865", "Adolphe Devillez", "Sans Titre"]
    ]
);
addSource("Historique UMONS - FPMs", "https://web.umons.ac.be/fpms/fr/a-propos-de-la-faculte/historique/");
?>

<!--<table>
    <thead>
    <tr>
        <th>Année</th>
        <th>Nom</th>
        <th>Titre</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>2022-today</td>
        <td>Véronique Felheim</td>
        <td>Doyenne</td>
    </tr>
    <tr>
        <td>2018-2022</td>
        <td>Christine Renote</td>
        <td>Doyenne</td>
    </tr>
    <tr>
        <td>2014-2018</td>
        <td>Pierre Dehombreux</td>
        <td>Doyen</td>
    </tr>
    <tr>
        <td>2009-2014</td>
        <td>Paul Lybaert</td>
        <td>Doyen</td>
    </tr>
    <tr>
        <td>2006-2009</td>
        <td>Calogero Conti</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1994-2006</td>
        <td>Serge Boucher</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1986-1994</td>
        <td>Chritisan Bouquehneau</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1974-1986</td>
        <td>René Emile Balend</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1970-1974</td>
        <td>Jean Baland</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1954-1970</td>
        <td>Pierre Houzeau de Lehaie</td>
        <td>Recteur</td>
    </tr>
    <tr>
        <td>1952-1954</td>
        <td>Pierre Houzeau de Lehaie</td>
        <td>Administrateur</td>
    </tr>
    <tr>
        <td>1946-1952</td>
        <td>A.J. Jadot</td>
        <td>Administrateur</td>
    </tr>
    <tr>
        <td>1934-1946</td>
        <td>Jules Yernaux</td>
        <td>Administrateur-Directeur</td>
    </tr>
    <tr>
        <td>1923-1934</td>
        <td>Jules Yernaux</td>
        <td>Administrateur</td>
    </tr>
    <tr>
        <td>1918-1923</td>
        <td>Armand Halleux</td>
        <td>Administrateur</td>
    </tr>
    <tr>
        <td>1890-1918</td>
        <td>Auguste Macquet</td>
        <td>Directeur</td>
    </tr>
    <tr>
        <td>1888-1890</td>
        <td>M. Lambert</td>
        <td>Directeur intérimaire</td>
    </tr>
    <tr>
        <td>1866-1888</td>
        <td>Adolphe Devillez</td>
        <td>Directeur</td>
    </tr>
    <tr>
        <td>1837-1865</td>
        <td>Adolphe Devillez</td>
        <td>Sans Titre</td>
    </tr>
    </tbody>
</table> -->
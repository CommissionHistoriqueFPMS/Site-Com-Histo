<?php
$title = "Listing interactif";
$id = "1listing-interactif";
baseArticle($title, $id);
?>

<!-- Fix "Flicker" barre de Recherche -->
<style>
.horizontal-display {
    flex: 1;
    display: flex;
    flex-direction: row;
}
.sidebar {
    flex: 0 0 310px;
}
.content {
    flex: 1 0 0;
    min-width: 0;
}
</style>

<p class="lc-sub" id="lc-sub">Chargement…</p>

<div class="lc-tabs">
    <button class="lc-tab lc-hidden" id="lc-tbrowse">Parcourir par cercle</button>
    <button class="lc-tab lc-hidden" id="lc-tsearch">Rechercher une personne</button>
</div>

<div id="lc-browse" data-displaytab="lc-tbrowse" class="lc-hidden">
    <select class="lc-field" id="lc-pick"></select>
    <div class="lc-meta" id="lc-cmeta"></div>
    <div class="lc-scroll"><table class="lc-table" id="lc-table"></table></div>
</div>

<div id="lc-search" data-displaytab="lc-tsearch" class="lc-hidden">
    <input class="lc-field" id="lc-q" data-displayfocus="lc-tsearch" type="text" placeholder="Tapez un nom (ex. Musin, Rudodo…)" autocomplete="off">
    <div id="lc-results"><p class="lc-hint">Commencez à taper pour retrouver quelqu'un à travers tous les comités.</p></div>
</div>
<?php include('include-php/suggestion-listing.php'); ?>
<script src="/javascript/listing.js"></script>
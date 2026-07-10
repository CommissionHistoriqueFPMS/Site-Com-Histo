<?php
$title = "Listing interactif";
$id = "1listing-interactif";
baseArticle($title, $id);
?>

<p class="lc-sub" id="lc-sub">Chargement…</p>

<div class="lc-tabs">
    <button class="lc-tab on" id="lc-tbrowse">Parcourir par cercle</button>
    <button class="lc-tab" id="lc-tsearch">Rechercher une personne</button>
</div>

<div id="lc-browse">
    <select class="lc-field" id="lc-pick"></select>
    <div class="lc-meta" id="lc-cmeta"></div>
    <div class="lc-scroll"><table class="lc-table" id="lc-table"></table></div>
</div>

<div id="lc-search" class="lc-hidden">
    <input class="lc-field" id="lc-q" type="text" placeholder="Tapez un nom (ex. Musin, Rudodo…)" autocomplete="off">
    <div id="lc-results"><p class="lc-hint">Commencez à taper pour retrouver quelqu'un à travers tous les comités.</p></div>
</div>

<script src="/javascript/listing.js"></script>
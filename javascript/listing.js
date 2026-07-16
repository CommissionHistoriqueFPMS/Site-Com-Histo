function updateLcTabState(def="#lc-tbrowse") {
    const state = (document.location.hash || def).slice(1);
    const tabs = document.querySelectorAll(".lc-tab");
    
    for (const tab of tabs) {
        const display = state === tab.id;
        
        tab.classList.remove("lc-hidden");
        if (display) { tab.classList.add("on") }
        else { tab.classList.remove("on") }
        
        const elements = document.querySelectorAll(`[data-displaytab="${tab.id}"]`);
        
        for (const element of elements) {
            if (display) { element.classList.remove("lc-hidden"); }
            else { element.classList.add("lc-hidden"); }
        }

        if (display) { 
            const foc = document.querySelector(`[data-displayfocus="${tab.id}"]`); 
            if(foc) foc.focus(); 
        }
    }

    
}

(()=> {
    document.querySelectorAll(".lc-tab").forEach((e) => {
        e.onclick = ()=> {
            document.location.hash = e.id;
            updateLcTabState();
            document.getElementById("lc-q").focus();
            window.scrollTo({behavior:"instant",top: document.querySelector(".container").offsetTop-200})
        }
    });
})();

(function () {
    const norm = s => s.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    const esc  = s => (s || "").replace(/[&<>"]/g, c =>
        ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]));

    fetch("/articles/listingcomite/listing.json")
        .then(r => r.json())
        .then(init)
        .catch(() => {
            document.getElementById("lc-sub").textContent =
                "Impossible de charger les données du listing.";
        });

    function init(DATA) {
        updateLcTabState();

        const nC = Object.keys(DATA).length;
        const nM = Object.values(DATA).reduce((s, e) => s + e.recs.length, 0);
        document.getElementById("lc-sub").innerHTML =
            nC + " cercles &amp; organismes · " + nM + " mandats répertoriés · archives depuis 1898";

        /* ---- Parcourir par cercle ---- */
        const pick = document.getElementById("lc-pick");
        Object.keys(DATA).sort((a, b) => a.localeCompare(b)).forEach(name => {
            const o = document.createElement("option");
            o.value = name; o.textContent = name; pick.appendChild(o);
        });

        function renderTable(name) {
            const e = DATA[name], roles = [];
            e.recs.forEach(r => Object.keys(r.r).forEach(k => { if (!roles.includes(k)) roles.push(k); }));
            let h = "<thead><tr><th>N°</th><th>Année</th>" +
                roles.map(r => "<th>" + esc(r) + "</th>").join("") + "</tr></thead><tbody>";
            e.recs.forEach(r => {
                h += "<tr><td class='y'>" + esc(r.n) + "</td><td class='y'>" + esc(r.a) + "</td>" +
                    roles.map(role => "<td>" + esc(r.r[role] || "") + "</td>").join("") + "</tr>";
            });
            document.getElementById("lc-table").innerHTML = h + "</tbody>";
            document.getElementById("lc-cmeta").textContent =
                e.recs.length + " mandats" + (e.c ? " · création " + e.c : "");
        }
        pick.addEventListener("change", () => renderTable(pick.value));
        renderTable(pick.value);

        /* ---- Index des personnes ---- */
        const index = {};
        for (const [ename, e] of Object.entries(DATA)) {
            e.recs.forEach(r => {
                for (const [role, people] of Object.entries(r.r)) {
                    // Evite d'indexer "Thème(s)" comme étant une personne
                    if (role === "Thème(s)") continue; 

                    people.split(/[+\/,]| et /).forEach(p => {
                        p = p.replace(/\(.*?\)/g, "").trim();
                        if (p.length > 3) (index[p] = index[p] || []).push({ c: ename, role, a: r.a });
                    });
                }
            });
        }
        const names = Object.keys(index);
        const q = document.getElementById("lc-q"), res = document.getElementById("lc-results");
        q.addEventListener("input", () => {
            const v = norm(q.value.trim());
            if (v.length < 2) { res.innerHTML = "<p class='lc-hint'>Tapez au moins 2 lettres.</p>"; return; }
            const hits = names.filter(n => norm(n).includes(v)).sort((a, b) => a.localeCompare(b));
            if (!hits.length) { res.innerHTML = "<p class='lc-hint'>Aucun résultat.</p>"; return; }
            res.innerHTML = hits.slice(0, 40).map(n => {
                const rows = index[n].sort((a, b) => b.a.localeCompare(a.a));
                return "<div class='lc-card'><h3>" + esc(n) + "</h3>" + rows.map(x =>
                    "<div class='lc-line'><span class='lc-role'>" + esc(x.role) +
                    "</span> — " + esc(x.c) + " <em>(" + esc(x.a) + ")</em></div>"
                ).join("") + "</div>";
            }).join("");
        });

    }
})();

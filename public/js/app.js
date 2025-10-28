/**
 * Application JavaScript - Takicom V2
 */

// Utilitaires
const App = {
  /**
   * Formater un nombre en devise DT
   */
  formatMoney: function (amount, decimals = 3) {
    const num = parseFloat(amount);
    if (isNaN(num)) return "0.000 DT";
    return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " DT";
  },

  /**
   * Calculer les montants d'une ligne
   */
  calculerLigne: function (quantite, prixUnitaire, tauxTva, tauxRemise = 0) {
    const montantBrut = quantite * prixUnitaire;
    const montantRemise = montantBrut * (tauxRemise / 100);
    const montantHT = montantBrut - montantRemise;
    const montantTVA = montantHT * (tauxTva / 100);
    const montantTTC = montantHT + montantTVA;

    return {
      montant_remise: parseFloat(montantRemise.toFixed(3)),
      montant_ht: parseFloat(montantHT.toFixed(3)),
      montant_tva: parseFloat(montantTVA.toFixed(3)),
      montant_ttc: parseFloat(montantTTC.toFixed(3)),
    };
  },

  /**
   * Afficher une notification
   */
  notify: function (message, type = "info") {
    const alert = document.createElement("div");
    alert.className = `alert alert-${type} fade-in`;
    alert.innerHTML = message;

    const container =
      document.querySelector(".flash-messages") ||
      document.querySelector(".content-body");
    if (container) {
      container.insertBefore(alert, container.firstChild);

      setTimeout(() => {
        alert.style.transition = "opacity 0.5s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500);
      }, 5000);
    }
  },

  /**
   * Confirmation de suppression
   */
  confirmDelete: function (
    message = "Êtes-vous sûr de vouloir supprimer cet élément ?"
  ) {
    return confirm(message);
  },

  /**
   * Requête AJAX
   */
  ajax: function (url, options = {}) {
    const defaultOptions = {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    };

    return fetch(url, { ...defaultOptions, ...options }).then((response) => {
      if (!response.ok) {
        throw new Error("Erreur réseau");
      }
      return response.json();
    });
  },
};

// Autocomplete pour la recherche
class Autocomplete {
  constructor(inputElement, options = {}) {
    this.input = inputElement;
    this.options = {
      minLength: 2,
      delay: 300,
      source: null,
      onSelect: null,
      ...options,
    };

    this.timeout = null;
    this.resultsList = null;

    this.init();
  }

  init() {
    // Créer la liste de résultats
    this.resultsList = document.createElement("ul");
    this.resultsList.className = "autocomplete-results";
    this.resultsList.style.cssText = `
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ced4da;
            border-top: none;
            max-height: 300px;
            overflow-y: auto;
            list-style: none;
            margin: 0;
            padding: 0;
            width: ${this.input.offsetWidth}px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: none;
        `;

    this.input.parentNode.style.position = "relative";
    this.input.parentNode.appendChild(this.resultsList);

    // Événements
    this.input.addEventListener("input", (e) => this.handleInput(e));
    this.input.addEventListener("keydown", (e) => this.handleKeydown(e));
    document.addEventListener("click", (e) => {
      if (e.target !== this.input) {
        this.hideResults();
      }
    });
  }

  handleInput(e) {
    const value = e.target.value.trim();

    clearTimeout(this.timeout);

    if (value.length < this.options.minLength) {
      this.hideResults();
      return;
    }

    this.timeout = setTimeout(() => {
      this.search(value);
    }, this.options.delay);
  }

  async search(term) {
    if (typeof this.options.source === "function") {
      const results = await this.options.source(term);
      this.showResults(results);
    } else if (typeof this.options.source === "string") {
      const url = `${this.options.source}?term=${encodeURIComponent(term)}`;
      App.ajax(url)
        .then((data) => this.showResults(data.results || []))
        .catch((err) => console.error("Erreur autocomplete:", err));
    }
  }

  showResults(results) {
    this.resultsList.innerHTML = "";

    if (results.length === 0) {
      const li = document.createElement("li");
      li.textContent = "Aucun résultat";
      li.style.padding = "10px";
      li.style.color = "#6c757d";
      this.resultsList.appendChild(li);
    } else {
      results.forEach((item) => {
        const li = document.createElement("li");
        li.textContent = item.label || item.name || item.designation;
        li.style.cssText =
          "padding: 10px; cursor: pointer; border-bottom: 1px solid #e9ecef;";
        li.dataset.item = JSON.stringify(item);

        li.addEventListener("mouseenter", () => {
          li.style.background = "#f8f9fa";
        });
        li.addEventListener("mouseleave", () => {
          li.style.background = "white";
        });
        li.addEventListener("click", () => {
          this.selectItem(item);
        });

        this.resultsList.appendChild(li);
      });
    }

    this.resultsList.style.display = "block";
  }

  hideResults() {
    this.resultsList.style.display = "none";
  }

  selectItem(item) {
    if (this.options.onSelect) {
      this.options.onSelect(item);
    }
    this.hideResults();
  }

  handleKeydown(e) {
    const items = this.resultsList.querySelectorAll("li[data-item]");
    const current = this.resultsList.querySelector("li.active");

    if (e.key === "ArrowDown") {
      e.preventDefault();
      if (!current) {
        items[0]?.classList.add("active");
      } else {
        current.classList.remove("active");
        const next = current.nextElementSibling;
        if (next) next.classList.add("active");
      }
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      if (current) {
        current.classList.remove("active");
        const prev = current.previousElementSibling;
        if (prev) prev.classList.add("active");
      }
    } else if (e.key === "Enter") {
      e.preventDefault();
      if (current) {
        const item = JSON.parse(current.dataset.item);
        this.selectItem(item);
      }
    } else if (e.key === "Escape") {
      this.hideResults();
    }
  }
}

// Gestionnaire de lignes de facture/devis
class LignesManager {
  constructor(tableId, options = {}) {
    this.table = document.getElementById(tableId);
    this.tbody = this.table?.querySelector("tbody");
    this.options = {
      tauxTvaDefaut: 19,
      ...options,
    };

    this.lignes = [];
    this.init();
  }

  init() {
    // Bouton ajouter ligne
    const btnAdd = document.getElementById("btnAjouterLigne");
    if (btnAdd) {
      btnAdd.addEventListener("click", () => this.ajouterLigne());
    }

    // Charger les lignes existantes si modification
    this.chargerLignesExistantes();
  }

  ajouterLigne(data = {}) {
    const ligne = {
      id: Date.now(),
      id_article: data.id_article || "",
      reference: data.reference || "",
      designation: data.designation || "",
      quantite: data.quantite || 1,
      prix_unitaire_ht: data.prix_unitaire_ht || 0,
      taux_tva: data.taux_tva || this.options.tauxTvaDefaut,
      taux_remise: data.taux_remise || 0,
      montant_ht: 0,
      montant_tva: 0,
      montant_remise: 0,
      montant_ttc: 0,
    };

    this.calculerMontantsLigne(ligne);
    this.lignes.push(ligne);
    this.render();
    this.calculerTotaux();
  }

  supprimerLigne(id) {
    if (App.confirmDelete("Supprimer cette ligne ?")) {
      this.lignes = this.lignes.filter((l) => l.id !== id);
      this.render();
      this.calculerTotaux();
    }
  }

  modifierLigne(id, field, value) {
    const ligne = this.lignes.find((l) => l.id === id);
    if (ligne) {
      ligne[field] = parseFloat(value) || 0;
      this.calculerMontantsLigne(ligne);
      this.render();
      this.calculerTotaux();
    }
  }

  calculerMontantsLigne(ligne) {
    const montants = App.calculerLigne(
      ligne.quantite,
      ligne.prix_unitaire_ht,
      ligne.taux_tva,
      ligne.taux_remise
    );

    Object.assign(ligne, montants);
  }

  render() {
    if (!this.tbody) return;

    this.tbody.innerHTML = "";

    if (this.lignes.length === 0) {
      const tr = document.createElement("tr");
      tr.innerHTML =
        '<td colspan="9" class="text-center text-muted">Aucune ligne ajoutée</td>';
      this.tbody.appendChild(tr);
      return;
    }

    this.lignes.forEach((ligne, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${ligne.reference}</td>
                <td>${ligne.designation}</td>
                <td><input type="number" class="form-control form-control-sm" value="${
                  ligne.quantite
                }" step="0.001" onchange="lignesManager.modifierLigne(${
        ligne.id
      }, 'quantite', this.value)"></td>
                <td><input type="number" class="form-control form-control-sm" value="${
                  ligne.prix_unitaire_ht
                }" step="0.001" onchange="lignesManager.modifierLigne(${
        ligne.id
      }, 'prix_unitaire_ht', this.value)"></td>
                <td><input type="number" class="form-control form-control-sm" value="${
                  ligne.taux_tva
                }" step="0.01" onchange="lignesManager.modifierLigne(${
        ligne.id
      }, 'taux_tva', this.value)"></td>
                <td><input type="number" class="form-control form-control-sm" value="${
                  ligne.taux_remise
                }" step="0.01" onchange="lignesManager.modifierLigne(${
        ligne.id
      }, 'taux_remise', this.value)"></td>
                <td class="text-right">${App.formatMoney(
                  ligne.montant_ttc
                )}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="lignesManager.supprimerLigne(${
                      ligne.id
                    })">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
      this.tbody.appendChild(tr);
    });
  }

  calculerTotaux() {
    const totaux = this.lignes.reduce(
      (acc, ligne) => {
        acc.ht += ligne.montant_ht;
        acc.tva += ligne.montant_tva;
        acc.remise += ligne.montant_remise;
        acc.ttc += ligne.montant_ttc;
        return acc;
      },
      { ht: 0, tva: 0, remise: 0, ttc: 0 }
    );

    // Mettre à jour les champs
    document.getElementById("montant_ht").value = totaux.ht.toFixed(3);
    document.getElementById("montant_tva").value = totaux.tva.toFixed(3);
    document.getElementById("total_remise").value = totaux.remise.toFixed(3);
    document.getElementById("montant_ttc").value = totaux.ttc.toFixed(3);

    // Affichage
    document.getElementById("display_ht").textContent = App.formatMoney(
      totaux.ht
    );
    document.getElementById("display_tva").textContent = App.formatMoney(
      totaux.tva
    );
    document.getElementById("display_remise").textContent = App.formatMoney(
      totaux.remise
    );
    document.getElementById("display_ttc").textContent = App.formatMoney(
      totaux.ttc
    );
  }

  chargerLignesExistantes() {
    // À implémenter pour la modification
  }

  getLignesJSON() {
    return JSON.stringify(
      this.lignes.map((l) => ({
        id_article: l.id_article,
        designation: l.designation,
        quantite: l.quantite,
        prix_unitaire_ht: l.prix_unitaire_ht,
        taux_tva: l.taux_tva,
        taux_remise: l.taux_remise,
        montant_ht: l.montant_ht,
        montant_tva: l.montant_tva,
        montant_remise: l.montant_remise,
        montant_ttc: l.montant_ttc,
      }))
    );
  }
}

// Export global
window.App = App;
window.Autocomplete = Autocomplete;
window.LignesManager = LignesManager;

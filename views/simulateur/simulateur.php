<?php
$api_url = "http://localhost/S4_WEB/Final_S4/Final_S4_Web/ws";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Simulateur de Prêt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .simulator-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #0056b3;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            font-size: 1.1em;
            display: block;
            margin-bottom: 10px;
        }

        .label-inline {
            display: flex;
            align-items: center;
            font-size: 1em;
        }

        input[type="range"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        select:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.3);
        }

        input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .value-display {
            font-weight: bold;
            color: #0056b3;
        }

        #results {
            margin-top: 30px;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            display: none;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-item strong {
            font-size: 1.2em;
            color: #dc3545;
        }

        .error {
            color: #dc3545;
            text-align: center;
            font-weight: bold;
            padding: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="simulator-container">
        <h1>Simulateur de Prêt</h1>

        <div class="form-group">
            <label for="type_pret">Type de prêt</label>
            <select id="type_pret" name="type_pret">
                <option value="">Sélectionnez un type de prêt</option>
            </select>
        </div>

        <div class="form-group">
            <label for="taux_pret">Taux de prêt</label>
            <select id="taux_pret" name="taux_pret" disabled>
                <option value="">Sélectionnez d'abord un type de prêt</option>
            </select>
        </div>

        <div class="form-group">
            <label for="montant">Montant à emprunter (€)</label>
            <input type="number" id="montant" min="1000" max="500000" step="100" value="50000">
        </div>

        <div class="form-group">
            <label for="duree">Durée du prêt : <span id="duree-val" class="value-display">120 mois</span></label>
            <input type="range" id="duree" min="1" max="300" step="1" value="120">
        </div>

        <div class="form-group">
            <label for="include_assurance" class="label-inline">
                <input type="checkbox" id="include_assurance">
                Inclure l'assurance emprunteur
            </label>
        </div>

        <div id="results">
            <h2>Votre simulation</h2>
            <div class="result-item" id="row-taux-assurance"><span>Taux d'assurance annuel</span> <span id="res-taux-assurance"></span></div>
            <div class="result-item" id="row-mensualite-assurance"><span>Coût mensuel de l'assurance</span> <span id="res-mensualite-assurance"></span></div>
            <hr>
            <div class="result-item"><span>Taux d'intérêt annuel</span> <span id="res-taux-interet"></span></div>
            <div class="result-item"><span>Coût total du crédit</span> <span id="res-cout-total"></span></div>
            <div class="result-item"><strong>Votre mensualité (assurance incluse)</strong> <strong><span id="res-mensualite-totale"></span></strong></div>
        </div>
        <div id="error-box" class="error"></div>
    </div>

    <script>
        const apiBase = "<?= $api_url ?>";

        // Récupérer les éléments du DOM
        const typePretSelect = document.getElementById('type_pret');
        const tauxPretSelect = document.getElementById('taux_pret');
        const montantInput = document.getElementById('montant');
        const dureeSlider = document.getElementById('duree');
        const assuranceCheckbox = document.getElementById('include_assurance');
        const dureeVal = document.getElementById('duree-val');
        const resultsDiv = document.getElementById('results');
        const errorBox = document.getElementById('error-box');

        // Mettre à jour les labels
        function updateLabels() {
            dureeVal.textContent = `${dureeSlider.value} mois (${(dureeSlider.value / 12).toFixed(1)} ans)`;
        }

        // Charger les types de prêts
        function loadTypePret() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", apiBase + "/type_pret", true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const types = JSON.parse(xhr.responseText);
                    typePretSelect.innerHTML = '<option value="">Sélectionnez un type de prêt</option>';
                    types.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.libelle;
                        typePretSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }

        // Charger les taux de prêt en fonction du type de prêt, montant et durée
        function loadTauxPret() {
            const type_pret = typePretSelect.value;
            const montant = montantInput.value;
            const duree_mois = dureeSlider.value;

            if (!type_pret) {
                tauxPretSelect.innerHTML = '<option value="">Sélectionnez d\'abord un type de prêt</option>';
                tauxPretSelect.disabled = true;
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `${apiBase}/taux_pret?id_type_pret=${type_pret}&montant=${montant}&duree_mois=${duree_mois}`, true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const taux = JSON.parse(xhr.responseText);
                        tauxPretSelect.innerHTML = '<option value="">Sélectionnez un taux de prêt</option>';
                        if (taux.length === 0) {
                            tauxPretSelect.innerHTML = '<option value="">Aucun taux disponible</option>';
                            tauxPretSelect.disabled = true;
                        } else {
                            taux.forEach(t => {
                                const option = document.createElement('option');
                                option.value = t.id;
                                option.textContent = `${t.taux_annuel}% (Durée: ${t.duree} mois, ${t.borne_inf}€ - ${t.borne_sup}€)`;
                                tauxPretSelect.appendChild(option);
                            });
                            tauxPretSelect.disabled = false;
                        }
                    } else {
                        tauxPretSelect.innerHTML = '<option value="">Erreur lors du chargement des taux</option>';
                        tauxPretSelect.disabled = true;
                    }
                }
            };
            xhr.send();
        }

        // Lancer la simulation
        function runSimulation() {
            const id_taux_pret = tauxPretSelect.value;
            const montant = montantInput.value;
            const duree_mois = dureeSlider.value;
            const include_assurance = assuranceCheckbox.checked;

            if (!id_taux_pret) {
                displayError("Veuillez sélectionner un taux de prêt.");
                return;
            }

            const data = `id_taux_pret=${id_taux_pret}&montant=${montant}&duree_mois=${duree_mois}&include_assurance=${include_assurance}`;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", apiBase + "/simulateur/calculer", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    resultsDiv.style.display = 'none';
                    errorBox.style.display = 'none';
                    if (xhr.status === 200) {
                        displayResults(JSON.parse(xhr.responseText));
                    } else {
                        displayError(JSON.parse(xhr.responseText).error || "Une erreur est survenue.");
                    }
                }
            };
            xhr.send(data);
        }

        function displayResults(data) {
            const showAssurance = data.taux_assurance_annuel > 0;
            document.getElementById('row-taux-assurance').style.display = showAssurance ? 'flex' : 'none';
            document.getElementById('row-mensualite-assurance').style.display = showAssurance ? 'flex' : 'none';

            document.getElementById('res-taux-interet').textContent = `${data.taux_interet_annuel.toFixed(2)} %`;
            document.getElementById('res-taux-assurance').textContent = `${data.taux_assurance_annuel.toFixed(2)} %`;
            document.getElementById('res-mensualite-assurance').textContent = `${data.mensualite_assurance.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-cout-total').textContent = `${data.cout_total_credit.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-mensualite-totale').textContent = `${data.mensualite_totale.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;

            resultsDiv.style.display = 'block';
        }

        function displayError(message) {
            errorBox.textContent = message;
            errorBox.style.display = 'block';
        }

        // Attacher les écouteurs d'événements
        typePretSelect.addEventListener('change', loadTauxPret);
        montantInput.addEventListener('input', loadTauxPret);
        dureeSlider.addEventListener('input', () => {
            updateLabels();
            loadTauxPret();
        });
        tauxPretSelect.addEventListener('change', runSimulation);
        assuranceCheckbox.addEventListener('change', runSimulation);

        // Lancement initial
        document.addEventListener('DOMContentLoaded', () => {
            updateLabels();
            loadTypePret();
        });
    </script>
</body>

</html>
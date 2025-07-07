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

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        #valider-pret,
        #export-pdf {
            padding: 10px 20px;
            background-color: #0056b3;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
        }

        #valider-pret:hover,
        #export-pdf:hover {
            background-color: #004494;
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
            <input type="number" id="montant" min="1000" max="999999.99" step="100" value="50000">
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
            <div class="result-item"><strong><span id="mensualite-label">Votre mensualité</span></strong> <strong><span id="res-mensualite-totale"></span></strong></div>
            <div class="button-group">
                <button id="valider-pret" style="display: none;">Valider le prêt</button>
                <button id="export-pdf" style="display: none;">Exporter en PDF</button>
            </div>
        </div>
        <div id="error-box" class="error"></div>
    </div>

    <script>
        const apiBase = "<?= $api_url ?>";

        // Récupérer les éléments du DOM
        const typeRessourceSelect = document.getElementById('type_ressource');
        const typePretSelect = document.getElementById('type_pret');
        const tauxPretSelect = document.getElementById('taux_pret');
        const montantInput = document.getElementById('montant');
        const dureeSlider = document.getElementById('duree');
        const assuranceCheckbox = document.getElementById('include_assurance');
        const dureeVal = document.getElementById('duree-val');
        const resultsDiv = document.getElementById('results');
        const errorBox = document.getElementById('error-box');
        const mensualiteLabel = document.getElementById('mensualite-label');
        const validerPretButton = document.getElementById('valider-pret');
        const exportPdfButton = document.getElementById('export-pdf');

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
                } else if (xhr.readyState === 4) {
                    displayError("Erreur lors du chargement des types de prêts.");
                }
            };
            xhr.send();
        }

        // Charger les taux de prêt en fonction du type de prêt
        function loadTauxPret() {
            const type_pret = typePretSelect.value;

            if (!type_pret) {
                tauxPretSelect.innerHTML = '<option value="">Sélectionnez d\'abord un type de prêt</option>';
                tauxPretSelect.disabled = true;
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `${apiBase}/taux_pret?id_type_pret=${type_pret}`, true);
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
                        displayError("Erreur lors du chargement des taux de prêt.");
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

            console.log('runSimulation - include_assurance:', include_assurance); // Débogage

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
                    validerPretButton.style.display = 'none';
                    exportPdfButton.style.display = 'none';
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            console.log('API Response:', data); // Débogage
                            if (data.error) {
                                displayError(data.error);
                            } else {
                                displayResults(data, include_assurance);
                                validerPretButton.style.display = 'block';
                                exportPdfButton.style.display = 'block';
                            }
                        } catch (e) {
                            displayError("Erreur lors du traitement de la réponse de la simulation.");
                        }
                    } else {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            displayError(errorData.error || "Une erreur est survenue lors de la simulation.");
                        } catch (e) {
                            displayError("Erreur lors de la simulation : réponse invalide du serveur.");
                        }
                    }
                }
            };
            xhr.send(data);
        }

        // Valider le prêt
        function validerPret() {
            const id_taux_pret = tauxPretSelect.value;
            const montant = montantInput.value;
            const duree_mois = dureeSlider.value;
            const include_assurance = assuranceCheckbox.checked;

            if (!id_taux_pret) {
                displayError("Veuillez sélectionner un taux de prêt avant de valider.");
                return;
            }

            const data = `id_taux_pret=${id_taux_pret}&montant=${montant}&duree_mois=${duree_mois}&include_assurance=${include_assurance}`;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", apiBase + "/simulateur/valider", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    errorBox.style.display = 'none';
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert("Prêt validé avec succès ! ID du prêt : " + response.pret_id);
                                resultsDiv.style.display = 'none';
                                validerPretButton.style.display = 'none';
                                exportPdfButton.style.display = 'none';
                            } else {
                                displayError(response.error || "Erreur lors de la validation du prêt.");
                            }
                        } catch (e) {
                            displayError("Erreur lors du traitement de la réponse de validation.");
                        }
                    } else {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            displayError(errorData.error || "Une erreur est survenue lors de la validation.");
                        } catch (e) {
                            displayError("Erreur lors de la validation : réponse invalide du serveur.");
                        }
                    }
                }
            };
            xhr.send(data);
        }

        // Exporter la simulation en PDF
        function exportPDF() {
            const id_taux_pret = tauxPretSelect.value;
            const montant = montantInput.value;
            const duree_mois = dureeSlider.value;
            const include_assurance = assuranceCheckbox.checked;

            if (!id_taux_pret) {
                displayError("Veuillez sélectionner un taux de prêt avant d'exporter.");
                return;
            }
            if (!id_type_ressource) {
                displayError("Veuillez sélectionner un type de ressource avant d'exporter.");
                return;
            }

            const data = `id_taux_pret=${id_taux_pret}&montant=${montant}&duree_mois=${duree_mois}&include_assurance=${include_assurance}&id_type_ressource=${id_type_ressource}`;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", apiBase + "/simulateur/export_pdf", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.responseType = 'blob'; // Attendre un fichier binaire (PDF)

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    errorBox.style.display = 'none';
                    if (xhr.status === 200) {
                        const blob = new Blob([xhr.response], {
                            type: 'application/pdf'
                        });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `simulation_pret_${new Date().getFullYear()}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    } else {
                        try {
                            xhr.responseText.then(text => {
                                const errorData = JSON.parse(text);
                                displayError(errorData.error || "Une erreur est survenue lors de l'exportation en PDF.");
                            });
                        } catch (e) {
                            displayError("Erreur lors de l'exportation : réponse invalide du serveur.");
                        }
                    }
                }
            };
            xhr.send(data);
        }

        function displayResults(data, include_assurance) {
            const taux_interet_annuel = parseFloat(data.taux_interet_annuel) || 0;
            const taux_assurance_annuel = parseFloat(data.taux_assurance_annuel) || 0;
            const mensualite_assurance = parseFloat(data.mensualite_assurance) || 0;
            const mensualite_totale = parseFloat(data.mensualite_totale) || 0;
            const cout_total_credit = parseFloat(data.cout_total_credit) || 0;

            console.log('displayResults - include_assurance:', include_assurance); // Débogage
            console.log('displayResults - taux_assurance_annuel:', taux_assurance_annuel); // Débogage

            const showAssurance = include_assurance && taux_assurance_annuel > 0;
            document.getElementById('row-taux-assurance').style.display = showAssurance ? 'flex' : 'none';
            document.getElementById('row-mensualite-assurance').style.display = showAssurance ? 'flex' : 'none';

            mensualiteLabel.textContent = include_assurance ? 'Votre mensualité (assurance incluse)' : 'Votre mensualité';

            document.getElementById('res-taux-interet').textContent = `${taux_interet_annuel.toFixed(2)} %`;
            document.getElementById('res-taux-assurance').textContent = `${taux_assurance_annuel.toFixed(2)} %`;
            document.getElementById('res-mensualite-assurance').textContent = `${mensualite_assurance.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-cout-total').textContent = `${cout_total_credit.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-mensualite-totale').textContent = `${mensualite_totale.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;

            resultsDiv.style.display = 'block';
        }

        function displayError(message) {
            errorBox.textContent = message;
            errorBox.style.display = 'block';
        }

        // Attacher les écouteurs d'événements
        typePretSelect.addEventListener('change', loadTauxPret);
        tauxPretSelect.addEventListener('change', runSimulation);
        montantInput.addEventListener('input', runSimulation);
        dureeSlider.addEventListener('input', updateLabels);
        dureeSlider.addEventListener('change', runSimulation);
        assuranceCheckbox.addEventListener('change', runSimulation);
        validerPretButton.addEventListener('click', validerPret);
        exportPdfButton.addEventListener('click', exportPDF);

        // Lancement initial
        document.addEventListener('DOMContentLoaded', () => {
            updateLabels();
            loadTypePret();
        });
    </script>
</body>

</html>
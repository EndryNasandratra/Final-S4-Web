<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Simulations - Banque</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        }

        .header {
            background: #2563eb;
            color: #fff;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            font-size: 1.2rem;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 56px);
        }

        .sidebar {
            background: #1e293b;
            color: #fff;
            width: 200px;
            padding: 1.5rem 1rem;
            box-sizing: border-box;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin-bottom: 1rem;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background: #2563eb;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            gap: 2rem;
        }

        .main-title {
            color: #2563eb;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .simulations-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th,
        td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
        }

        td input[type="checkbox"] {
            transform: scale(1.2);
        }

        .error-message {
            color: #dc2626;
            text-align: center;
            font-weight: bold;
            padding: 1rem;
            background: #fee2e2;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
        }

        .comparison-container {
            display: none;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }

        .comparison-table {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }

        .comparison-table div {
            padding: 0.8rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .comparison-table div.header {
            font-weight: bold;
            background: #2563eb;
            color: #fff;
            text-align: center;
        }

        .comparison-table div.label {
            font-weight: 600;
        }

        .comparison-table div.value {
            text-align: center;
        }

        .comparison-table div:last-child {
            border-bottom: none;
        }

        .save-form {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            display: none;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: 600;
            font-size: 1.1em;
            display: block;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        select,
        input[type="date"] {
            width: 100%;
            padding: 0.8rem;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }

        select:focus,
        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        button {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 500;
            margin: 0 auto;
            display: block;
        }

        button:hover {
            background: #1d4ed8;
        }

        button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.6rem;
            margin-right: 5px;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #e0e7ff;
        }

        @media (max-width: 768px) {
            .comparison-table {
                grid-template-columns: 1fr;
            }

            .simulations-container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">MNA_Banque</div> - Liste des Simulations
    </div>
    <div class="layout">
        <nav class="sidebar">
        <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="ajout_pret.php">Ajout de pret</a>
            <a href="simulateur_pret.php">Simulateur de pret</a>
            <a href="montant_dispo.php">Solde mensuel</a>
            <a href="formSimuler.php">Simulateur pour un apreçu de pret</a>
            <a href="comparerSimulation.php">Comparer les simulations enregistés</a>
            <a href="#">Deconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Liste des Simulations</h1>
            
            <div class="simulations-container">
                <div id="error-message" class="error-message"></div>
                
                <table id="simulations-table">
                    <thead>
                        <tr>
                            <th>Sélectionner</th>
                            <th>ID Simulation</th>
                            <th>Montant Emprunté (€)</th>
                            <th>Taux Prêt (%)</th>
                            <th>Durée (mois)</th>
                            <th>Taux Assurance (%)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button id="compare-button" disabled>Comparer les simulations</button>

                <div id="comparison-container" class="comparison-container">
                    <h2 style="color: #2563eb; margin-bottom: 1rem; text-align: center;">Comparaison des Simulations</h2>
                    <div id="comparison-table" class="comparison-table"></div>
                    <div id="save-form" class="save-form">
                        <h3 style="color: #2563eb; margin-bottom: 1rem;">Enregistrer le prêt</h3>
                        <div class="form-group">
                            <label for="selected-simulation">Simulation à enregistrer</label>
                            <select id="selected-simulation" name="selected-simulation">
                                <option value="">Sélectionnez une simulation</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="client">Client</label>
                            <select id="client" name="client">
                                <option value="">Sélectionnez un client</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date-pret">Date du prêt</label>
                            <input type="date" id="date-pret" name="date-pret" value="2025-07-08">
                        </div>
                        <button id="save-pret">Enregistrer le prêt</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const apiBase = "http://localhost/Final_S4_Web/ws";

        // Récupérer les éléments du DOM
        const simulationsTableBody = document.querySelector('#simulations-table tbody');
        const errorMessage = document.getElementById('error-message');
        const compareButton = document.getElementById('compare-button');
        const comparisonContainer = document.getElementById('comparison-container');
        const comparisonTable = document.getElementById('comparison-table');
        const saveForm = document.getElementById('save-form');
        const selectedSimulationSelect = document.getElementById('selected-simulation');
        const clientSelect = document.getElementById('client');
        const datePretInput = document.getElementById('date-pret');
        const savePretButton = document.getElementById('save-pret');

        function displayError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            comparisonContainer.style.display = 'none';
            compareButton.disabled = true;
            saveForm.style.display = 'none';
        }

        async function loadSimulations() {
            try {
                const response = await fetch(`${apiBase}/simulations`);
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    displayError(`Erreur lors du chargement des simulations: ${response.status} ${response.statusText}`);
                    return;
                }
                const simulations = await response.json();
                simulationsTableBody.innerHTML = '';
                if (simulations.length === 0) {
                    simulationsTableBody.innerHTML = '<tr><td colspan="6">Aucune simulation disponible</td></tr>';
                    compareButton.disabled = true;
                    return;
                }
                simulations.forEach(s => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><input type="checkbox" class="simulation-checkbox" value="${s.id}"></td>
                        <td>${s.id}</td>
                        <td>${parseFloat(s.montant_emprunte).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</td>
                        <td>${parseFloat(s.taux_pret).toFixed(2)}%</td>
                        <td>${s.duree}</td>
                        <td>${s.taux_assurance ? parseFloat(s.taux_assurance).toFixed(2) + '%' : 'Sans assurance'}</td>
                    `;
                    simulationsTableBody.appendChild(row);
                });
                attachCheckboxListeners();
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors du chargement des simulations: ' + error.message);
            }
        }

        async function loadClients() {
            try {
                const response = await fetch(`${apiBase}/allClients`);
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    displayError(`Erreur lors du chargement des clients: ${response.status} ${response.statusText}`);
                    return;
                }
                const clients = await response.json();
                clientSelect.innerHTML = '<option value="">Sélectionnez un client</option>';
                if (clients.length === 0) {
                    clientSelect.innerHTML = '<option value="">Aucun client disponible</option>';
                    clientSelect.disabled = true;
                } else {
                    clients.forEach(c => {
                        const option = document.createElement('option');
                        option.value = c.id;
                        option.textContent = c.nom;
                        clientSelect.appendChild(option);
                    });
                    clientSelect.disabled = false;
                }
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors du chargement des clients: ' + error.message);
            }
        }

        function attachCheckboxListeners() {
            const checkboxes = document.querySelectorAll('.simulation-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const selected = Array.from(checkboxes).filter(cb => cb.checked);
                    compareButton.disabled = selected.length !== 2;
                    saveForm.style.display = 'none';
                });
            });
        }

        async function compareSimulations() {
            const checkboxes = document.querySelectorAll('.simulation-checkbox:checked');
            if (checkboxes.length !== 2) {
                displayError('Veuillez sélectionner exactement deux simulations pour comparer.');
                return;
            }
            const ids = Array.from(checkboxes).map(cb => cb.value);
            try {
                const response = await fetch(`${apiBase}/simulations/compare`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ids: ids
                    })
                });
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    try {
                        const result = JSON.parse(text);
                        displayError('Erreur lors de la comparaison: ' + (result.error || 'Erreur inconnue'));
                    } catch (e) {
                        displayError(`Erreur serveur: ${response.status} ${response.statusText}`);
                    }
                    return;
                }
                const data = await response.json();
                if (data.error) {
                    displayError(data.error);
                    return;
                }
                displayComparison(data);
                populateSimulationSelect(data);
                saveForm.style.display = 'block';
                comparisonContainer.style.display = 'block';
                errorMessage.style.display = 'none';
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors de la comparaison: ' + error.message);
            }
        }

        // Remplir le sélecteur de simulations
        function populateSimulationSelect(data) {
            selectedSimulationSelect.innerHTML = '<option value="">Sélectionnez une simulation</option>';
            data.forEach(s => {
                const option = document.createElement('option');
                option.value = s.id;
                option.textContent = `Simulation ${s.id} (${parseFloat(s.montant_emprunte).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})})`;
                selectedSimulationSelect.appendChild(option);
            });
        }

        function displayComparison(data) {
            comparisonTable.innerHTML = `
                <div class="header">Critère</div>
                <div class="header">Simulation ${data[0].id}</div>
                <div class="header">Simulation ${data[1].id}</div>
                <div class="label">Montant Emprunté</div>
                <div class="value">${parseFloat(data[0].montant_emprunte).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
                <div class="value">${parseFloat(data[1].montant_emprunte).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
                <div class="label">Taux d'Intérêt Annuel</div>
                <div class="value">${parseFloat(data[0].taux_pret).toFixed(2)}%</div>
                <div class="value">${parseFloat(data[1].taux_pret).toFixed(2)}%</div>
                <div class="label">Durée (mois)</div>
                <div class="value">${data[0].duree}</div>
                <div class="value">${data[1].duree}</div>
                <div class="label">Taux d'Assurance Annuel</div>
                <div class="value">${data[0].taux_assurance ? parseFloat(data[0].taux_assurance).toFixed(2) + '%' : 'Sans assurance'}</div>
                <div class="value">${data[1].taux_assurance ? parseFloat(data[1].taux_assurance).toFixed(2) + '%' : 'Sans assurance'}</div>
                <div class="label">Mensualité</div>
                <div class="value">${parseFloat(data[0].mensualite_totale).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
                <div class="value">${parseFloat(data[1].mensualite_totale).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
                <div class="label">Coût Total du Crédit</div>
                <div class="value">${parseFloat(data[0].cout_total_credit).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
                <div class="value">${parseFloat(data[1].cout_total_credit).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
            `;
        }

        async function savePret() {
            const id_simulation = selectedSimulationSelect.value;
            const id_client = clientSelect.value;
            const date_pret = datePretInput.value;

            if (!id_simulation) {
                displayError('Veuillez sélectionner une simulation.');
                return;
            }
            if (!id_client) {
                displayError('Veuillez sélectionner un client.');
                return;
            }
            if (!date_pret) {
                displayError('Veuillez spécifier une date de prêt.');
                return;
            }

            try {
                const response = await fetch(`${apiBase}/simulations/savePretBySimulation`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_simulation: id_simulation,
                        id_client: id_client,
                        date_pret: date_pret
                    })
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    try {
                        const result = JSON.parse(text);
                        displayError('Erreur lors de l\'enregistrement du prêt: ' + (result.error || 'Erreur inconnue'));
                    } catch (e) {
                        displayError(`Erreur serveur: ${response.status} ${response.statusText}`);
                    }
                    return;
                }

                const result = await response.json();
                if (result.success) {
                    alert(`Prêt enregistré avec succès ! ID du prêt : ${result.pret_id}`);
                    saveForm.style.display = 'none';
                    comparisonContainer.style.display = 'none';
                    loadSimulations();
                } else {
                    displayError(result.error || 'Erreur lors de l\'enregistrement du prêt.');
                }
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors de l\'enregistrement du prêt: ' + error.message);
            }
        }

        // Attacher les écouteurs d'événements
        compareButton.addEventListener('click', compareSimulations);
        savePretButton.addEventListener('click', savePret);

        // Lancement initial
        document.addEventListener('DOMContentLoaded', () => {
            loadSimulations();
            loadClients();
        });
    </script>
</body>
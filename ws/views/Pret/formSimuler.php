<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Prêt Sans Client - Banque</title>
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

        .simulator-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            font-size: 1.1em;
            display: block;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 0.8rem;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .results-container {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            display: none;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-item strong {
            font-size: 1.2em;
            color: #dc2626;
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

        .button-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
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
            .form-grid {
                grid-template-columns: 1fr;
            }

            .simulator-container {
                padding: 1rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">MNA_Banque</div> - Simulateur de Prêt Sans Client
    </div>
    <div class="layout">
        <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Paramètres</a>
            <a href="validation_pret.php">Validation prêt</a>
            <a href="list_interet_mensuel.php">Intérêt mensuel</a>
            <a href="ajout_pret.php">Ajout de prêt</a>
            <a href="simulateur_pret.php">Simulateur de prêt</a>
            <a href="simulateur_sans_client.php">Simulateur sans client</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Simulateur de Prêt Sans Client</h1>

            <div class="simulator-container">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="taux_pret">Taux de prêt</label>
                        <select id="taux_pret" name="taux_pret">
                            <option value="">Sélectionnez un taux de prêt</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="montant">Montant à emprunter (€)</label>
                        <input type="number" id="montant" min="1000" max="999999.99" step="100" value="50000">
                    </div>
                    <div class="form-group">
                        <label for="taux_assurance">Taux d'assurance</label>
                        <select id="taux_assurance" name="taux_assurance">
                            <option value="">Sans assurance</option>
                        </select>
                    </div>
                </div>

                <div id="error-message" class="error-message"></div>

                <div id="results" class="results-container">
                    <h2 style="color: #2563eb; margin-bottom: 1rem;">Votre simulation</h2>
                    <div class="result-item">
                        <span>Durée du prêt</span>
                        <span id="res-duree"></span>
                    </div>
                    <div class="result-item" id="row-taux-assurance">
                        <span>Taux d'assurance annuel</span>
                        <span id="res-taux-assurance"></span>
                    </div>
                    <div class="result-item" id="row-mensualite-assurance">
                        <span>Coût mensuel de l'assurance</span>
                        <span id="res-mensualite-assurance"></span>
                    </div>
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 1rem 0;">
                    <div class="result-item">
                        <span>Taux d'intérêt annuel</span>
                        <span id="res-taux-interet"></span>
                    </div>
                    <div class="result-item">
                        <span>Coût total du crédit</span>
                        <span id="res-cout-total"></span>
                    </div>
                    <div class="result-item">
                        <strong><span id="mensualite-label">Votre mensualité</span></strong>
                        <strong><span id="res-mensualite-totale"></span></strong>
                    </div>

                    <div class="button-group">
                        <button id="valider-simulation" style="display: none;">Enregister la simulation</button>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        const apiBase = "http://localhost/Final_S4_Web/ws";

        // Récupérer les éléments du DOM
        const tauxPretSelect = document.getElementById('taux_pret');
        const montantInput = document.getElementById('montant');
        const tauxAssuranceSelect = document.getElementById('taux_assurance');
        const resultsDiv = document.getElementById('results');
        const errorMessage = document.getElementById('error-message');
        const mensualiteLabel = document.getElementById('mensualite-label');
        const validerSimulationButton = document.getElementById('valider-simulation');

        // Afficher les erreurs
        function displayError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            resultsDiv.style.display = 'none';
            validerSimulationButton.style.display = 'none';
        }

        // Charger les taux de prêt
        async function loadTauxPret() {
            try {
                const response = await fetch(`${apiBase}/all_taux_pret`);
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    displayError(`Erreur lors du chargement des taux de prêt: ${response.status} ${response.statusText}`);
                    return;
                }
                const taux = await response.json();
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
            } catch (error) {
                console.error('Erreur:', error);
                tauxPretSelect.innerHTML = '<option value="">Erreur lors du chargement des taux</option>';
                tauxPretSelect.disabled = true;
                displayError('Erreur lors du chargement des taux de prêt: ' + error.message);
            }
        }

        async function loadTauxAssurance() {
            try {
                const response = await fetch(`${apiBase}/all_taux_assurance`);
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    displayError(`Erreur lors du chargement des taux d'assurance: ${response.status} ${response.statusText}`);
                    return;
                }
                const taux = await response.json();
                tauxAssuranceSelect.innerHTML = '<option value="">Sans assurance</option>';
                if (taux.length === 0) {
                    tauxAssuranceSelect.innerHTML = '<option value="">Aucun taux d\'assurance disponible</option>';
                    tauxAssuranceSelect.disabled = true;
                } else {
                    taux.forEach(t => {
                        const option = document.createElement('option');
                        option.value = t.id;
                        option.textContent = `${t.taux}%`;
                        tauxAssuranceSelect.appendChild(option);
                    });
                    tauxAssuranceSelect.disabled = false;
                }
            } catch (error) {
                console.error('Erreur:', error);
                tauxAssuranceSelect.innerHTML = '<option value="">Erreur lors du chargement des taux</option>';
                tauxAssuranceSelect.disabled = true;
                displayError('Erreur lors du chargement des taux d\'assurance: ' + error.message);
            }
        }

        // Lancer la simulation
        async function runSimulation() {
            const id_taux_pret = tauxPretSelect.value;
            const montant = parseFloat(montantInput.value);
            const id_taux_assurance = tauxAssuranceSelect.value;

            if (!id_taux_pret) {
                displayError('Veuillez sélectionner un taux de prêt.');
                return;
            }
            if (isNaN(montant) || montant <= 0) {
                displayError('Veuillez entrer un montant valide.');
                return;
            }

            try {
                const response = await fetch(`${apiBase}/simulateur/calculer_sans_client`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_taux_pret: id_taux_pret,
                        montant: montant,
                        id_taux_assurance: id_taux_assurance || null
                    })
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    try {
                        const result = JSON.parse(text);
                        displayError('Erreur lors de la simulation: ' + (result.error || 'Erreur inconnue'));
                    } catch (e) {
                        displayError(`Erreur serveur: ${response.status} ${response.statusText}`);
                    }
                    return;
                }

                const data = await response.json();
                if (!data.error) {
                    displayResults(data, id_taux_assurance);
                    validerSimulationButton.style.display = 'block';
                } else {
                    displayError(data.error || 'Une erreur est survenue lors de la simulation.');
                }
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors de la simulation: ' + error.message);
            }
        }

        async function validerSimulation() {
            const id_taux_pret = tauxPretSelect.value;
            const montant = parseFloat(montantInput.value);
            const id_taux_assurance = tauxAssuranceSelect.value;

            if (!id_taux_pret) {
                displayError('Veuillez sélectionner un taux de prêt.');
                return;
            }
            if (isNaN(montant) || montant <= 0) {
                displayError('Veuillez entrer un montant valide.');
                return;
            }
            if (!id_taux_assurance) {
                displayError('Veuillez sélectionner un taux d\'assurance ou l\'option "Sans assurance".');
                return;
            }

            try {
                const response = await fetch(`${apiBase}/simulateur/save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_taux_pret: id_taux_pret,
                        montant: montant,
                        id_taux_assurance: id_taux_assurance || null
                    })
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    try {
                        const result = JSON.parse(text);
                        displayError('Erreur lors de la sauvegarde de la simulation: ' + (result.error || 'Erreur inconnue'));
                    } catch (e) {
                        displayError(`Erreur serveur: ${response.status} ${response.statusText}`);
                    }
                    return;
                }

                const result = await response.json();
                if (result.success) {
                    alert(`Simulation sauvegardée avec succès ! ID de la simulation : ${result.simulation_id}`);
                    resultsDiv.style.display = 'none';
                    validerSimulationButton.style.display = 'none';
                } else {
                    displayError(result.error || 'Erreur lors de la sauvegarde de la simulation.');
                }
            } catch (error) {
                console.error('Erreur:', error);
                displayError('Erreur lors de la sauvegarde de la simulation: ' + error.message);
            }
        }

        function displayResults(data, id_taux_assurance) {
            const duree_mois = parseInt(data.duree_mois) || 0;
            const taux_interet_annuel = parseFloat(data.taux_interet_annuel) || 0;
            const taux_assurance_annuel = parseFloat(data.taux_assurance_annuel) || 0;
            const mensualite_assurance = parseFloat(data.mensualite_assurance) || 0;
            const mensualite_totale = parseFloat(data.mensualite_totale) || 0;
            const cout_total_credit = parseFloat(data.cout_total_credit) || 0;

            const showAssurance = id_taux_assurance && taux_assurance_annuel > 0;
            document.getElementById('row-taux-assurance').style.display = showAssurance ? 'flex' : 'none';
            document.getElementById('row-mensualite-assurance').style.display = showAssurance ? 'flex' : 'none';

            mensualiteLabel.textContent = showAssurance ? 'Votre mensualité (assurance incluse)' : 'Votre mensualité';

            document.getElementById('res-duree').textContent = `${duree_mois} mois (${(duree_mois / 12).toFixed(1)} ans)`;
            document.getElementById('res-taux-interet').textContent = `${taux_interet_annuel.toFixed(2)} %`;
            document.getElementById('res-taux-assurance').textContent = `${taux_assurance_annuel.toFixed(2)} %`;
            document.getElementById('res-mensualite-assurance').textContent = `${mensualite_assurance.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-cout-total').textContent = `${cout_total_credit.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;
            document.getElementById('res-mensualite-totale').textContent = `${mensualite_totale.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}`;

            resultsDiv.style.display = 'block';
            errorMessage.style.display = 'none';
        }

        // Attacher les écouteurs d'événements
        tauxPretSelect.addEventListener('change', runSimulation);
        montantInput.addEventListener('input', runSimulation);
        tauxAssuranceSelect.addEventListener('change', runSimulation);
        validerSimulationButton.addEventListener('click', validerSimulation);

        // Lancement initial
        document.addEventListener('DOMContentLoaded', () => {
            loadTauxPret();
            loadTauxAssurance();
        });
    </script>
</body>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remboursement - Banque</title>
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

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.6rem;
            margin-right: 5px;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #e0e7ff;
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
        input[type="date"],
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
        <div class="logo">MNA_Banque</div> - Remboursement
    </div>
    <div class="layout">
        <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="ajout_pret.php">Ajout de pret</a>
            <a href="simulateur_pret.php">Simulateur de pret</a>
            <a href="formSimuler.php">Simulateur pour un apreçu de pret</a>
            <a href="comparerSimulation.php">Comparer les simulations enregistés</a>
            <a href="remboursement.php">Remboursement</a>
            <a href="montant_dispo.php">Solde mensuel</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Remboursement</h1>

            <div class="simulator-container">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pret">Prêt en cours</label>
                        <select id="pret" name="pret">
                            <option value="">Sélectionnez un prêt en cours</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mois_paiement">Mois de paiement</label>
                        <select id="mois_paiement" name="mois_paiement">
                            <option value="">Sélectionnez un mois</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="montant_retour">Montant retour (€)</label>
                        <input type="number" id="montant_retour" min="0" step="0.01" value="0">
                    </div>
                    <div class="form-group">
                        <label for="pourcentage_assurance">Pourcentage assurance (%)</label>
                        <input type="number" id="pourcentage_assurance" min="0" max="100" step="0.01" value="0">
                    </div>
                    <div class="form-group full-width">
                        <label for="date_retour">Date de retour</label>
                        <input type="date" id="date_retour" value="2025-07-08">
                    </div>
                </div>

                <div id="error-message" class="error-message"></div>

                <div class="button-group">
                    <button id="ajouter-remboursement">Ajouter le remboursement</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        const apiBase = "http://localhost/Final_S4_Web/ws";
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth();

        // Récupérer les éléments du DOM
        const pretSelect = document.getElementById('pret');
        const moisPaiementSelect = document.getElementById('mois_paiement');
        const montantRetourInput = document.getElementById('montant_retour');
        const pourcentageAssuranceInput = document.getElementById('pourcentage_assurance');
        const dateRetourInput = document.getElementById('date_retour');
        const errorMessage = document.getElementById('error-message');
        const ajouterRemboursementButton = document.getElementById('ajouter-remboursement');

        // Charger les prêts en cours (par exemple, statuts 'Valide' ou 'En cours')
        async function loadPretsEnCours() {
            try {
                const response = await fetch(`${apiBase}/prets`);
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                const prets = await response.json();
                pretSelect.innerHTML = '<option value="">Sélectionnez un prêt en cours</option>';
                prets.forEach(pret => {
                    if (pret.statut && ['Valide', 'En cours'].includes(pret.statut)) {
                        const option = document.createElement('option');
                        option.value = pret.id;
                        option.textContent = `Prêt #${pret.id} - ${pret.client_nom} ${pret.client_prenom} - ${pret.montant_emprunte}€`;
                        pretSelect.appendChild(option);
                    }
                });
            } catch (error) {
                console.error('Erreur:', error);
                errorMessage.textContent = 'Erreur lors du chargement des prêts: ' + error.message;
                errorMessage.style.display = 'block';
            }
        }

        // Générer les options de mois jusqu'à la date de retour
        function generateMoisOptions() {
            const dateRetour = new Date(dateRetourInput.value);
            moisPaiementSelect.innerHTML = '<option value="">Sélectionnez un mois</option>';

            for (let year = currentYear; year <= dateRetour.getFullYear(); year++) {
                const startMonth = (year === currentYear) ? currentMonth : 0;
                const endMonth = (year === dateRetour.getFullYear()) ? dateRetour.getMonth() : 11;

                for (let month = startMonth; month <= endMonth; month++) {
                    const monthName = new Date(year, month, 1).toLocaleString('fr-FR', {
                        month: 'long',
                        year: 'numeric'
                    });
                    const option = document.createElement('option');
                    option.value = `${year}-${month + 1}`; // Format YYYY-MM
                    option.textContent = monthName;
                    moisPaiementSelect.appendChild(option);
                }
            }
        }

        // Mettre à jour les mois lorsque la date de retour change
        dateRetourInput.addEventListener('change', generateMoisOptions);

        // Ajouter un remboursement
        async function ajouterRemboursement() {
            const id_pret = pretSelect.value;
            const mois_paiement = moisPaiementSelect.value;
            const montant_retour = parseFloat(montantRetourInput.value);
            const pourcentage_assurance = parseFloat(pourcentageAssuranceInput.value);
            const date_retour = dateRetourInput.value;

            if (!id_pret) {
                errorMessage.textContent = 'Veuillez sélectionner un prêt.';
                errorMessage.style.display = 'block';
                return;
            }
            if (!mois_paiement) {
                errorMessage.textContent = 'Veuillez sélectionner un mois de paiement.';
                errorMessage.style.display = 'block';
                return;
            }
            if (isNaN(montant_retour) || montant_retour <= 0) {
                errorMessage.textContent = 'Veuillez entrer un montant valide.';
                errorMessage.style.display = 'block';
                return;
            }
            if (isNaN(pourcentage_assurance) || pourcentage_assurance < 0) {
                errorMessage.textContent = 'Veuillez entrer un pourcentage d\'assurance valide.';
                errorMessage.style.display = 'block';
                return;
            }
            if (!date_retour) {
                errorMessage.textContent = 'Veuillez sélectionner une date.';
                errorMessage.style.display = 'block';
                return;
            }

            // Calculer le montant avec assurance
            const montant_avec_assurance = montant_retour + (montant_retour * (pourcentage_assurance / 100));

            try {
                const response = await fetch(`${apiBase}/remboursements`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_pret: id_pret,
                        montant_retour: montant_avec_assurance,
                        date_retour: date_retour
                    })
                });

                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }

                const result = await response.json();
                if (result.success) {
                    alert('Remboursement ajouté avec succès!');
                    montantRetourInput.value = '0';
                    pourcentageAssuranceInput.value = '0';
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.textContent = result.error || 'Erreur lors de l\'ajout du remboursement.';
                    errorMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Erreur:', error);
                errorMessage.textContent = 'Erreur lors de l\'ajout du remboursement: ' + error.message;
                errorMessage.style.display = 'block';
            }
        }

        // Attacher les écouteurs d'événements
        ajouterRemboursementButton.addEventListener('click', ajouterRemboursement);

        // Lancement initial
        document.addEventListener('DOMContentLoaded', () => {
            loadPretsEnCours();
            generateMoisOptions();
        });
    </script>
</body>

</html>
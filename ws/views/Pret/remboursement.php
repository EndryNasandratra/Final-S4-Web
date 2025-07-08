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
            <a href="../Ressources/settings.php">Paramètres</a>
            <a href="validation_pret.php">Validation prêt</a>
            <a href="list_interet_mensuel.php">Intérêt mensuel</a>
            <a href="ajout_pret.php">Ajout de prêt</a>
            <a href="simulateur_pret.php">Simulateur de prêt</a>
            <a href="remboursement.php">Remboursement</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Remboursement</h1>

            <div class="simulator-container">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pret">Prêt</label>
                        <select id="pret" name="pret">
                            <option value="">Sélectionnez un prêt</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="montant_retour">Montant retour (€)</label>
                        <input type="number" id="montant_retour" min="0" step="0.01" value="0">
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

        // Récupérer les éléments du DOM
        const pretSelect = document.getElementById('pret');
        const montantRetourInput = document.getElementById('montant_retour');
        const dateRetourInput = document.getElementById('date_retour');
        const errorMessage = document.getElementById('error-message');
        const ajouterRemboursementButton = document.getElementById('ajouter-remboursement');

        // Charger les prêts
        async function loadPrets() {
            try {
                const response = await fetch(`${apiBase}/prets/validated`);
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                const prets = await response.json();
                pretSelect.innerHTML = '<option value="">Sélectionnez un prêt</option>';
                prets.forEach(pret => {
                    const option = document.createElement('option');
                    option.value = pret.id;
                    option.textContent = `Prêt #${pret.id} - ${pret.client_nom} ${pret.client_prenom} - ${pret.montant}€`;
                    pretSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur:', error);
                errorMessage.textContent = 'Erreur lors du chargement des prêts: ' + error.message;
                errorMessage.style.display = 'block';
            }
        }

        // Ajouter un remboursement
        async function ajouterRemboursement() {
            const id_pret = pretSelect.value;
            const montant_retour = parseFloat(montantRetourInput.value);
            const date_retour = dateRetourInput.value;

            if (!id_pret) {
                errorMessage.textContent = 'Veuillez sélectionner un prêt.';
                errorMessage.style.display = 'block';
                return;
            }
            if (isNaN(montant_retour) || montant_retour <= 0) {
                errorMessage.textContent = 'Veuillez entrer un montant valide.';
                errorMessage.style.display = 'block';
                return;
            }
            if (!date_retour) {
                errorMessage.textContent = 'Veuillez sélectionner une date.';
                errorMessage.style.display = 'block';
                return;
            }

            try {
                const response = await fetch(`${apiBase}/remboursements`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_pret: id_pret,
                        montant_retour: montant_retour,
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
            loadPrets();
        });
    </script>
</body>

</html>
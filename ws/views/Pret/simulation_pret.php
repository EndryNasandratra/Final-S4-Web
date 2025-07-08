<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation de pret</title>
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
            align-items: flex-start;
            justify-content: center;
            background: #f8fafc;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 2rem 0;
        }

        .container {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 2rem 1.5rem;
            min-width: 60%;
            box-sizing: border-box;
        }

        h2 {
            color: #2563eb;
            margin-bottom: 1.2rem;
            font-size: 1.2rem;
        }

        .form-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            color: #1e293b;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 0.7rem 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .simulation-results {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .result-item:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #2563eb;
            font-size: 1.1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            background: #059669;
            color: #fff;
        }

        .btn-success:hover {
            background: #047857;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem;
                display: flex;
                flex-direction: row;
                gap: 1rem;
                justify-content: center;
            }

            .main-content {
                padding: 1rem 0;
            }

            .container {
                min-width: 95vw;
            }

            .form-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">MNA_Banque</div> - Simulation de pret
    </div>
    <div class="layout">
        <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="simulation_pret.php">Simulation de pret</a>
            <a href="montant_dispo.php">Solde mensuel</a>
            <a href="#">Deconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Simulation de pret</h2>
                <form id="simulationForm">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="type_pret">Type de pret</label>
                            <select name="type_pret" id="type_pret" required onchange="loadDurees()">
                                <option value="">Selectionner un type de pret</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="duree">Duree (annees)</label>
                            <select name="duree" id="duree" required onchange="loadTaux()">
                                <option value="">Selectionner la duree</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="montant">Montant emprunte (€)</label>
                            <input type="number" id="montant" name="montant" step="0.01" placeholder="10000" required onchange="validateMontant()">
                        </div>
                        <div class="form-group">
                            <label for="taux_assurance">Taux d'assurance</label>
                            <select name="taux_assurance" id="taux_assurance" required>
                                <option value="">Selectionner le taux d'assurance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mode_remboursement">Mode de remboursement</label>
                            <select name="mode_remboursement" id="mode_remboursement" required>
                                <option value="">Selectionner</option>
                                <option value="1">Mensuel</option>
                                <option value="2">Trimestriel</option>
                                <option value="3">Semestriel</option>
                                <option value="4">Annuel</option>
                            </select>
                        </div>
                    </div>

                    <div class="simulation-results" id="simulationResults" style="display: none;">
                        <h3>Resultats de la simulation</h3>
                        <div class="result-item">
                            <span>Montant emprunte :</span>
                            <span id="montant_emprunte">0.00 €</span>
                        </div>
                        <div class="result-item">
                            <span>Duree :</span>
                            <span id="duree_affichage">0 ans</span>
                        </div>
                        <div class="result-item">
                            <span>Taux annuel :</span>
                            <span id="taux_annuel">0.00 %</span>
                        </div>
                        <div class="result-item">
                            <span>Taux d'assurance :</span>
                            <span id="taux_assurance_affichage">0.00 %</span>
                        </div>
                        <div class="result-item">
                            <span>Mensualite :</span>
                            <span id="mensualite">0.00 €</span>
                        </div>
                        <div class="result-item">
                            <span>Assurance mensuelle :</span>
                            <span id="assurance_mensajouterPret €</Ajouter                        </div>
                        <div class=" result-item">
                                <span>Total mensuel :</span>
                                <span id="total_mensuel">0.00 €</span>
                        </div>
                        <div class="result-item">
                            <span>Coût total :</span>
                            <span id="cout_total">0.00 €</span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary" onclick="calculerSimulation()">Calculer</button>
                        <button type="button" class="btn btn-success" onclick="ajouterPret()">Ajouter le pret</button>
                        <button type="button" class="btn btn-secondary" onclick="genererPDF()">Generer PDF</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Variables globales
        let typesPret = [];
        let tauxAssurance = [];
        let currentSimulation = null;

        // Charger les types de prets au chargement de la page
        async function loadTypesPret() {
            try {
                const response = await fetch('/Final_S4_Web/ws/api/types-pret');
                const data = await response.json();

                if (data.success) {
                    typesPret = data.data;
                    const select = document.getElementById('type_pret');
                    select.innerHTML = '<option value="">Selectionner un type de pret</option>';

                    typesPret.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = `${type.libelle} (max: ${type.montant_max}€, duree: ${type.duree_max}ans)`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Erreur lors du chargement des types de pret:', error);
            }
        }

        // Charger les durees disponibles pour un type de pret
        async function loadDurees() {
            const typePretId = document.getElementById('type_pret').value;
            if (!typePretId) return;

            try {
                const response = await fetch(`/Final_S4_Web/ws/api/types-pret/${typePretId}/durees`);
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('duree');
                    select.innerHTML = '<option value="">Selectionner la duree</option>';

                    data.data.forEach(duree => {
                        const option = document.createElement('option');
                        option.value = duree.duree;
                        option.textContent = `${duree.duree} ans`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Erreur lors du chargement des durees:', error);
            }
        }

        // Charger les taux d'assurance
        async function loadTauxAssurance() {
            try {
                const response = await fetch('/Final_S4_Web/ws/api/taux-assurance');
                const data = await response.json();

                if (data.success) {
                    tauxAssurance = data.data;
                    const select = document.getElementById('taux_assurance');
                    select.innerHTML = '<option value="">Selectionner le taux d\'assurance</option>';

                    tauxAssurance.forEach(taux => {
                        const option = document.createElement('option');
                        option.value = taux.id;
                        option.textContent = `${taux.taux}%`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Erreur lors du chargement des taux d\'assurance:', error);
            }
        }

        // Valider le montant selon le type de pret
        function validateMontant() {
            const typePretId = document.getElementById('type_pret').value;
            const montant = parseFloat(document.getElementById('montant').value);

            if (typePretId && montant) {
                const typePret = typesPret.find(t => t.id == typePretId);
                if (typePret && montant > typePret.montant_max) {
                    alert(`Le montant maximum pour ce type de pret est de ${typePret.montant_max}€`);
                    document.getElementById('montant').value = typePret.montant_max;
                }
            }
        }

        // Calculer la simulation
        async function calculerSimulation() {
            const montant = parseFloat(document.getElementById('montant').value);
            const duree = parseInt(document.getElementById('duree').value);
            const typePretId = parseInt(document.getElementById('type_pret').value);
            const tauxAssuranceId = parseInt(document.getElementById('taux_assurance').value);

            if (!montant || !duree || !typePretId || !tauxAssuranceId) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            try {
                const response = await fetch('/Final_S4_Web/ws/api/prets/simuler', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        montant: montant,
                        duree: duree,
                        type_pret_id: typePretId,
                        taux_assurance_id: tauxAssuranceId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    currentSimulation = data.data;
                    displaySimulation(currentSimulation);
                } else {
                    alert('Erreur lors de la simulation: ' + data.message);
                }
            } catch (error) {
                console.error('Erreur lors de la simulation:', error);
                alert('Erreur lors de la simulation');
            }
        }

        // Afficher les resultats de la simulation
        function displaySimulation(simulation) {
            document.getElementById('montant_emprunte').textContent = formatCurrency(simulation.montant);
            document.getElementById('duree_affichage').textContent = simulation.duree + ' ans';
            document.getElementById('taux_annuel').textContent = simulation.taux_annuel + ' %';
            document.getElementById('taux_assurance_affichage').textContent = simulation.taux_assurance + ' %';
            document.getElementById('mensualite').textContent = formatCurrency(simulation.mensualite);
            document.getElementById('assurance_mensuelle').textContent = formatCurrency(simulation.assurance_mensuelle);
            document.getElementById('total_mensuel').textContent = formatCurrency(simulation.total_mensuel);
            document.getElementById('cout_total').textContent = formatCurrency(simulation.cout_total);

            document.getElementById('simulationResults').style.display = 'block';
        }

        // Fonction utilitaire pour formater les montants
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount || 0);
        }

        // Ajouter le pret
        function ajouterPret() {
            if (!currentSimulation) {
                alert('Veuillez d\'abord calculer la simulation');
                return;
            }

            if (confirm('etes-vous sûr de vouloir ajouter ce pret ?')) {
                // Ici, on enverrait les donnees au serveur pour sauvegarder le pret
                alert('Pret ajoute avec succes !');
                // Redirection vers la liste des prets
                window.location.href = 'list_pret.php';
            }
        }

        // Generer PDF
        function genererPDF() {
            if (!currentSimulation) {
                alert('Veuillez d\'abord calculer la simulation');
                return;
            }

            // Ici, on appellerait une API pour generer le PDF
            alert('Generation du PDF en cours...');
            // window.open('generer_pdf.php?id_simulation=' + simulationId, '_blank');
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadTypesPret();
            loadTauxAssurance();
        });
    </script>
</body>

</html>
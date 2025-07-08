<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des prets</title>
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
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 0;
        }
        .container {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 2rem 1.5rem;
            min-width: 95%;
            box-sizing: border-box;
        }
        h2 {
            color: #2563eb;
            margin-bottom: 1.2rem;
            font-size: 1.2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 0.7rem;
            text-align: left;
        }
        th {
            background: #e0e7ff;
            color: #2563eb;
        }
        tr:nth-child(even) {
            background: #f1f5f9;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        .btn-success {
            background: #059669;
            color: #fff;
        }
        .btn-success:hover {
            background: #047857;
        }
        .btn-danger {
            background: #dc2626;
            color: #fff;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .btn-info {
            background: #0891b2;
            color: #fff;
        }
        .btn-info:hover {
            background: #0e7490;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .loading {
            text-align: center;
            padding: 2rem;
            color: #64748b;
        }
        .error {
            color: #dc2626;
            text-align: center;
            padding: 1rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            margin: 1rem 0;
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
                min-width: 99vw;
            }
        }
    </style>
</head>
<body>
    <div class="header"><div class="logo">MNA_Banque</div> - Validation des prets</div>
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
            <div class="container">
                <h2>Prets en attente de validation</h2>
                <div id="tableContainer">
                    <div class="loading">Chargement des prets en attente...</div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Charger les prets en attente
        async function loadPendingPrets() {
            try {
                const response = await fetch('/Final_S4_Web/ws/prets/not-validated');
                const data = await response.json();
                
                if (Array.isArray(data)) {
                    displayPrets(data);
                } else {
                    document.getElementById('tableContainer').innerHTML = 
                        '<div class="error">Erreur lors du chargement des donnees</div>';
                }
            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('tableContainer').innerHTML = 
                    '<div class="error">Erreur lors du chargement des donnees: ' + error.message + '</div>';
            }
        }

        // Afficher les prets dans le tableau
        function displayPrets(prets) {
            const tableContainer = document.getElementById('tableContainer');
            
            if (prets.length === 0) {
                tableContainer.innerHTML = '<div class="loading">Aucun pret en attente de validation</div>';
                return;
            }

            let tableHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>N° Pret</th>
                            <th>Client</th>
                            <th>Employe</th>
                            <th>Montant</th>
                            <th>Duree</th>
                            <th>Taux</th>
                            <th>Date demande</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            prets.forEach(pret => {
                const numeroPret = `PRT-${pret.id.toString().padStart(6, '0')}`;
                const clientName = pret.client_nom && pret.client_prenom ? 
                    pret.client_nom + ' ' + pret.client_prenom : 'N/A';
                const employeName = pret.employe_nom + ' ' + pret.employe_prenom;
                const formattedDate = new Date(pret.date_debut).toLocaleDateString('fr-FR');
                const formattedMontant = parseFloat(pret.montant).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                tableHTML += `
                    <tr>
                        <td>${numeroPret}</td>
                        <td>${clientName}</td>
                        <td>${employeName}</td>
                        <td>${formattedMontant} €</td>
                        <td>${pret.duree} mois</td>
                        <td>${pret.taux}%</td>
                        <td>${formattedDate}</td>
                        <td><span class="status-pending">En attente</span></td>
                        <td>
                            <button class="btn btn-success" onclick="validerPret(${pret.id})">Valider</button>
                            <button class="btn btn-danger" onclick="rejeterPret(${pret.id})">Rejeter</button>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            tableContainer.innerHTML = tableHTML;
        }

        function voirDetails(pretId) {
            alert('Affichage des details du pret ID: ' + pretId);
            // Ici, on pourrait ouvrir une modal ou rediriger vers une page de details
        }
        
        async function validerPret(pretId) {
            if (confirm('etes-vous sûr de vouloir valider ce pret ?')) {
                try {
                    const response = await fetch(`/Final_S4_Web/ws/prets/${pretId}/validate`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });

                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        alert('Pret valide avec succes !');
                        // Recharger la liste des prets en attente
                        loadPendingPrets();
                    } else {
                        alert('Erreur lors de la validation: ' + (result.error || 'Erreur inconnue'));
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la validation du pret');
                }
            }
        }
        
        async function rejeterPret(pretId) {
            const raison = prompt('Veuillez indiquer la raison du rejet :');
            if (raison !== null && raison.trim() !== '') {
                if (confirm('etes-vous sûr de vouloir rejeter ce pret ?')) {
                    try {
                        const response = await fetch(`/Final_S4_Web/ws/prets/${pretId}/reject`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                raison: raison
                            })
                        });

                        const result = await response.json();
                        
                        if (response.ok && result.success) {
                            alert('Pret rejete avec succes !');
                            // Recharger la liste des prets en attente
                            loadPendingPrets();
                        } else {
                            alert('Erreur lors du rejet: ' + (result.error || 'Erreur inconnue'));
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        alert('Erreur lors du rejet du pret');
                    }
                }
            } else if (raison !== null) {
                alert('Veuillez indiquer une raison pour le rejet.');
            }
        }

        // Charger les donnees au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadPendingPrets();
        });
    </script>
</body>
</html>
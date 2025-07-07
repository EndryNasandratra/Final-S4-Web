<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des prêts</title>
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
    <div class="header"><div class="logo">MNA_Banque</div> - Validation des prêts</div>
    <div class="layout">
        <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="simulation_pret.php">Simulation de prêt</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Prêts en attente de validation</h2>
                <table>
                    <thead>
                        <tr>
                            <th>N° Prêt</th>
                            <th>Client</th>
                            <th>Employé</th>
                            <th>Montant</th>
                            <th>Durée</th>
                            <th>Taux</th>
                            <th>Date demande</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PRT-2024-001</td>
                            <td>Dupont Jean</td>
                            <td>Martin Sophie</td>
                            <td>15 000,00 €</td>
                            <td>24 mois</td>
                            <td>3,5%</td>
                            <td>15/01/2024</td>
                            <td><span class="status-pending">En attente</span></td>
                            <td>
                                <button class="btn btn-info" onclick="voirDetails('PRT-2024-001')">Détails</button>
                                <button class="btn btn-success" onclick="validerPret('PRT-2024-001')">Valider</button>
                                <button class="btn btn-danger" onclick="rejeterPret('PRT-2024-001')">Rejeter</button>
                            </td>
                        </tr>
                        <tr>
                            <td>PRT-2024-002</td>
                            <td>Durand Alice</td>
                            <td>Bernard Paul</td>
                            <td>8 500,00 €</td>
                            <td>12 mois</td>
                            <td>2,9%</td>
                            <td>14/01/2024</td>
                            <td><span class="status-pending">En attente</span></td>
                            <td>
                                <button class="btn btn-info" onclick="voirDetails('PRT-2024-002')">Détails</button>
                                <button class="btn btn-success" onclick="validerPret('PRT-2024-002')">Valider</button>
                                <button class="btn btn-danger" onclick="rejeterPret('PRT-2024-002')">Rejeter</button>
                            </td>
                        </tr>
                        <tr>
                            <td>PRT-2024-003</td>
                            <td>Petit Luc</td>
                            <td>Leroy Emma</td>
                            <td>25 000,00 €</td>
                            <td>36 mois</td>
                            <td>4,1%</td>
                            <td>13/01/2024</td>
                            <td><span class="status-pending">En attente</span></td>
                            <td>
                                <button class="btn btn-info" onclick="voirDetails('PRT-2024-003')">Détails</button>
                                <button class="btn btn-success" onclick="validerPret('PRT-2024-003')">Valider</button>
                                <button class="btn btn-danger" onclick="rejeterPret('PRT-2024-003')">Rejeter</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script>
        function voirDetails(numeroPret) {
            alert('Affichage des détails du prêt ' + numeroPret);
            // Ici, on pourrait ouvrir une modal ou rediriger vers une page de détails
        }
        
        function validerPret(numeroPret) {
            if (confirm('Êtes-vous sûr de vouloir valider le prêt ' + numeroPret + ' ?')) {
                // Ici, on enverrait la validation au serveur
                alert('Prêt ' + numeroPret + ' validé avec succès !');
                // Recharger la page ou mettre à jour le statut
                // window.location.reload();
            }
        }
        
        function rejeterPret(numeroPret) {
            const raison = prompt('Veuillez indiquer la raison du rejet :');
            if (raison !== null) {
                if (confirm('Êtes-vous sûr de vouloir rejeter le prêt ' + numeroPret + ' ?')) {
                    // Ici, on enverrait le rejet au serveur avec la raison
                    alert('Prêt ' + numeroPret + ' rejeté. Raison : ' + raison);
                    // Recharger la page ou mettre à jour le statut
                    // window.location.reload();
                }
            }
        }
    </script>
</body>
</html>
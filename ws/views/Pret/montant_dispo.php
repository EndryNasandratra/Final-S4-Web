<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Montant a disposition par mois</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            min-width: 95%;
            box-sizing: border-box;
        }

        h2 {
            color: #2563eb;
            margin-bottom: 1.2rem;
            font-size: 1.2rem;
        }

        form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        label {
            font-size: 0.95rem;
            color: #1e293b;
        }

        input[type="month"] {
            padding: 0.3rem 0.7rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 0.7rem;
            text-align: right;
        }

        th {
            background: #e0e7ff;
            color: #2563eb;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f1f5f9;
        }

        .loading,
        .error {
            text-align: center;
            padding: 1rem;
        }

        .error {
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.6rem;
            margin-Right: 5px;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #e0e7ff;
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

            form {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">MNA_Banque</div> - Gestion des ressources
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
            <a href="remboursement.php">Remboursement</a>
            <a href="montant_dispo.php">Solde mensuel</a>
            <a href="#">Deconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Montant total a disposition de l'EF par mois</h2>
                <form id="filterForm">
                    <div>
                        <label for="dateDebut">Mois/Annee debut</label><br>
                        <input type="month" id="dateDebut" name="dateDebut" required>
                    </div>
                    <div>
                        <label for="dateFin">Mois/Annee fin</label><br>
                        <input type="month" id="dateFin" name="dateFin" required>
                    </div>
                    <div>
                        <button type="submit">Filtrer</button>
                    </div>
                </form>
                <div id="tableContainer">
                    <div class="loading">Chargement des donnees...</div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function loadMontantDispo(dateDebut, dateFin) {
            let url = '/Final_S4_Web/ws/prets/montant-dispo-par-mois';
            const params = new URLSearchParams();
            if (dateDebut) params.append('dateDebut', dateDebut);
            if (dateFin) params.append('dateFin', dateFin);
            if ([...params].length) url += '?' + params.toString();
            console.log('Appel API URL:', url);
            document.getElementById('tableContainer').innerHTML = '<div class="loading">Chargement des donnees...</div>';
            fetch(url)
                .then(response => {
                    console.log('Reponse brute:', response);
                    if (!response.ok) throw new Error('Erreur reseau: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Donnees JSON reçues:', data);
                    displayTable(data);
                })
                .catch(error => {
                    console.error('Erreur lors du fetch:', error);
                    document.getElementById('tableContainer').innerHTML = '<div class="error">Erreur: ' + error.message + '</div>';
                });
        }

        function displayTable(data) {
            if (!Array.isArray(data) || data.length === 0) {
                document.getElementById('tableContainer').innerHTML = '<div class="loading">Aucune donnee trouvee</div>';
                return;
            }
            let html = `<table><thead><tr>
        <th>Mois</th><th>Annee</th><th>Montant non emprunte (€)</th><th>Remboursements clients (€)</th><th>Total a disposition (€)</th>
    </tr></thead><tbody>`;
            data.forEach(row => {
                html += `<tr>
            <td style="text-align:center">${row.mois}</td>
            <td style="text-align:center">${row.annee}</td>
            <td>${Number(row.total_non_emprunte).toLocaleString('fr-FR', {minimumFractionDigits:2})}</td>
            <td>${Number(row.total_remboursements).toLocaleString('fr-FR', {minimumFractionDigits:2})}</td>
            <td><b>${Number(row.total_dispo).toLocaleString('fr-FR', {minimumFractionDigits:2})}</b></td>
        </tr>`;
            });
            html += '</tbody></table>';
            document.getElementById('tableContainer').innerHTML = html;
        }
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const dateDebut = document.getElementById('dateDebut').value;
            const dateFin = document.getElementById('dateFin').value;
            if (dateDebut && dateFin) {
                loadMontantDispo(dateDebut, dateFin);
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Valeurs par defaut : 1 an glissant
            const now = new Date();
            const fin = now.toISOString().slice(0, 7);
            const debut = new Date(now.getFullYear() - 1, now.getMonth(), 1).toISOString().slice(0, 7);
            document.getElementById('dateDebut').value = debut;
            document.getElementById('dateFin').value = fin;
            loadMontantDispo(debut, fin);
        });
    </script>
</body>

</html>
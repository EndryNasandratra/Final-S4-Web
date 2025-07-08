<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des intérêts mensuels</title>
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
        .filter-toggle-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        .filter-toggle-btn:hover {
            background: #1d4ed8;
        }
        .filters-block {
            display: none;
            margin-bottom: 1rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .filters-block.visible {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filters-block input, .filters-block select {
            padding: 0.3rem 0.7rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }
        .filters-block label {
            font-size: 0.95rem;
            color: #1e293b;
            margin-bottom: 0.2rem;
            display: block;
        }
        .filters-block .filter-group {
            display: flex;
            flex-direction: column;
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
        .chart-container {
            margin-top: 2rem;
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem 1rem;
            border: 1px solid #cbd5e1;
            max-width: 900px;
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
            .filters-block.visible {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header"><div class="logo">MNA_Banque</div> - Gestion des ressources</div>
    <div class="layout">
    <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="ajout_pret.php">Ajout de prêt</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Liste des intérêts mensuels</h2>
                <button type="button" class="filter-toggle-btn" onclick="toggleFilters()">Afficher les filtres</button>
                <form id="filterForm" method="get" onsubmit="return handleFilter(event)">
                    <div class="filters-block" id="filtersBlock">
                        <div class="filter-group">
                            <label for="mois_debut">Mois début</label>
                            <select name="mois_debut" id="mois_debut">
                                <option value="">--</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="annee_debut">Année début</label>
                            <input type="text" name="annee_debut" id="annee_debut" placeholder="Année début" value="">
                        </div>
                        <div class="filter-group">
                            <label for="mois_fin">Mois fin</label>
                            <select name="mois_fin" id="mois_fin">
                                <option value="">--</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="annee_fin">Année fin</label>
                            <input type="text" name="annee_fin" id="annee_fin" placeholder="Année fin" value="">
                        </div>
                        <div class="filter-group">
                            <button type="submit">Filtrer</button>
                        </div>
                    </div>
                    <table id="interetTable">
                        <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Année</th>
                            <th>Somme des intérêts mensuels</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        <!-- Généré dynamiquement -->
                        </tbody>
                    </table>
                    <div class="chart-container">
                        <canvas id="interetChart"></canvas>
                    </div>
                </form>
                <script>
                // Variables globales
                let donneesBrutes = [];
                let chart = null;

                const moisMap = {
                    1: 'Janvier', 2: 'Février', 3: 'Mars', 4: 'Avril', 5: 'Mai', 6: 'Juin',
                    7: 'Juillet', 8: 'Août', 9: 'Septembre', 10: 'Octobre', 11: 'Novembre', 12: 'Décembre'
                };
                const moisMapReverse = Object.fromEntries(Object.entries(moisMap).map(([k, v]) => [v, parseInt(k)]));

                function moisAnneeToInt(mois, annee) {
                    if (!mois || !annee) return null;
                    return parseInt(annee) * 100 + parseInt(mois);
                }

                let chart = null;

                function afficherTableau(moisDebut, anneeDebut, moisFin, anneeFin) {
                    const tbody = document.getElementById('tableBody');
                    tbody.innerHTML = '';
                    let start = moisAnneeToInt(moisDebut, anneeDebut);
                    let end = moisAnneeToInt(moisFin, anneeFin);
                    // Regrouper les intérêts par mois/année
                    let interetsParMois = {};
                    donneesBrutes.forEach(d => {
                        let m = moisMapReverse[d.mois];
                        let a = d.annee;
                        let key = `${a}-${m}`;
                        let val = moisAnneeToInt(m, a);
                        if (( !start && !end ) || ( (!start || val >= start ) && ( !end || val <= end ))) {
                            if (!interetsParMois[key]) interetsParMois[key] = { mois: m, annee: a, somme: 0 };
                            interetsParMois[key].somme += d.interet;
                        }
                    });
                    // Trier par année/mois croissant
                    let lignes = Object.values(interetsParMois).sort((a, b) => moisAnneeToInt(a.mois, a.annee) - moisAnneeToInt(b.mois, b.annee));
                    // Affichage tableau
                    lignes.forEach(l => {
                        let tr = document.createElement('tr');
                        tr.innerHTML = `<td>${moisMap[l.mois]}</td><td>${l.annee}</td><td>${l.somme.toFixed(2)} €</td>`;
                        tbody.appendChild(tr);
                    });
                    // Affichage graphe
                    afficherGraphe(lignes);
                }

                function afficherGraphe(lignes) {
                    const ctx = document.getElementById('interetChart').getContext('2d');
                    const labels = lignes.map(l => `${moisMap[l.mois]} ${l.annee}`);
                    const data = lignes.map(l => l.somme.toFixed(2));
                    if (chart) chart.destroy();
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Somme des intérêts mensuels (€)',
                                data: data,
                                backgroundColor: '#2563eb',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: '#1e293b' }
                                },
                                x: {
                                    ticks: { color: '#1e293b' }
                                }
                            }
                        }
                    });
                }

                function handleFilter(event) {
                    event.preventDefault();
                    let moisDebut = document.getElementById('mois_debut').value;
                    let anneeDebut = document.getElementById('annee_debut').value;
                    let moisFin = document.getElementById('mois_fin').value;
                    let anneeFin = document.getElementById('annee_fin').value;
                    afficherTableau(moisDebut, anneeDebut, moisFin, anneeFin);
                    return false;
                }

                // Charger les données depuis l'API
                async function loadInteretsData() {
                    try {
                        const response = await fetch('/ws/api/prets');
                        const data = await response.json();
                        
                        if (data.success) {
                            // Transformer les données des prêts en données d'intérêts
                            donneesBrutes = data.data.map(pret => {
                                const datePret = new Date(pret.date_pret);
                                const mois = datePret.getMonth() + 1; // getMonth() retourne 0-11
                                const annee = datePret.getFullYear();
                                
                                // Calculer l'intérêt mensuel basé sur le taux et le montant
                                const tauxMensuel = (pret.taux_annuel || 0) / 100 / 12;
                                const interetMensuel = (pret.montant_emprunte || 0) * tauxMensuel;
                                
                                return {
                                    client: `${pret.client_nom || 'N/A'} ${pret.client_prenom || ''}`,
                                    pret: `Prêt #${pret.id}`,
                                    mois: moisMap[mois],
                                    annee: annee,
                                    interet: interetMensuel
                                };
                            });
                            
                            // Affichage initial
                            afficherTableau('', '', '', '');
                        } else {
                            console.error('Erreur:', data.message);
                            document.getElementById('tableBody').innerHTML = 
                                '<tr><td colspan="3" style="text-align: center; color: red;">Erreur lors du chargement des données</td></tr>';
                        }
                    } catch (error) {
                        console.error('Erreur réseau:', error);
                        document.getElementById('tableBody').innerHTML = 
                            '<tr><td colspan="3" style="text-align: center; color: red;">Erreur de connexion</td></tr>';
                    }
                }

                // Initialisation au chargement de la page
                document.addEventListener('DOMContentLoaded', function() {
                    loadInteretsData();
                });
                </script>
            </div>
        </main>
    </div>
    <script>
        function toggleFilters() {
            var filtersBlock = document.getElementById('filtersBlock');
            filtersBlock.classList.toggle('visible');
        }
    </script>
</body>
</html>

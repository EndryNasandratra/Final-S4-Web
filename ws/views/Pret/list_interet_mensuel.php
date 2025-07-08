<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Dynamique des Interets Gagnes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem;
            gap: 2rem;
        }
        .main-title {
            color: #2563eb;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            text-align: center;
        }
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group label {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        .filter-group select,
        .filter-group input {
            padding: 0.8rem;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            font-size: 1rem;
        }
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .filters button {
            padding: 0.8rem 1.5rem;
            border: none;
            background: #2563eb;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            height: fit-content;
        }
        .filters button:hover {
            background: #1d4ed8;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            margin-bottom: 2rem;
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #cbd5e1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 1rem;
            text-align: left;
        }
        th {
            background: #e0e7ff;
            color: #2563eb;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        tfoot {
            font-weight: bold;
        }
        .total-row td {
            background: #e0e7ff;
            text-align: right;
            font-weight: bold;
        }
        .total-row td:first-child {
            text-align: left;
        }
        .loading-message,
        .error-message {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 2rem;
        }
        .error-message {
            color: #dc2626;
            background: #fee2e2;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
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
                padding: 1rem;
            }
            .filters {
                flex-direction: column;
                gap: 1rem;
            }
            .chart-container {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">MNA_Banque</div> - Rapport des Interets Gagnes
    </div>
    <div class="layout">
        <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/settings.php">Parametres</a>
            <a href="validation_pret.php">Validation pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="ajout_pret.php">Ajout de pret</a>
            <a href="simulateur_pret.php">Simulateur de pret</a>
            <a href="#">Deconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Rapport des Interets Gagnes par Mois</h1>
            
            <div class="report-container">
                <div class="filters">
                    <div class="filter-group">
                        <label for="start-month">Date de debut</label>
                        <div style="display:flex; gap: 5px;">
                            <select id="start-month">
                                <?php for ($m = 1; $m <= 12; $m++) echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>"; ?>
                            </select>
                            <input type="number" id="start-year" value="<?= date('Y') - 1 ?>" style="width: 80px;">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="end-month">Date de fin</label>
                        <div style="display:flex; gap: 5px;">
                            <select id="end-month">
                                <?php for ($m = 1; $m <= 12; $m++) echo "<option value='$m' " . ($m == date('n') ? 'selected' : '') . ">" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>"; ?>
                            </select>
                            <input type="number" id="end-year" value="<?= date('Y') ?>" style="width: 80px;">
                        </div>
                    </div>
                    <button id="filter-button">Filtrer</button>
                </div>

                <div class="chart-container" id="chart-wrapper">
                    <canvas id="interest-chart"></canvas>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Interets Totaux Gagnes</th>
                        </tr>
                    </thead>
                    <tbody id="report-body">
                        <tr>
                            <td colspan="2" class="loading-message">Chargement...</td>
                        </tr>
                    </tbody>
                    <tfoot id="report-foot"></tfoot>
                </table>
            </div>
        </main>
    </div>

    <script>
        const apiBase = "http://localhost/Final_S4_Web/ws";
        const urlGetAllData = `${apiBase}/interets_mensuels`;
        const urlGetFilteredData = `${apiBase}/filtrer_interets_mensuels`;

        const startMonthEl = document.getElementById('start-month');
        const startYearEl = document.getElementById('start-year');
        const endMonthEl = document.getElementById('end-month');
        const endYearEl = document.getElementById('end-year');
        const filterButton = document.getElementById('filter-button');
        const reportBody = document.getElementById('report-body');
        const reportFoot = document.getElementById('report-foot');
        const chartWrapper = document.getElementById('chart-wrapper');

        let myChart = null;

        function showLoading() {
            reportBody.innerHTML = `<tr><td colspan="2" class="loading-message">Chargement des donnees...</td></tr>`;
            reportFoot.innerHTML = '';
            chartWrapper.style.display = 'none';
        }

        async function fetchInitialData() {
            showLoading();
            try {
                const response = await fetch(urlGetAllData);
                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                const data = await response.json();
                displayReport(data);
            } catch (error) {
                console.error('Erreur:', error);
                reportBody.innerHTML = `<tr><td colspan="2" class="error-message">Impossible de charger le rapport.</td></tr>`;
            }
        }

        async function fetchFilteredData() {
            showLoading();
            try {
                const params = new URLSearchParams({
                    start_year: startYearEl.value,
                    start_month: startMonthEl.value,
                    end_year: endYearEl.value,
                    end_month: endMonthEl.value
                });
                const finalUrl = `${urlGetFilteredData}?${params.toString()}`;
                const response = await fetch(finalUrl);
                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                const data = await response.json();
                displayReport(data);
            } catch (error) {
                console.error('Erreur:', error);
                reportBody.innerHTML = `<tr><td colspan="2" class="error-message">Impossible de charger le rapport filtre.</td></tr>`;
            }
        }

        function displayReport(data) {
            reportBody.innerHTML = '';
            reportFoot.innerHTML = '';

            if (Object.keys(data).length === 0) {
                reportBody.innerHTML = `<tr><td colspan="2" class="loading-message">Aucune donnee a afficher pour la periode selectionnee.</td></tr>`;
                chartWrapper.style.display = 'none';
                return;
            }
            chartWrapper.style.display = 'block';

            let totalGeneral = 0;
            const monthFormatter = new Intl.DateTimeFormat('fr-FR', {
                year: 'numeric',
                month: 'long'
            });
            const currencyFormatter = new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            });

            // Preparer les donnees pour le graphique
            const chartLabels = [];
            const chartDataPoints = [];

            for (const monthKey in data) {
                const totalInterets = data[monthKey];
                totalGeneral += totalInterets;
                const tr = document.createElement('tr');
                const date = new Date(monthKey + '-02');
                const formattedMonth = monthFormatter.format(date);
                tr.innerHTML = `<td>${formattedMonth.charAt(0).toUpperCase() + formattedMonth.slice(1)}</td><td style="text-align:right;">${currencyFormatter.format(totalInterets)}</td>`;
                reportBody.appendChild(tr);

                // Pour le graphique
                chartLabels.push(formattedMonth.charAt(0).toUpperCase() + formattedMonth.slice(1));
                chartDataPoints.push(totalInterets);
            }

            const totalRow = document.createElement('tr');
            totalRow.className = 'total-row';
            totalRow.innerHTML = `<td>Total General</td><td>${currencyFormatter.format(totalGeneral)}</td>`;
            reportFoot.appendChild(totalRow);

            // Detruire l'ancien graphique s'il existe
            if (myChart) {
                myChart.destroy();
            }

            const ctx = document.getElementById('interest-chart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Interets Gagnes',
                        data: chartDataPoints,
                        backgroundColor: 'rgba(37, 99, 235, 0.6)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return currencyFormatter.format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += currencyFormatter.format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Attacher les ecouteurs d'evenements
        document.addEventListener('DOMContentLoaded', fetchInitialData);
        filterButton.addEventListener('click', fetchFilteredData);
    </script>
</body>
</html>

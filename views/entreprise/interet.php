<?php
$api_url = "http://localhost/Final_S4//ws";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Dynamique des Interets Gagnes</title>
    <!-- 1. AJOUT DE LA BIBLIOTHeQUE CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .report-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .filter-group select,
        .filter-group input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .filters button {
            padding: 8px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            height: 37px;
        }

        .filters button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tfoot {
            font-weight: bold;
        }

        .total-row td {
            background-color: #e9ecef;
            text-align: right;
        }

        .total-row td:first-child {
            text-align: left;
        }

        .loading-message,
        .error-message {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }

        /* Style pour le conteneur du graphique */
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <h1>Rapport des Interets Gagnes par Mois</h1>
        <div class="filters">
            <div class="filter-group">
                <label for="start-month">Date de debut</label>
                <div style="display:flex; gap: 5px;">
                    <select id="start-month"><?php for ($m = 1; $m <= 12; $m++) echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>"; ?></select>
                    <input type="number" id="start-year" value="<?= date('Y') - 1 ?>" style="width: 80px;">
                </div>
            </div>
            <div class="filter-group">
                <label for="end-month">Date de fin</label>
                <div style="display:flex; gap: 5px;">
                    <select id="end-month"><?php for ($m = 1; $m <= 12; $m++) echo "<option value='$m' " . ($m == date('n') ? 'selected' : '') . ">" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>"; ?></select>
                    <input type="number" id="end-year" value="<?= date('Y') ?>" style="width: 80px;">
                </div>
            </div>
            <button id="filter-button">Filtrer</button>
        </div>

        <!-- 2. AJOUT DU CANVAS POUR LE GRAPHIQUE -->
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

    <script>
        const urlGetAllData = '<?= $api_url ?>/interets_mensuels';
        const urlGetFilteredData = '<?= $api_url ?>/filtrer_interets_mensuels';

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
            chartWrapper.style.display = 'none'; // Cacher le graphique pendant le chargement
        }

        function fetchInitialData() {
            showLoading();
            fetch(urlGetAllData)
                .then(response => {
                    if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                    return response.json();
                })
                .then(data => displayReport(data))
                .catch(error => {
                    reportBody.innerHTML = `<tr><td colspan="2" class="error-message">Impossible de charger le rapport.</td></tr>`;
                });
        }

        function fetchFilteredData() {
            showLoading();
            const params = new URLSearchParams({
                start_year: startYearEl.value,
                start_month: startMonthEl.value,
                end_year: endYearEl.value,
                end_month: endMonthEl.value
            });
            const finalUrl = `${urlGetFilteredData}?${params.toString()}`;
            fetch(finalUrl)
                .then(response => {
                    if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                    return response.json();
                })
                .then(data => displayReport(data))
                .catch(error => {
                    reportBody.innerHTML = `<tr><td colspan="2" class="error-message">Impossible de charger le rapport filtre.</td></tr>`;
                });
        }

        function displayReport(data) {
            // -- Partie Tableau (inchangee) --
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
                // Pour le tableau
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

            // -- Partie Graphique --
            // Detruire l'ancien graphique s'il existe (tres important lors du filtrage)
            if (myChart) {
                myChart.destroy();
            }

            const ctx = document.getElementById('interest-chart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'bar', // ou 'line' pour un graphique en ligne
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Interets Gagnes',
                        data: chartDataPoints,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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
                                // Formater l'axe Y pour afficher le symbole €
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

        document.addEventListener('DOMContentLoaded', fetchInitialData);
        filterButton.addEventListener('click', fetchFilteredData);
    </script>
</body>

</html>
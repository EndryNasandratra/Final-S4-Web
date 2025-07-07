<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des prêts</title>
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
        .filters-block input {
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
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.6rem;
            margin-Right: 5px;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #e0e7ff;
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
</head>
<body>
<div class="header"><div class="logo">MNA_Banque</div> - Gestion des ressources</div>
<div class="layout">
     <nav class="sidebar">
            <a href="list_pret.php">Accueil</a>
            <a href="../Ressources/ajout_ressource.php">Ajouter une ressource</a>
            <a href="create_type_pret.php">Ajouter un type de pret</a>
            <a href="create_pret.php">Ajouter un pret</a>
            <a href="list_interet_mensuel.php">Interet mensuel</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Liste des prêts</h2>
                <button type="button" class="filter-toggle-btn" onclick="toggleFilters()">Afficher les filtres</button>
                <form method="get">
                    <div class="filters-block" id="filtersBlock">
                        <div class="filter-group">
                            <label for="client">Client</label>
                            <input type="text" name="client" id="client" placeholder="Client" value="">
                        </div>
                        <div class="filter-group">
                            <label for="employe">Employé</label>
                            <input type="text" name="employe" id="employe" placeholder="Employé" value="">
                        </div>
                        <div class="filter-group">
                            <label for="taux">Taux</label>
                            <input type="text" name="taux" id="taux" placeholder="Taux" value="">
                        </div>
                        <div class="filter-group">
                            <label for="montant">Montant</label>
                            <input type="text" name="montant" id="montant" placeholder="Montant" value="">
                        </div>
                        <div class="filter-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" value="">
                        </div>
                        <div class="filter-group">
                            <label for="duree">Durée</label>
                            <input type="text" name="duree" id="duree" placeholder="Durée" value="">
                        </div>
                        <div class="filter-group">
                            <button type="submit">Filtrer</button>
                        </div>
                    </div>
                    <table>
                        <tr>
                            <th>Client</th>
                            <th>Employé</th>
                            <th>Taux (%)</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Durée</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Dupont Jean</td>
                            <td>Martin Sophie</td>
                            <td>3.5</td>
                            <td>10000.00</td>
                            <td>2024-06-01</td>
                            <td>24</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Durand Alice</td>
                            <td>Bernard Paul</td>
                            <td>2.9</td>
                            <td>5000.00</td>
                            <td>2024-05-15</td>
                            <td>12</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Petit Luc</td>
                            <td>Leroy Emma</td>
                            <td>4.1</td>
                            <td>20000.00</td>
                            <td>2024-04-20</td>
                            <td>36</td>
                            <td></td>
                        </tr>
                    </table>
                </form>
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
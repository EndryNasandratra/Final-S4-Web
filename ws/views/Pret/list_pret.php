<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des prêts - MNA Banque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
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
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.6rem;
            margin-right: 5px;
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
            margin-right: 0.5rem;
        }
        button:hover {
            background: #1d4ed8;
        }
        .btn-success {
            background: #059669;
        }
        .btn-success:hover {
            background: #047857;
        }
        .btn-danger {
            background: #dc2626;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-en-attente {
            background: #fef3c7;
            color: #92400e;
        }
        .status-valide {
            background: #d1fae5;
            color: #065f46;
        }
        .status-rejete {
            background: #fee2e2;
            color: #991b1b;
        }
        .status-en-cours {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-rembourse {
            background: #e0e7ff;
            color: #3730a3;
        }
        .status-en-retard {
            background: #fecaca;
            color: #7f1d1d;
        }
        .loading {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
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
            .filters-block.visible {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="header"><div class="logo">MNA_Banque</div> - Gestion des prêts</div>
<div class="layout">
    <nav class="sidebar">
        <a href="list_pret.php">Accueil</a>
        <a href="../Ressources/settings.php">Paramètres</a>
        <a href="validation_pret.php">Validation prêt</a>
        <a href="list_interet_mensuel.php">Intérêt mensuel</a>
        <a href="simulation_pret.php">Simulation de prêt</a>
        <a href="#">Déconnexion</a>
    </nav>
    <main class="main-content">
        <div class="container">
            <h2>Liste des prêts</h2>
            <button type="button" class="filter-toggle-btn" onclick="toggleFilters()">Afficher les filtres</button>
            
            <div class="filters-block" id="filtersBlock">
                <div class="filter-group">
                    <label for="clientFilter">Client</label>
                    <input type="text" id="clientFilter" placeholder="Filtrer par client">
                </div>
                <div class="filter-group">
                    <label for="employeFilter">Employé</label>
                    <input type="text" id="employeFilter" placeholder="Filtrer par employé">
                </div>
                <div class="filter-group">
                    <label for="statusFilter">Statut</label>
                    <select id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="En attente">En attente</option>
                        <option value="Validé">Validé</option>
                        <option value="Rejeté">Rejeté</option>
                        <option value="En cours">En cours</option>
                        <option value="Remboursé">Remboursé</option>
                        <option value="En retard">En retard</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="montantFilter">Montant min</label>
                    <input type="number" id="montantFilter" placeholder="Montant minimum">
                </div>
                <div class="filter-group">
                    <label for="dateFilter">Date</label>
                    <input type="date" id="dateFilter">
                </div>
                <div class="filter-group">
                    <button type="button" onclick="applyFilters()">Appliquer les filtres</button>
                    <button type="button" onclick="clearFilters()">Effacer</button>
                </div>
            </div>

            <div id="prets-container">
                <div class="loading">Chargement des prêts...</div>
            </div>
        </div>
    </main>
</div>

<script>
    let prets = [];
    let filteredPrets = [];

    // Charger les prêts au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        loadPrets();
    });

    async function loadPrets() {
        try {
            const response = await fetch('/ws/api.php?endpoint=prets');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            prets = data.data || [];
            filteredPrets = [...prets];
            displayPrets();
        } catch (error) {
            console.error('Erreur réseau:', error);
            document.getElementById('prets-container').innerHTML = 
                '<div class="error">Erreur lors du chargement des données: ' + error.message + '</div>';
        }
    }

    function displayPrets() {
        const container = document.getElementById('prets-container');
        
        if (filteredPrets.length === 0) {
            container.innerHTML = '<div class="loading">Aucun prêt trouvé</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Employé</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        filteredPrets.forEach(pret => {
            const statusClass = getStatusClass(pret.statut_libelle);
            const actions = getActions(pret);
            
            html += `
                <tr>
                    <td>${pret.id}</td>
                    <td>${pret.client_nom || 'N/A'} ${pret.client_prenom || ''}</td>
                    <td>${pret.employe_nom || 'N/A'} ${pret.employe_prenom || ''}</td>
                    <td>${formatCurrency(pret.montant_emprunte)}</td>
                    <td>${formatDate(pret.date_pret)}</td>
                    <td><span class="status-badge ${statusClass}">${pret.statut_libelle || 'N/A'}</span></td>
                    <td>${actions}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    function getStatusClass(status) {
        switch(status) {
            case 'En attente': return 'status-en-attente';
            case 'Validé': return 'status-valide';
            case 'Rejeté': return 'status-rejete';
            case 'En cours': return 'status-en-cours';
            case 'Remboursé': return 'status-rembourse';
            case 'En retard': return 'status-en-retard';
            default: return '';
        }
    }

    function getActions(pret) {
        if (pret.statut_libelle === 'En attente') {
            return `
                <button class="btn-success" onclick="validerPret(${pret.id})">Valider</button>
                <button class="btn-danger" onclick="rejeterPret(${pret.id})">Rejeter</button>
            `;
        }
        return '';
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('fr-FR');
    }

    function toggleFilters() {
        const filtersBlock = document.getElementById('filtersBlock');
        filtersBlock.classList.toggle('visible');
    }

    function applyFilters() {
        const clientFilter = document.getElementById('clientFilter').value.toLowerCase();
        const employeFilter = document.getElementById('employeFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const montantFilter = document.getElementById('montantFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;

        filteredPrets = prets.filter(pret => {
            const clientMatch = !clientFilter || 
                (pret.client_nom && pret.client_nom.toLowerCase().includes(clientFilter)) ||
                (pret.client_prenom && pret.client_prenom.toLowerCase().includes(clientFilter));
            
            const employeMatch = !employeFilter || 
                (pret.employe_nom && pret.employe_nom.toLowerCase().includes(employeFilter)) ||
                (pret.employe_prenom && pret.employe_prenom.toLowerCase().includes(employeFilter));
            
            const statusMatch = !statusFilter || pret.statut_libelle === statusFilter;
            
            const montantMatch = !montantFilter || pret.montant_emprunte >= parseFloat(montantFilter);
            
            const dateMatch = !dateFilter || pret.date_pret === dateFilter;

            return clientMatch && employeMatch && statusMatch && montantMatch && dateMatch;
        });

        displayPrets();
    }

    function clearFilters() {
        document.getElementById('clientFilter').value = '';
        document.getElementById('employeFilter').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('montantFilter').value = '';
        document.getElementById('dateFilter').value = '';
        
        filteredPrets = [...prets];
        displayPrets();
    }

    async function validerPret(pretId) {
        if (confirm('Êtes-vous sûr de vouloir valider ce prêt ?')) {
            try {
                const response = await fetch(`/ws/api.php?endpoint=valider-pret-${pretId}`, {
                    method: 'GET'
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.status === 'success') {
                        alert('Prêt validé avec succès !');
                        loadPrets(); // Recharger les données
                    } else {
                        alert('Erreur lors de la validation du prêt: ' + result.message);
                    }
                } else {
                    alert('Erreur lors de la validation du prêt');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la validation du prêt');
            }
        }
    }

    async function rejeterPret(pretId) {
        if (confirm('Êtes-vous sûr de vouloir rejeter ce prêt ?')) {
            try {
                const response = await fetch(`/ws/api.php?endpoint=rejeter-pret-${pretId}`, {
                    method: 'GET'
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.status === 'success') {
                        alert('Prêt rejeté avec succès !');
                        loadPrets(); // Recharger les données
                    } else {
                        alert('Erreur lors du rejet du prêt: ' + result.message);
                    }
                } else {
                    alert('Erreur lors du rejet du prêt');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors du rejet du prêt');
            }
        }
    }
</script>
</body>
</html>
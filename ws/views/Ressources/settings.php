<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banque</title>
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
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            flex: 1;
        }
        .left-column {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        .container {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 2rem 1.5rem;
            box-sizing: border-box;
            height: fit-content;
        }
        .container.full-width {
            grid-column: 1 / -1;
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
        h2 {
            color: #2563eb;
            margin-bottom: 1.2rem;
            font-size: 1.2rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        label {
            color: #1e293b;
            margin-bottom: 0.2rem;
            font-weight: 500;
        }
        input, select {
            padding: 0.5rem 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        button {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.6rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 500;
        }
        button:hover {
            background: #1d4ed8;
        }
        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 0.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .statistics-card {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .statistics-card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }
        .statistics-card p {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .left-column, .right-column {
                gap: 1.5rem;
            }
        }
        @media (max-width: 700px) {
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
            .content-grid {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header"><div class="logo">MNA_Banque</div> - Gestion des ressources</div>
    <div class="layout">
        <nav class="sidebar">
            <a href="../pret/list_pret.php">Accueil</a>
            <a href="settings.php">Parametres</a>
            <a href="../pret/validation_pret.php">Validation pret</a>
            <a href="../pret/list_interet_mensuel.php">Interet mensuel</a>
            <a href="../pret/ajout_pret.php">Ajout de prêt</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <h1 class="main-title">Paramètres</h1>
            
            <div class="content-grid">
                <div class="left-column">
                    <div class="container">
                        <h2>Ajouter un type de ressource</h2>
                        <form id="addTypeRessourceForm">
                            <label for="libelle">Libellé du type :</label>
                            <input type="text" id="libelle" name="libelle" placeholder="Ex: Liquidités, Immobilisations..." required>
                            <button type="submit">Ajouter le type</button>
                        </form>
                    </div>
                    
                    <div class="container">
                        <h2>Ajouter une ressource</h2>
                        <form id="addRessourceForm">
                            <label for="type">Type de ressource :</label>
                            <select id="type" name="type" required>
                                <option value="">-- Sélectionner --</option>
                            </select>
                            <label for="valeur">Valeur :</label>
                            <input type="number" step="0.01" id="valeur" name="valeur" placeholder="0.00" required>
                            <button type="submit">Ajouter la ressource</button>
                        </form>
                    </div>
                </div>
                
                <div class="right-column">
                    <div class="container">
                        <h2>Statistiques</h2>
                        <div id="statistics">
                            <p>Chargement des statistiques...</p>
                        </div>
                    </div>
                    
                    <div class="container">
                        <h2>Liste des ressources</h2>
                        <div id="ressourcesList">
                            <p>Chargement des ressources...</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
    
    <script>
        // Variables globales
        let typesRessource = [];
        let ressources = [];

        // Charger les types de ressources
        async function loadTypesRessource() {
            try {
                const response = await fetch('http://localhost/Final-S4-Web/ws/types-ressource');
                const data = await response.json();
                
                typesRessource = data;
                const select = document.getElementById('type');
                select.innerHTML = '<option value="">-- Sélectionner --</option>';
                
                typesRessource.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = type.libelle;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des types de ressources:', error);
            }
        }

        // Charger les ressources
        async function loadRessources() {
            try {
                const response = await fetch('http://localhost/Final-S4-Web/ws/ressources');
                const data = await response.json();
                
                ressources = data;
                displayRessources(ressources);
            } catch (error) {
                console.error('Erreur réseau:', error);
                document.getElementById('ressourcesList').innerHTML = 
                    '<p style="color: red;">Erreur de connexion</p>';
            }
        }

        // Afficher les ressources
        function displayRessources(ressources) {
            const container = document.getElementById('ressourcesList');
            
            if (ressources.length === 0) {
                container.innerHTML = '<p>Aucune ressource trouvée</p>';
                return;
            }

            let html = '<table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">';
            html += '<thead><tr style="background: #e0e7ff; color: #2563eb;">';
            html += '<th style="border: 1px solid #cbd5e1; padding: 0.7rem; text-align: left;">Type</th>';
            html += '<th style="border: 1px solid #cbd5e1; padding: 0.7rem; text-align: left;">Valeur</th>';
            html += '<th style="border: 1px solid #cbd5e1; padding: 0.7rem; text-align: left;">Actions</th>';
            html += '</tr></thead><tbody>';

            ressources.forEach(ressource => {
                html += '<tr style="border-bottom: 1px solid #e2e8f0;">';
                html += `<td style="border: 1px solid #cbd5e1; padding: 0.7rem;">${ressource.type_ressource_libelle || 'N/A'}</td>`;
                html += `<td style="border: 1px solid #cbd5e1; padding: 0.7rem;">${formatCurrency(ressource.valeur)}</td>`;
                html += `<td style="border: 1px solid #cbd5e1; padding: 0.7rem;">
                    <button onclick="editRessource(${ressource.id})" style="background: #059669; margin-right: 5px;">Modifier</button>
                    <button onclick="deleteRessource(${ressource.id})" style="background: #dc2626;">Supprimer</button>
                </td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // Charger les statistiques
        async function loadStatistics() {
            try {
                const response = await fetch('http://localhost/Final-S4-Web/ws/ressources/total');
                const data = await response.json();
                
                if (data.success) {
                    const total = data.data.total;
                    document.getElementById('statistics').innerHTML = `
                        <div class="statistics-card">
                            <h3>Total des ressources</h3>
                            <p>${formatCurrency(total)}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erreur lors du chargement des statistiques:', error);
                document.getElementById('statistics').innerHTML = 
                    '<p style="color: red;">Erreur lors du chargement des statistiques</p>';
            }
        }

        // Fonction utilitaire pour formater les montants
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount || 0);
        }

        // Gérer l'ajout d'un type de ressource
        document.getElementById('addTypeRessourceForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const libelle = document.getElementById('libelle').value.trim();

            if (!libelle) {
                alert('Veuillez saisir un libellé');
                return;
            }

            try {
                const response = await fetch('http://localhost/Final-S4-Web/ws/types-ressource', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ libelle: libelle })
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    alert('Type de ressource ajouté avec succès !');
                    document.getElementById('libelle').value = '';
                    // Recharger les types de ressources
                    loadTypesRessource();
                } else {
                    alert('Erreur lors de l\'ajout: ' + (result.error || 'Erreur inconnue'));
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout du type de ressource');
            }
        });

        // Gérer l'ajout d'une ressource
        document.getElementById('addRessourceForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const typeId = document.getElementById('type').value;
            const valeur = parseFloat(document.getElementById('valeur').value);

            if (!typeId || !valeur) {
                alert('Veuillez remplir tous les champs');
                return;
            }

            try {
                const response = await fetch('http://localhost/Final-S4-Web/ws/ressources', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        id_type_ressource: typeId,
                        valeur: valeur 
                    })
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    alert('Ressource ajoutée avec succès !');
                    document.getElementById('type').value = '';
                    document.getElementById('valeur').value = '';
                    // Recharger les ressources et statistiques
                    loadRessources();
                    loadStatistics();
                } else {
                    alert('Erreur lors de l\'ajout: ' + (result.error || 'Erreur inconnue'));
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout de la ressource');
            }
        });

        // Fonctions d'action (à implémenter)
        function editRessource(id) {
            alert('Fonctionnalité de modification à implémenter pour la ressource ' + id);
        }

        function deleteRessource(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')) {
                alert('Fonctionnalité de suppression à implémenter pour la ressource ' + id);
                // Recharger les ressources après suppression
                // loadRessources();
            }
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadTypesRessource();
            loadRessources();
            loadStatistics();
        });
    </script>
</body>
</html>
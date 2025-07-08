<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de pret</title>
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
        <div class="logo">MNA_Banque</div> - Ajout de pret
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
            <div class="container">
                <h2>Ajout de pret</h2>
                <form id="ajoutPretForm">
                    <div class="form-section">
                        <div class="form-group">
                            <label for="client">Client</label>
                            <select name="client" id="client" required>
                                <option value="">Selectionner un client</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employe">Employe</label>
                            <select name="employe" id="employe" required>
                                <option value="">Selectionner un employe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taux_pret">Taux de pret</label>
                            <select name="taux_pret" id="taux_pret" required>
                                <option value="">Selectionner un taux</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taux_assurance">Taux d'assurance</label>
                            <select name="taux_assurance" id="taux_assurance" required>
                                <option value="">Selectionner un taux d'assurance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="montant">Montant emprunte (€)</label>
                            <input type="number" id="montant" name="montant" step="0.01" placeholder="10000" required>
                        </div>
                        <div class="form-group">
                            <label for="date_pret">Date du pret</label>
                            <input type="date" id="date_pret" name="date_pret" required>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success">Ajouter le pret</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='list_pret.php'">Annuler</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Charger les clients
        async function loadClients() {
            try {
                const response = await fetch('http://localhost/Final_S4_Web/ws/clients');
                const data = await response.json();

                const select = document.getElementById('client');
                select.innerHTML = '<option value="">Selectionner un client</option>';

                data.forEach(client => {
                    const option = document.createElement('option');
                    option.value = client.id;
                    option.textContent = `${client.nom} ${client.prenom}`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des clients:', error);
            }
        }

        // Charger les employes
        async function loadEmployes() {
            try {
                const response = await fetch('http://localhost/Final_S4_Web/ws/employes');
                const data = await response.json();

                const select = document.getElementById('employe');
                select.innerHTML = '<option value="">Selectionner un employe</option>';

                data.forEach(employe => {
                    const option = document.createElement('option');
                    option.value = employe.id;
                    option.textContent = `${employe.nom} ${employe.prenom}`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des employes:', error);
            }
        }

        // Charger les taux de pret
        async function loadTauxPret() {
            try {
                const response = await fetch('http://localhost/Final_S4_Web/ws/taux-pret');
                const data = await response.json();

                const select = document.getElementById('taux_pret');
                select.innerHTML = '<option value="">Selectionner un taux</option>';

                data.forEach(taux => {
                    const option = document.createElement('option');
                    option.value = taux.id;
                    option.textContent = `${taux.taux_annuel}% - ${taux.duree} mois`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des taux de pret:', error);
            }
        }

        // Charger les taux d'assurance
        async function loadTauxAssurance() {
            try {
                const response = await fetch('http://localhost/Final_S4_Web/ws/taux-assurance');
                const data = await response.json();

                const select = document.getElementById('taux_assurance');
                select.innerHTML = '<option value="">Selectionner un taux d\'assurance</option>';

                data.forEach(taux => {
                    const option = document.createElement('option');
                    option.value = taux.id;
                    option.textContent = `${taux.taux}%`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des taux d\'assurance:', error);
            }
        }

        // Gerer la soumission du formulaire
        document.getElementById('ajoutPretForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                id_client: formData.get('client'),
                id_employe: formData.get('employe'),
                id_taux_pret: formData.get('taux_pret'),
                id_taux_assurance: formData.get('taux_assurance'),
                montant_emprunte: formData.get('montant'),
                date_pret: formData.get('date_pret')
            };

            try {
                const response = await fetch('http://localhost/Final_S4_Web/ws/prets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Pret ajoute avec succes !');
                    window.location.href = 'list_pret.php';
                } else {
                    alert('Erreur lors de l\'ajout du pret: ' + (result.error || 'Erreur inconnue'));
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout du pret');
            }
        });

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadClients();
            loadEmployes();
            loadTauxPret();
            loadTauxAssurance();

            // Definir la date d'aujourd'hui par defaut
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_pret').value = today;
        });
    </script>
</body>

</html>
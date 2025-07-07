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
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .container {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 2rem 1.5rem;
            min-width: 30%;
            height: auto;
            box-sizing: border-box;
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
        }
        input, select {
            padding: 0.5rem 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.6rem 1rem;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #1d4ed8;
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
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="header"><div class="logo">MNA_Banque</div> - Gestion des ressources</div>
    <div class="layout">
        <nav class="sidebar">
            <a href="../pret/list_pret.php">Accueil</a>
            <a href="ajout_ressource.php">Ajouter une ressource</a>
            <a href="../pret/create_type_pret.php">Ajouter un type de pret</a>
            <a href="../pret/create_pret.php">Ajouter un pret</a>
            <a href="../pret/list_interet_mensuel.php">Interet mensuel</a>
            <a href="#">Déconnexion</a>
        </nav>
        <main class="main-content">
            <div class="container">
                <h2>Ajouter une ressource</h2>
                <form action="/ajout-ressource" method="POST">
                    <label for="type">Type de ressource :</label>
                    <select id="type" name="type" required>
                        <option value="">-- Sélectionner --</option>
                        <!-- Les options doivent être générées dynamiquement -->
                    </select>
                    <label for="valeur">Valeur :</label>
                    <input type="number" step="0.01" id="valeur" name="valeur" required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
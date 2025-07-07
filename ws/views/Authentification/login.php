<?php
require_once '../../db.php'; // Chemin vers getDB()
require_once '../../controllers/LoginController.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Utilise la fonction de connexion existante
    $pdo = getDB();

    // Passe l'objet PDO au contrôleur
    $loginController = new LoginController($pdo);
    $result = $loginController->login($email, $password);

    if ($result && $result['success']) {
        header('Location: /dashboard.php');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banque</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 1.5rem;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #e0e7ff;
        }
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        input, select {
            padding: 0.7rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border 0.2s;
        }
        input:focus, select:focus {
            border: 1.5px solid #2563eb;
        }
        button {
            padding: 0.7rem 1rem;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background: #1d4ed8;
        }
        .register-link {
            margin-top: 1rem;
            text-align: center;
        }
        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: text-decoration 0.2s;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .container {
                padding: 1.2rem 0.5rem;
                max-width: 95vw;
            }
            .logo {
                font-size: 1.3rem;
            }
        }
        footer {
            margin-top: 1rem;
            text-align: center;
        }
        .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">MNA_Banque</div>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="post"> 
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Nom d'utilisateur" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <footer>
        <span>Copyright 2025 @ MNA_Banque</span>
    </footer>
</body>
</html>
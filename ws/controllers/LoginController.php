<?php
require_once __DIR__ . '/../models/LoginModel.php';

class LoginController
{
    private $loginModel;

    public function __construct($db)
    {
        $this->loginModel = new LoginModel($db);
    }

    public function login($username, $password)
    {
        $user = $this->loginModel->validateUser($username, $password);
        if ($user) {
            // Ici, tu peux gérer la session ou retourner l'utilisateur
            return [
                'success' => true,
                'user' => $user
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Nom d\'utilisateur ou mot de passe incorrect'
            ];
        }
    }
}

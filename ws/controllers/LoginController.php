<?php
require_once __DIR__ . '/../models/LoginModel.php';
require_once __DIR__ . '/../helpers/Utils.php';

class LoginController
{
    public static function login()
    {
        $request = Flight::request();
        $email = $request->data->email;
        $password = $request->data->password;

        $result = LoginModel::validateUser($email, $password);

        if ($result) {
            Flight::json([
                'success' => true,
                'redirect' => 'http://localhost/ITU/S4/Final-S4-Web/ws/views/Pret/list_pret.php'
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => "Nom d'utilisateur ou mot de passe incorrect"
            ]);
        }
    }
}

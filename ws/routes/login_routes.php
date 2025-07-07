<?php
require_once __DIR__ . '/../controllers/LoginController.php';

Flight::route('POST /login', function () {
    $data = Flight::request()->data;
    $db = Flight::get('db');
    $controller = new LoginController($db);

    $result = $controller->login($data['username'], $data['password']);
    Flight::json($result);
});

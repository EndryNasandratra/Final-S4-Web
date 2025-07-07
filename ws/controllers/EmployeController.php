<?php
require_once __DIR__ . '/../models/Employe.php';

class EmployeController {
    public static function getAll() {
        $employes = Employe::getAll();
        Flight::json($employes);
    }

    public static function getById($id) {
        $employe = Employe::getById($id);
        Flight::json($employe);
    }
}
?>
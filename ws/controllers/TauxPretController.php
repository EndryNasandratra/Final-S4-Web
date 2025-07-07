<?php
require_once __DIR__ . '/../models/TauxPret.php';

class TauxPretController {
    public static function getAll() {
        $tauxPrets = TauxPret::getAll();
        Flight::json($tauxPrets);
    }

    public static function getById($id) {
        $tauxPret = TauxPret::getById($id);
        Flight::json($tauxPret);
    }
}
?>
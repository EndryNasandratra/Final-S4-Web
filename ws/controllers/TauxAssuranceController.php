<?php
require_once __DIR__ . '/../models/TauxAssurance.php';

class TauxAssuranceController {
    public static function getAll() {
        $tauxAssurances = TauxAssurance::getAll();
        Flight::json($tauxAssurances);
    }

    public static function getById($id) {
        $tauxAssurance = TauxAssurance::getById($id);
        Flight::json($tauxAssurance);
    }
}
?>
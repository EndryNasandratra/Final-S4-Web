<?php
require_once __DIR__ . '/../models/Remboursement.php';

class RemboursementController {
    public static function getAll() {
        $remboursements = Remboursement::getAll();
        Flight::json($remboursements);
    }

    public static function getByPretId($pretId) {
        $remboursements = Remboursement::getByPretId($pretId);
        Flight::json($remboursements);
    }

    public static function create() {
        $data = Flight::request()->data;
        
        if (empty($data->id_pret) || empty($data->montant_retour) || empty($data->date_retour)) {
            Flight::json(['error' => 'Tous les champs obligatoires doivent etre remplis'], 400);
            return;
        }

        try {
            $remboursement = Remboursement::create($data);
            Flight::json(['success' => true, 'data' => $remboursement], 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
?>
<?php
require_once __DIR__ . '/../controllers/RemboursementController.php';

// Routes pour les remboursements
Flight::route('GET /remboursements', ['RemboursementController', 'getAll']);
Flight::route('POST /remboursements', ['RemboursementController', 'create']);
Flight::route('GET /remboursements/@id', ['RemboursementController', 'getById']);
Flight::route('PUT /remboursements/@id', ['RemboursementController', 'update']);
Flight::route('DELETE /remboursements/@id', ['RemboursementController', 'delete']);

Flight::route('GET /remboursements', ['RemboursementController', 'getAll']);
Flight::route('GET /remboursements/pret/@id', ['RemboursementController', 'getByPretId']);
Flight::route('POST /remboursements', ['RemboursementController', 'create']);

// Routes spécifiques pour les remboursements par prêt
Flight::route('GET /prets/@id/remboursements', ['RemboursementController', 'getByPretId']);
Flight::route('GET /prets/@id/total-remboursement', ['RemboursementController', 'getTotalRemboursementByPret']);

// Route pour récupérer les prêts avec leurs remboursements
Flight::route('GET /prets-avec-remboursements', ['RemboursementController', 'getPretsAvecRemboursement']);
?>
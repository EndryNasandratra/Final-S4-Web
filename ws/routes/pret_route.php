<?php
require_once __DIR__ . '/../controllers/PretController.php';
require_once __DIR__ . '/../controllers/ClientController.php';
require_once __DIR__ . '/../controllers/EmployeController.php';
require_once __DIR__ . '/../controllers/TauxPretController.php';
require_once __DIR__ . '/../controllers/TauxAssuranceController.php';

Flight::route('GET /prets', ['PretController', 'getAll']);
Flight::route('POST /prets', ['PretController', 'create']);
Flight::route('GET /prets/validated', ['PretController', 'getValidated']);
Flight::route('GET /prets/not-validated', ['PretController', 'getNotValidated']);
Flight::route('GET /prets/@id', ['PretController', 'getById']);
Flight::route('PUT /prets/@id/validate', ['PretController', 'validerPret']);
Flight::route('PUT /prets/@id/reject', ['PretController', 'rejeterPret']);
Flight::route('GET /prets/validated/filter', ['PretController', 'filterValidated']);
Flight::route('GET /prets/montant-dispo-par-mois', ['PretController', 'getMontantDispoParMois']);

// Routes pour les clients
Flight::route('GET /clients', ['ClientController', 'getAll']);
Flight::route('GET /clients/@id', ['ClientController', 'getById']);

// Routes pour les employes
Flight::route('GET /employes', ['EmployeController', 'getAll']);
Flight::route('GET /employes/@id', ['EmployeController', 'getById']);

// Routes pour les taux de pret
Flight::route('GET /taux-pret', ['TauxPretController', 'getAll']);
Flight::route('GET /taux-pret/@id', ['TauxPretController', 'getById']);

// Routes pour les taux d'assurance
Flight::route('GET /taux-assurance', ['TauxAssuranceController', 'getAll']);
Flight::route('GET /taux-assurance/@id', ['TauxAssuranceController', 'getById']);

?>
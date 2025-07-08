<?php
require_once __DIR__ . '/../controllers/SimulateurController.php';

Flight::route('GET /simulateur', ['SimulationController', 'displaySimulator']);

Flight::route('POST /simulateur/calculer', ['SimulationController', 'handleCalculation']);

Flight::route('GET /type_pret', ['SimulationController', 'getAllTypePret']);

Flight::route('GET /taux_pret', ['SimulationController', 'getAllTauxPretById']);

Flight::route('GET /allClients', ['SimulationController', 'getAllClients']);

Flight::route('POST /simulateur/valider', ['SimulationController', 'validerPret']);

Flight::route('POST /simulateur/export_pdf', ['SimulationController', 'exportSimulationPDF']);

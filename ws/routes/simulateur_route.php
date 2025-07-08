<?php
require_once __DIR__ . '/../controllers/SimulateurController.php';

Flight::route('GET /simulateur', ['SimulationController', 'displaySimulator']);

Flight::route('POST /simulateur/calculer', ['SimulationController', 'handleCalculation']);

Flight::route('GET /type_pret', ['SimulationController', 'getAllTypePret']);

Flight::route('GET /taux_pret', ['SimulationController', 'getAllTauxPretById']);

Flight::route('GET /all_taux_pret', ['SimulationController', 'getAllTauxPret']);

Flight::route('GET /all_taux_assurance', ['SimulationController', 'getAllTauxAssurance']);

Flight::route('GET /allClients', ['SimulationController', 'getAllClients']);

Flight::route('POST /simulateur/valider', ['SimulationController', 'validerPret']);

Flight::route('POST /simulateur/export_pdf', ['SimulationController', 'exportSimulationPDF']);

Flight::route('POST /simulateur/calculer_sans_client', ['SimulationController', 'calculerPretSansClient']);

Flight::route('POST /simulateur/save', ['SimulationController', 'saveSimulation']);

Flight::route('GET /simulations', ['SimulationController', 'getAllSimulations']);

Flight::route('POST /simulations/compare', ['SimulationController', 'compareSimulations']);

Flight::route('POST /simulations/savePretBySimulation', ['SimulationController', 'savePretBySimulation']);

<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/etudiant_routes.php';
require 'routes/pret_route.php';
require 'routes/ressource_routes.php';
require 'routes/ressources_routes.php';
require 'routes/interet_routes.php';
require 'routes/simulateur_route.php';
require 'routes/app_routes.php';
require 'routes/login_routes.php';

Flight::start();

?>
<?php

class SimpleController {
    public static function getClients() {
        $clients = [
            ['id' => 1, 'nom' => 'Dupont', 'prenom' => 'Jean'],
            ['id' => 2, 'nom' => 'Durand', 'prenom' => 'Alice'],
            ['id' => 3, 'nom' => 'Petit', 'prenom' => 'Luc']
        ];
        Flight::json($clients);
    }

    public static function getEmployes() {
        $employes = [
            ['id' => 1, 'nom' => 'Martin', 'prenom' => 'Sophie'],
            ['id' => 2, 'nom' => 'Bernard', 'prenom' => 'Paul'],
            ['id' => 3, 'nom' => 'Leroy', 'prenom' => 'Emma']
        ];
        Flight::json($employes);
    }

    public static function getTauxPret() {
        $taux = [
            ['id' => 1, 'taux_annuel' => 3.5, 'duree' => 24],
            ['id' => 2, 'taux_annuel' => 2.9, 'duree' => 12],
            ['id' => 3, 'taux_annuel' => 4.1, 'duree' => 36]
        ];
        Flight::json($taux);
    }

    public static function getTauxAssurance() {
        $taux = [
            ['id' => 1, 'taux' => 0.5],
            ['id' => 2, 'taux' => 0.8],
            ['id' => 3, 'taux' => 1.2]
        ];
        Flight::json($taux);
    }
}
?> 
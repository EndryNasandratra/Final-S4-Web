<?php

require_once __DIR__ . '/../models/SimulationModel.php';

class SimulationController
{
    public static function displaySimulator()
    {
        Flight::render('views/simulateur/simulateur.php');
    }

    public static function handleCalculation()
    {
        $data = Flight::request()->data;
        $montant = (float) $data->montant;
        $dureeMois = (int) $data->duree_mois;

        if ($montant <= 0 || $dureeMois <= 0) {
            Flight::json(['error' => 'Le montant et la durée doivent être positifs.'], 400);
            return;
        }

        try {
            $tauxPret = SimulationModel::getTauxAnuel($dureeMois, $montant);

            if (!$tauxPret) {
                Flight::json(['error' => 'Aucun taux trouvé pour ce montant et cette durée.'], 404);
                return;
            }
            $tauxAnnuelInteret = (float) $tauxPret['taux_annuel'];

            $tauxAssurance = SimulationModel::getTauxAssurance();
            $tauxAnnuelAssurance = $tauxAssurance ? (float) $tauxAssurance['taux'] : 0;

            $tauxMensuelInteret = ($tauxAnnuelInteret / 100) / 12;
            $mensualiteHorsAssurance = 0;
            if ($tauxMensuelInteret > 0) {
                $mensualiteHorsAssurance = $montant * ($tauxMensuelInteret * pow(1 + $tauxMensuelInteret, $dureeMois)) / (pow(1 + $tauxMensuelInteret, $dureeMois) - 1);
            } else {
                $mensualiteHorsAssurance = $montant / $dureeMois;
            }

            $mensualiteAssurance = ($montant * ($tauxAnnuelAssurance / 100)) / 12;

            $mensualiteTotale = $mensualiteHorsAssurance + $mensualiteAssurance;
            $coutTotalCredit = $mensualiteTotale * $dureeMois - $montant;

            $resultat = [
                'montant_emprunte' => $montant,
                'duree_mois' => $dureeMois,
                'taux_interet_annuel' => $tauxAnnuelInteret,
                'taux_assurance_annuel' => $tauxAnnuelAssurance,
                'mensualite_hors_assurance' => round($mensualiteHorsAssurance, 2),
                'mensualite_assurance' => round($mensualiteAssurance, 2),
                'mensualite_totale' => round($mensualiteTotale, 2),
                'cout_total_credit' => round($coutTotalCredit, 2)
            ];

            Flight::json($resultat);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur serveur lors du calcul.', 'details' => $e->getMessage()], 500);
        }
    }
    public static function getAllTypePret()
    {
        Flight::json(AppModel::getAll("type_pret"));
    }
}

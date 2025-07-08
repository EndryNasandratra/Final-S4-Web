<?php

require_once __DIR__ . '/../models/InteretModel.php';

class InteretController
{
    public static function showInterestsReport()
    {
        try {
            $donnees = InteretModel::calculateInterestByMonth();
            Flight::json($donnees);
        } catch (Exception $e) {
            Flight::json(['error' => 'Impossible de générer le rapport', 'details' => $e->getMessage()], 500);
        }
    }
    public static function getDetailedFilteredInterestsReport()
    {
        try {
            $startYear = Flight::request()->query['start_year'];
            $startMonth = Flight::request()->query['start_month'];
            $endYear = Flight::request()->query['end_year'];
            $endMonth = Flight::request()->query['end_month'];

            $donnees = InteretModel::calculateInterestByMonthDetailedFilter(
                $startYear,
                $startMonth,
                $endYear,
                $endMonth
            );

            Flight::json($donnees);
        } catch (Exception $e) {
            Flight::json([
                'error' => 'Une erreur est survenue lors de la génération du rapport détaillé.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

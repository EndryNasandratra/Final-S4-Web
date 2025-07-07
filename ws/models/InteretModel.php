<?php

require_once __DIR__ . '/AppModel.php';

class InteretModel
{
    // formule : Intérêt_m = Capital_rest_du_(m-1) × (Taux_annuel / 12)
    public static function calculateInterestByMonth()
    {
        $allRepayments = AppModel::getAll("v_interert_pret");

        $interetsParMois = [];
        $capitalRestantParPret = [];

        foreach ($allRepayments as $repayment) {
            $id_pret = $repayment['id_pret'];

            if (!isset($capitalRestantParPret[$id_pret])) {
                $capitalRestantParPret[$id_pret] = (float) $repayment['montant_emprunte'];
            }
            $capitalAvantPaiement = $capitalRestantParPret[$id_pret];
            $tauxMensuel = ($repayment['taux_annuel'] / 100) / 12;
            $interetDuMois = $capitalAvantPaiement * $tauxMensuel;

            $capitalRembourse = $repayment['montant_retour'] - $interetDuMois;
            $capitalRestantParPret[$id_pret] -= $capitalRembourse;

            $cleMois = date('Y-m', strtotime($repayment['date_retour']));

            if (!isset($interetsParMois[$cleMois])) {
                $interetsParMois[$cleMois] = 0;
            }
            $interetsParMois[$cleMois] += $interetDuMois;
        }

        ksort($interetsParMois);

        return $interetsParMois;
    }
    public static function calculateInterestByMonthDetailedFilter(
        $startYear = null,
        $startMonth = null,
        $endYear = null,
        $endMonth = null
    ) {
        $baseSql = "SELECT * FROM v_interert_pret";

        $whereConditions = ["id_statut_pret = 2"];
        $params = [];

        if (is_numeric($startYear) && is_numeric($startMonth)) {
            $whereConditions[] = "(YEAR(date_retour) > :start_year OR (YEAR(date_retour) = :start_year AND MONTH(date_retour) >= :start_month))";
            $params[':start_year'] = $startYear;
            $params[':start_month'] = $startMonth;
        }

        if (is_numeric($endYear) && is_numeric($endMonth)) {
            $whereConditions[] = "(YEAR(date_retour) < :end_year OR (YEAR(date_retour) = :end_year AND MONTH(date_retour) <= :end_month))";
            $params[':end_year'] = $endYear;
            $params[':end_month'] = $endMonth;
        }

        $finalSql = $baseSql . " WHERE " . implode(" AND ", $whereConditions) . " ORDER BY id_pret, date_retour";

        $stmt = getDB()->prepare($finalSql);
        $stmt->execute($params);
        $allRepayments = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $interetsParMois = [];
        $capitalRestantParPret = [];

        foreach ($allRepayments as $repayment) {
            $id_pret = $repayment['id_pret'];
            if (!isset($capitalRestantParPret[$id_pret])) {
                $capitalRestantParPret[$id_pret] = (float) $repayment['montant_emprunte'];
            }
            $capitalAvantPaiement = $capitalRestantParPret[$id_pret];
            $tauxMensuel = ($repayment['taux_annuel'] / 100) / 12;
            $interetDuMois = $capitalAvantPaiement * $tauxMensuel;
            $capitalRembourse = $repayment['montant_retour'] - $interetDuMois;
            $capitalRestantParPret[$id_pret] -= $capitalRembourse;
            $cleMois = date('Y-m', strtotime($repayment['date_retour']));
            if (!isset($interetsParMois[$cleMois])) {
                $interetsParMois[$cleMois] = 0;
            }
            $interetsParMois[$cleMois] += $interetDuMois;
        }

        ksort($interetsParMois);
        return $interetsParMois;
    }
}

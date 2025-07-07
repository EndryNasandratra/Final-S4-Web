<?php

require_once __DIR__ . '/../db.php';
class SimulationModel
{
    public static function getTauxAnuel($dureeMois, $montant)
    {
        $db = getDB();
        $stmt = $db->prepare("
                SELECT taux_annuel FROM taux_pret 
                WHERE duree = :duree 
                AND :montant BETWEEN borne_inf AND borne_sup
                LIMIT 1
            ");
        $stmt->execute([':duree' => $dureeMois, ':montant' => $montant]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function getTauxAssurance()
    {
        $db = getDB();
        $stmtAssurance = $db->query("SELECT taux FROM taux_assurance ORDER BY ID LIMIT 1");
        return  $stmtAssurance->fetch(PDO::FETCH_ASSOC);
    }
}

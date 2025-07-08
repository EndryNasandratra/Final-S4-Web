<?php

require_once __DIR__ . '/../models/AppModel.php';

class AppController
{
    public static  function sendTo()
    {
        $pageName = Flight::request()->query['pageName'];
        Flight::render($pageName);
    }

    public static function createTypePret()
    {
        $data = Flight::request()->data;
        $libelle = $data['libelle'] ?? null;
        $duree_max = $data["duree_max"] ?? 0;
        $montant_max = $data["montant_max"] ?? 0;

        try {
            $result = AppModel::insert("type_pret", [
                "libelle" => $libelle,
                "duree_max" => $duree_max,
                "montant_max" => $montant_max
            ]);
            Flight::json(['status' => 'success', 'message' => 'Type pret ajoutee avec succes !'],201);
        } catch (\Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }
    public static function createTauxPret()
    {
        $data = Flight::request()->data;
        $id_typre_pret = $data['id_type_pret'] ?? null;
        $taux_annuel = $data["taux_annuel"] ?? 0;
        $duree = $data["duree"] ?? 0;
        $borne_inf = $data["borne_inf"] ?? 0;
        $borne_sup = $data["borne_sup"] ?? 0;

        try {
            $result = AppModel::insert("taux_pret", [
                "id_type_pret" => $id_typre_pret,
                "taux_annuel" => $taux_annuel,
                "duree" => $duree,
                "borne_inf" => $borne_inf,
                "borne_sup" => $borne_sup
            ]);
            Flight::json(['status' => 'success', 'message' => 'Taux pret ajoute avec succes !'], 201);
        } catch (\Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }
}

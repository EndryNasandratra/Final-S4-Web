<?php
require_once __DIR__ . '/../db.php';

class SimulationModel
{
    public static function getTauxAnnuel($dureeMois, $montant)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT taux_annuel 
            FROM taux_pret 
            WHERE duree = :duree 
            AND :montant BETWEEN borne_inf AND borne_sup
            LIMIT 1
        ");
        $stmt->execute(['duree' => $dureeMois, 'montant' => $montant]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTauxAssurance()
    {
        $db = getDB();
        $stmt = $db->query("SELECT taux FROM taux_assurance ORDER BY id LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function calculerSimulation($id_taux_pret, $montant, $duree_mois, $include_assurance)
    {
        if (!$id_taux_pret || $montant <= 0 || $duree_mois <= 0) {
            throw new Exception('Donnees invalides fournies.', 400);
        }

        try {
            $db = getDB();
            $stmt = $db->prepare('
                SELECT taux_annuel, duree, borne_inf, borne_sup 
                FROM taux_pret 
                WHERE id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $taux_pret = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$taux_pret) {
                throw new Exception('Taux de pret non trouve.', 400);
            }

            $montant = (float) $montant;
            $duree_mois = (int) $duree_mois;
            $borne_inf = (float) $taux_pret['borne_inf'];
            $borne_sup = (float) $taux_pret['borne_sup'];
            $duree = (int) $taux_pret['duree'];

            $taux_interet_annuel = (float) $taux_pret['taux_annuel'];
            $taux_assurance_annuel = 0.0;
            $mensualite_assurance = 0.0;

            if ($include_assurance) {
                $stmt = $db->query('SELECT taux FROM taux_assurance LIMIT 1');
                $taux_assurance = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($taux_assurance && isset($taux_assurance['taux'])) {
                    $taux_assurance_annuel = (float) $taux_assurance['taux'];
                    $mensualite_assurance = ($montant * $taux_assurance_annuel / 100) / 12;
                }
            }

            $taux_mensuel = $taux_interet_annuel / 100 / 12;
            $mensualite_sans_assurance = ($montant * $taux_mensuel) / (1 - pow(1 + $taux_mensuel, -$duree_mois));
            $mensualite_totale = $mensualite_sans_assurance + $mensualite_assurance;
            $cout_total_credit = ($mensualite_sans_assurance * $duree_mois) - $montant;

            return [
                'taux_interet_annuel' => $taux_interet_annuel,
                'taux_assurance_annuel' => $taux_assurance_annuel,
                'mensualite_assurance' => $mensualite_assurance,
                'mensualite_totale' => $mensualite_totale,
                'cout_total_credit' => $cout_total_credit
            ];
        } catch (PDOException $e) {
            throw new Exception('Erreur lors du calcul : ' . $e->getMessage(), 500);
        }
    }

    public static function validerPret($id_taux_pret, $montant, $duree_mois, $include_assurance, $id_client, $id_admin = 1)
    {
        if (!$id_taux_pret || $montant <= 0 || $duree_mois <= 0) {
            throw new Exception('Donnees invalides fournies.', 400);
        }

        try {
            $db = getDB();
            $stmt = $db->prepare('
                SELECT taux_annuel, duree, borne_inf, borne_sup 
                FROM taux_pret 
                WHERE id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $taux_pret = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$taux_pret) {
                throw new Exception('Taux de pret non trouve.', 400);
            }

            $montant = (float) $montant;
            $duree_mois = (int) $duree_mois;

            $id_taux_assurance = null;
            if ($include_assurance) {
                $stmt = $db->query('SELECT id FROM taux_assurance LIMIT 1');
                $taux_assurance = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($taux_assurance && isset($taux_assurance['id'])) {
                    $id_taux_assurance = (int) $taux_assurance['id'];
                } else {
                    throw new Exception('Taux d\'assurance non trouve.', 400);
                }
            } else {
                $id_taux_assurance = 1;
            }

            // Selectionner la ressource par defaut (id_type_resssource = 1)
            $id_type_ressource = 1;
            $stmt = $db->prepare('
                SELECT id, valeur 
                FROM ressources 
                WHERE id_type_resssource = :id_type_ressource
            ');
            $stmt->execute(['id_type_ressource' => $id_type_ressource]);
            $ressource = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ressource) {
                throw new Exception('Ressource par defaut non trouvee.', 400);
            }

            $newRessourceValue = $ressource['valeur'] - $montant;
            if ($newRessourceValue < 0) {
                throw new Exception('Solde insuffisant pour la ressource par defaut.', 400);
            }

            $now = new DateTime();
            $date = $now->format('Y-m-d');
            $stmt = $db->prepare('
                INSERT INTO historique_ressource (id_ressource, valeur, estEntree, date_historique)
                VALUES (:id_ressource, :valeur, :estEntree, :date_historique)
            ');
            $stmt->execute([
                'id_ressource' => $ressource['id'],
                'valeur' => $montant,
                'estEntree' => false,
                'date_historique' => $date
            ]);

            $stmt = $db->prepare('
                UPDATE ressources 
                SET valeur = :valeur 
                WHERE id = :id
            ');
            $stmt->execute([
                'valeur' => $newRessourceValue,
                'id' => $ressource['id']
            ]);

            $db->beginTransaction();

            $stmt = $db->prepare('
                INSERT INTO pret (id_client, id_employe, id_taux_pret, id_remboursement, id_taux_assurance, montant_emprunte, date_pret)
                VALUES (:id_client, :id_employe, :id_taux_pret, :id_remboursement, :id_taux_assurance, :montant_emprunte, CURRENT_DATE)
            ');
            $stmt->execute([
                'id_client' => $id_client,
                'id_employe' => $id_admin,
                'id_taux_pret' => $id_taux_pret,
                'id_remboursement' => 1,
                'id_taux_assurance' => $id_taux_assurance,
                'montant_emprunte' => $montant
            ]);
                        
            $pret_id = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO statut_pret (libelle, id_pret) VALUES (?, ?)");
            $stmt->execute(['Valide', $pret_id]);

            // Valider la transaction
            $db->commit();

            return [
                'success' => true,
                'pret_id' => $pret_id
            ];
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la validation du pret : ' . $e->getMessage(), 500);
        }
    }

    public static function getAllTypePret()
    {
        $db = getDB();
        $stmt = $db->query('SELECT id, libelle FROM type_pret');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllTauxPretById($id_type_pret)
    {
        if (!$id_type_pret) {
            return [];
        }
        $db = getDB();
        $stmt = $db->prepare('
            SELECT id, taux_annuel, duree, borne_inf, borne_sup
            FROM taux_pret
            WHERE id_type_pret = :id_type_pret
        ');
        $stmt->execute(['id_type_pret' => $id_type_pret]);
        $taux = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($taux as &$t) {
            $t['taux_annuel'] = (float) $t['taux_annuel'];
            $t['duree'] = (int) $t['duree'];
            $t['borne_inf'] = (float) $t['borne_inf'];
            $t['borne_sup'] = (float) $t['borne_sup'];
        }
        return $taux;
    }
    public static function  getAllSimulations()
    {
        try {
            $db = getDB();
            $stmt = $db->query('
               SELECT 
                   s.id,
                   s.montant_emprunte,
                   tp.taux_annuel AS taux_pret,
                   tp.duree,
                   ta.taux AS taux_assurance
               FROM simulation s
               JOIN taux_pret tp ON s.id_taux_pret = tp.id
               LEFT JOIN taux_assurance ta ON s.id_taux_assurance = ta.id
           ');
            $simulations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $simulations;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la recuperation des simulations : ' . $e->getMessage(), 500);
        }
    }

}

<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function getAll() {
        $stmt = getDB()->query("SELECT * FROM pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getValidated() {
        $stmt = getDB()->query("SELECT 
                p.id,
                c.nom as client_nom,
                c.prenom as client_prenom,
                e.nom as employe_nom,
                e.prenom as employe_prenom,
                tp.taux_annuel as taux,
                p.montant_emprunte as montant,
                p.date_pret as date_debut,
                tp.duree,
                tpret.libelle as type_pret
            FROM pret p
            LEFT JOIN clients c ON p.id_client = c.id
            INNER JOIN employes e ON p.id_employe = e.id
            INNER JOIN taux_pret tp ON p.id_taux_pret = tp.id
            INNER JOIN type_pret tpret ON tp.id_type_pret = tpret.id
            INNER JOIN statut_pret sp ON p.id = sp.id_pret
            WHERE sp.libelle = 'Valide'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getNotValidated() {
        $stmt = getDB()->query("SELECT 
                p.id,
                c.nom as client_nom,
                c.prenom as client_prenom,
                e.nom as employe_nom,
                e.prenom as employe_prenom,
                tp.taux_annuel as taux,
                p.montant_emprunte as montant,
                p.date_pret as date_debut,
                tp.duree,
                tpret.libelle as type_pret
            FROM pret p
            LEFT JOIN clients c ON p.id_client = c.id
            INNER JOIN employes e ON p.id_employe = e.id
            INNER JOIN taux_pret tp ON p.id_taux_pret = tp.id
            INNER JOIN type_pret tpret ON tp.id_type_pret = tpret.id
            INNER JOIN statut_pret sp ON p.id = sp.id_pret
            WHERE sp.libelle = 'En attente'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT * FROM pret WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {  
        $db = getDB();
        
        try {
            // Demarrer une transaction
            $db->beginTransaction();
            
            // Inserer le pret
            $stmt = $db->prepare("INSERT INTO pret (id_client, id_employe, id_taux_pret, id_remboursement, id_taux_assurance, montant_emprunte, date_pret) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data->id_client, 
                $data->id_employe, 
                $data->id_taux_pret, 
                $data->id_remboursement, 
                $data->id_taux_assurance, 
                $data->montant_emprunte, 
                $data->date_pret
            ]);
            
            $id = $db->lastInsertId();
            
            // S'assurer que l'ID est valide
            if (!$id) {
                throw new Exception("Erreur lors de la creation du pret: ID non genere");
            }
            
            // Creer un statut par defaut "En attente"
            $stmt = $db->prepare("INSERT INTO statut_pret (libelle, id_pret) VALUES (?, ?)");
            $stmt->execute(['En attente', $id]);

            $stmt = $db->prepare("SELECT * FROM ressources WHERE id=1");
            $stmt->execute();
            $ressource = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ressource) {
                throw new Exception("Ressource non trouvee");
            }

            $new_value = $ressource['valeur'] - $data->montant_emprunte;

            $stmt = $db->prepare("UPDATE ressources set valeur= $new_value WHERE id=1");
            $stmt->execute();

            $stmt = $db->prepare("INSERT INTO historique_ressource (id_ressource, valeur, date_historique) VALUES (1, $data->montant_emprunte, $data->date_pret)");
            $stmt->execute();
            
            // Valider la transaction
            $db->commit();
            
            // Retourner les donnees du pret cree
            return self::getById($id);
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $db->rollBack();
            throw $e;
        }
    }

    public static function update($id, $data) {
        $stmt = getDB()->prepare("UPDATE pret SET id_etudiant = ?, id_type_pret = ?, montant = ?, duree = ?, taux_interet = ?, taux_assurance = ?, date_debut = ?, date_fin = ? WHERE id = ?");
        $stmt->execute([$data->id_etudiant, $data->id_type_pret, $data->montant, $data->duree, $data->taux_interet, $data->taux_assurance, $data->date_debut, $data->date_fin, $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $stmt = getDB()->prepare("DELETE FROM pret WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function getByEtudiantId($etudiantId) {
        $stmt = getDB()->prepare("SELECT * FROM pret WHERE id_etudiant = ?");
        $stmt->execute([$etudiantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByTypePretId($typePretId) {   
        $stmt = getDB()->prepare("SELECT * FROM pret WHERE id_type_pret = ?");
        $stmt->execute([$typePretId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatut($pretId, $nouveauStatut) {
        $db = getDB();
        
        try {
            $db->beginTransaction();
            
            // Mettre a jour le statut du pret
            $stmt = $db->prepare("UPDATE statut_pret SET libelle = ? WHERE id_pret = ?");
            $stmt->execute([$nouveauStatut, $pretId]);
            
            // Verifier que la mise a jour a affecte au moins une ligne
            if ($stmt->rowCount() === 0) {
                throw new Exception("Pret non trouve ou statut deja a jour");
            }
            
            $db->commit();
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function validerPret($pretId) {
        return self::updateStatut($pretId, 'Valide');
    }

    public static function rejeterPret($pretId, $raison = '') {
        return self::updateStatut($pretId, 'Refuse');
    }

    // Filtrer les prets valides selon les arguments fournis
    public static function filterValidated($filters = []) {
        $db = getDB();
        $sql = "SELECT 
                p.id,
                c.nom as client_nom,
                c.prenom as client_prenom,
                e.nom as employe_nom,
                e.prenom as employe_prenom,
                tp.taux_annuel as taux,
                p.montant_emprunte as montant,
                p.date_pret as date_debut,
                tp.duree,
                tpret.libelle as type_pret
            FROM pret p
            LEFT JOIN clients c ON p.id_client = c.id
            INNER JOIN employes e ON p.id_employe = e.id
            INNER JOIN taux_pret tp ON p.id_taux_pret = tp.id
            INNER JOIN type_pret tpret ON tp.id_type_pret = tpret.id
            INNER JOIN statut_pret sp ON p.id = sp.id_pret
            WHERE sp.libelle = 'Valide'";
        $params = [];
        if (!empty($filters['client'])) {
            $sql .= " AND (c.nom LIKE :client OR c.prenom LIKE :client)";
            $params[':client'] = '%' . $filters['client'] . '%';
        }
        if (!empty($filters['employe'])) {
            $sql .= " AND (e.nom LIKE :employe OR e.prenom LIKE :employe)";
            $params[':employe'] = '%' . $filters['employe'] . '%';
        }
        if (!empty($filters['taux'])) {
            $sql .= " AND tp.taux_annuel = :taux";
            $params[':taux'] = $filters['taux'];
        }
        if (!empty($filters['montant'])) {
            $sql .= " AND p.montant_emprunte = :montant";
            $params[':montant'] = $filters['montant'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(p.date_pret) = :date";
            $params[':date'] = $filters['date'];
        }
        if (!empty($filters['duree'])) {
            $sql .= " AND tp.duree = :duree";
            $params[':duree'] = $filters['duree'];
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retourne le montant total a disposition de l'EF par mois (reste montant non emprunte + remboursements des clients)
    public static function getMontantDispoParMois($dateDebut, $dateFin) {
        $db = getDB();
        // Generer la liste des mois entre dateDebut et dateFin
        $start = new DateTime($dateDebut.'-01');
        $end = new DateTime($dateFin.'-01');
        $end->modify('last day of this month');
        $months = [];
        $period = new DatePeriod($start, new DateInterval('P1M'), (clone $end)->modify('+1 day'));
        foreach ($period as $dt) {
            $months[$dt->format('Y-m')] = [
                'mois' => $dt->format('m'),
                'annee' => $dt->format('Y'),
                'total_non_emprunte' => 0,
                'total_remboursements' => 0,
                'total_dispo' => 0
            ];
        }
        // 1. Montant total des ressources (fixe)
        $sqlRess = "SELECT valeur as total_ressources FROM ressources where id = 1";
        $rowRess = $db->query($sqlRess)->fetch(PDO::FETCH_ASSOC);
        $totalRessources = $rowRess ? floatval($rowRess['total_ressources']) : 0;
        // 2. Pour chaque mois, calculer le montant total emprunte jusqu'a la fin du mois
        foreach ($months as $key => &$m) {
            $finMois = $m['annee'] . '-' . $m['mois'] . '-31';
            // Correction pour le dernier jour du mois
            $finMoisObj = DateTime::createFromFormat('Y-m-d', $finMois);
            if (!$finMoisObj) $finMoisObj = new DateTime($m['annee'] . '-' . $m['mois'] . '-01');
            $finMoisObj->modify('last day of this month');
            $finMois = $finMoisObj->format('Y-m-d');
            // Montant total emprunte jusqu'a la fin du mois
            $sqlPret = "SELECT SUM(montant_emprunte) as total_emprunte FROM pret WHERE date_pret <= :finMois";
            $stmtPret = $db->prepare($sqlPret);
            $stmtPret->execute([':finMois' => $finMois]);
            $rowPret = $stmtPret->fetch(PDO::FETCH_ASSOC);
            $totalEmprunte = $rowPret ? floatval($rowPret['total_emprunte']) : 0;
            // Remboursements du mois
            $sqlRemb = "SELECT SUM(montant_retour) as total_rembourse FROM remboursement WHERE date_retour >= :debutMois AND date_retour <= :finMois";
            $debutMois = $m['annee'] . '-' . $m['mois'] . '-01';
            $stmtRemb = $db->prepare($sqlRemb);
            $stmtRemb->execute([
                ':debutMois' => $debutMois,
                ':finMois' => $finMois
            ]);
            $rowRemb = $stmtRemb->fetch(PDO::FETCH_ASSOC);
            $totalRembourse = $rowRemb ? floatval($rowRemb['total_rembourse']) : 0;
            // Calcul du solde mensuel
            $m['total_non_emprunte'] = $totalRessources - $totalEmprunte;
            $m['total_remboursements'] = $totalRembourse;
            $m['total_dispo'] = $m['total_non_emprunte'] + $m['total_remboursements'];
        }
        return array_values($months);
    }
}
?>

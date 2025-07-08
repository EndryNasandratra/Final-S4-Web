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
}
?>

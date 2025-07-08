CREATE OR REPLACE VIEW v_interert_pret AS
SELECT 
    p.id as id_pret,
    c.nom as client_nom,
    c.prenom as client_prenom,
    e.nom as employe_nom,
    e.prenom as employe_prenom,
    tp.taux_annuel as taux,
    p.montant_emprunte as montant,
    p.date_pret as date_debut,
    r.date_retour as date_retour,
    tp.duree,
    tpret.libelle as type_pret
FROM pret p
LEFT JOIN clients c ON p.id_client = c.id
INNER JOIN employes e ON p.id_employe = e.id
INNER JOIN taux_pret tp ON p.id_taux_pret = tp.id
INNER JOIN type_pret tpret ON tp.id_type_pret = tpret.id
INNER JOIN statut_pret sp ON p.id = sp.id_pret
JOIN remboursement r ON p.id_remboursement = r.id
WHERE sp.libelle = 'Valide'
ORDER BY p.id, r.date_retour;


SELECT SUM(montant_retour) as total_rembourse
FROM remboursement
WHERE date_retour >= '2025-05-01' AND date_retour <= '2025-05-31'
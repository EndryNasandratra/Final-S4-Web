CREATE OR REPLACE VIEW v_interert_pret AS

SELECT p.id AS id_pret,p.id_statut_pret, p.montant_emprunte, tp.taux_annuel, r.montant_retour, r.date_retour
FROM
    remboursement r
    JOIN pret p ON r.id_pret = p.id
    JOIN taux_pret tp ON p.id_taux_pret = tp.id
WHERE
    p.id_statut_pret = 2
ORDER BY p.id, r.date_retour;

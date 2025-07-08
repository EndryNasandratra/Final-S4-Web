<?php
require_once __DIR__ . '/../db.php';

require("utils/fpdf.php");
class PDFModel extends FPDF
{

    public  function generateSimulationPDF($simulationData, $typePret, $tauxPret, $typeRessource, $year)
    {
        $this->SetMargins(15, 10, 15);
        $this->SetAutoPageBreak(true, 15);
        $this->AliasNbPages();

        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(100, 100, 100);

        $this->AddPage();

        // En-tete
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Simulation de Pret', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, 'Date : ' . date('d/m/Y'), 0, 1, 'C');
        $this->Ln(10);

        // Titre du tableau
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Details de la Simulation', 0, 1, 'C');
        $this->Ln(5);

        // En-tetes du tableau
        $this->SetFont('Arial', 'B', 10);
        $headers = ['Description', 'Valeur'];
        $widths = [100, 80];
        for ($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 10, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Donnees du tableau
        $this->SetFont('Arial', '', 10);
        $rows = [
            ['Type de pret', $typePret['libelle']],
            ['Taux annuel', number_format($tauxPret['taux_annuel'], 2) . ' %'],
            ['Duree', $tauxPret['duree'] . ' mois (' . number_format($tauxPret['duree'] / 12, 1) . ' ans)'],
            ['Montant emprunte', number_format($simulationData['montant'], 2) . ' EUR'],
            ['Type de ressource', $typeRessource['libelle']],
            ['Taux d\'assurance annuel', number_format($simulationData['taux_assurance_annuel'], 2) . ' %'],
            ['Cout mensuel de l\'assurance', number_format($simulationData['mensualite_assurance'], 2) . ' EUR'],
            ['Mensualite totale', number_format($simulationData['mensualite_totale'], 2) . ' EUR'],
            ['Cout total du credit', number_format($simulationData['cout_total_credit'], 2) . ' EUR']
        ];

        foreach ($rows as $row) {
            $this->Cell($widths[0], 8, $this->truncate($row[0], 50), 1);
            $this->Cell($widths[1], 8, $this->truncate($row[1], 50), 1, 0, 'R');
            $this->Ln();
        }

        // Note de bas de page
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, '* Tous les montants sont en Euros (EUR)', 0, 1);
        $this->Cell(0, 5, 'Genere le ' . date('d/m/Y H:i:s'), 0, 1);

        // Nom du fichier
        $filename = "simulation_pret_{$typePret['libelle']}_{$year}.pdf";
        $this->Output('D', $filename);
    }

    private function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length - 3) . '...';
        }
        return $string;
    }
}

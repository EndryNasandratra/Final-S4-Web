<?php

namespace app\models;

require('utils/fpdf.php');

use FPDF;
use Flight;

class PDFModel extends FPDF
{
    private $db;

    public function __construct($db)
    {
        parent::__construct('L', 'mm', 'A3');

        $this->db = $db;
    }

    public function generateAnnualBudgetPDF($data, $departementName, $year): void
    {
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);
        $this->AliasNbPages();

        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(100, 100, 100);

        $this->SetFont('Arial', '', 9);

        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $this->AddPage('L');

            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, "Budget Annuel - $departementName ($year) - Trimestre $quarter", 0, 1, 'C');
            $this->Ln(8);

            $colWidthRubrique = 55;
            $colWidthMonth = 32;
            $headerHeight = 18;
            $rowHeight = 17;

            $this->SetFont('Arial', 'B', 12);
            $this->Cell($colWidthRubrique, $headerHeight * 2, 'Rubrique', 1, 0, 'C', true);

            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $startMonth + 2;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $monthName = date('M', mktime(0, 0, 0, $month, 10));
                $this->Cell($colWidthMonth * 3, $headerHeight, $monthName, 1, 0, 'C', true);
            }
            $this->Ln();

            $this->Cell($colWidthRubrique, 0, '', 0);
            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $this->Cell($colWidthMonth, $headerHeight, 'Previsions', 1, 0, 'C', true);
                $this->Cell($colWidthMonth, $headerHeight, 'Realisations', 1, 0, 'C', true);
                $this->Cell($colWidthMonth, $headerHeight, 'Ecart', 1, 0, 'C', true);
            }
            $this->Ln();

            $this->SetFont('Arial', '', 12);

            $this->printTableRow('Solde Debut', $data, $startMonth, $endMonth, $colWidthRubrique, $colWidthMonth, $rowHeight, false);

            $this->printTableRow('Recettes', $data, $startMonth, $endMonth, $colWidthRubrique, $colWidthMonth, $rowHeight, true, 'Recette');

            $this->printTableRow('Depenses', $data, $startMonth, $endMonth, $colWidthRubrique, $colWidthMonth, $rowHeight, true, 'Depense');

            $this->printTableRow('Solde Fin', $data, $startMonth, $endMonth, $colWidthRubrique, $colWidthMonth, $rowHeight, false);

            $this->Ln(10);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 5, '* Tous les montants sont en ' . html_entity_decode('Ariary;'), 0, 1);
        }

        $this->Output('D', "budget_annuel_{$departementName}_{$year}.pdf");
        // $this->Output();
    }

    private function printTableRow($label, $data, $startMonth, $endMonth, $colWidthRubrique, $colWidthMonth, $rowHeight, $isOperation, $nature = null): void
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($colWidthRubrique, $rowHeight, $label, 1, 0, 'L');

        $this->SetFont('Arial', '', 11);

        for ($month = $startMonth; $month <= $endMonth; $month++) {
            if ($isOperation) {
                $prev = $data[$month]['Previsions'][$nature] ?? 0;
                $real = $data[$month]['Realisations'][$nature] ?? 0;
                $ecart = $real - $prev;

                $this->SetTextColor(0);
                if ($ecart < 0) $this->SetTextColor(255, 0, 0);
                if ($ecart > 0 && $nature === 'Recette') $this->SetTextColor(0, 128, 0);

                $this->Cell($colWidthMonth, $rowHeight, number_format($prev, 2), 1, 0, 'R');
                $this->Cell($colWidthMonth, $rowHeight, number_format($real, 2), 1, 0, 'R');
                $this->Cell($colWidthMonth, $rowHeight, number_format($ecart, 2), 1, 0, 'R');

                $this->SetTextColor(0);
            } else {
                if ($label === 'Solde Debut') {
                    $value = $data[$month]['SoldeInitial'] ?? 0;
                    $this->Cell($colWidthMonth, $rowHeight, number_format($value, 2), 1, 0, 'R');
                    $this->Cell($colWidthMonth, $rowHeight, number_format($value, 2), 1, 0, 'R');
                    $this->Cell($colWidthMonth, $rowHeight, number_format(0, 2), 1, 0, 'R');
                } else {
                    $prev = $data[$month]['SoldeFinal']['Previsions'] ?? 0;
                    $real = $data[$month]['SoldeFinal']['Realisations'] ?? 0;
                    $this->Cell($colWidthMonth, $rowHeight, number_format($prev, 2), 1, 0, 'R');
                    $this->Cell($colWidthMonth, $rowHeight, number_format($real, 2), 1, 0, 'R');
                    $this->Cell($colWidthMonth, $rowHeight, number_format(0, 2), 1, 0, 'R');
                }
            }
        }
        $this->Ln();
    }
    public function generateDetailsPDF($details, $id_departement, $month, $year): void
    {
        $deptName = Flight::productModel()->getById("Departement", "id_departement", $id_departement)["nom_dept"];
        $monthName = date('M', mktime(0, 0, 0, $month, 10));
        $this->AddPage();

        $this->SetFont('Arial', 'B', 16);

        $this->Cell(0, 10, "Details Budget - Mois " . date('F', mktime(0, 0, 0, $month, 10)) . " $year", 0, 1, 'C');
        $this->Ln(10);

        $this->generateBudgetSection($details['Previsions'], 'Previsions');

        $this->generateBudgetSection($details['Realisations'], 'Realisations');

        $this->SetY(-15);
        $filename = "details_budget_{$deptName}_{$monthName}_{$year}.pdf";
        $this->Output();
        // $this->Output('D', $filename);
    }

    private function generateBudgetSection($data, $title): void
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $title, 0, 1);
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 10);
        $headers = ['Nom', 'Montant', 'Date', 'Categorie', 'Nature', 'Utilisateur', 'Departement'];
        $widths = [50, 30, 30, 30, 30, 40, 30];

        for ($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 10, $headers[$i], 1, 0, 'C');
        }
        $this->Ln();

        $this->SetFont('Arial', '', 8);
        foreach ($data as $key => $item) {
            if ($key === "Somme") continue;

            $this->Cell($widths[0], 8, $this->truncate($item['nom_budget_element'], 30), 1);
            $this->Cell($widths[1], 8, number_format($item['montant_budget'], 2), 1, 0, 'R');
            $this->Cell($widths[2], 8, $item['date_budget'], 1);

            $category = Flight::productModel()->getById('Categories', "id_category", $item['id_category'])["libelle"];
            $nature = Flight::productModel()->getById('Nature', "id_nature", $item['id_nature'])["libelle"];;
            $user = Flight::productModel()->getById('Utilisateur', "id_user", $item['id_utilisateur'])["nom_user"];;
            $dept = Flight::productModel()->getById('Departement', "id_departement", $item['id_departement'])["nom_dept"];;

            $this->Cell($widths[3], 8, $this->truncate($category, 15), 1);
            $this->Cell($widths[4], 8, $this->truncate($nature, 15), 1);
            $this->Cell($widths[5], 8, $this->truncate($user, 20), 1);
            $this->Cell($widths[6], 8, $this->truncate($dept, 15), 1);
            $this->Ln();
        }

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(array_sum(array_slice($widths, 0, 6)), 8, 'Total ' . $title . ' (' . array_key_first($data["Somme"]) . ')', 1, 0, 'R');
        $this->Cell($widths[6], 8, number_format($data["Somme"][array_key_first($data["Somme"])], 2), 1, 0, 'R');
        $this->Ln(15);
    }

    private function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length - 3) . '...';
        }
        return $string;
    }
}


voici un exemple de fichier modele qui permet d'improter en pdf
Maintenant 
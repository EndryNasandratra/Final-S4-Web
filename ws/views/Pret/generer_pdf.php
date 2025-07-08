<?php
// Inclure la bibliotheque TCPDF (a installer via composer)
require_once('tcpdf/tcpdf.php');

// Recuperation des donnees du pret (en pratique, depuis la base de donnees)
$pret_data = [
    'numero' => 'PRT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
    'date' => date('d/m/Y'),
    'client' => [
        'nom' => 'Dupont Jean',
        'adresse' => '123 Rue de la Paix, 75001 Paris',
        'telephone' => '01 23 45 67 89',
        'email' => 'dupont.jean@email.com'
    ],
    'employe' => 'Martin Sophie',
    'montant' => 15000.00,
    'duree' => 24,
    'taux' => 3.5,
    'mensualite' => 650.50,
    'interets_totaux' => 612.00,
    'cout_total' => 15612.00,
    'taeg' => 3.65
];

// Creation du PDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'MNA_Banque', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln();
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'Ajout de pret', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(20);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Creation du document PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Definition des metadonnees
$pdf->SetCreator('MNA_Banque');
$pdf->SetAuthor('MNA_Banque');
$pdf->SetTitle('Ajout de pret - ' . $pret_data['numero']);
$pdf->SetSubject('Ajout de pret');
$pdf->SetKeywords('pret, simulation, banque');

// Definition des marges
$pdf->SetMargins(15, 40, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Definition des sauts de page automatiques
$pdf->SetAutoPageBreak(TRUE, 25);

// Definition de la police
$pdf->SetFont('helvetica', '', 10);

// Ajout d'une page
$pdf->AddPage();

// Titre principal
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Ajout de pret', 0, 1, 'C');
$pdf->Ln(5);

// Informations du pret
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Informations du pret', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(40, 6, 'Numero de pret :', 0, 0);
$pdf->Cell(0, 6, $pret_data['numero'], 0, 1);

$pdf->Cell(40, 6, 'Date de simulation :', 0, 0);
$pdf->Cell(0, 6, $pret_data['date'], 0, 1);

$pdf->Cell(40, 6, 'Employe :', 0, 0);
$pdf->Cell(0, 6, $pret_data['employe'], 0, 1);

$pdf->Ln(5);

// Informations du client
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Informations du client', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(40, 6, 'Nom :', 0, 0);
$pdf->Cell(0, 6, $pret_data['client']['nom'], 0, 1);

$pdf->Cell(40, 6, 'Adresse :', 0, 0);
$pdf->Cell(0, 6, $pret_data['client']['adresse'], 0, 1);

$pdf->Cell(40, 6, 'Telephone :', 0, 0);
$pdf->Cell(0, 6, $pret_data['client']['telephone'], 0, 1);

$pdf->Cell(40, 6, 'Email :', 0, 0);
$pdf->Cell(0, 6, $pret_data['client']['email'], 0, 1);

$pdf->Ln(5);

// Conditions du pret
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Conditions du pret', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(60, 6, 'Montant emprunte :', 0, 0);
$pdf->Cell(0, 6, number_format($pret_data['montant'], 2, ',', ' ') . ' €', 0, 1);

$pdf->Cell(60, 6, 'Duree :', 0, 0);
$pdf->Cell(0, 6, $pret_data['duree'] . ' mois', 0, 1);

$pdf->Cell(60, 6, 'Taux annuel :', 0, 0);
$pdf->Cell(0, 6, $pret_data['taux'] . ' %', 0, 1);

$pdf->Cell(60, 6, 'Mensualite :', 0, 0);
$pdf->Cell(0, 6, number_format($pret_data['mensualite'], 2, ',', ' ') . ' €', 0, 1);

$pdf->Cell(60, 6, 'Interets totaux :', 0, 0);
$pdf->Cell(0, 6, number_format($pret_data['interets_totaux'], 2, ',', ' ') . ' €', 0, 1);

$pdf->Cell(60, 6, 'Coût total :', 0, 0);
$pdf->Cell(0, 6, number_format($pret_data['cout_total'], 2, ',', ' ') . ' €', 0, 1);

$pdf->Cell(60, 6, 'TAEG :', 0, 0);
$pdf->Cell(0, 6, $pret_data['taeg'] . ' %', 0, 1);

$pdf->Ln(10);

// Tableau d'amortissement (exemple pour les 6 premiers mois)
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Tableau d\'amortissement (extrait)', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9);

// En-tetes du tableau
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(25, 6, 'echeance', 1, 0, 'C', true);
$pdf->Cell(35, 6, 'Mensualite', 1, 0, 'C', true);
$pdf->Cell(35, 6, 'Interets', 1, 0, 'C', true);
$pdf->Cell(35, 6, 'Capital', 1, 0, 'C', true);
$pdf->Cell(35, 6, 'Capital restant', 1, 1, 'C', true);

// Donnees du tableau
$capital_restant = $pret_data['montant'];
$taux_mensuel = $pret_data['taux'] / 100 / 12;

for ($mois = 1; $mois <= 6; $mois++) {
    $interets = $capital_restant * $taux_mensuel;
    $capital = $pret_data['mensualite'] - $interets;
    $capital_restant -= $capital;
    
    $pdf->Cell(25, 6, $mois, 1, 0, 'C');
    $pdf->Cell(35, 6, number_format($pret_data['mensualite'], 2, ',', ' '), 1, 0, 'R');
    $pdf->Cell(35, 6, number_format($interets, 2, ',', ' '), 1, 0, 'R');
    $pdf->Cell(35, 6, number_format($capital, 2, ',', ' '), 1, 0, 'R');
    $pdf->Cell(35, 6, number_format($capital_restant, 2, ',', ' '), 1, 1, 'R');
}

$pdf->Ln(10);

// Conditions generales
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Conditions generales', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9);

$conditions = [
    'Cette simulation est valable 30 jours a compter de sa date d\'emission.',
    'Le taux d\'interet peut varier selon l\'evolution des conditions de marche.',
    'Des frais de dossier peuvent s\'appliquer.',
    'L\'assurance emprunteur est obligatoire et facturee en sus.',
    'En cas de retard de paiement, des penalites seront appliquees.',
    'Le pret peut etre rembourse par anticipation avec des frais.',
    'Cette simulation n\'engage pas la banque et ne constitue pas une offre ferme.'
];

foreach ($conditions as $condition) {
    $pdf->Cell(0, 5, '• ' . $condition, 0, 1);
}

$pdf->Ln(10);

// Signature
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, 'Signature de l\'employe :', 0, 1, 'L');
$pdf->Line(15, $pdf->GetY(), 80, $pdf->GetY());
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(0, 5, $pret_data['employe'], 0, 1, 'L');

// Sortie du PDF
$pdf->Output('ajout_pret_' . $pret_data['numero'] . '.pdf', 'D');
?> 
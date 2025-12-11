<?php
require '../resources/fpdf186/fpdf.php';
require '../resources/Outils/PDF.php';
require '../src/Modeles/PdoGsb.php';
require '../config/define.php';
require '../resources/Outils/Utilitaires.php';
use Modeles\PdoGsb;

$pdo = PdoGsb::getPdoGsb();

$idVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$dataF = $pdo->getLesFraisForfait($idVisiteur, $mois);
$headerF = array_keys($dataF);

$dataHF = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$headerHF = array_keys($dataHF);

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetTitle("Fiche de frais");
$pdf->SetFont('Arial','B',16);
$pdf->Image("./images/logo.jpg", $x=78);
$pdf->Ln();
//$pdf->FancyTable($headerF, $dataF);
$pdf->Ln();
$pdf->FancyTable($headerHF, $dataHF);
$pdf->Output();


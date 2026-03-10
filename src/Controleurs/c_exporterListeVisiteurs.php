<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$listeVisiteurs = $pdo->getInfosAllVisiteurs();

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();


for ($i = 0; $i < count($listeVisiteurs) ; $i++) {
    $activeWorksheet->setCellValue('B' . $i + 2, $listeVisiteurs[$i]['nom']);
    $activeWorksheet->setCellValue('C' . $i + 2, $listeVisiteurs[$i]['prenom']);
    $activeWorksheet->setCellValue('D' . $i + 2, $listeVisiteurs[$i]['email']);
    $activeWorksheet->setCellValue('E' . $i + 2, $listeVisiteurs[$i]['adresse']);
    $activeWorksheet->setCellValue('F' . $i + 2, $listeVisiteurs[$i]['codepost']);
    $activeWorksheet->setCellValue('G' . $i + 2, $listeVisiteurs[$i]['ville']);
    $activeWorksheet->setCellValue('H' . $i + 2, $listeVisiteurs[$i]['dateembauche']);
}

$writer = new Xlsx($spreadsheet);
$file = './liste_visiteurs.xlsx';
$writer->save('liste_visiteurs.xlsx');

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);

header("Refresh: 0;URL=index.php");
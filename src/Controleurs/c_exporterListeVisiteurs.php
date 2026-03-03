<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', $_SESSION['idVisiteur']);

$writer = new Xlsx($spreadsheet);
$writer->save('liste_visiteurs.xlsx');

header("Refresh: 0;URL=index.php");
<?php

class PDF extends FPDF {

function FancyTable($header, $data) {
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(10, 25, 32, 60, 30, 20);
    for($i=0;$i<count($header);$i++){
        $this->Cell($w[$i],7,iconv('UTF-8', 'windows-1252', $header[$i]),1,0,'C',true);
    }
    
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row) {
        for ($i=0;$i<count($header);$i++){
            $this->Cell($w[$i],6,iconv('UTF-8', 'windows-1252', $row[$i]),'LR',0,'L',$fill);
        }
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
    }
}


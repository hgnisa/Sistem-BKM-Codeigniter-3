<?php
// require(base_url().'vendor/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdf.php');

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
 

## DOCUMENT HEADER
$pdf->Cell(0,5,'',0,1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,0,'LAPORAN HARIAN HASIL KERJA',0,0,'C');
$pdf->Cell(0,4,'',0,1);
$pdf->Cell(0,0,'PSR KT ADHIGANA JAWI',0,0,'C');
$pdf->Cell(0,4,'',0,1);
$pdf->Cell(0,0,'PT. KOEBOERAYA BANGUN PERKASA',0,0,'C');

## DOCUMENT DATE
list($year, $month, $day) = explode("-", $date);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,10,'',0,1);
$pdf->Cell(0,0,'Tanggal: '.$day." ".$month_name." ".$year,0,0);

## DOCUMENT HEADER TABLE
$pdf->setFillColor(200,200,200);
// $pdf->Rect(0, 0, 210, 297, 'F');
$pdf->Cell(0,5,'',0,1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(80,5,'JENIS PEKERJAAN',1,0,'C',1);
$pdf->Cell(35,5,'HASIL KERJA' ,1,0,'C',1);
$pdf->Cell(35,5,'SATUAN',1,0,'C',1);
$pdf->Cell(120,5,'KAVLING',1,0,'C',1);


## DOCUMENT TABLE
$pdf->Cell(80,5,'',0,1);
$pdf->SetFont('Arial','',8);

if(count($kegiatan) > 0){
    $note = 5;
    foreach($kegiatan as $d){    
        $pdf->Cell(80,5, $d['job'],1,0);
        $pdf->Cell(35,5, $d['volume'],1,0,'C');  
        $pdf->Cell(35,5, $d['satuan'],1,0,'C');
        $pdf->Cell(120,5, $d['kavling'],1,1,'');
    }
}else{
    $note = 15;
    $pdf->Cell(270,10, "Tidak ada data laporan",1,0,'C');
}


## DOCUMENT NOTE
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$note,'',0,1);
$pdf->Cell(0,5,"Note: ",0,0);
$pdf->Cell(0,30,'',0,1);


## DOCUMENT TTD
$pdf->SetLeftMargin(240);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,10,'',0,1,'C');
$pdf->Cell(30,4,"Bagan Batu, ".date("d")." ".$month_name_now." ".date("Y"),0,1,'C');
$pdf->Cell(30,4,"Direkap Oleh",0,0,'C');
$pdf->Cell(30,20,'',0,1);
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(30,4,"SHALLY ANGGRAINI UTAMI, S.AP",0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,4,"ADMIN",0,0,'C');

$outputfile = "Laporan Harian ".$day." ".$month." ".$year.".pdf";
$pdf->Output('I',$outputfile);
// $pdf->Output('D',$outputfile);
?>

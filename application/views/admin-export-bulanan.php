<?php
require_once(APPPATH.'libraries/fpdf/fpdf.php');

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
 
## DOCUMENT HEADER
$pdf->Cell(0,5,'',0,1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,0,'REALISASI KERJA HARIAN',0,0,'C');
$pdf->Cell(0,4,'',0,1);
$pdf->Cell(0,0,'KT ADHIGANA JAWI - BAGAN BATU',0,0,'C');

## DOCUMENT DATE
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,10,'',0,1);
$pdf->Cell(0,0,'Paket L dan M',0,0);

## get date by periode
if($periode == "awal"){
    $count = 15*8;
}else{
    $t = date("t", strtotime($year."-".$month."-01"));
    $count = ($t-15)*8;
}

## DOCUMENT HEADER TABLE
$pdf->setFillColor(200,200,200);
$pdf->Cell(0,5,'',0,1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(80,10,'JENIS KEGIATAN',1,0,'C',1);
$pdf->Cell($count,5,'TANGGAL' ,1,0,'C',1);
$pdf->Cell(35,10,'TOTAL',1,0,'C',1);
$pdf->Cell(35,10,'SATUAN',1,0,'C',1);
$pdf->Cell(0,5,'',0,1);
$pdf->SetLeftMargin(90);
if($periode == "awal"){
    for($i=1;$i<=15;$i++) {
        $pdf->Cell(8,5,$i,1,0,'C',1);
    }
}else{
    for($i=16;$i<=$t;$i++) {
        $pdf->Cell(8,5,$i,1,0,'C',1);
    }
}

$pdf->SetLeftMargin(10);

## DOCUMENT TABLE
$pdf->Cell(80,5,'',0,1);
$pdf->SetFont('Arial','',8);

if(count($kegiatan) > 0){
    foreach($kegiatan as $d){
        $pdf->Cell(80,5, $d['pekerjaan_name'],1,0);
        if($periode == "start"){
            for($i=1;$i<=15;$i++) {
                $amount = 0;
                foreach($totalKegiatan as $val){
                    if($i == $val['date'] and $d['pekerjaan_name'] == $val['pekerjaan']){
                        $amount = $val['amount'];
                    }
                }
                $pdf->Cell(8,5, $amount ? $amount : "",1,0,'C');
            }
        }else{
            for($i=16;$i<=$t;$i++) {
                $amount = 0;
                foreach($totalKegiatan as $val){
                    if($i == $val['date'] and $d['pekerjaan_name'] == $val['pekerjaan']){
                        $amount = $val['amount'];
                    }
                }
                $pdf->Cell(8,5, $amount ? $amount : "",1,0,'C');
            }
        }
        $pdf->Cell(35,5,$d['keg_volume'],1,0,'C');  
        $pdf->Cell(35,5,$d['keg_satuan'],1,1,'C');
    }
}else{
    $total = 80+$count+35+35;
    $pdf->Cell($total,10, "Tidak ada data laporan",1,0,'C');
}

$outputfile = "Laporan Bulanan ".$month_name." ".$year.".pdf";
$pdf->Output('I',$outputfile);
// $pdf->Output('D',$outputfile);
?>

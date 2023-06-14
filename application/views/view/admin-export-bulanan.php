<?php
require('../../vendor/fpdf/fpdf.php');
include '../model/connection.php';
include '../model/class-user.php';
include '../model/class-kegiatan.php';
include '../model/class-pekerjaan.php';
include '../model/class-kavling.php';
$user = new user();     
$kegiatan = new kegiatan(); 
$pekerjaan = new pekerjaan(); 
$kavling = new kavling(); 

$periode = $_GET['periode'];
$month = $_GET['month'];
$year = $_GET['year'];

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
if($periode == "awal"){
    $range1 = $year."-".$month."-01";
    $range2 = $year."-".$month."-15";
}else{
    $range1 = $year."-".$month."-16";
    $range2 = $year."-".$month."-".date("t", strtotime( $year."-".$month."-01"));
}
$whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";
$data = $kegiatan->show_sql("SELECT  * FROM kegiatan $whereclause GROUP BY pekerjaan_id");
if(count($data) > 0){
    foreach($data as $d){
    
        ## get pekerjaan
        $pekerjaan_id = $d['pekerjaan_id'];
        $getpekerjaan = $pekerjaan->show_pekerjaan_detail($pekerjaan_id);

        $pdf->Cell(80,5, $getpekerjaan['pekerjaan_name'],1,0);
        if($periode == "awal"){
            for($i=1;$i<=15;$i++) {
                if($i < 10){
                    $date = $year."-".$month."-0".$i;
                }else{
                    $date = $year."-".$month."-".$i;
                }
    
                $amount = $kegiatan->show_sql("SELECT SUM(keg_volume) as amount FROM kegiatan WHERE keg_date = '$date' AND pekerjaan_id = '$pekerjaan_id'");
                $pdf->Cell(8,5,$amount[0]['amount'] ? $amount[0]['amount'] : "",1,0,'C');
            }
        }else{
            for($i=16;$i<=$t;$i++) {
                $date = $year."-".$month."-".$i;
    
                $amount = $kegiatan->show_sql("SELECT SUM(keg_volume) as amount FROM kegiatan WHERE keg_date = '$date' AND pekerjaan_id = '$pekerjaan_id'");
            
                $pdf->Cell(8,5,$amount[0]['amount'] ? $amount[0]['amount'] : "",1,0,'C');
            }
        }
    
        $total = $kegiatan->show_sql("SELECT SUM(keg_volume) as total FROM kegiatan $whereclause AND pekerjaan_id = '$pekerjaan_id'");
        $pdf->Cell(35,5, $total[0]['total'],1,0,'C');  
    
    
        $querysatuan = $kegiatan->show_sql("SELECT keg_satuan FROM kegiatan $whereclause AND pekerjaan_id = '$pekerjaan_id'");
        foreach($querysatuan as $datasatuan){
            $satuan[] = $datasatuan['keg_satuan'];
        }
        $satuan = array_unique(array_map("strtoupper", $satuan));
        $pdf->Cell(35,5,implode(", ", $satuan),1,1,'C');
    }

    ## table footer
    // $pdf->SetFont('Arial','B',8);
    // $pdf->Cell(80+$count,5,"Total",1,0,'C');
}else{
    $total = 80+$count+35+35;
    $pdf->Cell($total,10, "Tidak ada data laporan",1,0,'C');
}

$outputfile = "Laporan Bulanan ".$kegiatan->month($month)." ".$year.".pdf";
$pdf->Output('I',$outputfile);
// $pdf->Output('D',$outputfile);
?>

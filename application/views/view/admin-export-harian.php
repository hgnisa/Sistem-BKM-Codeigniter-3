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

$date = $_GET['date'];

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
$pdf->Cell(0,0,'Tanggal: '.$day." ".$kegiatan->month($month)." ".$year,0,0);

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
$datakegiatan = $kegiatan->show_sql("SELECT  * FROM kegiatan WHERE keg_date = '$date' GROUP BY pekerjaan_id");
if(count($datakegiatan) > 0){
    $note = 5;
    foreach($datakegiatan as $d){

        ## get pekerjaan
        $pekerjaan_id = $d['pekerjaan_id'];
        $getpekerjaan = $pekerjaan->show_pekerjaan_detail($pekerjaan_id);

        ## get sum volume/pekerjaan
        $getvolume = $kegiatan->show_sql("SELECT SUM(keg_volume) as hasilkerja FROM kegiatan WHERE pekerjaan_id = '$pekerjaan_id' AND keg_date = '$date'");

        ## get kavling
        $datakav = array();
        $datakavling = $kegiatan->show_sql("SELECT kav_id FROM kegiatan WHERE pekerjaan_id = '$pekerjaan_id' AND keg_date = '$date'");

        foreach($datakavling as $kav){     
            $getkav = $kavling->show_kavling_detail($kav['kav_id']);   
            $datakav[] = $getkav['kav_name'];
        }

        $getkavling = implode(", ", $datakav);
    
        $pdf->Cell(80,5, $getpekerjaan['pekerjaan_name'],1,0);
        $pdf->Cell(35,5, $getvolume[0]['hasilkerja'],1,0,'C');  
        $pdf->Cell(35,5, $d['keg_satuan'],1,0,'C');
        $pdf->Cell(120,5, $getkavling,1,1,'');
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
$pdf->Cell(30,4,"Bagan Batu, ".date("d")." ".$kegiatan->month(date("m"))." ".date("Y"),0,1,'C');
$pdf->Cell(30,4,"Direkap Oleh",0,0,'C');
$pdf->Cell(30,20,'',0,1);
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(30,4,"SHALLY ANGGRAINI UTAMI, S.AP",0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,4,"ADMIN",0,0,'C');

$outputfile = "Laporan Harian ".$day." ".$kegiatan->month($month)." ".$year.".pdf";
// $pdf->Output('I',$outputfile);
$pdf->Output('D',$outputfile);
?>

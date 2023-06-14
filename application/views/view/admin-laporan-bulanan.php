<?php
    include '../model/connection.php';
    include '../model/class-user.php';
    include '../model/class-kegiatan.php';
    include '../model/class-pekerjaan.php';
    include '../model/class-kavling.php';
    $user = new user();     
    $kegiatan = new kegiatan(); 
    $pekerjaan = new pekerjaan(); 
    $kavling = new kavling(); 

    session_start();
    $_SESSION['menu'] = 4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM  - Laporan Bulanan</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#dataTable').dataTable( {
                "ordering": false
            });
        });
    </script>
    <style>
        .table-responsive{
            font-size: small;
        }
        .monthly{
            color: #3a3b45;
            background-color: #f8f9fc;
            border-color: #858796;
        }
        .monthly:hover{
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .monthly-active{
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require("admin-web-menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require("admin-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Bulanan</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                            ## if awal or akhir bulan
                                            $periode = $_GET['periode'];
                                            if($periode){
                                                $periode = $periode;
                                            }else{
                                                $periode = "awal";
                                            }

                                            ## if there is date filter or not
                                            $month = $_GET['month'];
                                            $year = $_GET['year'];
                                                    
                                            ## always show by today or filtered kegiatan
                                            if(!empty($month) and !empty($year)){
                                                $month = $month;
                                                $year = $year;
                                            }else{
                                                $month = date("m");
                                                $year = date("Y");
                                            }

                                            $url = "?month=$month&year=$year&";
                                        ?>
                                        <div class="col-lg-12">
                                            <form action="admin-laporan-bulanan.php?sub=product" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
                                                <div class="input-group">
                                                    <label>Filter berdasarkan bulan: &nbsp;</label>
                                                    <select name="month" size='1' class="form-control bg-light border-1 small" placeholder="Cari"
                                                        aria-label="Search" aria-describedby="basic-addon2" style="width: 130px">
                                                        <option value="01" <?php print $month == '01' ? "selected" : "";?>>Januari</option>
                                                        <option value="02" <?php print $month == '02' ? "selected" : "";?>>Februari</option>
                                                        <option value="03" <?php print $month == '03' ? "selected" : "";?>>Maret</option>
                                                        <option value="04" <?php print $month == '04' ? "selected" : "";?>>April</option>
                                                        <option value="05" <?php print $month == '05' ? "selected" : "";?>>Mei</option>
                                                        <option value="06" <?php print $month == '06' ? "selected" : "";?>>Juni</option>
                                                        <option value="07" <?php print $month == '07' ? "selected" : "";?>>Juli</option>
                                                        <option value="08" <?php print $month == '08' ? "selected" : "";?>>Agustus</option>
                                                        <option value="09" <?php print $month == '09' ? "selected" : "";?>>September</option>
                                                        <option value="10" <?php print $month == '10' ? "selected" : "";?>>Oktober</option>
                                                        <option value="11" <?php print $month == '11' ? "selected" : "";?>>November</option>
                                                        <option value="12" <?php print $month == '12' ? "selected" : "";?>>Desember</option>
                                                    </select>
                                                    <select name="year" class="form-control bg-light border-1 small" style="width: 90px">
                                                        <?php
                                                        for($i=date("Y")-5;$i<=date("Y");$i++) {
                                                            $sel = ($i == date('Y')) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php print $i;?>" <?php print $i == $year ? "selected" : "";?>><?php print date("Y", mktime(0,0,0,0,1,$i+1));?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="input-group-append input-sm">
                                                        <button type="submit" class="btn btn-small btn-primary">
                                                            <i class="fas fa-search fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div style="float: right">
                                                <label>Pilih Periode:&nbsp;</label>
                                                <a href="admin-laporan-bulanan.php<?php print $url;?>periode=awal" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm monthly<?php print $periode == 'awal' ? '-active' : '';?>"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Awal Bulan </a>       
                                                <a href="admin-laporan-bulanan.php<?php print $url;?>periode=akhir" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm monthly<?php print $periode == 'akhir' ? '-active' : '';?>"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Akhir Bulan </a> 
                                            </div>
                                        </div>
                                    </div>

                                    <a href="admin-export-bulanan.php<?php print $url;?>periode=akhir" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mt-3"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Export Laporan </a><br><br>

                                    <div class="table-responsive">
                                        <table class="table tables-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center" width="140">Jenis Pekerjaan</th>
                                                    <th class="text-center">Tanggal</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $filtered = $year."-".$month;
                                                    if($periode == "awal"){
                                                        $range1 = $year."-".$month."-01";
                                                        $range2 = $year."-".$month."-15";
                                                    }else{
                                                        $range1 = $year."-".$month."-16";
                                                        $range2 = $year."-".$month."-".date("t", strtotime($filtered."-01"));
                                                    }
                                                    $whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";

                                                    $sql = "SELECT  * FROM kegiatan $whereclause GROUP BY pekerjaan_id";
                                                    $query = $kegiatan->show_sql($sql);
                                                    if(count($query) > 0){
                                                        $no = 1;
                                                        foreach($query as $data){
                                                            unset($satuan);
                                                            unset($tanggal);
                                                            $totalvol=0;
                                                            $querydate = $kegiatan->show_sql("SELECT * FROM kegiatan $whereclause AND pekerjaan_id = '{$data['pekerjaan_id']}' ");
                                                            foreach($querydate as $datadate){
                                                                ## all dates
                                                                $tanggal[] = date("d", $datadate['keg_timestamp']);

                                                                ## total volume
                                                                $totalvol += $datadate['keg_volume'];

                                                                ## all satuan
                                                                $satuan[] = $datadate['keg_satuan'];
                                                            }
                                                            ?>
                                                                <tr>
                                                                    <td><?php print $no;?></td>
                                                                    <td>
                                                                        <?php
                                                                            $getpekerjaan = $pekerjaan->show_pekerjaan_detail($data['pekerjaan_id']);
                                                                            print $getpekerjaan['pekerjaan_name'];
                                                                        ?>
                                                                    </td>
                                                                    <td align="center">
                                                                        <?php 
                                                                            asort($tanggal); 
                                                                            print implode(", ", array_unique($tanggal));
                                                                        ?>
                                                                    </td>
                                                                    <td align="center"><?php print $totalvol; ?></td>
                                                                    <td align="center">
                                                                        <?php
                                                                            $satuan = array_unique(array_map("strtoupper", $satuan));
                                                                            print implode(", ", $satuan);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            $no++;
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require("web-footer.php"); ?>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/jquery.easing.1.3.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/chart.js/Chart.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../js/demo/datatables-demo.js"></script>
</body>
</html>
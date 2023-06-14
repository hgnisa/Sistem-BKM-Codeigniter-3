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
    $_SESSION['menu'] = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM  - Dashboard</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <style>
        .table-responsive{
            font-size: small;
        }

        .label-success {
            background-color: #5cb85c !important;
            padding-right: 5px;
            padding-left: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            border-radius: 3px;
            color: #fff;
        }

        .label-danger {
            background-color: #e74a3b !important;
            padding-right: 7px;
            padding-left: 7px;
            padding-top: 1px;
            padding-bottom: 1px;
            border-radius: 3px;
            color: #fff;
        }

        .label-warning {
            background-color: #f6c23e !important;
            padding-right: 7px;
            padding-left: 7px;
            padding-top: 1px;
            padding-bottom: 1px;
            border-radius: 3px;
            color: #fff;
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Laporan Harian</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" sssid="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="110">Tanggal</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="150">Jumlah Volume Satuan</th>
                                                    <th class="text-center" width="180">Kavling</th>
                                                    <th class="text-center" width="150">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $filtered = date('Y')."-".date('m');
                                                    $range1 = $filtered."-01";
                                                    $range2 = $filtered."-".date("t", strtotime($filtered."-01"));
                                                    
                                                    $whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";

                                                    $sql = "SELECT  * FROM kegiatan $whereclause GROUP BY keg_date ORDER BY keg_date DESC";
                                                    $datatanggal = $kegiatan->show_sql($sql);
                                                    if(count($datatanggal) > 0){
                                                        $no = 1;
                                                        foreach($datatanggal as $data){
                                                            ## hanya tampilkan 10 rows data di dashboard
                                                            if($no < 11){
                                                                ?>
                                                                    <?php
                                                                        unset($namapkj);
                                                                        unset($totalkav);
                                                                        $totalvolume = 0;
                                                                        $sql = "SELECT * FROM kegiatan WHERE keg_date = '{$data['keg_date']}'";
                                                                        $datakegiatan = $kegiatan->show_sql($sql);
                                                                        foreach($datakegiatan as $datakeg){

                                                                            $namapkj[] = $pekerjaan->show_pekerjaan_detail($datakeg['pekerjaan_id'])['pekerjaan_name'];

                                                                            $totalvolume += $datakeg['keg_volume'];

                                                                            $totalkav[] = $datakeg['kav_id'];

                                                                            if($datakeg['keg_status'] == 'p'){
                                                                                $totalstatus = 'p';
                                                                            }elseif($datakeg['keg_status'] == 'n'){
                                                                                $totalstatus = 'n';
                                                                            }else{
                                                                                $totalstatus = 'y';
                                                                            }
                                                                        }
                                                                    ?>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <?php 
                                                                                $dates = strtotime($data['keg_date']);
                                                                                print date('d/m/y',$dates);
                                                                            ?>
                                                                        </td>
                                                                        <td><?php print implode(', ', array_unique($namapkj)); ?></td>
                                                                        <td align="center"><?php print $totalvolume;?></td>
                                                                        <td>
                                                                            <?php 
                                                                                unset($datakavling);
                                                                                foreach($totalkav as $kav){     
                                                                                    $getkav = $kavling->show_kavling_detail($kav['kav_id']);   
                                                                                    $datakavling[] = $getkav['kav_name'];
                                                                                }
                                                                                print implode(', ', array_unique($datakavling))
                                                                            ?>
                                                                        </td>
                                                                        <td align="center">
                                                                            <?php 
                                                                            if($totalstatus == "y"){
                                                                                ?>
                                                                                    <span class="label label-success">
                                                                                        <i class="fas fa-check fa-sm"></i> Diterima
                                                                                    </span>
                                                                                <?php
                                                                            }elseif($totalstatus == "n"){
                                                                                ?>
                                                                                    <span class="label label-danger">
                                                                                        <i class="fas fa-times fa-sm"></i> Ditolak
                                                                                    </span>
                                                                                <?php
                                                                            }else{
                                                                                ?> 
                                                                                <span class="label label-warning">
                                                                                    <i class="fas fa-clock fa-sm"></i> Belum disetujui
                                                                                </span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                            $no++;
                                                        }
                                                    }else{
                                                        ?>
                                                            <tr>
                                                                <td colspan="5" align="center">
                                                                    Tidak ada laporan harian
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <a rel="nofollow" href="admin-laporan-harian.php">Lihat Laporan Harian &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="card shadow mb-4">
                                <?php
                                    $year = date('Y');
                                    $month = date('m');
                                ?>
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan <?php print $kegiatan->month($month)." ".$year;?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="250">Tanggal</th>
                                                    <th class="text-center" width="150">Jumlah Volume Satuan</th>
                                                    <th class="text-center" width="150">Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $range1 = $year."-".$month."-01";
                                                    $range2 = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
                                                    $whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";

                                                    $sql = "SELECT  * FROM kegiatan $whereclause GROUP BY pekerjaan_id ORDER BY pekerjaan_id ASC";
                                                    $query = $kegiatan->show_sql($sql);
                                                    if(count($query) > 0){
                                                        $no = 1;
                                                        foreach($query as $data){
                                                            ## hanya tampilkan 10 row data di dashboard
                                                            if($no < 11){
                                                                ?>
                                                                <?php
                                                                    unset($satuan);
                                                                    unset($tanggal);
                                                                    $totalvolume = 0;
                                                                    $querydate = $kegiatan->show_sql("SELECT * FROM kegiatan $whereclause AND pekerjaan_id = '{$data['pekerjaan_id']}' ");
                                                                    foreach($querydate as $datadate){
                                                                        ## all dates
                                                                        $tanggal[] = date("d", $datadate['keg_timestamp']);

                                                                        ## total volumes
                                                                        $totalvolume += $datadate['keg_volume'];

                                                                        ## all satuan
                                                                        $satuan[] = $datadate['keg_satuan'];
                                                                    }
                                                                ?>
                                                                    <tr>
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
                                                                        <td align="center"><?php print $totalvolume;?></td>
                                                                        <td align="center"><?php print implode(", ", array_unique(array_map("strtoupper", $satuan))); ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                            $no++;
                                                        }
                                                    }else{
                                                        ?>
                                                            <tr>
                                                                <td colspan="5" align="center">
                                                                    Tidak ada laporan bulanan
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <a rel="nofollow" href="admin-laporan-bulanan.php">Lihat Laporan Bulanan &rarr;</a>
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
    <!-- Bootstrap core JavaScript-->
    <script src="../../js/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../js/jquery.easing.1.3.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>
</body>
</html>
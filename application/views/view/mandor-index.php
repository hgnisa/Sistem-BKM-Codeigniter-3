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
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
        <?php require("mandor-web-menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require("mandor-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Profil Mandor</h6>
                                </div>
                                <?php
                                    $userdata = $user->show_user_detail($_COOKIE['cook_id']);
                                ?>
                                <div class="card-body pd-0">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <?php 
                                            if($userdata['user_profile']){
                                                $imgurl = "../../img/profile/".$userdata['user_profile'];
                                            }else{
                                                $imgurl = "../../img/profile-empty.jpg";
                                            }
                                            ?>
                                            <img class="img-fluid" style="width: 10rem; height: 10rem; border-radius: 50%"
                                            src="<?php print $imgurl;?>" alt="...">
                                        </div>
                                        <div class="col-lg-9">
                                            <br>
                                            <table border="0">
                                                <tr valign="top">
                                                    <td width="120"><span class="small">Nama</span></td>
                                                    <td width="50">:</td>
                                                    <td width="300"><span class="small font-weight-bold"><?php print ucwords($userdata['user_name']);?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Username</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $userdata['user_username'];?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Terakhir Login</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print date("d M Y H:i:s", $userdata['user_lastlogin']);?></span></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <a href="mandor-edit-profile.php?id=<?php print $_COOKIE['cook_id'];?>">Ubah Profil &rarr;</a>
                                        </div>                          
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <?php
                                    $year = date('Y');
                                    $month = date('m');
                                ?>
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan <?php print $kegiatan->month($month)." ".$year;?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
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
                                                    $range1 = $year."-".$month."-01";
                                                    $range2 = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));
                                                    $whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";

                                                    $sql = "SELECT  * FROM kegiatan $whereclause GROUP BY keg_date ORDER BY keg_date DESC";
                                                    $query = $kegiatan->show_sql($sql);
                                                    if(count($query) > 0){
                                                        $no = 1;
                                                        foreach($query as $data){
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
                                                                        <td><?php print implode(', ', $namapkj); ?></td>
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
                                                                    </tr>
                                                                <?php
                                                            }
                                                            $no++;
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <a rel="nofollow" href="mandor-rekap-laporan.php">Lihat Rekap Laporan &rarr;</a>
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
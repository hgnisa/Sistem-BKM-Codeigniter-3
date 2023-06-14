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
    <title>Sistem BKM  - Verifikasi Rekap Laporan</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2-new.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .table-responsive{
            font-size: small;
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
                        <h1 class="h3 mb-0 text-gray-800">Verifikasi Rekap Laporan</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <button onclick="window.location='mandor-rekap-laporan.php'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Kembali</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <?php
                                        $date = $_GET['date'];
                                        list($year, $month, $day) = explode("-", $date);

                                        $dateshow = $day." ".$kegiatan->month($month)." ".$year;
                                    ?>  
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan <?php print $dateshow; ?> </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row col-md-12 mb-3">
                                        <a href="../controller/process_kegiatan.php?form=submit&action=approve&date=<?php print $date;?>" onclick="return confirm('Apakah Anda yakin ingin menyetujui rekap laporan tanggal <?php print $dateshow;?>?');" class="btn btn-sm btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check fa-sm"></i>
                                            </span>
                                            <span class="text">Setujui Laporan</span>
                                        </a> 

                                        &nbsp;&nbsp;

                                        <a href="../controller/process_kegiatan.php?form=submit&action=reject&date=<?php print $date;?>" onclick="return confirm('Apakah Anda yakin ingin menolak rekap laporan tanggal <?php print $dateshow;?>?');" class="btn btn-sm btn-danger btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-times fa-sm"></i>
                                            </span>
                                            <span class="text">Tolak Laporan</span>
                                        </a> 
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="180">Nama</th>
                                                    <th class="text-center" width="120">Hasil Kerja (Volume)</th>
                                                    <th class="text-center" width="80">Satuan</th>
                                                    <th class="text-center" width="180">Kavling</th>
                                                    <th class="text-center" width="10"><i class="fa fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $whereClause="keg_date = '$date'";

                                                $sql = "SELECT * FROM kegiatan ORDER BY keg_id ASC";
                                                if($whereClause){
                                                    $whereClause=" WHERE $whereClause";
                                                    $sql="SELECT * FROM kegiatan $whereClause ORDER BY keg_id ASC";
                                                }

                                                $datakegiatan = $kegiatan->show_sql($sql);
                                                if(count($datakegiatan) > 0){
                                                    $no = 1;
                                                    foreach($datakegiatan as $data){
                                                        ?>
                                                            <tr>
                                                                <td align="center"><?php print $no; ?></td>
                                                                <td>
                                                                    <?php
                                                                        $getpekerjaan = $pekerjaan->show_pekerjaan_detail($data['pekerjaan_id']);
                                                                        print $getpekerjaan['pekerjaan_name'];
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $getbhl = $user->show_user_detail($data['user_id']);
                                                                        print $getbhl['user_name'];
                                                                    ?>
                                                                </td>
                                                                <td align="center"><?php print $data['keg_volume'];?></td>
                                                                <td align="center"><?php print $data['keg_satuan'];?></td>
                                                                <td>
                                                                    <?php
                                                                        $getkav = $kavling->show_kavling_detail($data['kav_id']);
                                                                        print $getkav['kav_name'];
                                                                    ?>
                                                                </td>
                                                                <td align="center">
                                                                    <a href="mandor-detail-kegiatan.php?id=<?php print $data['keg_id'];?>&date=<?php print $date;?>&from=verif" title="Detail Kegiatan">
                                                                        <i class="fa fas-form fa-list"></i>
                                                                    </a>&nbsp;&nbsp;
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

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
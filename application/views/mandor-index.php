<?php
    $_SESSION['menu'] = 1;
    date_default_timezone_set("Asia/Jakarta");
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
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                                <div class="card-body pd-0">
                                    <div class="row">
                                        <div class="col-lg-3 text-center">
                                            <?php 
                                            if($users->user_profile){
                                                $imgurl = base_url()."img/profile/".$users->user_profile;
                                            }else{
                                                $imgurl = base_url()."img/profile-empty.jpg";
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
                                                    <td width="300"><span class="small font-weight-bold"><?php print ucwords($users->user_name);?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Username</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $users->user_username;?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Terakhir Login</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print date("d M Y H:i:s", $users->user_lastlogin);?></span></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <a href="<?php print base_url(); ?>mandor/profile/<?php print $users->user_id;?>">Ubah Profil &rarr;</a>
                                        </div>                          
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan <?php print $month_name." ".$year;?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="110">Tanggal</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="120">Jumlah Volume Satuan</th>
                                                    <th class="text-center" width="250">Kavling</th>
                                                    <th class="text-center" width="150">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    if(count($rekap) > 0){
                                                        $no = 1;
                                                        foreach($rekap as $key => $data){
                                                            if($no < 11){
                                                                ?>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <?php 
                                                                                $dates = strtotime($data['keg_date']);
                                                                                print date('d/m/y',$dates);
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php 
                                                                                print $data['pekerjaan_name'];
                                                                            ?>
                                                                        </td>
                                                                        <td align="center">
                                                                            <?php 
                                                                                print $data['keg_volume'];
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php 
                                                                                print $data['kav_name'];
                                                                            ?>
                                                                        </td>
                                                                        <td align="center">
                                                                            <?php 
                                                                            if($data['keg_status'] == "y"){
                                                                                ?>
                                                                                    <span class="label label-success">
                                                                                        <i class="fas fa-check fa-sm"></i> Diterima
                                                                                    </span>
                                                                                <?php
                                                                            }elseif($data['keg_status'] == "n"){
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
                                                                    Tidak ada data
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <a rel="nofollow" href="<?php print base_url();?>mandor/recap">Lihat Rekap Laporan &rarr;</a>
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
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
    <script src="<?php print base_url();?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php print base_url();?>js/jquery.easing.1.3.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php print base_url();?>js/sb-admin-2.min.js"></script>
</body>
</html>
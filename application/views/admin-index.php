<?php
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
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
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
                                    <table class="table table-bordered" id="dataTable   " width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="100">Tanggal</th>
                                                    <th class="text-center">Nama BHL</th>
                                                    <th class="text-center" width="300">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="150">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    if(count($harian) > 0){
                                                        $no = 1;
                                                        foreach($harian as $key => $data){
                                                            if($no < 3){
                                                                ?>  
                                                                <tr>
                                                                    <td colspan="5" style="padding-left: 20px">
                                                                        <?php 
                                                                            $dates = strtotime($key);
                                                                            print date('d/m/y',$dates);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                foreach($harian[$key] as $data) {
                                                                    if($data['keg_status'] == 'p'){
                                                                        $status = 'p';
                                                                    }elseif($data['keg_status'] == 'n'){
                                                                        $status = 'n';
                                                                    }else{
                                                                        $status = 'y';
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="2" style="padding-left: 120px;"><strong><?php print $data['user_name'];?></strong></td>
                                                                        <td><?php print $data['pekerjaan_name'];?></td>
                                                                        <td align="center">
                                                                            <?php 
                                                                            if($status == "y"){
                                                                                ?>
                                                                                    <span class="label label-success">
                                                                                        <i class="fas fa-check fa-sm"></i> Diterima
                                                                                    </span>
                                                                                <?php
                                                                            }elseif($status == "n"){
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
                                        <a rel="nofollow" href="<?php print base_url();?>admin/report/daily">Lihat Laporan Harian &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan <?php print $monthname." ".$year;?></h6>
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
                                                    if(count($bulanan) > 0){
                                                        $no = 1;
                                                        foreach($bulanan as $data){
                                                            if($no < 11){
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php print $data['pekerjaan_name'];?>
                                                                    </td>
                                                                    <td align="center"><?php print $data['keg_date'];?></td>
                                                                    <td align="center"><?php print $data['keg_volume'];?></td>
                                                                    <td align="center"><?php print $data['keg_satuan'];?></td>
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
                                        <a rel="nofollow" href="<?php print base_url();?>admin/report/monthly/start?m=<?php print date('m');?>&y=<?php print date('Y');?>">Lihat Laporan Bulanan &rarr;</a>
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
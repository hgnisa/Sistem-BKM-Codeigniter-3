<?php
    $_SESSION['menu'] = 3;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM  - Detail Kegiatan</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Kegiatan</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <?php                             
                            if($from == "verif"){
                                $urlback = base_url()."mandor/verifikasi/".$date;
                            }else{
                                $urlback = base_url()."mandor/kegiatan?date=".$date;
                            }
                            ?>
                            <button onclick="window.location='<?php print $urlback;?>'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Kembali</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Detail Kegiatan</h6>
                                </div>
                                <div class="card-body pd-0">
                                    <div class="row">
                                        <div class="col-lg-4 mx-5 text-center">
                                            <?php 
                                            if($kegiatan['keg_image']){
                                                $imgurl = base_url()."img/kegiatan/".$kegiatan['keg_image'];
                                            }else{
                                                $imgurl = base_url()."img/image-empty.jpg";
                                            }
                                            ?>
                                            <img class="img-fluid" src="<?php print $imgurl;?>" alt="...">
                                        </div>
                                        <div class="col-lg-6">
                                            <table cellspacing="4" cellpadding="4" bordir="1">
                                                <tr valign="top">
                                                    <td width="120"><span class="small">Tanggal</span></td>
                                                    <td width="50">:</td>
                                                    <td width="300">
                                                        <span class="small font-weight-bold">
                                                            <?php 
                                                                print $fulldate;
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Jenis Pekerjaan</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold">
                                                        <?php 
                                                            print $kegiatan['pekerjaan_name'];
                                                        ?>
                                                    </span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Kavling</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold">
                                                        <?php 
                                                           print $kegiatan['kav_name'];
                                                        ?>
                                                    </span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td width="120"><span class="small">Nama</span></td>
                                                    <td width="50">:</td>
                                                    <td><span class="small font-weight-bold">
                                                        <?php 
                                                            print $kegiatan['user_name'];
                                                        ?>
                                                    </span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Volume</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $kegiatan['keg_volume'] ? $kegiatan['keg_volume'] : "-";?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Satuan</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $kegiatan['keg_satuan'] ? $kegiatan['keg_satuan'] : "-";?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Cuaca</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $kegiatan['keg_cuaca'] ? ucwords($kegiatan['keg_cuaca']) : "-";?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Unit</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $kegiatan['keg_unit'] ? $kegiatan['keg_unit'] : "-";?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Keterangan</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold"><?php print $kegiatan['keg_keterangan'] ? $kegiatan['keg_keterangan'] : "-" ;?></span></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td><span class="small">Status</span></td>
                                                    <td>:</td>
                                                    <td><span class="small font-weight-bold">
                                                        <?php
                                                            if($kegiatan['keg_status'] == 'p'){
                                                                ?>
                                                                    <span class="label label-warning">
                                                                        <i class="fas fa-clock fa-sm"></i> Belum disetujui
                                                                    </span>
                                                                <?php
                                                            }elseif($kegiatan['keg_status'] == 'y'){
                                                                ?>
                                                                <span class="label label-success">
                                                                    <i class="fas fa-check fa-sm"></i> Disetujui
                                                                </span>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <span class="label label-danger">
                                                                    <i class="fas fa-times fa-sm"></i> Ditolak
                                                                </span>
                                                                <?php
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        </div>                      
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

    <script src="<?php print base_url();?>js/jquery.min.js"></script>
    <script src="<?php print base_url();?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php print base_url();?>js/jquery.easing.1.3.js"></script>
    <script src="<?php print base_url();?>js/sb-admin-2.min.js"></script>
    <script src="<?php print base_url();?>vendor/chart.js/Chart.min.js"></script>
    <script src="<?php print base_url();?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?php print base_url();?>js/demo/datatables-demo.js"></script>
</body>
</html>
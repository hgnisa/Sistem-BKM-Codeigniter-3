<?php
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
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2-new.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/datatables/da
			## notification
			$data['notify'] = $this->reports->groupBy(array('keg_status' => 'p'), 'keg_date');taTables.bootstrap4.min.css" rel="stylesheet">
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
                            <button onclick="window.location='<?php print base_url();?>mandor/recap'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Kembali</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">  
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan <?php print $fulldate; ?> </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row col-md-12 mb-3">
                                        <a href="<?php print base_url();?>mandor/verifyReport/y/<?php print $date;?>" onclick="return confirm('Apakah Anda yakin ingin menyetujui rekap laporan tanggal <?php print $fulldate;?>?');" class="btn btn-sm btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check fa-sm"></i>
                                            </span>
                                            <span class="text">Setujui Laporan</span>
                                        </a> 

                                        &nbsp;&nbsp;

                                        <a href="<?php print base_url();?>mandor/verifyReport/n/<?php print $date;?>" onclick="return confirm('Apakah Anda yakin ingin menolak rekap laporan tanggal <?php print $fulldate;?>?');" class="btn btn-sm btn-danger btn-icon-split">
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
                                                if(count($rekap) > 0){
                                                    $no = 1;
                                                    foreach($rekap as $data){
                                                        ?>
                                                            <tr>
                                                                <td align="center"><?php print $no; ?></td>
                                                                <td>
                                                                    <?php print $data['pekerjaan_name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php print $data['user_name']; ?>
                                                                </td>
                                                                <td align="center"><?php print $data['keg_volume']; ?></td>
                                                                <td align="center"><?php print $data['keg_satuan']; ?></td>
                                                                <td>
                                                                    <?php print $data['kav_name']; ?>
                                                                </td>
                                                                <td align="center">
                                                                    <a href="<?php print base_url();?>mandor/detailReport/<?php print $data['keg_id'];?>/<?php print $date;?>/verif" title="Detail Kegiatan">
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
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
    <title>Sistem BKM  - Kegiatan</title>
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2-new.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
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
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Kegiatan</h6>
                                </div>
                                <div class="card-body">
                                    <a href="<?php print base_url();?>mandor/exportReport?<?php print $date;?>" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Export Laporan </a>    
                                    <form action="<?php print base_url();?>mandor/report" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
                                        <div class="input-group">
                                            <label>Filter berdasarkan tanggal: &nbsp;</label>
                                            <input type="date" name="date" class="form-control bg-light border-1 small" placeholder="Cari" aria-label="Search" aria-describedby="basic-addon2" value="<?php print empty($date) ? date("Y-m-d") : date_format(date_create($date),"Y-m-d");?>">
                                            <div class="input-group-append input-sm">
                                                <button type="submit" class="btn btn-small btn-primary">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive" style="margin-top: 30px; margin-bottom: 30px">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="180">Nama</th>
                                                    <th class="text-center" width="80">Hasil Kerja (Volume)</th>
                                                    <th class="text-center" width="80">Satuan</th>
                                                    <th class="text-center" width="180">Kavling</th>
                                                    <th class="text-center" width="10"><i class="fa fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if(count($kegiatan) > 0){
                                                    $no = 1;
                                                    foreach($kegiatan as $data){
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
                                                                    <a href="<?php print base_url();?>mandor/detailReport/<?php print $data['keg_id']; ?>/<?php print $date;?>" title="Detail Kegiatan">
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

    <!-- Bootstrap core JavaScript-->
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
    <script src="<?php print base_url();?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php print base_url();?>js/jquery.easing.1.3.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php print base_url();?>js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php print base_url();?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php print base_url();?>js/demo/datatables-demo.js"></script>

</body>
</html>
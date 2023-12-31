<?php
    $_SESSION['menu'] = 6;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM  - Data Kavling</title>
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2-new.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#dataTable').dataTable( {
                "ordering": false
            });
        });
    </script>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require("admin-web-menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require("admin-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Data Kavling</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Kavling</h6>
                                </div>
                                <div class="card-body">
                                    <a href="<?php print base_url();?>admin/kav/add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i>  Tambah Kavling </a>
                                    <br/><br/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center">Nama </th>
                                                    <th class="text-center">Nama di SHM </th>
                                                    <th class="text-center">Lokasi </th>
                                                    <th class="text-center">Luas (M2) </th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                if(count($kavling) > 0){
                                                    $no = 1;
                                                    foreach($kavling as $data){
                                                        ?>
                                                            <tr>
                                                                <td><?php print $no;?></td>
                                                                <td><?php print $data->kav_name;?></td>
                                                                <td><?php print $data->kav_shm;?></td>
                                                                <td><?php print $data->kav_lokasi;?></td>
                                                                <td class="text-center"><?php print $data->kav_luas;?></td>
                                                                <td align="center">
                                                                    <a href="<?php print base_url();?>admin/kav/edit/<?php print $data->kav_id;?>">
                                                                        <i class="fas fas-form fa-edit"></i>
                                                                    </a>
                                                                </td>
                                                                <td align="center"> 
                                                                    <a href="<?php print base_url();?>admin/kav/deleteKavling/<?php print $data->kav_id;?>"  onclick="return confirm('Apakah Anda yakin ingin menghapus kavling <?php print $data->kav_name;?>?');">
                                                                        <i class="fas fas-form fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    $no++;
                                                    }
                                                }?>
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
    <script src="<?php print base_url();?>vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?php print base_url();?>js/demo/datatables-demo.js"></script>
</body>
</html>
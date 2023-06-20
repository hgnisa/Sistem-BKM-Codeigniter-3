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
    <title>Sistem BKM  - Edit Kavling</title>
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
                        <div class="col-lg-12 mb-2">
                            <div class="card shadow mb-2">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Kavling</h6>
                                </div>
                                <div class="alert mt-3" id="alert" style="display: none;">
                                    <span class="closebtn"  onclick="this.parentElement.style.display='none';">&times;</span>
                                    <div id="closebtn" style="font-size: 13px"> This is an alert box. </div>
                                </div>
                                <div class="card-body">
                                    <form class="user" action="<?php print base_url();?>admin/kav/updateKavling/<?php print $kavling->kav_id;?>" method="post" enctype="multipart/form-data" name="editkavling" id="editkavling">
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="kav_name">Nama</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="kav_name" name="kav_name" value="<?php print $kavling->kav_name;?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="kav_shm">Nama</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="kav_shm" name="kav_shm" value="<?php print $kavling->kav_shm;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="kav_lokasi">Lokasi</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="kav_lokasi" name="kav_lokasi" value="<?php print $kavling->kav_lokasi;?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="kav_luas">Luas</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="kav_luas" name="kav_luas" value="<?php print $kavling->kav_luas;?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9 form-inline">
                                                <button type="submit" class="btn btn-primary btn-sm form-input-sm mt-0" style="margin-right: 8px"><i class="fa fa-edit fa-fw"></i> Edit
                                                <button onclick="window.location='<?php print base_url();?>admin/kav'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Batal
                                            </div>
                                        </div>
                                    </form>
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
    <script>
        window.addEventListener('DOMContentLoaded',function(){
            if(window.localStorage.getItem('error') == 'true') {
                alerterror(window.localStorage.getItem('errormsg'));
            }
        })

        function alerterror(data) { 
            var x = document.getElementById("alert");
            x.style.display = "block";

            document.getElementById('closebtn').innerHTML = data;
        }         

        var close = document.getElementsByClassName("closebtn");
        var i;
        for (i = 0; i < close.length; i++) {
            close[i].onclick = function(){
                window.localStorage.setItem('error', false);
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function(){ div.style.display = "none"; }, 600);
            }
        }
    </script>
</body>
</html>
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
    <title>Sistem BKM - Ubah Pengguna</title>
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2-new.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="<?php print base_url();?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require("mandor-web-menu.php");?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require("mandor-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ubah Pengguna</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ubah Pengguna</h6>
                                </div>
                                <div class="alert mt-3" id="alert" style="display: none;">
                                    <span class="closebtn"  onclick="this.parentElement.style.display='none';">&times;</span>
                                    <div id="closebtn" style="font-size: 13px"> This is an alert box. </div>
                                </div>
                                <div class="card-body">
                                    <form class="user" action="<?php print base_url(); ?>user/update/<?php print $users->user_id;?>" method="post" enctype="multipart/form-data" name="edituser" id="edituser">
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="name">Nama</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="name" name="name" value="<?php print $users->user_name;?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="username">Username</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-input-sm" id="username" name="username" value="<?php print $users->user_username;?>" minlength='4' required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="password">Password Baru</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-input-sm" id="password" name="password" minlength='4'>
                                                <p style="font-size: 12px; margin-bottom: 0px"> *Isi kolom ini hanya jika Anda ingin mengganti password</p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label>Tipe</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label form-input-sm">
                                                        <input type="radio" class="form-check-input" name="type" id="typemandor" value="mandor" checked>Mandor
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            if($users->user_profile){
                                                ?>
                                                <div class="form-group row" id="mandorimage">
                                                    <div class="col-sm-3 mt-2 form-input-sm"></div>
                                                    <div class="col-sm-6">
                                                    <img class="img-fluid" style="width: 10rem; height: 10rem; border-radius: 50%" src="<?php print base_url();?>img/profile/<?php print $users->user_profile;?>" alt="...">
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                        <div class="form-group row" id="mandorimage">
                                            <div class="col-sm-3 mt-2 form-input-sm">
                                                <label for="mandorimage">Gambar Profil</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="file" class="form-control form-input-sm" id="mandorimage" name="mandorimage">
                                                <p style="font-size: 12px; margin-bottom: 0px"> *Upload gambar hanya jika Anda ingin mengganti gambar profile</p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-3 mt-2"></div>
                                            <div class="col-sm-9 form-inline mt-4">
                                                <button type="submit" class="btn btn-primary btn-sm form-input-sm mt-0" style="margin-right: 8px"><i class="fa fa-edit fa-fw"></i> Ubah 
                                                <button onclick="window.location='<?php print base_url();?>mandor'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Batal
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

        jQuery(document).ready(function($){
            $('#typemandor').click(function() {
                $('#mandorimage').show();
            });
            $('#typeadmin').click(function() {
                $('#mandorimage').hide();
            });
        });

        $(document).ready(function(){
            $('.detailprofile').on('click',function(){
                var dataURL = $(this).attr('data-href');
                $('.body-history').load(dataURL,function(){
                    $('#modaldetailprofile').modal({show:true});
                });
            }); 
        });
    </script>
</body>
</html>
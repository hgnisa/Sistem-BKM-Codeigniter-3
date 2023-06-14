<?php
    include '../model/connection.php';
    include '../model/class-user.php';
    $user = new user(); 

    if(empty($_COOKIE['cook_id'])){
        header("location:../../index.php");
    }

    session_start();
    $_SESSION['menu'] = 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM - Kelola Pengguna</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2-new.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript">
        var action = '<?php print $_GET['action'];?>';
        jQuery(document).ready(function($){
            if(action == 'add' || action == 'edit'){
                if($('#typeadmin').is(':checked')) {  
                    $('#mandorimage').hide();
                }else if($('#typemandor').is(':checked') || $('#typebhl').is(':checked')) {  
                    $('#mandorimage').hide();
                }
            }
        });

        $(document).ready( function () {
            $('#dataTable').dataTable( {
                "ordering": false
            });
        });

        if(action == ''){
            window.localStorage.setItem('error', false);
        }  
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
                    </div>
                    <?php if(empty($_GET['action'])) { ?>
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
                                    </div>
                                    <div class="card-body">
                                        <a href="admin-pengguna.php?action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i>  Tambah Pengguna </a>
                                        <br/><br/>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5">No</th>
                                                        <th class="text-center">Nama</th>
                                                        <th class="text-center" width="100">Username</th>
                                                        <th class="text-center" width="100">Keterangan</th>
                                                        <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                        <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                        <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $data = $user->show_user();
                                                    if(count($data) > 0){
                                                        $no = 1;
                                                        foreach($data as $datauser){
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?php print $no;?></td>
                                                                    <td><?php print ucwords($datauser['user_name']);?></td>
                                                                    <td><?php print $datauser['user_username'];?></td>
                                                                    <td align="center"><?php print ucwords($datauser['user_type']);?></td> 
                                                                    <td align="center"> 
                                                                        <?php
                                                                            if($datauser['user_profile']){
                                                                                ?>
                                                                                <a href="#" onclick="return false" data-toggle="modal" data-target="#modaldetailprofile" data-backdrop="static" data-href="admin-modal-profile.php?id=<?php print $datauser['user_id']; ?>" class="detailprofile" title="Gambar: <?php print $datauser['user_name'];?>"><i class="fa fas-form fa-image"></i></a>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td align="center"> 
                                                                        <a href="admin-pengguna.php?action=edit&id=<?php print $datauser['user_id'];?>" title="Edit: <?php print $datauser['user_name'];?>">
                                                                            <i class="fa fas-form fa-edit"></i>
                                                                        </a>&nbsp;&nbsp;
                                                                       
                                                                    </td>
                                                                    <td align="center"> 
                                                                        <a href="../controller/process_user.php?form=submit&action=del&id=<?php print $datauser['user_id'];?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna <?php print ucwords($datauser['user_name']);?>?');">
                                                                            <i class="fas fas-form fa-trash"></i>
                                                                        </a> 
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
                        <div class="modal fade" id="modaldetailprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Profil Pengguna</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body body-history">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }elseif($_GET['action']=="add"){ ?>
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Tambah Pengguna</h6>
                                    </div>
                                    <div class="alert mt-3" id="alert" style="display: none;">
                                        <span class="closebtn"  onclick="this.parentElement.style.display='none';">&times;</span>
                                        <div id="closebtn" style="font-size: 13px"> This is an alert box. </div>
                                    </div>
                                    <div class="card-body">
                                        <form class="user" action="../controller/process_user.php?form=submit&action=add" method="post" enctype="multipart/form-data" name="adduser" id="adduser">
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="name">Nama</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-input-sm" id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="username">Username</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-input-sm" id="username" name="username" minlength='4' required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="password">Password</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" class="form-control form-input-sm" id="password" name="password" minlength='4' required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="confirmpass">Konfirmasi Password</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" class="form-control form-input-sm" id="confirmpass" name="confirmpass" minlength='4' required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label>Tipe</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label form-input-sm">
                                                            <input type="radio" class="form-check-input" name="type" value="admin" id="typeadmin" checked="">Admin
                                                        </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label form-input-sm">
                                                            <input type="radio" class="form-check-input" name="type" value="mandor" id="typemandor">Mandor
                                                        </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label form-input-sm">
                                                            <input type="radio" class="form-check-input" name="type" value="bhl" id="typebhl">BHL
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="mandorimage">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="mandorimage">Gambar</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="file" class="form-control form-input-sm" id="mandorimage" name="mandorimage">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2"></div>
                                                <div class="col-sm-9 form-inline mt-4">
                                                    <button type="submit" class="btn btn-primary btn-sm form-input-sm mt-0" style="margin-right: 8px"><i class="fa fa-plus fa-fw"></i> Tambah
                                                    <button onclick="window.location='admin-pengguna.php'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Batal
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }elseif($_GET['action']=="edit"){ ?>
                        <?php
                            $id = $_GET['id']; 
                            $data = $user->show_user_detail($id);
                        ?>
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Edit Pengguna</h6>
                                    </div>
                                    <div class="card-body">
                                        <form class="user" action="../controller/process_user.php?form=submit&action=edit&id=<?php print $_GET['id'];?>" method="post" enctype="multipart/form-data" name="edituser" id="edituser">
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="name">Nama</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-input-sm" id="name" name="name" value="<?php print $data['user_name'];?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="username">Username</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-input-sm" id="username" name="username" value="<?php print $data['user_username'];?>" minlength='4' required>
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
                                                            <input type="radio" class="form-check-input" name="type" id="typeadmin" value="admin" <?php print $data['user_type'] == 'admin' ? 'checked' : ''; ?>>Admin
                                                        </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label form-input-sm">
                                                            <input type="radio" class="form-check-input" name="type" id="typemandor" value="mandor" <?php print $data['user_type'] == 'mandor' ? 'checked' : ''; ?>>Mandor
                                                        </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label form-input-sm">
                                                            <input type="radio" class="form-check-input" name="type" id="typebhl" value="bhl" <?php print $data['user_type'] == 'bhl' ? 'checked' : ''; ?>>BHL
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if($data['user_profile']){
                                                    ?>
                                                    <div class="form-group row">
                                                        <div class="col-sm-3 mt-2 form-input-sm"></div>
                                                        <div class="col-sm-6">
                                                        <img class="img-fluid" style="width: 10rem; height: 10rem; border-radius: 50%" src="../../img/profile/<?php print $data['user_profile'];?>" alt="...">
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                            <div class="form-group row" id="mandorimage">
                                                <div class="col-sm-3 mt-2 form-input-sm">
                                                    <label for="mandorimage">Gambar</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="file" class="form-control form-input-sm" id="mandorimage" name="mandorimage">
                                                    <p style="font-size: 12px; margin-bottom: 0px"> *Upload gambar hanya jika Anda ingin mengganti gambar profile</p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 mt-2"></div>
                                                <div class="col-sm-9 form-inline mt-4">
                                                    <button type="submit" class="btn btn-primary btn-sm form-input-sm mt-0" style="margin-right: 8px"><i class="fa fa-edit fa-fw"></i> Edit 
                                                    <button onclick="window.location='admin-pengguna.php'" type="submit" class="btn btn-warning btn-sm form-input-sm mt-0 pr-3"><i class="fa fa-arrow-left fa-fw"></i> Batal
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>

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
            var action = '<?php print $_GET['action']?>';
            if(action == 'add' || action == 'edit'){
                // $('#typemandor').click(function() {
                    // $('#mandorimage').show();
                // });
                // $('#typeadmin').click(function() {
                //     $('#mandorimage').hide();
                // });
                // $('#typebhl').click(function() {
                //     $('#mandorimage').show();
                // });

                if($('#typebhl').is(':checked') || $('#typemandor').is(':checked')) {  
                    $('#mandorimage').show();
                }else{
                    $('#mandorimage').hide();
                }

                $("#typeadmin").change(function() {
                    if(this.checked) {
                        $("#mandorimage").hide();
                    }
                });
                $("#typebhl").change(function() {
                    if(this.checked) {
                        $("#mandorimage").show();
                    }
                });
                $("#typemandor").change(function() {
                    if(this.checked) {
                        $("#mandorimage").show();
                    }
                });
            }

            // if(action == 'add' || action == 'edit'){
            //    
            // }
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
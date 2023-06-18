<?php
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
    <title>Sistem BKM - Pengguna</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
                                </div>
                                <div class="card-body">
                                    <a href="<?php print base_url();?>admin/pengguna/add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna </a>
                                    <br/><br/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center">Nama</th>
                                                    <th class="text-center" width="150">Username</th>
                                                    <th class="text-center" width="100">Keterangan</th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if(count($pengguna) > 0){
                                                    $no = 1;
                                                    foreach($pengguna as $data){
                                                        ?>
                                                            <tr>
                                                                <td class="text-center"><?php print $no;?></td>
                                                                <td><?php print ucwords($data['user_name']);?></td>
                                                                <td><?php print $data['user_username'];?></td>
                                                                <td align="center"><?php print ucwords($data['user_type']);?></td> 
                                                                <td align="center"> 
                                                                    <?php
                                                                        if($data['user_profile']){
                                                                            ?>
                                                                            <a href="#" onclick="return false" data-toggle="modal" data-target="#modaldetailprofile" data-backdrop="static" data-href="<?php print base_url();?>admin/pengguna/<?php print $data['user_id']; ?>" class="detailprofile" title="Gambar: <?php print $data['user_name'];?>"><i class="fa fas-form fa-image"></i></a>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td align="center"> 
                                                                    <a href="<?php print base_url();?>admin/pengguna/edit/<?php print $data['user_id'];?>" title="Edit: <?php print $data['user_name'];?>">
                                                                        <i class="fa fas-form fa-edit"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                    
                                                                </td>
                                                                <td align="center"> 
                                                                    <a href="<?php print base_url();?>admin/pengguna/del/<?php print $data['user_id'];?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna <?php print ucwords($data['user_name']);?>?');">
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
            var action = '<?php print $_GET['action']?>';
            if(action == 'add' || action == 'edit'){
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
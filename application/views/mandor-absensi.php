<?php
    $_SESSION['menu'] = 2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem BKM  - Absensi</title>
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
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require("mandor-web-menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                 <?php require("mandor-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Absensi</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Absensi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <form action="<?php print base_url();?>mandor/absensi" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
                                            <div class="input-group">
                                                <label>Filter berdasarkan bulan: &nbsp;</label>
                                                <select name="m" size='1' class="form-control bg-light border-1 small" placeholder="Cari"
                                                    aria-label="Search" aria-describedby="basic-addon2" style="width: 130px">
                                                    <option value="01" <?php print $month == '01' ? "selected" : "";?>>Januari</option>
                                                    <option value="02" <?php print $month == '02' ? "selected" : "";?>>Februari</option>
                                                    <option value="03" <?php print $month == '03' ? "selected" : "";?>>Maret</option>
                                                    <option value="04" <?php print $month == '04' ? "selected" : "";?>>April</option>
                                                    <option value="05" <?php print $month == '05' ? "selected" : "";?>>Mei</option>
                                                    <option value="06" <?php print $month == '06' ? "selected" : "";?>>Juni</option>
                                                    <option value="07" <?php print $month == '07' ? "selected" : "";?>>Juli</option>
                                                    <option value="08" <?php print $month == '08' ? "selected" : "";?>>Agustus</option>
                                                    <option value="09" <?php print $month == '09' ? "selected" : "";?>>September</option>
                                                    <option value="10" <?php print $month == '10' ? "selected" : "";?>>Oktober</option>
                                                    <option value="11" <?php print $month == '11' ? "selected" : "";?>>November</option>
                                                    <option value="12" <?php print $month == '12' ? "selected" : "";?>>Desember</option>
                                                </select>
                                                <select name="y" class="form-control bg-light border-1 small" style="width: 90px">
                                                    <?php
                                                    for($i=date("Y")-5;$i<=date("Y");$i++) {
                                                        $sel = ($i == date('Y')) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php print $i;?>" <?php print $i == $year ? "selected" : "";?>><?php print date("Y", mktime(0,0,0,0,1,$i+1));?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="input-group-append input-sm">
                                                    <button type="submit" class="btn btn-small btn-primary">
                                                        <i class="fas fa-search fa-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><br><br><br>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="80">Tanggal</th>
                                                    <th class="text-center" width="150">Nama</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center">Cuaca</th>
                                                    <th class="text-center">Kavling</th>
                                                    <th class="text-center">Waktu Absensi</th>
                                                    <th class="text-center" width="10"><i class="fas fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(count($absensi) > 0){
                                                        $no = 1;
                                                        foreach($absensi as $data){
                                                            ?>
                                                            <tr>
                                                                <td align="center">
                                                                    <?php 
                                                                    $dates = strtotime($data['date']);
                                                                    print date('d/m/y',$dates);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php print $data['name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php print $data['pekerjaan']; ?>
                                                                </td>
                                                                <td align="center"><?php print ucwords($data['cuaca']);?></td>
                                                                <td>
                                                                    <?php print $data['kavling']; ?>
                                                                </td>
                                                                <td align="center">
                                                                    <?php print date("d M Y H:i:s", $data['waktu']); ?>
                                                                </td>
                                                                <td class="text-center"> 
                                                                    <?php
                                                                        if($data['image']){
                                                                            ?>
                                                                            <a href="#" onclick="return false" data-toggle="modal" data-target="#modaldetailkeg" data-backdrop="static" data-href="<?php print base_url();?>mandor/modal/<?php print $data['id']; ?>" class="detailabsensi"><i class="fa fas-form fa-image"></i></a>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
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
            <div class="modal fade" id="modaldetailkeg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Gambar Absensi</h5>
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
        $(document).ready(function(){
            $('.detailabsensi').on('click',function(){
                var dataURL = $(this).attr('data-href');
                $('.body-history').load(dataURL,function(){
                    $('#modaldetailkeg').modal({show:true});
                });
            }); 
        });
    </script>
</body>
</html>
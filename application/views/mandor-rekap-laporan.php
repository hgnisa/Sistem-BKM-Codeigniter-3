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
                        <h1 class="h3 mb-0 text-gray-800">Rekap Laporan</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <form action="<?php print base_url();?>mandor/rekap" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
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
                                                    <th class="text-center" width="110">Tanggal</th>
                                                    <th class="text-center">Jenis Pekerjaan</th>
                                                    <th class="text-center" width="150">Jumlah Volume Satuan</th>
                                                    <th class="text-center" width="180">Kavling</th>
                                                    <th class="text-center" width="130">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(count($rekap) > 0){
                                                        $no = 1;
                                                        foreach($rekap as $data){
                                                            if($data['keg_status'] == 'p'){
                                                                $totalstatus = 'p';
                                                            }elseif($data['keg_status'] == 'n'){
                                                                $totalstatus = 'n';
                                                            }else{
                                                                $totalstatus = 'y';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td align="center">
                                                                    <?php 
                                                                        $dates = strtotime($data['keg_date']);
                                                                        print date('d/m/y',$dates);
                                                                    ?>
                                                                </td>
                                                                <td><?php print $data['pekerjaan_name'];?></td>
                                                                <td align="center"><?php print $data['keg_volume'];?></td>
                                                                <td><?php print $data['kav_name'];?></td>
                                                                <td align="center">
                                                                    <?php 
                                                                    if($totalstatus == "p"){
                                                                        ?>                                                                        
                                                                        <a href="<?php print base_url();?>mandor/verifikasi/<?php print $data['keg_date'];?>" class="btn btn-sm btn-warning" style="padding: 1px 3px 1px 3px">
                                                                            <span class="icon text-white-50" style="font-size: 12px">
                                                                                <i class="fas fa-user-edit fa-sm"></i>
                                                                            </span>
                                                                            <span class="text" style="font-size: 12px">Verifikasi</span>
                                                                        </a>
                                                                        <?php
                                                                    } elseif($totalstatus == "n"){
                                                                        ?>
                                                                            <a class="btn btn-sm btn-danger" style="padding: 1px 3px 1px 3px">
                                                                                <span class="icon text-white-50" style="font-size: 12px">
                                                                                    <i class="fas fa-times"></i>
                                                                                </span>
                                                                                <span class="text" style="font-size: 12px">Ditolak</span>
                                                                            </a>
                                                                        <?php
                                                                    } elseif($totalstatus == "y"){
                                                                        ?>
                                                                            <a class="btn btn-sm btn-success" style="padding: 1px 5px 1px 5px">
                                                                                <span class="icon text-white-50" style="font-size: 12px">
                                                                                    <i class="fas fa-check"></i>
                                                                                </span>
                                                                                <span class="text" style="font-size: 12px">Diterima</span>
                                                                            </a>
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
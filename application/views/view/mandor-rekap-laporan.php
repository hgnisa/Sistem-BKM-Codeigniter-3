<?php
    include '../model/connection.php';
    include '../model/class-user.php';
    include '../model/class-kegiatan.php';
    include '../model/class-pekerjaan.php';
    include '../model/class-kavling.php';
    $user = new user();
    $kegiatan = new kegiatan();
    $pekerjaan = new pekerjaan();
    $kavling = new kavling();
    
    session_start();
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
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2-new.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
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
                    <?php
                        ## if there is date filter or not
                        $month = $_GET['month'];
                        $year = $_GET['year'];
                                
                        ## always show by today or filtered kegiatan
                        if(!empty($month) and !empty($year)){
                            $month = $month;
                            $year = $year;
                        }else{
                            $month = date("m");
                            $year = date("Y");
                        }

                        $url = "?month=$month&year=$year&";
                    ?>
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekap Laporan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <form action="mandor-rekap-laporan.php?" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
                                            <div class="input-group">
                                                <label>Filter berdasarkan bulan: &nbsp;</label>
                                                <select name="month" size='1' class="form-control bg-light border-1 small" placeholder="Cari"
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
                                                <select name="year" class="form-control bg-light border-1 small" style="width: 90px">
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
                                                    $filtered = $year."-".$month;
                                                    $range1 = $filtered."-01";
                                                    $range2 = $filtered."-".date("t", strtotime($filtered."-01"));
                                                    
                                                    $whereclause = "WHERE keg_date BETWEEN '$range1' AND '$range2'";

                                                    $sql = "SELECT  * FROM kegiatan $whereclause GROUP BY keg_date ORDER BY keg_date DESC";
                                                    $query = $kegiatan->show_sql($sql);
                                                    if(count($query) > 0){
                                                        $no = 1;
                                                        foreach($query as $data){
                                                            unset($namapkj);
                                                            unset($totalkav);
                                                            $totalvolume = 0;
                                                            $sql = "SELECT * FROM kegiatan WHERE keg_date = '{$data['keg_date']}'";
                                                            $datakegiatan = $kegiatan->show_sql($sql);
                                                            foreach($datakegiatan as $datakeg){
                                                                $namapkj[] = $pekerjaan->show_pekerjaan_detail($datakeg['pekerjaan_id'])['pekerjaan_name'];

                                                                $totalvolume += $datakeg['keg_volume'];

                                                                $totalkav[] = $datakeg['kav_id'];

                                                                if($datakeg['keg_status'] == 'p'){
                                                                    $totalstatus = 'p';
                                                                }elseif($datakeg['keg_status'] == 'n'){
                                                                    $totalstatus = 'n';
                                                                }else{
                                                                    $totalstatus = 'y';
                                                                }
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td align="center">
                                                                    <?php 
                                                                    $dates = strtotime($data['keg_date']);
                                                                    print date('d/m/y',$dates);
                                                                    ?>
                                                                </td>
                                                                <td><?php print implode(', ', array_unique($namapkj)); ?></td>
                                                                <td align="center"><?php print $totalvolume;?></td>
                                                                <td>
                                                                    <?php 
                                                                        unset($datakavling);
                                                                        foreach($totalkav as $kav){     
                                                                            $getkav = $kavling->show_kavling_detail($kav['kav_id']);   
                                                                            $datakavling[] = $getkav['kav_name'];
                                                                        }
                                                                        print implode(', ', array_unique($datakavling))
                                                                    ?>
                                                                </td>
                                                                
                                                                <td align="center">
                                                                    <?php 
                                                                    if($totalstatus == "p"){
                                                                        ?>
                                                                        <!-- <a href="mandor-kegiatan.php?date=<?php print $data['keg_date'];?>" title="Lihat Detail Laporan: <?php print date('d/m/y', $dates);?>"  target="_blank" class="btn btn-sm btn-secondary" style="padding: 1px 3px 1px 3px; margin-right: 5px">
                                                                            <span class="icon text-white-50" style="font-size: 12px">
                                                                                <i class="fas fa-list fa-sm"></i>
                                                                            </span>
                                                                            <span class="text" style="font-size: 12px">Detail</span>
                                                                        </a> -->
                                                                        
                                                                        <a href="mandor-verifikasi.php?date=<?php print $data['keg_date'];?>" class="btn btn-sm btn-warning" style="padding: 1px 3px 1px 3px">
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
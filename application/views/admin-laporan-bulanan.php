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
    <title>Sistem BKM  - Laporan Bulanan</title>
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
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
        .monthly{
            color: #3a3b45;
            background-color: #f8f9fc;
            border-color: #858796;
        }
        .monthly:hover{
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .monthly-active{
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php require("admin-web-menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require("admin-web-header-menu.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Bulanan</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="<?php print base_url();?>admin/report/monthly/<?php print $periode;?>" name="search" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="float: right;" method="get">
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
                                            <div style="float: right">
                                                <label>Pilih Periode:&nbsp;</label>
                                                <a href="<?php print base_url();?>admin/report/monthly/start<?php print $param ? "?".$param : "";?>" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm monthly<?php print $periode == 'start' ? '-active' : '';?>"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Awal Bulan </a> 

                                                <a href="<?php print base_url();?>admin/report/monthly/end<?php print $param ? "?".$param : "";?>" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm monthly<?php print $periode == 'end' ? '-active' : '';?>"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Akhir Bulan </a> 
                                            </div>
                                        </div>
                                    </div>

                                    <a href="<?php print base_url();?>admin/report/exportMonthly?periode=<?php print $periode;?>&month=<?php print $month;?>&year=<?php print $year;?>" id="ex-trig" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mt-3"><i class="fas fa-file-pdf fa-sm text-white-70"></i> &nbsp;Export Laporan </a><br><br>

                                    <div class="table-responsive">
                                        <table class="table tables-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5">No</th>
                                                    <th class="text-center" width="140">Jenis Pekerjaan</th>
                                                    <th class="text-center">Tanggal</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(count($bulanan) > 0){
                                                        $no = 1;
                                                        foreach($bulanan as $data){
                                                            ?>
                                                                <tr>
                                                                    <td><?php print $no;?></td>
                                                                    <td>
                                                                        <?php print $data['pekerjaan_name']; ?>
                                                                    </td>
                                                                    <td align="center">
                                                                        <?php 
			                                                                print implode(", ", $data['keg_date']);
                                                                        ?>
                                                                    </td>
                                                                    <td align="center"><?php print $data['keg_volume']; ?></td>
                                                                    <td align="center">
                                                                        <?php
                                                                            print $data['keg_satuan'];
                                                                        ?>
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
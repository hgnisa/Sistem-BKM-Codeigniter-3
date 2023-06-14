<?php
    session_start();
    include '../model/class-kegiatan.php';
    $kegiatan = new kegiatan(); 

    $datakeg = $kegiatan->show_kegiatan_detail($_GET['id']);
?>
<div class="col-lg-12 text-center">
    <img class="img-fluid"style="width: 16rem; height: 23rem; padding: 10px 10px 10px 10px;" src="../../img/kegiatan/<?php print $datakeg['keg_image'];?>">
</div>



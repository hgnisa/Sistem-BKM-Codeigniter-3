<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <?php 
                    $rekap = $kegiatan->show_sql("SELECT * FROM kegiatan WHERE keg_status = 'p' GROUP BY keg_date");

                    foreach($rekap as $c){  
                        $count[] = $c['keg_date'];
                    }  
                    
                    $notify = count($count); 

                    if($notify > 0){
                        ?>
                            <span class="badge badge-danger badge-counter"><?php print $notify."+";?></span>
                        <?php
                    }
                    ?>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <?php 
                if(!empty($rekap)){
                    foreach($rekap as $data){  
                        ?>
                            <a class="dropdown-item d-flex align-items-center" href="mandor-verifikasi.php?date=<?php print $data['keg_date'];?>" target="_blank">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-black-500">Laporan <?php print date("d", $data['keg_timestamp'])." ".$kegiatan->month(date("m", $data['keg_timestamp']))." ".date("Y", $data['keg_timestamp']);?></div>
                                    <span class="font-weight-bold">Rekap Laporan belum diverifikasi.</span>
                                </div>
                            </a>
                        <?php
                    }
                }else{
                    ?>
                    <a class="dropdown-item text-center small text-gray-500" style="pointer-events: none; cursor: default;">Tidak ada notifikasi</a>
                    <?php
                }
                ?>
                <a class="text-center small text-gray-500" style="pointer-events: none; cursor: default; font-size: 10px">&nbsp;</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php
                        $userdata = $user->show_user_detail($_COOKIE['cook_id']);
                        print ucwords($userdata['user_name']);
                    ?>
                </span>
                <?php
                    if($userdata['user_profile']){
                        $imgurl = "../../img/profile/".$userdata['user_profile'];
                    }else{
                        
                        $imgurl = "../../img/profile-empty.jpg";
                    }
                ?>
                <img class="img-profile rounded-circle" src="<?php print $imgurl;?>">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="mandor-edit-profile.php?id=<?php print $_COOKIE['cook_id'];?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda yakin?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih tombol "Keluar" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="../../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
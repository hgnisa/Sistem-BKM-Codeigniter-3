<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <?php 
                    $count = count($notify); 

                    if($count > 0){
                        ?>
                            <span class="badge badge-danger badge-counter"><?php print $count."+";?></span>
                        <?php
                    }
                ?>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifikasi
                </h6>
                <?php 
                if(count($notify) > 0){
                    foreach($notify as $key => $value){
                        $dates = strtotime($value->keg_date);
                        $dates = date('d/m/y',$dates);
                        ?>
                            <a class="dropdown-item d-flex align-items-center" href="<?php print base_url();?>mandor/verify/<?php print $value->keg_date;?>/<?php print $value->user_id;?>" target="_blank">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-black-500">Laporan <?php print $dates?></div>
                                    <span class="font-weight-bold">Rekap Laporan belum diverifikasi.</span>
                                </div>
                            </a>
                        <?php
                    }
                }else{
                    ?>
                    <div class="text-center pt-3">
                        <span>Tidak ada laporan baru.</span>
                    </div>
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
                        print $users->user_name;
                    ?>
                </span>
                <?php
                    if($users->user_profile){
                        $imgurl = base_url()."img/profile/".$users->user_profile;
                    }else{
                        
                        $imgurl = base_url()."img/profile-empty.jpg";
                    }
                ?>
                <img class="img-profile rounded-circle" src="<?php print $imgurl;?>">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php print base_url();?>mandor/profile/<?php print $users->user_id;?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php print base_url();?>auth/logout" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Keluar
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
                <a class="btn btn-primary" href="<?php print base_url();?>auth/logout">Keluar</a>
            </div>
        </div>
    </div>
</div>
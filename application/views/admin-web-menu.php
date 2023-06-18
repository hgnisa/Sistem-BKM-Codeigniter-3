<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin-index.php">
        <div class="sidebar-brand-text mx-3">Sistem BKM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if($_SESSION['menu'] == 1){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li> 

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item <?php if($_SESSION['menu'] == 2){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin/pekerjaan">
            <i class="fas fa-fw fa-list-ul"></i>
            <span>Data Pekerjaan</span></a>
    </li>

    <!-- Nav Item - Kavling -->
    <li>
        <li class="nav-item <?php if($_SESSION['menu'] == 6){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin/kavling">
            <i class="fas fa-fw fa-list-ul"></i>
            <span>Data Kavling</span></a>
    </li>

    <!-- Nav Item - Laporan Harian -->
    <li class="nav-item <?php if($_SESSION['menu'] == 3){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin/harian">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Laporan Harian</span></a>
    </li>

    <!-- Nav Item - Rekap Laporan -->
    <li class="nav-item <?php if($_SESSION['menu'] == 4){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin/bulanan">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Laporan Bulanan</span></a>
    </li>

     <li class="nav-item <?php if($_SESSION['menu'] == 5){ print "active"; } ?>">
        <a class="nav-link" href="<?php print base_url();?>admin/pengguna">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Kelola Pengguna</span></a>
    </li>
    <br>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="mandor-index.php">
        <div class="sidebar-brand-text mx-3">Sistem BKM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if($_SESSION['menu'] == 1){ print "active"; } ?>">
        <a class="nav-link" href="mandor-index.php">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item <?php if($_SESSION['menu'] == 2){ print "active"; } ?>">
        <a class="nav-link" href="mandor-absensi.php">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Absensi</span></a>
    </li>

    <!-- Nav Item - Kegiatan -->
    <li class="nav-item <?php if($_SESSION['menu'] == 3){ print "active"; } ?>">
        <a class="nav-link" href="mandor-kegiatan.php">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Kegiatan</span></a>
    </li>

    <!-- Nav Item - Rekap Laporan -->
    <li class="nav-item <?php if($_SESSION['menu'] == 4){ print "active"; } ?>">
        <a class="nav-link" href="mandor-rekap-laporan.php">
            <i class="far fa-fw fa-folder-open"></i>
            <span>Rekap Laporan</span></a>
    </li>

    <br>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
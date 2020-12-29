<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url("assets") ?>/img/logo/logo_white_soraya.png">
        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 13px;">E-Employee</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item <?= $nav_title == "dashboard" ? "active" : "" ?>">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Features
    </div>
    <li class="nav-item <?= $nav_title == "pegawai" ? "active" : "" ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap" aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-user-friends"></i>
            <span>Pegawai</span>
        </a>
        <div id="collapseBootstrap" class="collapse <?= $nav_title == "pegawai" ? "show" : "" ?>"" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pegawai</h6>
                <a class="collapse-item <?= $detail_title == "pegawai" ? "active" : "" ?>" href="<?= base_url("pegawai"); ?>">Data Pegawai</a>
                <a class="collapse-item <?= $detail_title == "tambah_pegawai" ? "active" : "" ?>" href="<?= base_url("pegawai/add"); ?>">Tambah Pegawai</a>

            </div>
        </div>
    </li>
    <li class="nav-item <?= $nav_title == "divisi" ? "active" : "" ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true" aria-controls="collapseForm">
            <i class="fas fa-briefcase"></i>
            <span>Divisi</span>
        </a>
        <div id="collapseForm" class="collapse <?= $nav_title == "divisi" ? "show" : "" ?>" aria-labelledby="headingForm" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Divisi</h6>
                <a class="collapse-item <?= $detail_title == "divisi" ? "active" : "" ?>" href="<?= base_url("divisi"); ?>">Daftar Divisi</a>
                <a class="collapse-item" id="btnTambahDivisi" href="#">Tambah Divisi</a>
            </div>
        </div>
    </li>
    <li class="nav-item <?= $nav_title == "jabatan" ? "active" : "" ?>">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true" aria-controls="collapseTable">
            <i class="fas fa-user-tie"></i>
            <span>Jabatan</span>
        </a>
        <div id="collapseTable" class="collapse <?= $nav_title == "jabatan" ? "show" : "" ?>" aria-labelledby="headingTable" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jabatan</h6>
                <a class="collapse-item <?= $detail_title == "jabatan" ? "active" : "" ?>" href="<?= base_url("jabatan"); ?>">Data Jabatan</a>
                <a class="collapse-item" id="btnTambahJabatan" href="#">Tambah Jabatan</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
            <i class="fas fa-fw fa-palette"></i>
            <span>UI Colors</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Examples
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true" aria-controls="collapsePage">
            <i class="fas fa-fw fa-columns"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Example Pages</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="version" id="version-ruangadmin"></div>
</ul>
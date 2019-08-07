<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url();?>dashboard" class="brand-link">
      <!--
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      -->
      <span class="brand-text font-weight-light">Sistem Cuti UISI</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url();?>assets/upload/karyawan/<?php echo (isset($karyawan->krw_foto) ? $karyawan->krw_foto : 'karya1.png');?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $karyawan->krw_nama;?> - Karyawan</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="<?php echo base_url();?>dashboard" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-header">MAIN MENU</li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profil
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Data diri</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ubah Password</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="<?php echo base_url();?>pengajuancuti" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
                <p>Pengajuan Cuti</p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="<?php echo base_url();?>historicuti" class="nav-link">
              <i class="nav-icon fas fa-clock"></i>
                <p>Histori Cuti</p>
            </a>
          </li>
          <li class="nav-header">SISTEM</li>
          <?php if(intval($karyawan->krw_level) !== KARYAWAN):?>
          <li class="nav-item ">
            <a href="<?php echo base_url();?>dashboard/change_login" class="nav-link">
              <i class="nav-icon fas fa-arrow-circle-right"></i>
                <p>Login sebagai <?php echo $level_karyawan[$karyawan->krw_level]['text'];?></p>
            </a>
          </li>
          <?php endif;?>
          <li class="nav-item ">
            <a href="<?php echo base_url();?>login/logout" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

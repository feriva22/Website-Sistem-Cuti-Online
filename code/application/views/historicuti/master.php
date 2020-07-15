<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Histori Cuti</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Histori Cuti</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Histori Cuti</h3>
              <!--
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
              -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="historicuti-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th rowspan="2" class="align-middle text-center"></th>
                  <th rowspan="2" class="align-middle text-center">Jenis</th>
                  <th rowspan="2" class="align-middle text-center">Berkas</th>
                  <th rowspan="2" class="align-middle text-center">Tanggal Pengajuan</th>
                  <th rowspan="2" class="align-middle text-center">Total Hari</th>
                  <th rowspan="2" class="align-middle text-center">Mulai</th>
                  <th rowspan="2" class="align-middle text-center">Selesai</th>
                  <th rowspan="2" class="align-middle text-center">Alasan</th>
                  <th colspan="3" class="text-center">Status</th>
                </tr>
                <tr>
                  <th>Atasan Langsung</th>
                  <th>Atasan Tidak Langsung</th>
                  <th>Kabag/Kadir SDMO & TIK</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
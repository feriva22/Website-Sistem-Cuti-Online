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
                  <th></th> 
                  <th>Tanggal Pengajuan</th>
                  <th>Total Hari</th>
                  <th>Mulai Cuti</th>
                  <th>Selesai Cuti</th>
                  <th>Alasan</th>
                  <th>Status Atasan Langsung</th>
                  <th>Status SDM</th>
                  <th>Status Atasan Tidak Langsung</th>
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
  
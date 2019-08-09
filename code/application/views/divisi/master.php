<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Unit Kerja</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Unit Kerja</li>
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
              <h3 class="card-title">Data Unit Kerja</h3>
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="divisi-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> <!-- id of karyawan -->
                  <th>Nama</th>
                  <th>Aksi</th>
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
  <div class="modal fade" id="divisi-modal" style="display: none;" aria-hidden="true" data-onEdit="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data Unit Kerja</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="divisi-form">
            <div class="form-group">
              <input type="hidden" class="form-control" id="dvs_id" name="dvs_id">
            </div>
            <div class="form-group">
              <label for="dvs_nama">Nama Unit Kerja</label>
              <input type="text" class="form-control" id="dvs_nama" name="dvs_nama" placeholder="Masukkan Nama Unit Kerja">
            </div>
            <div class="form-group">
              <label for="dvs_attljbt_pk">Atasan Tidak Langsung</label>
              <select class="form-control" name="dvs_attljbt_pk" id="dvs_attljbt_pk">
                  <option value="">Silahkan pilih</option>
                  <?php foreach($atasan_tidak_langsung as $row){ ?>
                    <option value="<?php echo $row->jbt_id; ?>"><?php echo $row->jbt_nama; ?></option>
                  <?php } ?>
              </select>
            </div>
        </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" id="submit-btn">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
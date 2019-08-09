<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Jabatan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Jabatan</li>
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
              <h3 class="card-title">Data Jabatan</h3>
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="jabatan-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> 
                  <th>Nama</th>
                  <th>Level</th>
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
  <div class="modal fade" id="jabatan-modal" style="display: none;" aria-hidden="true" data-onEdit="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data jabatan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="jabatan-form">
            <div class="form-group">
              <input type="hidden" class="form-control" id="jbt_id" name="jbt_id">
            </div>
            <div class="form-group">
              <label for="jbt_nama">Nama jabatan</label>
              <input type="text" class="form-control" id="jbt_nama" name="jbt_nama" placeholder="Masukkan Nama Jabatan">
            </div>
            <div class="form-group">
              <label for="jbt_level">Level</label>
              <select class="form-control" name="jbt_level" id="jbt_level">
                  <option value="">Silahkan pilih</option>
                  <?php foreach($status_level as $c_statuslvlK => $c_statuslvlV){ ?>
                    <option value="<?php echo $c_statuslvlK; ?>"><?php echo $c_statuslvlV['text']; ?></option>
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
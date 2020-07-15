<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Jatah Cuti</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Jatah Cuti</li>
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
              <h3 class="card-title">Data Jatah Cuti</h3>
              <?php if(check_login_as() != KARYAWAN):?>
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
              <?php endif;?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="jatahcuti-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> 
                  <th>Karyawan</th>
                  <th>Jenis</th>
                  <th>Jumlah</th>
                  <th>Sisa</th>
                  <th>Tanggal Berlaku</th>
                  <th>Tanggal Hangus</th>
                  <th>Status</th>
                  <?php if(check_login_as() != KARYAWAN):?>
                  <th>Aksi</th>
                  <?php endif;?>
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
  <div class="modal fade" id="jatahcuti-modal" style="display: none;" aria-hidden="true" data-onEdit="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data jatahcuti</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="jatahcuti-form">
            <div class="form-group">
              <input type="hidden" class="form-control" id="jtc_id" name="jtc_id">
            </div>
            <div class="form-group">
              <label for="jtc_karyawan">Karyawan</label>
              <select class="form-control" name="jtc_karyawan" id="jtc_karyawan">
                <option value="">Silahkan pilih</option>
                <?php foreach($allkaryawan as $row){ ?>
                  <option value="<?php echo $row->krw_id; ?>"><?php echo $row->krw_nama; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group date">
                <label for="jtc_jenis">Jenis Cuti</label>
                <div class="input-group date" id="jtc_jenis" data-target-input="nearest">
                  <select class="form-control" name="jtc_jenis" id="jtc_jenis">
                    <option value="">Silahkan pilih</option>
                    <option value="1">Cuti Tahunan</option>
                    <option value="2">Cuti Besar</option>
                  </select>
                </div>
            </div>
            <div class="form-group">
              <label for="jtc_jumlah">Jumlah</label>
              <input type="number" class="form-control" id="jtc_jumlah" name="jtc_jumlah" placeholder="Masukkan Jumlah Jatah Cuti">
            </div>
            <div class="form-group">
              <label for="jtc_sisa">Sisa</label>
              <input type="number" class="form-control" id="jtc_sisa" name="jtc_sisa" placeholder="Masukkan Sisa Jatah Cuti">
            </div>
            <div class="form-group date">
                <label for="jtc_validstart">Tanggal Berlaku</label>
                <div class="input-group date" id="jtc_validstart" data-target-input="nearest">
                    <input type="text" name="jtc_validstart" class="form-control datetimepicker-input" placeholder="Masukkan Mulai Tanggal Berlaku Cuti" data-target="#jtc_validstart"/>
                    <div class="input-group-append" data-target="#jtc_validstart" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group date">
                <label for="jtc_validend">Tanggal Hangus</label>
                <div class="input-group date" id="jtc_validend" data-target-input="nearest">
                    <input type="text" name="jtc_validend" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Hangus Jatah Cuti" data-target="#jtc_validend"/>
                    <div class="input-group-append" data-target="#jtc_validend" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label for="jtc_status">Status</label>
              <select class="form-control" name="jtc_status" id="jtc_status">
                <option value="">Silahkan pilih</option>
                <option value="1">Aktif</option>
                <option value="2">Blokir</option>
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
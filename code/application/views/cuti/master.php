<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cuti</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Cuti</li>
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
              <h3 class="card-title">Data Cuti</h3>
              <!--
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
              -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="cuti-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> 
                  <th>Karyawan</th>
                  <th>Tanggal Pengajuan</th>
                  <th>Total</th>
                  <th>Mulai Cuti</th>
                  <th>Selesai Cuti</th>
                  <th>Alasan</th>
                  <th>Status Atasan Langsung</th>
                  <th>Status SDM</th>
                  <th>Status Atasan Tidak langsung</th>
                  <?php if($login_as != ADMIN):?>
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
  <div class="modal fade" id="cuti-modal" style="display: none;" aria-hidden="true" data-onEdit="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data cuti</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="cuti-form">
            <div class="form-group">
              <input type="hidden" class="form-control" id="cti_id" name="cti_id">
            </div>
            <div class="form-group">
              <label for="cti_karyawan">Karyawan</label>
              <select class="form-control" name="cti_karyawan" id="cti_karyawan" disabled>
                <option value="">Silahkan pilih</option>
                <?php foreach($allkaryawan as $row){ ?>
                  <option value="<?php echo $row->krw_id; ?>"><?php echo $row->krw_nama; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="cti_tglpengajuan">Tanggal Pengajuan</label>
              <input type="text" class="form-control" id="cti_tglpengajuan" name="cti_tglpengajuan" placeholder="Masukkan Tanggal pengajuan Cuti" disabled>
            </div>
            <div class="form-group">
              <label for="cti_hari">Total Hari cuti</label>
              <input type="number" class="form-control" id="cti_hari" name="cti_hari" placeholder="Masukkan Total pengajuan Cuti">
            </div>
            <div class="form-group date">
                <label for="cti_mulai">Tanggal Mulai Cuti</label>
                <div class="input-group date" id="cti_mulai" data-target-input="nearest">
                    <input type="text" name="cti_mulai" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Mulai" data-target="#cti_mulai"/>
                    <div class="input-group-append" data-target="#cti_mulai" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group date">
                <label for="cti_selesai">Tanggal Selesai Cuti</label>
                <div class="input-group date" id="cti_selesai" data-target-input="nearest">
                    <input type="text" name="cti_selesai" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Selesai" data-target="#cti_selesai"/>
                    <div class="input-group-append" data-target="#cti_selesai" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label for="cti_alasan">Alasan</label>
              <input type="text" class="form-control" id="cti_alasan" name="cti_alasan" placeholder="Masukkan Alasan Cuti">
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
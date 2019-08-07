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
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="jatahcuti-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> 
                  <th>Karyawan</th>
                  <th>Tanggal Valid</th>
                  <th>Jumlah</th>
                  <th>Sisa</th>
                  <th>Mulai Delay</th>
                  <th>Selesai Delay</th>
                  <th>Status</th>
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
                <label for="jtc_validdate">Tanggal Mulai Valid</label>
                <div class="input-group date" id="jtc_validdate" data-target-input="nearest">
                    <input type="text" name="jtc_validdate" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Valid" data-target="#jtc_validdate"/>
                    <div class="input-group-append" data-target="#jtc_validdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
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
                <label for="jtc_delaystart">Mulai Delay</label>
                <div class="input-group date" id="jtc_delaystart" data-target-input="nearest">
                    <input type="text" name="jtc_delaystart" class="form-control datetimepicker-input" placeholder="Masukkan Mulai Delay Jatah Cuti" data-target="#jtc_delaystart"/>
                    <div class="input-group-append" data-target="#jtc_delaystart" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group date">
                <label for="jtc_delayend">Selesai Delay</label>
                <div class="input-group date" id="jtc_delayend" data-target-input="nearest">
                    <input type="text" name="jtc_delayend" class="form-control datetimepicker-input" placeholder="Masukkan Selesai Delay Jatah Cuti" data-target="#jtc_delayend"/>
                    <div class="input-group-append" data-target="#jtc_delayend" data-toggle="datetimepicker">
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pengajuan Cuti Baru</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Pengajuan Cuti</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- form start -->
              <form role="form" action="<?php echo base_url();?>pengajuancuti/add" method="POST">
                <div class="card-body">
                  <div class="form-group date">
                      <label for="cti_mulai">Mulai Tanggal</label>
                      <div class="input-group date" id="cti_mulai" data-target-input="nearest">
                          <input type="text" name="cti_mulai" class="form-control datetimepicker-input" placeholder="Masukkan Mulai Tanggal" data-target="#cti_mulai"/>
                          <div class="input-group-append" data-target="#cti_mulai" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group date">
                      <label for="cti_selesai">Selesai Tanggal</label>
                      <div class="input-group date" id="cti_selesai" data-target-input="nearest">
                          <input type="text" name="cti_selesai" class="form-control datetimepicker-input" placeholder="Masukkan Selesai Tanggal" data-target="#cti_selesai"/>
                          <div class="input-group-append" data-target="#cti_selesai" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="cti_alasan">Alasan Cuti</label>
                    <input type="text" class="form-control" id="cti_alasan" name="cti_alasan" placeholder="Masukkan Alasan Cuti">
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
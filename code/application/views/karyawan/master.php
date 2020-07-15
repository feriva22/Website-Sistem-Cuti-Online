<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Karyawan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Home</a></li>
              <li class="breadcrumb-item active">Karyawan</li>
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
              <h3 class="card-title">Data Karyawan</h3>
              <div class="float-right">
                <button class="btn btn-sm btn-success btn-add">Tambah</button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="karyawan-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th> <!-- id of karyawan -->
                  <th>Username</th>
                  <th>Email</th>
                  <th>Nama</th>
                  <th>NIK</th>
                  <th>Tanggal Lahir</th>
                  <th>Jenis Kelamin</th>
                  <th>Alamat</th>
                  <th>Agama</th>
                  <th>Foto</th>
                  <th>Tanggal Masuk</th>
                  <th>Unit Kerja</th>
                  <th>Jabatan</th>
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
  <div class="modal fade" id="karyawan-modal" style="display: none;" aria-hidden="true" data-onEdit="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form role="form" id="karyawan-form">
            <div class="form-group">
              <input type="hidden" class="form-control" id="krw_id" name="krw_id">
            </div>
            <div class="form-group">
              <label for="krw_username">Username</label>
              <input type="text" class="form-control" id="krw_username" name="krw_username" placeholder="Masukkan Username">
            </div>
            <div class="form-group">
              <label for="krw_password">Password</label>
              <input type="password" class="form-control" id="krw_password" name="krw_password" placeholder="Masukkan Password">
            </div>
            <div class="form-group">
              <label for="krw_email">Email </label>
              <input type="email" class="form-control" id="krw_email" name="krw_email" placeholder="Masukkan email">
            </div>
            <div class="form-group">
              <label for="krw_nama">Nama</label>
              <input type="text" class="form-control" id="krw_nama" name="krw_nama" placeholder="Masukkan Nama">
            </div>
            <div class="form-group">
              <label for="krw_nik">NIK</label>
              <input type="number" class="form-control" id="krw_nik" name="krw_nik" placeholder="Masukkan NIK">
            </div>
            <div class="form-group date">
                <label for="krw_tgllahir">Tanggal Lahir</label>
                <div class="input-group date" id="krw_tgllahir" data-target-input="nearest">
                    <input type="text" name="krw_tgllahir" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Lahir" data-target="#krw_tgllahir"/>
                    <div class="input-group-append" data-target="#krw_tgllahir" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label for="krw_jeniskelamin">Jenis Kelamin</label>
              <select class="form-control" name="krw_jeniskelamin" id="krw_jeniskelamin">
                <option value="">Silahkan pilih</option>
                <option value="1">Laki-laki</option>
                <option value="2">Perempuan</option>
              </select>
            </div>
            <div class="form-group">
              <label for="krw_alamat">Alamat</label>
              <input type="text" class="form-control" id="krw_alamat" name="krw_alamat" placeholder="Masukkan Alamat">
            </div>
            <div class="form-group">
              <label for="krw_agama">Agama</label>
              <select class="form-control" name="krw_agama" id="krw_agama">
                <option value="">Silahkan pilih</option>
                <?php foreach($data_agama as $c_agamaK => $c_agamaV){ ?>
                  <option value="<?php echo $c_agamaK; ?>"><?php echo $c_agamaV; ?></option>
                <?php } ?>
              </select>
            </div>
            <!--
            <div class="form-group">
              <label for="krw_foto">Foto</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="krw-foto" name="krw-foto">
                  <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                </div>
                <div class="input-group-append">
                  <span class="input-group-text" id="">Upload</span>
                </div>
              </div>
            </div>
            -->
            <div class="form-group date">
                <label for="krw_tglmasuk">Tanggal masuk</label>
                <div class="input-group date" id="krw_tglmasuk" data-target-input="nearest">
                    <input type="text" name="krw_tglmasuk" class="form-control datetimepicker-input" placeholder="Masukkan Tanggal Masuk" data-target="#krw_tglmasuk"/>
                    <div class="input-group-append" data-target="#krw_tglmasuk" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label for="krw_divisi">Unit Kerja</label>
              <select class="form-control" name="krw_divisi" id="krw_divisi">
                <option value="">Silahkan pilih</option>
                <?php foreach($divisi as $row){ ?>
                  <option value="<?php echo $row->dvs_id; ?>"><?php echo $row->dvs_nama; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="krw_jabatan">Jabatan</label>
              <select class="form-control" name="krw_jabatan" id="krw_jabatan">
                <option value="">Silahkan pilih</option>
                <?php foreach($jabatan as $row){ ?>
                  <option value="<?php echo $row->jbt_id; ?>"><?php echo $row->jbt_nama; ?></option>
                <?php } ?>
              </select>
            </div>
            <!--
            <div class="form-group">
              <label for="krw_level">Level</label>
              <select class="form-control" name="krw_level" id="krw_level">
                <option value="">Silahkan pilih</option>
                <?php //foreach($level_karyawan as $c_lvlkaryawanK => $c_lvlkaryawanV){ ?>
                  <option value="<?php //echo $c_lvlkaryawanK; ?>"><?php //echo $c_lvlkaryawanV['text']; ?></option>
                <?php //} ?>
              </select>
            </div>
            -->
            <!--
            <div class="form-group">
              <label for="krw_ovrd_atasanpk">Override Atasan Langsung(Kosongi bila staf/dosen)</label>
              <select class="form-control" name="krw_ovrd_atasanpk" id="krw_ovrd_atasanpk">
                <option value="">Silahkan pilih</option>
                <?php //foreach($atasan_karyawan as $row){ ?>
                  <option value="<?php //echo $row->krw_id; ?>"><?php //echo $row->krw_nama; ?></option>
                <?php //} ?>
              </select>
            </div>
            -->
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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="google-signin-scope" content="profile email">
  <meta name="google-signin-client_id" content=<?php echo GOOGLE_CLIENT_ID;?>>
  <script src="https://apis.google.com/js/platform.js" async defer></script>

  <title><?php echo $this->site_info->get_page_title(TRUE);?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/adminlte.min.css">
  <!-- Sweet2 style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistem cuti</b> UISI</a>
  </div>
  <!-- /.login-logo -->
  
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="<?php echo base_url();?>login/auth" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Ingat Saya
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-1">
        <a href="#">Lupa password</a>
      </p>
      <!--
      <?php if(isset($authUrl)) { ?>
      <div class="social-auth-links text-center mb-3">
        <p>- ATAU -</p>
        <a href="<?php echo $authUrl;?>" class="btn btn-block btn-danger">
          <i class="fab fa-google mr-2"></i> Login Menggunakan Google
        </a>
      </div>
      -->
      <!--
      <?php } else { ?>
        <a href="https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=<?php echo base_url(); ?>login/logout" class="btn btn-block btn-danger">
          <i class="fab fa-google mr-2"></i> Logout Dari Google
        </a>
      <?php } ?>
      -->
      <div class="social-auth-links mb-3">
          <br>
          <div class="float-left mr-2" style="padding-top: 10px">Login dengan Email UISI</div>          
          <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>        
      </div>
      <!-- /.social-auth-links -->
      
    </div>

    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script type="text/javascript">

  var base_url = '<?php echo base_url();?>';
</script>

<!-- jQuery -->
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Sweet2 -->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Master template -->
<script src="<?php echo base_url();?>assets/js/jquery.mastertemplate.js"></script>
<!-- login js -->
<script src="<?php echo base_url();?>assets/js/login.js"></script>


<!-- user defined js -->
<?php if(isset($add_php_js)){
    $this->view($add_php_js['src'],$add_php_js['data']); }
?>


<?php if(is_exist($this->session->flashdata('msg'))){
  $result = $this->session->flashdata('msg');
  echo '<script type="text/javascript">
        $(function() {
          showMessage("'.$result['type'].'","'.$result['message'].'");
        });
        </script>';
} 
?>

</body>
</html>

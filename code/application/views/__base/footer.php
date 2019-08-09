<footer class="main-footer">
    <strong>Copyright &copy; 2019 <a href="<?php echo base_url();?>">Sistem Cuti UISI</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->
<script type="text/javascript">
  var base_url = "<?php echo base_url();?>";
</script>
<!-- jQuery -->
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.js"></script>
<!-- Sweet2 -->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Master Template JS -->
<script src="<?php echo base_url();?>assets/js/jquery.mastertemplate.js"></script>
<!-- add js plugin -->
<?php if(isset($add_js)):?>
  <?php foreach($add_js as $js):?>
    <script type="text/javascript" src="<?php echo base_url();?>assets/<?php echo $js;?>"></script>
  <?php endforeach;?>
<?php endif;?>

<!-- user defined js -->
<?php if(isset($add_php_js)){
    $this->view($add_php_js['src'],$add_php_js['data']); }
?>

<?php if($this->session->flashdata('msg') != NULL){
  $result = $this->session->flashdata('msg');
  //echo json_encode($result['message']);
  echo '<script type="text/javascript">
        $(function() {
          showMessage("'.$result['type'].'","'.$result['message'].'");
        });
        </script>';
} 
?>
</body>
</html>

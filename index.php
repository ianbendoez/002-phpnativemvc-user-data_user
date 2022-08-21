<?php
require_once("../../../config/database.php");
require_once("model.php");
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
session_start(); 
if ( !isset($_SESSION['session_username']) or !isset($_SESSION['session_id']) or !isset($_SESSION['session_level']) or !isset($_SESSION['session_kode_akses']) or !isset($_SESSION['session_hak_akses']) )
{
  echo '<div class="callout callout-danger">
          <h4>Session Berakhir!!!</h4>
          <p>Silahkan logout dan login kembali. Terimakasih.</p>
        </div>';
} else {
$db = new db();
$username = $_SESSION['session_username'];
$id_menus = 1; 
$cekMenusUser = $db->cekMenusUser($username,$id_menus); 
    foreach($cekMenusUser[1] as $data){
      $create = $data['c'];
      $read = $data['r'];
      $update = $data['u'];
      $delete = $data['d'];
      $nama_menus = $data['nama_menus'];
      $keterangan = $data['keterangan'];
    }
if($cekMenusUser[2] == 1) {
?>

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $nama_menus;?></h3>
        <?php if($create == "y") { ?>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" id="btn-create">
            <i class="fa fa-plus"></i> Tambah</button>
        </div>
        <?php } ?>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control" id="kriteria" placeholder="Masukan kriterian pencarian...">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" id="btn-search"><i class="fa fa-fw fa-search"></i>Cari</button>
                    </span>
              </div>
              <small class="text-aqua"><?php echo $keterangan;?></small>
            </div>
            <!-- /.form-group -->
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div id="pages"></div>

  </section>
  <!-- /.content -->
</div>
<script>
  // Data tabel
  function loadTable() {
    let value = {
      view : 'table',
      kriteria : $('#kriteria').val(),
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        $('#pages').html(data);
        Swal.close()
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  // Form tambah
  function formCreate() {
    let value = {
      view : 'form_create',
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $('#pages').html(data);
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  $('#btn-search').click(function() {
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    loadTable();
  });

  $('#btn-create').click(function() {
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    formCreate();
  });
</script>

<?php 
}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>
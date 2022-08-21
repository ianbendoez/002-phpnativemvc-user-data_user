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
$view=$_POST['view'];
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

<?php 
if($view == 'table'){
    $kriteria = htmlspecialchars($_POST['kriteria']);
    $getTable = $db->getTable($kriteria); 
?>
<div class="modal fade modal-default" id="modal-menu" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myLargeModalLabel">Daftar Menu</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Data User</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped text-nowrap">
      <thead>
      <tr>
        <th style="text-align:center;">#</th>
        <th style="text-align:center;">Aksi</th>
        <th style="text-align:center;">Username</th>
        <th style="text-align:center;">Hak Akses</th>
        <th style="text-align:center;">Kode Akses</th>
        <th style="text-align:center;">Level</th>
        <th style="text-align:center;">Dashboard</th>
        <th style="text-align:center;">Status</th>
      </tr>
      </thead>
      <tbody>
      <?php
      $no = 1;
      foreach($getTable[1] as $row){
      ?>
      <tr>
        <td style="text-align:center;"><?php echo $no++; ?></td>
        <td style="text-align:center;">
          <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-fw fa-cogs"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
              <ul class="dropdown-menu" role="menu">
              <?php if($create == "y") {?>
              <li><a href="javascript:void(0)" class="read" id="<?php echo $row['username'];?>"><i class="fa fa-fw fa-eye"></i>Detail</a></li>
              <?php } ?>
              <?php if($update == "y") {?>
              <li><a href="javascript:void(0)" class="update" id="<?php echo $row['username'];?>"><i class="fa fa-fw fa-edit"></i>Edit</a></li>
              <?php } ?>
              <?php if($delete == "y") {?>
              <li><a href="javascript:void(0)" class="delete text-red" id="<?php echo $row['username'];?>"><i class="fa fa-fw fa-trash-o"></i>Hapus</a></li>
              <?php } ?>
              </ul>
          </div>
          <?php if($create == "y" && $read == "y" && $update == "y" && $delete == "y") {?>
          <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-fw fa-ban text-red"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
              <ul class="dropdown-menu" role="menu">
              <li><a href="javascript:void(0)" class="reset text-blue" id="<?php echo $row['username'];?>"><i class="fa fa-fw fa-refresh"></i>Reset Password</a></li>
              <li><a href="javascript:void(0)" class="menu text-green" id="<?php echo $row['username'];?>"><i class="fa fa-fw fa-navicon"></i>Daftar Menu</a></li>
              </ul>
          </div>
          <?php } ?>
        </td>
        <td><?php echo $row['username'];?></td>
        <td>
          <?php 
            $kode = "hak_akses";
            $item = $row['hak_akses'];
            $getReferensi = $db->getReferensi($kode,$item); 
            foreach($getReferensi[1] as $ref){
              echo $ref['keterangan'];
            }
          ?>
        </td>
        <td>
          <?php 
            $kode = "kode_akses";
            $item = $row['kode_akses'];
            $getReferensi = $db->getReferensi($kode,$item); 
            foreach($getReferensi[1] as $ref){
              echo $ref['keterangan'];
            }
          ?>
          </td>
        <td style="text-align:right;"><?php echo number_format($row['level'],0,',','.');?></td>
        <td style="text-align:left;"><?php echo $row['dashboard'];?></td>
        <td style="text-align:center;">
          <?php 
            $kode = "status_user";
            $item = $row['status'];
            $getReferensi = $db->getReferensi($kode,$item); 
            foreach($getReferensi[1] as $ref){
              echo $ref['html'];
            }
          ?>
        </td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  // Form edit
  function formUpdate(id) {
    let value = {
      view : 'form_update',
      id : id,
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

  $(document).off('click', '.update').on('click', '.update', function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    formUpdate($(this).attr('id'));
  });

  // Form edit
  function modalMenu(id) {
    let value = {
      view : 'modal_menu',
      id : id,
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $('.modal-body').html(data);
        $("#modal-menu").modal("show");
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

  $(document).off('click', '.menu').on('click', '.menu', function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    modalMenu($(this).attr('id'));
  });

  $(document).off('click', '.delete').on('click', '.delete', function(){
    let id = $(this).attr('id');
    Swal.fire({
      title: 'Hapus Data?',
      text: "Data akan dihapus selamanya!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        });
        let value = {
          controller : 'delete',
          id : id,
        }
        $.ajax({
          url:"menus/<?php echo $id_menus;?>/controller.php",
          type: "POST",
          data: value,
          success: function(data, textStatus, jqXHR)
          { 
            loadTable();
            $resp = JSON.parse(data);
            if($resp['status'] == true){
              toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
            } else {
              toastr.error($resp['message'], $resp['title'], {closeButton: true});
            }
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
    })
  });

  $(document).off('click', '.reset').on('click', '.reset', function(){
    let id = $(this).attr('id');
    Swal.fire({
      title: 'Reset Password?',
      text: "Password dirubah menjadi '"+id+"'",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Reset',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        });
        let value = {
          controller : 'reset_password',
          id : id,
        }
        $.ajax({
          url:"menus/<?php echo $id_menus;?>/controller.php",
          type: "POST",
          data: value,
          success: function(data, textStatus, jqXHR)
          { 
            loadTable();
            $resp = JSON.parse(data);
            if($resp['status'] == true){
              toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
            } else {
              toastr.error($resp['message'], $resp['title'], {closeButton: true});
            }
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
    })
  });

  // Form read
  function formRead(id) {
    let value = {
      view : 'form_read',
      id : id,
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

  $(document).off('click', '.read').on('click', '.read', function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    formRead($(this).attr('id'));
  });
</script>
<?php }?>


<?php 
if($view == 'form_create' && $create == "y"){
?>
<div class="box box-success box-solid">
  <div class="box-header">
    <h3 class="box-title">Tambah User</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal" id="input_form_create">
      <div class="box-body">
        <div class="form-group">
          <label for="username" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" name="controller" value="create">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username...">
          </div>
        </div>
        <div class="form-group">
          <label for="hak_akses" class="col-sm-2 control-label">Hak Akses</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="hak_akses" name="hak_akses" style="width: 100%;">
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "hak_akses";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_akses" class="col-sm-2 control-label">Kode Akses</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="kode_akses" name="kode_akses" style="width: 100%;">
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "kode_akses";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_user" class="col-sm-2 control-label">Kode User</label>
          <div class="col-sm-10">
            <select class="form-control select2" id="kode_user" name="kode_user" style="width: 100%;">
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="button" class="btn btn-success pull-right" id="btn-save">Simpan</button>
      </div>

    </form>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $('#kode_akses').change(function(){
    $('#kode_user').html('<option value="">-- Pilih --</option>');
  });

  $('#kode_user').select2({
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_kode_user',
          kode_akses: $('#kode_akses').val(),
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });
  $('#btn-save').click(function() {
    if($('#username').val() == ''){
      $('#username').focus();
      Swal.fire("Validasi!", "Username wajib diisi.");
      return;
    }
    if($('#hak_akses').val() == ''){
      $('#hak_akses').focus();
      Swal.fire("Validasi!", "Hak Akses wajib diisi.");
      return;
    }
    if($('#kode_akses').val() == ''){
      $('#kode_akses').focus();
      Swal.fire("Validasi!", "Kode Akses wajib diisi.");
      return;
    }
    Swal.fire({
      title: 'Tambah Data?',
      text: "Data akan ditambahkan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tambah',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
            var val = $('#input_form_create').serialize();
            $.ajax({
              url:"menus/<?php echo $id_menus;?>/controller.php",
              type: "POST",
              data: val,
              success: function(data, textStatus, jqXHR)
              { 
                loadTable();
                $resp = JSON.parse(data);
                if($resp['status'] == true){
                  toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
                } else {
                  toastr.error($resp['message'], $resp['title'], {closeButton: true});
                }
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
        });
      }
    })
  });
</script>
<?php }?>

<?php 
if($view == 'form_update' && $update == "y"){
  $id = $_POST['id'];
  $getDataById = $db->getDataById($id); 
  foreach($getDataById[1] as $data){
?>
<div class="box box-warning box-solid">
  <div class="box-header">
    <h3 class="box-title">Edit User</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal" id="input_form_update">
      <div class="box-body">
        <div class="form-group">
          <label for="hak_akses" class="col-sm-2 control-label">Hak Akses</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" name="controller" value="update">
            <input type="hidden" class="form-control" name="username" value="<?php echo $id;?>">
            <select class="form-control select22" id="hak_akses" name="hak_akses" style="width: 100%;">
              <?php 
                $kode = "hak_akses";
                $item = $data['hak_akses'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "hak_akses";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_akses" class="col-sm-2 control-label">Kode Akses</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="kode_akses" name="kode_akses" style="width: 100%;">
              <?php 
                $kode = "kode_akses";
                $item = $data['kode_akses'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "kode_akses";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_user" class="col-sm-2 control-label">Kode User</label>
          <div class="col-sm-10">
            <select class="form-control select2" id="kode_user" name="kode_user" style="width: 100%;">
              <?php 
                $kode = $data['kode_user'];
                $getKodeUserById = $db->getKodeUserById($kode); 
                foreach($getKodeUserById[1] as $row){
              ?>
              <option value="<?php echo $row['id'];?>"><?php echo $row['nama'];?></option>
              <?php } ?>
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="level" class="col-sm-2 control-label">Level</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="level" name="level" placeholder="Level..." value="<?php echo $data['level'];?>">
          </div>
        </div>
        <div class="form-group">
          <label for="dashboard" class="col-sm-2 control-label">Dashboard</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="dashboard" name="dashboard" placeholder="Dashboard..." value="<?php echo $data['dashboard'];?>">
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="status" name="status" style="width: 100%;">
              <?php 
                $kode = "status_user";
                $item = $data['status'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "status_user";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="button" class="btn btn-warning pull-right" id="btn-save">Simpan</button>
      </div>

    </form>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>

  $('#kode_akses').change(function(){
    $('#kode_user').html('<option value="">-- Pilih --</option>');
  });

  $('#kode_user').select2({
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_kode_user',
          kode_akses: $('#kode_akses').val(),
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });

  $('#btn-save').click(function() {
    if($('#hak_akses').val() == ''){
      $('#hak_akses').focus();
      Swal.fire("Validasi!", "Hak Akses wajib diisi.");
      return;
    }
    if($('#kode_akses').val() == ''){
      $('#kode_akses').focus();
      Swal.fire("Validasi!", "Kode Akses wajib diisi.");
      return;
    }
    if($('#level').val() == ''){
      $('#level').focus();
      Swal.fire("Validasi!", "Level wajib diisi.");
      return;
    }
    if($('#dashboard').val() == ''){
      $('#dashboard').focus();
      Swal.fire("Validasi!", "Dashboard wajib diisi.");
      return;
    }
    if($('#status').val() == ''){
      $('#status').focus();
      Swal.fire("Validasi!", "Status wajib diisi.");
      return;
    }
    Swal.fire({
      title: 'Edit Data?',
      text: "Data akan dirubah!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Edit',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
            var val = $('#input_form_update').serialize();
            $.ajax({
              url:"menus/<?php echo $id_menus;?>/controller.php",
              type: "POST",
              data: val,
              success: function(data, textStatus, jqXHR)
              { 
                loadTable();
                $resp = JSON.parse(data);
                if($resp['status'] == true){
                  toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
                } else {
                  toastr.error($resp['message'], $resp['title'], {closeButton: true});
                }
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
        });
      }
    })
  });
</script>
<?php }}?>


<?php 
if($view == 'modal_menu' && $create == "y" && $read == "y" && $update == "y" && $delete == "y"){
  $id = $_POST['id'];
  $getMenusAll = $db->getMenusAll();
?>
<!-- /.box-header -->
<code>Perubahan/Kostum menu diluar Hak Akses dapat menggunakan menu ini.</code>
<div class="box-body table-responsive">
  <table class="table table-bordered table-striped">
      <thead>
      <tr>
        <th style="text-align:center;">#</th>
        <th style="text-align:center;">Nama Menu</th>
        <th style="text-align:center;">Menu</th>
        <th style="text-align:center;">Sub Menu</th>
        <th style="text-align:center;">C</th>
        <th style="text-align:center;">R</th>
        <th style="text-align:center;">U</th>
        <th style="text-align:center;">D</th>
        <th style="text-align:center;">Status</th>
      </tr>
      </thead>
      <tbody>
        <?php
        foreach($getMenusAll[1] as $row){
        ?>
        <tr>
          <td style="text-align:center;">
            <?php 
              $idMenus = $row['id_menus'];
              $getMenuById = $db->getMenuById($id,$idMenus); 
              if($getMenuById[2] == 1){
                $checked = "checked"; 
              } else {
                $checked = "";
              }
            ?>
            <input type="checkbox" class="menus" id="<?php echo $row['id_menus'];?>" <?php echo $checked;?> >
          </td>
          <td><?php echo $row['nama_menus'];?></td>
          <td><?php echo $row['nama_menu'];?></td>
          <td><?php echo $row['nama_menu_sub'];?></td>
          <td style="text-align:center;">
            <?php 
              $idMenus = $row['id_menus'];
              $getMenuById = $db->getMenuById($id,$idMenus); 
              if($getMenuById[2] == 1){
                foreach($getMenuById[1] as $dt){
                  if($dt['c'] == "y") { 
                    $checked = "checked"; 
                  } else {
                    $checked = "";
                  }
                }
              } else {
                $checked = "";
              }
            ?>
            <input type="checkbox" class="c" id="<?php echo $row['id_menus'];?>" <?php echo $checked;?> >
          </td>
          <td style="text-align:center;">
            <?php 
              $idMenus = $row['id_menus'];
              $getMenuById = $db->getMenuById($id,$idMenus); 
              if($getMenuById[2] == 1){
                foreach($getMenuById[1] as $dt){
                  if($dt['r'] == "y") { 
                    $checked = "checked"; 
                  } else {
                    $checked = "";
                  }
                }
              } else {
                $checked = "";
              }
            ?>
            <input type="checkbox" class="r" id="<?php echo $row['id_menus'];?>" <?php echo $checked;?> >
          </td>
          <td style="text-align:center;">
            <?php 
              $idMenus = $row['id_menus'];
              $getMenuById = $db->getMenuById($id,$idMenus); 
              if($getMenuById[2] == 1){
                foreach($getMenuById[1] as $dt){
                  if($dt['u'] == "y") { 
                    $checked = "checked"; 
                  } else {
                    $checked = "";
                  }
                }
              } else {
                $checked = "";
              }
            ?>
            <input type="checkbox" class="u" id="<?php echo $row['id_menus'];?>" <?php echo $checked;?> >
          </td>
          <td style="text-align:center;">
            <?php 
              $idMenus = $row['id_menus'];
              $getMenuById = $db->getMenuById($id,$idMenus); 
              if($getMenuById[2] == 1){
                foreach($getMenuById[1] as $dt){
                  if($dt['d'] == "y") { 
                    $checked = "checked"; 
                  } else {
                    $checked = "";
                  }
                }
              } else {
                $checked = "";
              }
            ?>
            <input type="checkbox" class="d" id="<?php echo $row['id_menus'];?>" <?php echo $checked;?> >
          </td>
          <td style="text-align:center;">
          <?php 
            $kode = "status_referensi";
            $item = $row['status'];
            $getReferensi = $db->getReferensi($kode,$item); 
            foreach($getReferensi[1] as $ref){
              echo $ref['html'];
            }
          ?>
        </td>
        </tr>
        <?php }?>
      </tbody>
    </table>
</div>
<!-- /.box-body -->
<script>
  $(':checkbox').click(function() {
    if ($(this).is(':checked')) {
        var status = "y";
    } else {
        var status = "n";
    }
    var aksi = $(this).attr('class');
    var id_menus = $(this).attr('id');
    var username = '<?php echo $id; ?>';
    let value = {
      controller : 'menu_user',
      aksi: aksi,
      id_menus: id_menus,
      username : username,
      status : status
    }
    // AJAX request
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      {
        $resp = JSON.parse(data);
        if($resp['status'] == true){
          toastr.success($resp['message'], '', {timeOut: 500, progressBar: true})
        } else {
          toastr.error($resp['message'], '', {timeOut: 1000, progressBar: true})
        }
      },
      error: function (request, textStatus, errorThrown) {
        toastr.error($resp['message'], '', {timeOut: 1000, progressBar: true})
      }
    });
  });
</script>
<?php }?>

<?php 
if($view == 'form_read' && $read == "y"){
  $id = $_POST['id'];
  $getDataById = $db->getDataById($id); 
  $getMenusAllById = $db->getMenusAllById($id);
  $getKodeAksesById = $db->getKodeAksesById($id);
  foreach($getDataById[1] as $data){
?>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title">Detail User</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal">
      <div class="box-body">
        <div class="form-group">
          <label for="hak_akses" class="col-sm-2 control-label">Hak Akses</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="hak_akses" name="hak_akses" style="width: 100%;">
              <?php 
                $kode = "hak_akses";
                $item = $data['hak_akses'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_akses" class="col-sm-2 control-label">Kode Akses</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="kode_akses" name="kode_akses" style="width: 100%;">
              <?php 
                $kode = "kode_akses";
                $item = $data['kode_akses'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_user" class="col-sm-2 control-label">Kode User</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="kode_user" name="kode_user" style="width: 100%;">
              <?php 
                $kode = $data['kode_user'];
                $getKodeUserById = $db->getKodeUserById($kode); 
                foreach($getKodeUserById[1] as $row){
              ?>
              <option value="<?php echo $row['id'];?>"><?php echo $row['nama'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="level" class="col-sm-2 control-label">Level</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="level" name="level" placeholder="Level..." value="<?php echo $data['level'];?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="dashboard" class="col-sm-2 control-label">Dashboard</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="dashboard" name="dashboard" placeholder="Dashboard..." value="<?php echo $data['dashboard'];?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="status" name="status" style="width: 100%;">
              <?php 
                $kode = "status_user";
                $item = $data['status'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <label class="text-red">Daftar Menu</label>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th style="text-align:center;">#</th>
              <th style="text-align:center;">Nama Menu</th>
              <th style="text-align:center;">Menu</th>
              <th style="text-align:center;">Sub Menu</th>
              <th style="text-align:center;">C</th>
              <th style="text-align:center;">R</th>
              <th style="text-align:center;">U</th>
              <th style="text-align:center;">D</th>
              <th style="text-align:center;">Status</th>
            </tr>
            </thead>
            <tbody>
              <?php
              foreach($getMenusAllById[1] as $row){
              ?>
              <tr>
                <td style="text-align:center;">
                  <?php 
                    $idMenus = $row['id_menus'];
                    $getMenuById = $db->getMenuById($id,$idMenus); 
                    if($getMenuById[2] == 1){
                      $checked = "checked"; 
                    } else {
                      $checked = "";
                    }
                  ?>
                  <input type="checkbox" <?php echo $checked;?> >
                </td>
                <td><?php echo $row['nama_menus'];?></td>
                <td><?php echo $row['nama_menu'];?></td>
                <td><?php echo $row['nama_menu_sub'];?></td>
                <td style="text-align:center;">
                  <?php 
                    $idMenus = $row['id_menus'];
                    $getMenuById = $db->getMenuById($id,$idMenus); 
                    if($getMenuById[2] == 1){
                      foreach($getMenuById[1] as $dt){
                        if($dt['c'] == "y") { 
                          $checked = "checked"; 
                        } else {
                          $checked = "";
                        }
                      }
                    } else {
                      $checked = "";
                    }
                  ?>
                  <input type="checkbox" <?php echo $checked;?> >
                </td>
                <td style="text-align:center;">
                  <?php 
                    $idMenus = $row['id_menus'];
                    $getMenuById = $db->getMenuById($id,$idMenus); 
                    if($getMenuById[2] == 1){
                      foreach($getMenuById[1] as $dt){
                        if($dt['r'] == "y") { 
                          $checked = "checked"; 
                        } else {
                          $checked = "";
                        }
                      }
                    } else {
                      $checked = "";
                    }
                  ?>
                  <input type="checkbox" <?php echo $checked;?> >
                </td>
                <td style="text-align:center;">
                  <?php 
                    $idMenus = $row['id_menus'];
                    $getMenuById = $db->getMenuById($id,$idMenus); 
                    if($getMenuById[2] == 1){
                      foreach($getMenuById[1] as $dt){
                        if($dt['u'] == "y") { 
                          $checked = "checked"; 
                        } else {
                          $checked = "";
                        }
                      }
                    } else {
                      $checked = "";
                    }
                  ?>
                  <input type="checkbox" <?php echo $checked;?> >
                </td>
                <td style="text-align:center;">
                  <?php 
                    $idMenus = $row['id_menus'];
                    $getMenuById = $db->getMenuById($id,$idMenus); 
                    if($getMenuById[2] == 1){
                      foreach($getMenuById[1] as $dt){
                        if($dt['d'] == "y") { 
                          $checked = "checked"; 
                        } else {
                          $checked = "";
                        }
                      }
                    } else {
                      $checked = "";
                    }
                  ?>
                  <input type="checkbox" <?php echo $checked;?> >
                </td>
                <td style="text-align:center;">
                <?php 
                  $kode = "status_referensi";
                  $item = $row['status'];
                  $getReferensi = $db->getReferensi($kode,$item); 
                  foreach($getReferensi[1] as $ref){
                    echo $ref['html'];
                  }
                ?>
              </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
      </div>
      <label class="text-red">Kode Akses</label>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th style="text-align:center;">Kode</th>
              <th style="text-align:center;">Nama</th>
            </tr>
            </thead>
            <tbody>
              <?php
              foreach($getKodeAksesById[1] as $row){
              ?>
              <tr>
                <td style="text-align:center;"><?php echo $row['id'];?></td>
                <td><?php echo $row['nama'];?></td>
              </tr>
              <?php }?>
            </tbody>
          </table>
      </div>
    </form>
  </div>
  <!-- /.box-body -->

</div>
<!-- /.box -->
<?php }}?>

<script>
  $('.select22').select2()

  $(function () {
    $('.table').DataTable({
      'language': {
        "emptyTable": "Data tidak ditemukan.",
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_",
        "infoEmpty": "Menampilkan 0 - 0 dari 0",
        "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
        "lengthMenu": "Tampilkan _MENU_ baris",
        "search": "Cari:",
        "zeroRecords": "Tidak ditemukan data yang sesuai.",
        "thousands": "'",
        "paginate": {
          "first": "<<",
          "last": ">>",
          "next": ">",
          "previous": "<"
        }
      },  
      'destroy'     : true,
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
<?php 
}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>
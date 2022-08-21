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
if(isset($_POST['controller'])) {
  $controller = $_POST['controller'];
} else {
  $controller = "";
}
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

// start - controller
if($controller == 'get_kode_user'){
  if (isset($_POST['kode_akses']) && isset($_POST['kriteria'])) {
    $kode_akses = $_POST['kode_akses'];
    $kriteria = $_POST['kriteria'];
    $getKodeUser = $db->getKodeUser($kode_akses);
    $data = array();
    foreach($getKodeUser[1] as $option){
      $data[] = array("id"=>$option['id'], "text"=>$option['nama']);
    } 
    echo json_encode($data);
  }
} else if($controller == 'delete' && $delete == "y"){
  $id = $_POST['id'];
 
  $deleteUserMenus = $db->deleteUserMenus($id);
  $run = $db->delete($id);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'reset_password' && $create == "y" && $read == "y" && $update == "y" && $delete == "y"){
  $id = $_POST['id'];

  $run = $db->reset_password($id);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'create' && $create == "y"){
  $username = htmlspecialchars($_POST['username']);
  $hak_akses = htmlspecialchars($_POST['hak_akses']);
  $kode_akses = htmlspecialchars($_POST['kode_akses']);
  $kode_user = htmlspecialchars($_POST['kode_user']);

  $kode = "hak_akses";
  $item = $hak_akses;
  $getReferensi = $db->getReferensi($kode,$item); 
  foreach($getReferensi[1] as $ref){
    $level = htmlspecialchars($ref['ket1']);
    $dashboard = htmlspecialchars($ref['ket2']);
  } 

  $selectMenuHakAkses = $db->selectMenuHakAkses($hak_akses);
  foreach($selectMenuHakAkses[1] as $menu){
    $idMenus = $menu['id_menus'];
    $id = $username.".".$idMenus;
    $c = $menu['c'];
    $r = $menu['r'];
    $u = $menu['u'];
    $d = $menu['d'];
    $createMenus = $db->createMenus($id,$username,$idMenus,$c,$r,$u,$d);
  }

  $run = $db->create($username,$hak_akses,$kode_akses,$kode_user,$dashboard,$level);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'update' && $update == "y"){
  $username = htmlspecialchars($_POST['username']);
  $hak_akses_1 = $_POST['hak_akses'];
  $kode_akses = htmlspecialchars($_POST['kode_akses']);
  $kode_user = htmlspecialchars($_POST['kode_user']);
  $level_1 = $_POST['level'];
  $dashboard_1 = $_POST['dashboard'];

  $id = $username;
  $getDataById = $db->getDataById($id); 
  foreach($getDataById[1] as $data){
    $hak_akses_2 = $data['hak_akses'];
    $level_2 = $data['level'];
    $dashboard_2 = $data['dashboard'];
  }

  //jika hak akses tidak berubah
  if($hak_akses_1 == $hak_akses_2) {
    $hak_akses = htmlspecialchars($hak_akses_2);
    // jika level berubah
    if($level_1 == $level_2) {
      $level = htmlspecialchars($level_2);
    } else {
      $level = htmlspecialchars($level_1);
    }
    //jika dashboard berubah
    if($dashboard_1 == $dashboard_2) {
      $dashboard = htmlspecialchars($dashboard_2);
    } else {
      $dashboard = htmlspecialchars($dashboard_1);
    }
  } else {
    $hak_akses = htmlspecialchars($_POST['hak_akses']);
    $kode = "hak_akses";
    $item = $hak_akses;
    $getReferensi = $db->getReferensi($kode,$item); 
    foreach($getReferensi[1] as $ref){
      $level = htmlspecialchars($ref['ket1']);
      $dashboard = htmlspecialchars($ref['ket2']);
    }
  }

  $status = htmlspecialchars($_POST['status']);

  $deleteUserMenus = $db->deleteUserMenus($id);

  $selectMenuHakAkses = $db->selectMenuHakAkses($hak_akses);
  foreach($selectMenuHakAkses[1] as $menu){
    $idMenus = $menu['id_menus'];
    $id = $username.".".$idMenus;
    $c = $menu['c'];
    $r = $menu['r'];
    $u = $menu['u'];
    $d = $menu['d'];
    $createMenus = $db->createMenus($id,$username,$idMenus,$c,$r,$u,$d);
  }

  $run = $db->update($username,$hak_akses,$kode_akses,$kode_user,$dashboard,$level,$status);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'menu_user' && $create == "y" && $read == "y" && $update == "y" && $delete == "y"){
  $aksi = $_POST['aksi'];
  $id_menus = $_POST['id_menus'];
  $username = $_POST['username'];
  $status = $_POST['status'];
  $id = $username.".".$id_menus;

  if($aksi == "menus") {
    if($status == "y") {
      $run = $db->tambah_menu($id,$username, $id_menus);
    } else if($status == "n") {
      $run = $db->hapus_menu($id);
    }
  } else if($aksi == "c") {
    $run1 = $db->tambah_menu($id,$username, $id_menus);
    $run = $db->edit_c_menu($id, $status);
  } else if($aksi == "r") {
    $run1 = $db->tambah_menu($id,$username, $id_menus);
    $run = $db->edit_r_menu($id, $status);
  } else if($aksi == "u") {
    $run1 = $db->tambah_menu($id,$username, $id_menus);
    $run = $db->edit_u_menu($id, $status);
  } else if($aksi == "d") {
    $run1 = $db->tambah_menu($id,$username, $id_menus);
    $run = $db->edit_d_menu($id, $status);
  }

  $retval['status'] = $run[0];
  $retval['message'] = $run[1];
  echo json_encode($retval);
} else {
  $retval['status'] = false;
  $retval['message'] = "Tidak memiliki hak akses.";
  $retval['title'] = "Error!";
  echo json_encode($retval); 
}
// end - controller

}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>
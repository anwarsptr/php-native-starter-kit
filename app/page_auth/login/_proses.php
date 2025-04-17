<?php
require_once DIR_PATH . 'init.php';

$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

if ($getProses == 'login') {
  verifyCSRFToken();
  $username = post('username');
  $password = post('password');
  if (empty($username)) { ResponseFailed('<b>Username</b> is required'); }
  if (empty($password)) { ResponseFailed('<b>Password</b> is required'); }
  $sql = getData('users', "username='$username'");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) {
    ResponseFailed("Username '<b>$username</b>' not found");
  }
  if ($password != decrypt($get['password'])) {
    ResponseFailed("Incorrect <b>Username</b> or <b>Password</b>");
  }
  $id = $get['id'];
  $tgl_now = tgl_now();
  $up = updateData('users', ["last_login"=>$tgl_now], "id='$id'");
  if ($up) {
    set_session('id_user', $id);
    set_session('name', $get['name']);
    set_session('username', $get['username']);
    set_session('foto', (!empty($get['foto']) && file_exists($get['foto'])) ? $get['foto']:'' );
    set_session('role_id', $get['role_id']);
    setExpiredSession();
    deleteCSRFToken();
    ResponseSuccess('Have a good activity');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

ResponseFailed("Not found");
?>

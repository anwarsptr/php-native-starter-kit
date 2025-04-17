<?php
require_once DIR_PATH . 'init.php';

$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

if ($getProses == 'save-change-password') {
  checkPermission('account-change-password-edit');
  verifyCSRFToken();
  $password0 = post('password0');
  $password1 = post('password1');
  $password2 = post('password2');
  if (empty($password0)) { ResponseFailed('<b>Old password</b> is required'); }
  if (empty($password1)) { ResponseFailed('<b>New password</b> is required'); }
  if (empty($password2)) { ResponseFailed('<b>Confirm new password</b> is required'); }
  if ($password1 != $password2) { ResponseFailed('<b>Confirm new password</b> is incorrect'); }
  $id = get_session('id_user');
  if (empty($id)) { ResponseFailed('Session Expired!'); }
  $sql = getData('users', "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Session Expired!'); }

  if (decrypt($get['password']) != $password0) {
    ResponseFailed('The <b>Old password</b> is incorrect');
  }

  $up = updateData('users', ["password"=>encrypt($password1)], "id='$id'");
  if ($up) {
    deleteCSRFToken();
    ResponseSuccess('Saved successfully');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'save-profile') {
  checkPermission('account-profile-edit');
  verifyCSRFToken();
  $name = post('name');
  $username = strtolower(post('username'));
  $foto = "";
  if (empty($name)) { ResponseFailed('<b>Name</b> is required'); }
  if (empty($username)) { ResponseFailed('<b>Username</b> is required'); }

  $id = get_session('id_user');
  if (empty($id)) { ResponseFailed('Session Expired!'); }
  $sql = getData('users', "username='$username' AND id!=$id");
  $get = mysqli_fetch_assoc($sql);
  if (!empty($get)) {
    ResponseFailed("Username '<b>$username</b>' already exists");
  }

  $sql = getData('users', "id=$id");
  $get = mysqli_fetch_assoc($sql);
  $foto_old = $get['foto'];

  $path = 'uploads/users/'.date('Y/m');
  $config = [
    'name'       => encrypt("$id".time()),
    'filename'   => 'avatar',
    'path'		   => $path,
    'size'		   => maxUploadFile('profile'),
    'type'       => 'img',
    'file_old'   => $foto_old
  ];
  $foto = upload_file($config);

  $up = updateData('users', ["name"=>$name, "username"=>$username, "foto"=>$foto, "updated_at"=>tgl_now(), "updated_by"=>get_session('name'), "updated_by_id"=>$id], "id='$id'");
  if ($up) {
    set_session('name', $name);
    set_session('username', $username);
    set_session('foto', $foto);
    deleteCSRFToken();
    ResponseSuccess('Saved successfully');
  }else{
    delete_file($foto);
    ResponseFailed("Failed, try again in a few minutes");
  }
}

ResponseFailed("Not found");
?>

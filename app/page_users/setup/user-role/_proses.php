<?php
require_once DIR_PATH . 'init.php';

$getMethod = @$_SERVER['REQUEST_METHOD'];
$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

$andDeleted = "AND users.deleted_at is null";

if ($getProses == 'get') {
  checkPermission('setup-user-role-show');
  verifyCSRFToken();
  $id = get_session('id_user');
  $status = (!empty($_GET['status'])) ? $_GET['status']:0;
  $tbl = 'users';
  $tbl2 = 'roles';
  $join[] = ["$tbl2", "$tbl.role_id=$tbl2.id"];
  $select = "$tbl.id, $tbl.username, $tbl.name, $tbl.last_login, $tbl2.name as role_name";
  $where = "$tbl.is_active=$status AND $tbl.id!=$id $andDeleted";
  $data = [
    'tbl' => $tbl,
    'select' => $select,
    'join' => $join,
    'where' => $where,
    'encrypt_id' => true,
  ];
  json_datatables($data);
}

if ($getProses == 'edit' && $getID!='') {
  checkPermission('setup-user-role-edit');
  verifyCSRFToken();
  $id = decrypt($getID);
  $sql = getData('users', "id=$id $andDeleted");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Data not found'); }
  $get['id'] = $getID;
  ResponseSuccess($get);
}

if ($getProses == 'save') {
  $form_id = post('form_id');
  $ifEdit = (!empty($form_id)) ? true:false;
  checkPermission($ifEdit ? 'setup-user-role-edit':'setup-user-role-add');
  verifyCSRFToken();
  $id = ($ifEdit) ? decrypt($form_id):'';
  $name = post('name');
  $username = strtolower(post('username'));
  $password = post('password');
  $role_id = post('role_id');
  $is_active = post('is_active');
  $is_active = (empty($is_active)) ? 0:$is_active;
  if (empty($name)) { ResponseFailed('<b>Name</b> is required'); }
  if (empty($username)) { ResponseFailed('<b>Username</b> is required'); }
  if (!$ifEdit && empty($password)) { ResponseFailed('<b>Password</b> is required'); }
  if (empty($role_id)) { ResponseFailed('<b>Role</b> is required'); }

  $where_username_old='';
  if ($ifEdit) {
    if ($id==1) { ResponseFailed("Permission Denied!"); }
    $sql = getData('users', "id=$id");
    $get = mysqli_fetch_assoc($sql);
    if (empty($get)) {
      ResponseFailed("Users not found");
    }
    $where_username_old = " AND id!=$id ";
    if (empty($password)) {
      $password = decrypt($get['password']);
    }
  }

  $sql = getData('users', "username='$username' $where_username_old");
  $get = mysqli_fetch_assoc($sql);
  if (!empty($get)) {
    ResponseFailed("Username '<b>$username</b>' already exists");
  }

  $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
  $post = ["name"=>$name, "username"=>$username, "password"=>encrypt($password), "role_id"=>$role_id, "is_active"=>$is_active];
  if ($ifEdit) {
    $post = array_merge(["updated_at"=>$tgl_now, "updated_by"=>$input_by, "updated_by_id"=>$input_by_id], $post);
    $save = updateData('users', $post, "id='$id'");
  }else {
    $post = array_merge(["created_at"=>$tgl_now, "created_by"=>$input_by, "created_by_id"=>$input_by_id], $post);
    $save = insertData('users', $post);
  }
  // log_r($post);
  if ($save) {
    ResponseSuccess('Saved successfully');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'delete' && $getMethod === 'DELETE' && $getID!='') {
  checkPermission('setup-user-role-delete');
  verifyCSRFToken();
  $id = decrypt($getID);
  if ($id==1) { ResponseFailed("Permission Denied!"); }
  $sql = getData('users', "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed("User not found"); }
  $foto = $get['foto'];
  begin();
  // $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
  // $delete = updateData('users', ["deleted_at"=>$tgl_now, "deleted_by"=>$input_by, "deleted_by_id"=>$input_by_id], "id='$id'");
  $delete = deleteData('users', "id='$id'");
  if ($delete) {
    // $delete = deleteData('', "user_id='$id'");
  }
  if ($delete) {
    commit();
    delete_file($foto);
    ResponseSuccess('Deleted successfully');
  }else{
    rollback();
    ResponseFailed("Failed, try again in a few minutes");
  }
}

ResponseFailed("Not found");
?>

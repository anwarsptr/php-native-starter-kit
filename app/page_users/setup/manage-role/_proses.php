<?php
require_once DIR_PATH . 'init.php';

$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

if ($getProses == 'get') {
  checkPermission('setup-manage-role-show');
  verifyCSRFToken();
  $data = [
    'tbl' => "roles",
    'select' => "id, name",
    'search' => ["name"],
    'where' => "is_active=1",
    'orderBy' => "order_by ASC",
    'encrypt_id' => true,
  ];
  json_datatables($data);
}

if ($getProses == 'save') {
  checkPermission('setup-manage-role-edit');
  verifyCSRFToken();
  $form_id = post('form_id');
  $id = decrypt($form_id);
  $name = post('name');
  if (empty($name)) { ResponseFailed('<b>Name</b> is required'); }
  $sql = getData('roles', "id=id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed("Role not found"); }
  $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
  $post = ["name"=>$name, "updated_at"=>$tgl_now, "updated_by"=>$input_by, "updated_by_id"=>$input_by_id];
  $save = updateData('roles', $post, "id='$id'");
  if ($save) {
    ResponseSuccess('Saved successfully');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'save-role') {
  checkPermission('setup-manage-role-edit-access');
  verifyCSRFToken();
  $id = decrypt(post('id'));
  $sql = getData('roles', "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Role not found'); }
  $up=false;
  begin();
  $up = deleteData('role_has_permissions', "role_id=$id");
  if ($up) {
    $i=1;
    foreach ($_POST as $key => $value) {
      if ($key !== 'id') {
        $akses = explode('_', $key);
        if (isset($akses[1])) {
          // Menambahkan permission ke role
          $permission_id = $akses[1];
          $data = ['role_id'=>$id, 'permission_id'=>$permission_id, '`order_by`'=>$i];
          $up = insertData('role_has_permissions', $data);
          if (!$up) {
            rollback();
            ResponseFailed("Failed to insert role has permissions");
          }
          $i++;
        }
      }
    }
  }
  if ($up) {
    commit();
    deleteCSRFToken();
    ResponseSuccess('Saved successfully');
  }else {
    rollback();
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'save-sort') {
  checkPermission('setup-manage-role-edit-sort');
  verifyCSRFToken();
  $id = decrypt(post('id'));
  $sql = getData('roles', "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Role not found'); }
  $i=1; $up=false;
  foreach ($_POST['item'] as $val) {
    $sql = getData('menus', "id=$val AND is_active=1");
    $item = mysqli_fetch_assoc($sql);
    if (!empty($item)) {
      $sql = getData('permissions', "id_menu=$val");
      $permissions = mysqli_fetch_all($sql, MYSQLI_ASSOC);
      if (!empty($permissions)) {
        $showPermission = firstWhere($permissions, "short_name", "Show");
        if ($showPermission) {
          $permission_id = $showPermission['id'];
          $up = updateData('role_has_permissions', ['`order_by`'=>$i], "permission_id=$permission_id AND role_id=$id");
        }
      }
      $i++;
    }
  }
  if ($up) {
    deleteCSRFToken();
    ResponseSuccess('Saved successfully');
  }else {
    ResponseFailed("Failed, try again in a few minutes");
  }
}

ResponseFailed("Not found");
?>

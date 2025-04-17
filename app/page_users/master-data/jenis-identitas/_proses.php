<?php
require_once DIR_PATH . 'init.php';

$getMethod = @$_SERVER['REQUEST_METHOD'];
$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

$tbl = 'md_jenis_identitas';
$andDeleted = " AND deleted_at is null";
$permissionName = 'master-data-jenis-identitas';

if ($getProses == 'generate-number') {
  getGenerateNumber($permissionName.'-add', $tbl, 'kode');
}

if ($getProses == 'get') {
  checkPermission($permissionName.'-show');
  verifyCSRFToken();
  $status = (!empty($_GET['status'])) ? $_GET['status']:0;
  $select = "id, kode, keterangan, created_at";
  $where = "is_active=$status $andDeleted";
  $data = [
    'tbl' => $tbl,
    'select' => $select,
    'where' => $where,
    'encrypt_id' => true,
  ];
  json_datatables($data);
}

if ($getProses == 'edit' && $getID!='') {
  checkPermission($permissionName.'-edit');
  verifyCSRFToken();
  $id = decrypt($getID);
  $sql = getData($tbl, "id=$id $andDeleted");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Data not found'); }
  $get['id'] = $getID;
  ResponseSuccess($get);
}

if ($getProses == 'detail' && $getID!='') {
  checkPermission($permissionName.'-detail');
  verifyCSRFToken();
  $id = decrypt($getID);
  $sql = getData($tbl, "id=$id $andDeleted");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed('Data not found'); }
  $get['id'] = $getID;
  ResponseSuccess($get);
}

if ($getProses == 'save') {
  $form_id = post('form_id');
  $ifEdit = (!empty($form_id)) ? true:false;
  checkPermission($ifEdit ? $permissionName.'-edit':$permissionName.'-add');
  verifyCSRFToken();
  $id = ($ifEdit) ? decrypt($form_id):'';
  $isActive = checkPermission($permissionName.'-active', true);

  $kode = strtoupper(post('kode'));
  $keterangan = post('keterangan');
  if (empty($kode)) { ResponseFailed('<b>Kode</b> is required'); }
  if (empty($keterangan)) { ResponseFailed('<b>Keterangan</b> is required'); }

  if ($isActive) {
    if ($_POST['is_active'] === "") { ResponseFailed('<b>Active</b> is required'); }
    $is_active = post('is_active');
    $is_active = (empty($is_active)) ? 0:$is_active;
  }else {
    $is_active = 0;
  }

  $where_kode_old='';
  if ($ifEdit) {
    $sql = getData($tbl, "id=$id");
    $get = mysqli_fetch_assoc($sql);
    if (empty($get)) { ResponseFailed("Data not found"); }
    $where_kode_old = " AND id!=$id ";
  }

  $sql = getData($tbl, "kode='$kode' $where_kode_old $andDeleted");
  $get = mysqli_fetch_assoc($sql);
  if (!empty($get)) {
    ResponseFailed("Kode '<b>$kode</b>' already exists");
  }

  $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
  $post = ["kode"=>$kode, "keterangan"=>$keterangan, "is_active"=>$is_active];
  if ($ifEdit) {
    unset($post['kode']);
    $post = array_merge(["updated_at"=>$tgl_now, "updated_by"=>$input_by, "updated_by_id"=>$input_by_id], $post);
    $save = updateData($tbl, $post, "id='$id'");
  }else {
    $post = array_merge(["created_at"=>$tgl_now, "created_by"=>$input_by, "created_by_id"=>$input_by_id], $post);
    $save = insertData($tbl, $post);
  }
  // log_r($post);
  if ($save) {
    ResponseSuccess('Saved successfully');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'delete' && $getMethod === 'DELETE' && $getID!='') {
  checkPermission($permissionName.'-delete');
  verifyCSRFToken();
  $id = decrypt($getID);
  $sql = getData($tbl, "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed("Data not found"); }
  $isDeletePermanent=true;

  if ($isDeletePermanent) {
    $sqlBukuTamu = getData('buku_tamu', "jenis_identitas_id=$id");
    $getBukuTamu = mysqli_fetch_assoc($sqlBukuTamu);
    if (!empty($getBukuTamu)) {
      $isDeletePermanent=false;
    }
  }

  begin();
  if ($isDeletePermanent) {
    $delete = deleteData($tbl, "id='$id'");
  }else {
    $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
    $delete = updateData($tbl, ["deleted_at"=>$tgl_now, "deleted_by"=>$input_by, "deleted_by_id"=>$input_by_id], "id='$id'");
  }
  if ($delete) {
    commit();
    ResponseSuccess('Deleted successfully');
  }else{
    rollback();
    ResponseFailed("Failed, try again in a few minutes");
  }
}

?>

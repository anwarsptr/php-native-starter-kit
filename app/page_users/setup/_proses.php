<?php
require_once DIR_PATH . 'init.php';

$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

if ($getProses == 'save-setup-webs') {
  checkPermission('setup-website-edit');
  verifyCSRFToken();
  $app_name = post('app_name');
  $title = post('title');
  $description = post('descriptions');
  $website = post('website');

  if (empty($app_name)) { ResponseFailed('<b>App Name</b> is required'); }
  if (empty($title)) { ResponseFailed('<b>Title</b> is required'); }

  $id = get_session('id_user');
  if (empty($id)) { ResponseFailed('Session Expired!'); }

  $tbl = 'setup_webs';
  $sql = getData($tbl, "id=1");
  $get = mysqli_fetch_assoc($sql);
  $favicon_old = $get['favicon'];
  $logo_old = $get['logo'];

  $path = 'uploads/webs';
  $size = maxUploadFile('setup-webs');
  $config = [
    'name'       => encrypt(time()),
    'filename'   => 'favicon',
    'path'		   => $path,
    'size'		   => $size,
    'type'       => 'img'
  ];
  $favicon = upload_file($config);
  if (empty($favicon)) { $favicon = $favicon_old; }

  $config = [
    'name'       => encrypt(time()),
    'filename'   => 'logo',
    'path'		   => $path,
    'size'		   => $size,
    'type'       => 'img'
  ];
  $logo = upload_file($config);
  if (empty($logo)) { $logo = $logo_old; }

  $up = updateData($tbl, [
    "app_name"=>$app_name, "title"=>$title, "description"=>$description, "website"=>$website,
    "logo"=>$logo, "favicon"=>$favicon,
    "updated_at"=>tgl_now(), "updated_by"=>get_session('name'), "updated_by_id"=>$id
  ], "id=1");

  if ($up) {
    deleteCSRFToken();
    if (!empty($favicon) && $favicon_old!=$favicon) {
      delete_file($favicon_old);
    }
    if (!empty($logo) && $logo_old!=$logo) {
      delete_file($logo_old);
    }
    ResponseSuccess('Saved successfully');
  }else{
    delete_file($foto);
    ResponseFailed("Failed, try again in a few minutes");
  }
}


if ($getProses == 'save-setup-running-numbers' && $getID!='') {
  checkPermission('setup-running-numbers-edit');
  verifyCSRFToken();
  $tbl = 'setup_running_numbers';
  $idnya = decrypt($getID);
  $sql = getData($tbl, "id='$idnya'");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) {
    ResponseFailed("The <b>running number</b> to be saved was not found");
  }
  $inisial = strtoupper(post('inisial'));
  $length = khususAngka(post('length'));
  $type = khususAngka(post('type'));
  $type = (empty($type)) ? 0:$type;
  $random_allow = strtoupper(post('random_allow'));

  if (empty($inisial)) { ResponseFailed('<b>Inisial</b> is required'); }
  if (empty($length)) { ResponseFailed('<b>Length</b> is required'); }
  // if (empty($type)) { ResponseFailed('<b>Type</b> is required'); }
  if (empty($random_allow)) { ResponseFailed('<b>Random Allow</b> is required'); }

  $up = updateData($tbl, [
    "inisial"=>$inisial, "length"=>$length, "type"=>$type, "random_allow"=>$random_allow,
    "updated_at"=>tgl_now(), "updated_by"=>get_session('name'), "updated_by_id"=>get_session('id_user')
  ], "id=$idnya");

  if ($up) {
    ResponseSuccess('Saved successfully');
  }else{
    ResponseFailed("Failed, try again in a few minutes");
  }
}

ResponseFailed("Not found");
?>

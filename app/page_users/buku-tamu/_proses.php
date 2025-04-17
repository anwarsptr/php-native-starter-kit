<?php
require_once DIR_PATH . 'init.php';

$getMethod = @$_SERVER['REQUEST_METHOD'];
$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

$tbl = 'buku_tamu';
$andDeleted = " AND deleted_at is null";
$permissionName = 'buku-tamu';

if ($getProses == 'generate-number') {
  getGenerateNumber($permissionName.'-add', $tbl, 'nomor');
}

if ($getProses == 'get') {
  checkPermission($permissionName.'-show');
  verifyCSRFToken();
  $status = (!empty($_GET['status'])) ? $_GET['status']:0;
  $select = "id, foto_kendaraan, nomor, tgl_kunjungan, jam_kunjungan, nama_tamu, no_telp_tamu, nomor_kendaraan, jenis_identitas, nama_yang_dikunjungi";
  $where = "no_tanda_masuk_dikembalikan=$status $andDeleted";
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

  $no_tanda_masuk_dikembalikan = post('no_tanda_masuk_dikembalikan');

  if (!$ifEdit) {
    $nomor = post('nomor');
    $tgl_kunjungan = post('tgl_kunjungan');
    $jam_kunjungan = post('jam_kunjungan');
    $nama_tamu = post('nama_tamu');
    $no_telp_tamu = khususAngka(post('no_telp_tamu'));
    $membawa_kendaraan = post('membawa_kendaraan');

    $no1 = post('no1_kendaraan');
    $no2 = post('no2_kendaraan');
    $no3 = post('no3_kendaraan');
    if ($no1!="" && $no2!="" && $no3!="") {
      $nomor_kendaraan = strtoupper("$no1 $no2 $no3");
    }else {
      $nomor_kendaraan = "";
    }

    $jenis_identitas_id = post('jenis_identitas_id');
    $blok_perumahan_id = post('blok_perumahan_id');
    $nama_yang_dikunjungi = post('nama_yang_dikunjungi');
    $no_tanda_masuk = post('no_tanda_masuk');
    $keterangan = post('keterangan');

    $isKendaraan = in_array($membawa_kendaraan, ['YES','YA']) ? true:false;

    if (empty($nomor)) { ResponseFailed('<b>Nomor</b> is required'); }
    if (empty($tgl_kunjungan)) { ResponseFailed('<b>Tanggal Kunjungan</b> is required'); }
    if (empty($jam_kunjungan)) { ResponseFailed('<b>Jam Kunjungan</b> is required'); }
    if (empty($nama_tamu)) { ResponseFailed('<b>Nama Tamu</b> is required'); }
    if (empty($no_telp_tamu)) { ResponseFailed('<b>Nomor Telp Tamu</b> is required'); }
    if (empty($membawa_kendaraan)) { ResponseFailed('<b>Membawa Kendaraan</b> is required'); }
    if ($isKendaraan && empty($nomor_kendaraan)) { ResponseFailed('<b>Nomor Kendaraan</b> is required'); }
    if (empty($jenis_identitas_id)) { ResponseFailed('<b>Jenis Identitas</b> is required'); }
    if (empty($nama_yang_dikunjungi)) { ResponseFailed('<b>Nama yang dikunjungi</b> is required'); }
    if (empty($no_tanda_masuk)) { ResponseFailed('<b>No Tanda Masuk Perumahan</b> is required'); }

    $sqlJI = getData('md_jenis_identitas', "id='$jenis_identitas_id'");
    $getJI = mysqli_fetch_assoc($sqlJI);
    if (empty($getJI)) {
      ResponseFailed("Jenis Identitas not found!");
    }

    $where_id_old=''; $foto_tanda_pengenal_old=[]; $foto_kendaraan_old=[];
    if ($ifEdit) {
      $sql = getData($tbl, "id=$id");
      $get = mysqli_fetch_assoc($sql);
      if (empty($get)) { ResponseFailed("Data not found"); }
      $foto_tanda_pengenal_old = (!empty($get['foto_tanda_pengenal'])) ? json_decode($get['foto_tanda_pengenal']):[];
      if (!empty($get['foto_kendaraan'])) {
        $foto_kendaraan_old = json_decode($get['foto_kendaraan']);
      }
      $where_id_old = " AND id!=$id ";
    }

    $sql = getData($tbl, "nomor='$nomor' $where_id_old $andDeleted");
    $get = mysqli_fetch_assoc($sql);
    if (!empty($get)) {
      ResponseFailed("Nomor '<b>$nomor</b>' already exists");
    }

    $foto_arr=[];
    $foto_kendaraan="";
    if ($isKendaraan) {
      if (!empty($_FILES['foto_kendaraan']['name'][0])) {
        $jml_foto = count($foto_kendaraan_old) + count($_FILES['foto_kendaraan']['name']);
        if ($jml_foto > 10) {
          ResponseFailed("Total Foto yang diupload tidak boleh lebih dari <b>10</b>");
        }

        $path = 'uploads/buku-tamu/kendaraan/'.date('Y/m');
        $maxUpload = maxUploadFile('buku-tamu');
        $ekstensi = ['png','jpg','jpeg'];
        $foto_arr = upload_multi('foto_kendaraan', $path, $nomor, $maxUpload, $ekstensi, 'Foto Kendaraan');
        if (!empty($foto_arr) && !empty($foto_kendaraan_old)) {
          $foto_arr = array_merge($foto_kendaraan_old, $foto_arr);
        }
        $foto_kendaraan = (!empty($foto_arr)) ? json_encode($foto_arr):"";
      }else {
        if ($ifEdit) {
          $foto_kendaraan = (!empty($foto_kendaraan_old)) ? json_encode($foto_kendaraan_old):"";
        }else {
          ResponseFailed("Foto Kendaraan is required");
        }
      }
    }

    $foto_tanda_pengenal="";
    if (!empty($_FILES['foto_tanda_pengenal']['name'][0])) {
      $jml_foto = count($foto_tanda_pengenal_old) + count($_FILES['foto_tanda_pengenal']['name']);
      if ($jml_foto > 10) {
        ResponseFailed("Total Foto yang diupload tidak boleh lebih dari <b>10</b>");
      }

      $path = 'uploads/buku-tamu/tanda_pengenal/'.date('Y/m');
      $maxUpload = maxUploadFile('buku-tamu');
      $ekstensi = ['png','jpg','jpeg'];
      $foto_arr = upload_multi('foto_tanda_pengenal', $path, $nomor, $maxUpload, $ekstensi, 'Foto Tanda Pengenal');
      if (!empty($foto_arr) && !empty($foto_tanda_pengenal_old)) {
        $foto_arr = array_merge($foto_tanda_pengenal_old, $foto_arr);
      }
      $foto_tanda_pengenal = (!empty($foto_arr)) ? json_encode($foto_arr):"";
    }else {
      $foto_tanda_pengenal = (!empty($foto_tanda_pengenal_old)) ? json_encode($foto_tanda_pengenal_old):"";
    }
  }

  $tgl_now=tgl_now(); $input_by=get_session('name'); $input_by_id=get_session('id_user');
  if ($ifEdit) {
    $post = ["no_tanda_masuk_dikembalikan"=>$no_tanda_masuk_dikembalikan==1?1:0];
  }else {
    $post = [
      "nomor"=>$nomor, "tgl_kunjungan"=>tgl_format($tgl_kunjungan, 'Y-m-d'), "jam_kunjungan"=>tgl_format($jam_kunjungan, 'H:i:s'),
      "nama_tamu"=>$nama_tamu, "no_telp_tamu"=>$no_telp_tamu, "membawa_kendaraan"=>$membawa_kendaraan, "nomor_kendaraan"=>$nomor_kendaraan,
      "jenis_identitas_id"=>$jenis_identitas_id, "jenis_identitas"=>$getJI['kode'].' - '.$getJI['keterangan'],
      "nama_yang_dikunjungi"=>$nama_yang_dikunjungi, "no_tanda_masuk"=>$no_tanda_masuk, "no_tanda_masuk_dikembalikan"=>0,
      "keterangan"=>$keterangan, "foto_tanda_pengenal"=>!empty($foto_tanda_pengenal) ? $foto_tanda_pengenal:null, "foto_kendaraan"=>!empty($foto_kendaraan) ? $foto_kendaraan:null,
    ];
  }
  if ($ifEdit) {
    // unset($post['nomor']);
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
    if (!empty($foto_arr)) { hapus_multi_foto($foto_arr); }
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
  $foto = (!empty($get['foto_tanda_pengenal'])) ? json_decode($get['foto_tanda_pengenal']):[];
  if (!empty($get['foto_kendaraan'])) {
    $foto_kendaraan = json_decode($get['foto_kendaraan']);
    $foto = array_merge($foto_kendaraan, $foto);
  }
  begin();
  $delete = deleteData($tbl, "id='$id'");
  if ($delete) {
    commit();
    if (!empty($foto)) {
      hapus_multi_foto($foto);
    }
    ResponseSuccess('Deleted successfully');
  }else{
    rollback();
    ResponseFailed("Failed, try again in a few minutes");
  }
}

if ($getProses == 'delete-foto' && $getMethod === 'DELETE' && $getID!='') {
  checkPermission($permissionName.'-delete');
  verifyCSRFToken();
  $id = decrypt($getID);
  $sql = getData($tbl, "id=$id");
  $get = mysqli_fetch_assoc($sql);
  if (empty($get)) { ResponseFailed("Data not found"); }
  $foto_delete = @$_GET['img'];
  $tipe_kendaraan = (@$_GET['tipe']=='kendaraan') ? true:false;
  $foto = $tipe_kendaraan ? $get['foto_kendaraan']:$get['foto_tanda_pengenal'];
  if (empty($foto)) {
    ResponseSuccess('Deleted successfully');
  }
  $foto_new=[];
  $foto = json_decode($foto);
  foreach ($foto as $key => $value) {
    if ($value != $foto_delete) {
      $foto_new[] = $value;
    }
  }
  $foto = (!empty($foto_new)) ? json_encode($foto_new):Null;

  $field_foto = $tipe_kendaraan ? 'foto_kendaraan':'foto_tanda_pengenal';

  begin();
  $delete = updateData($tbl, ["$field_foto"=>$foto], "id='$id'");
  if ($delete) {
    commit();
    if (!empty($foto_delete)) {
      delete_file($foto_delete);
    }
    ResponseSuccess('Deleted successfully');
  }else{
    rollback();
    ResponseFailed("Failed, try again in a few minutes");
  }
}
?>

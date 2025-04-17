<?php
require_once DIR_PATH . 'init.php';

function base_url($url=''){
  $urlnya = BASEURL;
  return (empty($url)) ? $urlnya:"$urlnya$url";
}

function redirect($url='')
{
  echo "<script>window.location='".base_url($url)."';</script>"; exit;
}

function getUri($val='')
{
  $request_uri = $_SERVER['REQUEST_URI'];
  // Pisahkan URL dengan `parse_url` untuk menghapus query string
  $parsed_baseurl = parse_url(BASEURL)['path'];
  $parsed_url = parse_url($request_uri);
  // Ambil path saja, tanpa parameter query
  $cleaned_uri = $parsed_url['path'];
  if (strpos(BASEURL,"localhost:") || $parsed_baseurl=='/') {
    return $cleaned_uri;
  }else{
    $path_new = str_replace($parsed_baseurl, '', $cleaned_uri);
    return "/$path_new";
  }
}

function app(){
  $app = mysqli_fetch_assoc(getData('setup_webs'));
  return $app;
}

function log_r($string = null, $var_dump = false)
{
  if ($var_dump) { var_dump($string); } else { echo "<pre>"; print_r($string); }
  exit;
}

function setExpiredSession()
{
  set_session('expired_time', time());
}

checkExpiredSession($expired_time);
function checkExpiredSession($expired_time='')
{
  if (!empty(get_session('id_user')) && !empty($expired_time) && $expired_time > 0) {
    $sessionAge = time() - get_session('expired_time');
    if ($sessionAge > $expired_time) {  // 3600 detik = 1 jam
      clearSession();
      redirect('');
    }
  }
}

// Fungsi untuk menghasilkan token CSRF
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token yang kuat dan acak
        $_SESSION['csrf_token_time'] = time();  // Menyimpan waktu pembuatan token
    }
    $tokenAge = time() - $_SESSION['csrf_token_time'];
    if ($tokenAge > 3600) {  // 3600 detik = 1 jam
      deleteCSRFToken();
      return generateCSRFToken();
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk memverifikasi token CSRF
function verifyCSRFToken() {
    // Mengambil header CSRF Token dari header HTTP
    $csrfToken = '';
    if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'];
    } elseif (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (isset($headers['X-CSRF-TOKEN'])) {
            $csrfToken = $headers['X-CSRF-TOKEN'];
        }
    }

    $requestType = '';
    if (isset($_SERVER['HTTP_X_REQUEST_TYPE'])) {
        $requestType = $_SERVER['HTTP_X_REQUEST_TYPE'];
    } elseif (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (isset($headers['X-REQUEST-TYPE'])) {
            $requestType = $headers['X-REQUEST-TYPE'];
        }
    }

    // Pastikan token yang dikirim ada dan sesuai dengan yang ada di sesi
    if (!isset($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
      $msg = 'CSRF token validation failed!';
      if ($requestType=='dataTables') {
        echo "$msg"; exit;
      }
      ResponseFailed($msg); // Token tidak valid
    }
    // Cek apakah token sudah lebih dari 1 jam
    $tokenAge = time() - $_SESSION['csrf_token_time'];
    if ($tokenAge > 3600) {  // 3600 detik = 1 jam
        $msg = 'CSRF token expired! Please refresh the page';
        if ($requestType=='dataTables') {
          echo "$msg"; exit;
        }
        ResponseFailed($msg);  // Token sudah kadaluarsa
    }
    return true;
}

function deleteCSRFToken()
{
  unset($_SESSION['csrf_token']);
  unset($_SESSION['csrf_token_time']);
}


function set_session($name='', $val='')
{
  $_SESSION[$name] = $val;
}

function get_session($name='')
{
  return @$_SESSION[$name];
}

function tgl_now($aksi='')
{
  date_default_timezone_set('Asia/Jakarta');
  if ($aksi=='tgl') {
    $v = date('Y-m-d');
  }elseif ($aksi=='jam') {
    $v = date('H:i:s');
  }elseif ($aksi=='x') {
    $v = date('YmdHis');
  }else {
    $v = date('Y-m-d H:i:s');
  }
  return $v;
}

function tgl_format($date,$format,$custom='')
{
  if ($custom=='') {
    return date($format,strtotime($date));
  }else {
    return date($format,strtotime($custom, strtotime($date)));
  }
}

function hari_indo($tgl='')
{
  // Konversi tanggal ke Day
  $hari = date('D', strtotime($tgl));
  // Array nama hari
  $nama_hari = array(
    'Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu',
    'Thu'=>'Kamis', 'Fri'=>"Jum'at", 'Sat'=>'Sabtu'
  );
  // Jika $hari tidak sesuai dgn array
  if (empty($nama_hari[$hari])) { return 'Hari tidak valid!'; }
  return $nama_hari[$hari]; //tampilkan hasil
}

function namaBulan()
{
  return [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober',  'November', 'Desember'
  ];
}

function bln_indo($tgl='')
{
  // Konversi tanggal ke bulan
  $bln = (int)date('m', strtotime($tgl));
  $bln = $bln-1;
  // Array nama bulan, kita coba buat lebih simpel
  $nama_bln = namaBulan();
  // Jika $bln tidak sesuai dgn array bulan
  if (empty($nama_bln[$bln])) { return 'Bulan tidak valid!'; }
  return $nama_bln[$bln]; //tampilkan hasil
}

function waktu($tgl='', $aksi='')
{
  $tgl = ($tgl=='') ? tgl_now() : $tgl;
  // Konversinya :D
  $harinya = hari_indo($tgl);
  $tglnya  = date('d', strtotime($tgl));
  $blnnya  = bln_indo($tgl);
  $thnnya  = date('Y', strtotime($tgl));
  $jamnya   = date('H', strtotime($tgl));
  $menitnya = date('i', strtotime($tgl));
  $detiknya = date('s', strtotime($tgl));

  // kita buat lebih simpel menggunakan array
  $arr = array(
    'hari'      => $harinya,
    'tgl'       => "$tglnya $blnnya $thnnya",
    'hari_tgl'  => "$harinya, $tglnya $blnnya $thnnya",
    'jam'       => $jamnya,
    'menit'     => $menitnya,
    'detik'     => $detiknya,
    'waktu'     => "$jamnya:$menitnya:$detiknya",
    'jam_menit' => "$jamnya:$menitnya",
    'hari_tgl_jam_menit' => $harinya.", $tglnya $blnnya $thnnya $jamnya:$menitnya",
    'tgldMYHi'  => "$tglnya ".substr($blnnya, 0,3)." $thnnya, $jamnya:$menitnya",
    'tglDDYmd'  => "$harinya $thnnya/".date('m', strtotime($tgl))."/$tglnya",
  );
  // Jika nama $aksi tidak ada dalam array maka tampilkan lengkapnya
  if (empty($arr[$aksi])) {
    return $harinya.", $tglnya $blnnya $thnnya $jamnya:$menitnya:$detiknya";
  }
  return $arr[$aksi]; //tampilkan hasil sesuai dgn $arr & $aksi
}

function format_angka($data=0,$data2='')
{
  $data = khususAngka($data);
  $v = (empty($data)) ? 0:number_format("$data",0,",",".");
  if ($data2=='rp') {
    $v = "Rp. ".$v;
  }
  return $v;
}

function khususAngka($number=0, $aksi='')
{
  return preg_replace("/[^0-9$aksi]+/", '', $number);
}


function createPath($path, $mode=0775, $aksi='return')
{
  if (!is_dir("$path")) {
    return mkdir("$path", $mode, true);
  }else {
    return ($aksi=='return')? false:true;
  }
}

function formatSizeUnits($bytes=0, $aksi='B') {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    if ($aksi=='KB') { $bytes=$bytes*1024; }
    $i=0; while ($bytes >= 1024) { $bytes /= 1024; $i++; }
    $unit = (!empty($units[$i])) ? ' '.$units[$i]:'';
    // Menggunakan rtrim untuk menghapus digit nol di belakang koma jika tidak diperlukan
    $formattedSize = rtrim(number_format($bytes, 2, '.', ''), '0');
    // Jika setelah rtrim hanya menyisakan koma, hapus koma juga
    $formattedSize = rtrim($formattedSize, '.');
    return $formattedSize . $unit;
}

function upload_file($config=array(), $aksi='')
{
  if (empty($config)) {
    $pesan_alert = "You Haven't Set Upload Configuration!";
    ResponseFailed($pesan_alert);
  }
  // $url      = (!empty($config['url'])),     ? $config['url'] : '';
  $name     = (!empty($config['name'])) ? $config['name'] : time();
  $filename = (!empty($config['filename'])) ? $config['filename'] : 'file';
  $path     = (!empty($config['path']))     ? $config['path'] : 'uploads';
  $size     = (!empty($config['size']))     ? $config['size'] : '1';
  $type     = (!empty($config['type']))     ? $config['type'] : '*';
  $ext      = (!empty($config['ext']))      ? $config['ext']  : '*';
  $file_old = (!empty($config['file_old'])) ? $config['file_old'] : '';
  $file_old_remove = (!empty($config['file_old_remove'])) ? $config['file_old_remove'] : true;

  if (empty($_FILES[$filename])) { return $file_old; }
  $file = $_FILES[$filename]["name"];
  if (empty($file)) { return $file_old; }

  $file_basename = substr($file, 0, strripos($file, '.')); // get file extention
  $file_ext = substr($file, strripos($file, '.')); // get file name
  $filesize = $_FILES[$filename]["size"];
  $allowed_file_types = ($type=="img") ? "jpg,jpeg,png":$ext;

  if ($ext != '*') {
    $extnya = $file_ext;
    $ext_arr = explode(',', $allowed_file_types);
    $ext_x=[];
    foreach ($ext_arr as $key => $value) { $ext_x[] = ".".trim("$value"); }
    if (!in_array($extnya, $ext_x)) {
      $pesan_alert = 'File dengan Ekstensi <b>'.$extnya.'</b> tidak diizinkan,<br/>Ekstensi yang diizinkan :<br/><b>'.$ext.'</b>';
      ResponseFailed($pesan_alert);
    }
  }

  // if (empty($filesize)) {
  //   ResponseFailed("Pastikan pengaturan php.ini <b>upload_max_filesize</b> & <b>post_max_size</b>");
  // }

  $file_size = (($size*1024)*1024);
  if ($filesize > $file_size || empty($filesize))
  {
    $mb = ($size < 1) ? 'KB':'MB';
    $sizenya = ($mb=='KB') ? ($size * 1024):$size;
    ResponseFailed("Upload File Gagal!, Size File terlalu besar,<br />Maksimal <b>$sizenya $mb</b>");
  }

  $dir = APP_PATH;
  createPath("$dir$path", 0775, 'cek');
  $newfilename = "$path/$name$file_ext";
  $path_new = move_uploaded_file($_FILES[$filename]["tmp_name"], "$dir$newfilename");
  if ($path_new) {
    if ($file_old_remove && !empty($file_old)) { delete_file($file_old); }
    return $newfilename;
  }else {
    ResponseFailed("Upload failed, Error#".$_FILES[$filename]["error"]);
  }
}

function upload_multi($filename='', $path='', $no_order='', $limit='', $ekstensi='', $nama='Foto', $foto_arr=[], $compress=true, $watermark=true)
{
  $fotonya=[];
  if (is_array($_FILES[$filename]['name']) || is_object($_FILES[$filename]['name']))
  {
    $dir = APP_PATH;
    createPath("$dir$path", 0775, 'cek');
    foreach ($_FILES[$filename]['name'] as $key => $value) {
      if ($_FILES[$filename]['error'][$key] <> 4) {
        $namafile  = $_FILES[$filename]['name'][$key];
        $tmp       = $_FILES[$filename]['tmp_name'][$key];
        $tipe_file = pathinfo($namafile, PATHINFO_EXTENSION);
        $ukuran    = $_FILES[$filename]['size'][$key] / 1024;
        $limit     = $limit * 1024;
        if ($ukuran > $limit) {
          $ukuranText = formatSizeUnits($ukuran, 'KB');
          hapus_multi_foto($fotonya);
          hapus_multi_foto($foto_arr);
          ResponseFailed('Ukuran '.$nama.' terlalu besar (<b>'.$ukuranText.'</b>)! maksimal <b class="text-danger">'.formatSizeUnits($limit, 'KB').'</b> perfoto.');
        }
        if(!in_array(strtolower($tipe_file), $ekstensi)){
          hapus_multi_foto($fotonya);
          hapus_multi_foto($foto_arr);
          ResponseFailed($tipe_file.' Ekstensi '.$nama.' Tidak Diperbolehkan! Ekstensi yang dibolehkan : jpeg|jpg|png');
        }
        $foto = $path.'/'.md5("$filename $namafile $no_order ".time().$key).".$tipe_file";
        $upload = move_uploaded_file($tmp, $foto);
        if ($upload) {
          // $cek_resize = customImage($foto, $compress, $watermark);
          // if (!$cek_resize) {
          //   hapus_multi_foto($fotonya);
          //   hapus_multi_foto($foto_arr);
          //   ResponseFailed('Maaf, Upload '.$nama.' Gagal di kompres!');
          // }
          $fotonya[] = $foto;
        }else {
          hapus_multi_foto($fotonya);
          hapus_multi_foto($foto_arr);
          ResponseFailed('Maaf, Upload '.$nama.' Gagal!');
        }
      }
    }
  }
  return $fotonya;
}

function hapus_multi_foto($foto=[])
{
  if (!empty($foto)) {
    if (is_array($foto)) {
      foreach ($foto as $key => $value) {
        if (file_exists($value)) { unlink($value); }
      }
    }else {
      if (file_exists($foto)) { unlink($foto); }
    }
  }
}

function delete_file($foto='', $dir='')
{
  if (!empty($foto)) {
    $dir = ($dir=='') ? APP_PATH:$dir;
    if (file_exists("$dir$foto")) { unlink("$dir$foto"); }
  }
  return true;
}

function clear_tag($postnya='')
{
  if (empty($postnya)) { return $postnya; }
  // Mengonversi ampersand ke placeholder
  $postnya = str_replace('&', '[@AMPERSAND@]', $postnya);
  // Membersihkan input dari tag HTML dan mengonversi karakter khusus menjadi entitas HTML
  $postnya = htmlentities(strip_tags($postnya));
  // Mengembalikan placeholder ke ampersand
  $postnya = str_replace('[@AMPERSAND@]', '&', $postnya);
  return trim($postnya);
}

function post($ket,$stt='')
{
  if (empty($_POST[$ket])) { return ''; }
  $postnya = $_POST[$ket];
  if ($stt=='1') {
    return $postnya;
  }else {
    return clear_tag($postnya);
  }
}

function validMinLength($str='', $min='')
{
  return (strlen($str) < $min) ? false:true;
}

function validMaxLength($str='', $max='')
{
  return (strlen($str) > $max) ? false:true;
}

function validName($pattern='', $value='')
{
  return !preg_match("/[^$pattern]/i", $value);
}

function validUsername($str='')
{
  // jika kosong
  if ($str=='') { return false; }
  // jika karakter kurang dari minimum dan lebih dari maksimum
  if (validLength($str) < 5 || validLength($str) > 16) { return false; }
  // jika string a-z0-9_ tidak valid maka FALSE selain itu TRUE
  return (preg_match('/[^a-z0-9_]/', $str)) ? false : true;
}

function validEmail($str='')
{
  // jika kosong
  if ($str=='') { return false; }
  // jika email tidak valid maka FALSE selain itu TRUE
  return (!preg_match('/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i', $str)) ? false : true;
}

function ajax_response($msg='', $alert='', $data=array())
{
  header('Content-Type: application/json');
  $stt = ($alert=='success' || $alert==200 || $alert==1) ? true : false;
  $response = [ 'status'=>$stt, 'message'=>$msg, 'alert'=>$alert ];
  if (!empty($data) && is_array($data)) {
    $response = array_merge($response, $data);
  }
  echo json_encode($response); exit;
}

function ResponseSuccess($msg='', $data=[])
{
  return ajax_response($msg, 'success', $data);
}

function ResponseFailed($msg='', $data=[])
{
  return ajax_response($msg, '', $data);
}

function ifLogin($return='', $kondisi='')
{
  if (!isset($_SESSION)) { session_start(); }
  $sesiExpired = 'Session Expired!';
  if ($return=='ajax') {
    if (empty($_SESSION)) { ResponseFailed($sesiExpired); }
    if (!empty($_SESSION['id_user'])) {
      $pesan = ($kondisi=='') ? 'Permission Denied!':$kondisi;
      if ($pesan==$sesiExpired) { clearSession(); }
      ResponseFailed($pesan);
    }
  }elseif ($return=='ajax_table') {
    if (empty($_SESSION)) { echo $sesiExpired; exit; }
    if (!empty($_SESSION['id_user'])) {
      $pesan = ($kondisi=='') ? 'Permission Denied!':$kondisi;
      if ($pesan==$sesiExpired) { clearSession(); }
      echo "$pesan"; exit;
    }
  }else {
    redirect('404');
  }
}

function clearSession($aksi='')
{
  if (!isset($_SESSION)) { session_start(); }
  if (!empty($_SESSION)) { session_destroy(); }
}

function getLevel()
{
  if (!isset($_SESSION)) { session_start(); }
  return $_SESSION['level'];
}

function ifSuperAdmin()
{
  return (getLevel()==1) ? true:false;
}

function ifSubAdmin()
{
  return (getLevel()==2) ? true:false;
}

function getAvatarRandom($jk='')
{
  $folder = (strtolower($jk)=='laki-laki') ? 'laki-laki':'perempuan';
  $foto_default = 'img/user-null.png';
  $random = rand(1, 12);
  return "img/users/$folder/$random.jpg";
}

function get_foto_profile($aksi='')
{
  $if_admin = ($aksi=='admin') ? true:false;
  $foto_default = 'img/user-null.png';
  if (!$if_admin) {
    if (!empty($_SESSION['foto_default_user'])) {
      $foto_default = (file_exists($_SESSION['foto_default_user'])) ? $_SESSION['foto_default_user']:$foto_default;
    }
  }
  $foto = ($if_admin) ? $_SESSION['foto_admin']:$_SESSION['foto_user'];
  if (!file_exists($foto)) { $foto = $foto_default; }
  return base_url($foto);
}

function getNomor($max=8)
{
  $characters  = "0123456789";
  $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $max; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  $kode = $randomString;
  $getKode = queryNumRows("pengaduan", "no_pengaduan='$kode'");
  return ($getKode > 0) ? getNomor($max):$kode;
}

function maxUploadFile($aksi='')
{
  // satuan MB
  if (in_array($aksi, ['profile','setup-webs'])) {
    return 0.2;
  }elseif (in_array($aksi, ['buku-tamu'])) {
    return 0.2;
  }
  return 0.1;
}

function getAvatar()
{
  $img_default = 'img/user.jpg';
  $foto = @$_SESSION['foto'];
  return (!empty($foto) && file_exists($foto)) ? $foto:$img_default;
}

function get3Initials($text="") {
    if ($text=="") return "";
    // Pisahkan teks berdasarkan spasi
    $words = explode(' ', $text);
    // Jika jumlah kata lebih dari atau sama dengan 3
    if (count($words) >= 3) {
        // Ambil huruf pertama dari setiap kata, maksimal 3 huruf
        $initials = strtoupper(substr($words[0], 0, 1)) .
                    strtoupper(substr($words[1], 0, 1)) .
                    strtoupper(substr($words[2], 0, 1));
    } else {
        // Jika kurang dari 3 kata, ambil 3 karakter pertama dari string
        $initials = strtoupper(substr($text, 0, 3));
    }
    return $initials;
}

function getMenuWithPermissionsByRoleName($role_id, $position = 'left', $active = 1)
{
    global $con; // Gunakan koneksi global

    // Ambil menu utama
    $sql = getData('menus', "position='$position' AND parent_id is null AND is_active=$active ORDER BY `order_by` ASC");
    $menus = mysqli_fetch_all($sql, MYSQLI_ASSOC);

    $return = [];
    $order_default = 99999999999999999999;
    $order_id=0; $order_id2=0;
    $no = "00";

    foreach ($menus as $key => $menu) {
        $menu_id = $menu['id'];
        // Ambil permissions untuk menu
        $permissions_query = "SELECT * FROM permissions
                              WHERE permissions.id_menu = $menu_id GROUP BY short_name ORDER BY id ASC";
        $result_permissions = mysqli_query($con, $permissions_query);
        if (!$result_permissions) {
            die("Query failed : " . mysqli_error($con));
        }
        $permissions = mysqli_fetch_all($result_permissions, MYSQLI_ASSOC);

        foreach ($permissions as &$permission) {
            $permission_id = $permission['id'];
            // Cek apakah role memiliki permission
            $check_permission_query = "SELECT COUNT(*) as count FROM role_has_permissions
                                       WHERE role_id = $role_id AND permission_id = $permission_id";
            $result_role_permissions = mysqli_query($con, $check_permission_query);
            if (!$result_role_permissions) {
               die("Query failed : " . mysqli_error($con));
            }
            $has_permission = mysqli_fetch_assoc($result_role_permissions);
            $permission['hasPermission'] = $has_permission['count'] > 0;

            if ($permission['short_name'] === 'Show') {
                $order_query = "SELECT `order_by` FROM role_has_permissions
                                WHERE permission_id = $permission_id AND role_id = $role_id";
                $result_order_query = mysqli_query($con, $order_query);
                if (!$result_order_query) {
                    die("Query failed : " . mysqli_error($con));
                }
                $order = mysqli_fetch_assoc($result_order_query);
                $order_id = ($order) ? $order['order_by'] : $order_id;
                $no = "$order_id$key";
            }
        }

        $menu['permissions'] = $permissions;
        $returnSub = [];
        // Ambil submenu
        $submenus_query = "SELECT * FROM menus WHERE position = '$position' AND parent_id = $menu_id AND is_active = $active ORDER BY `order_by` ASC";
        $result_submenus_query = mysqli_query($con, $submenus_query);
        if (!$result_submenus_query) {
            die("Query failed : " . mysqli_error($con));
        }
        $subMenus = mysqli_fetch_all($result_submenus_query, MYSQLI_ASSOC);

        foreach ($subMenus as $key2 => $submenu) {
            $submenu_id = $submenu['id'];
            $no2 = '';
            // Ambil permissions untuk submenu
            $sub_permissions_query = "SELECT * FROM permissions
                                      WHERE permissions.id_menu = $submenu_id GROUP BY short_name ORDER BY id ASC";
            $result_sub_permissions_query = mysqli_query($con, $sub_permissions_query);
            if (!$result_sub_permissions_query) {
                die("Query failed : " . mysqli_error($con));
            }
            $subPermissions = mysqli_fetch_all($result_sub_permissions_query, MYSQLI_ASSOC);

            foreach ($subPermissions as &$subPermission) {
                $subPermission_id = $subPermission['id'];
                // Cek apakah role memiliki permission
                $check_sub_permission_query = "SELECT COUNT(*) as count FROM role_has_permissions
                                               WHERE role_id = $role_id AND permission_id = $subPermission_id";
                $result_check_sub_permission_query = mysqli_query($con, $check_sub_permission_query);
                if (!$result_check_sub_permission_query) {
                   die("Query failed : " . mysqli_error($con));
                }
                $has_subPermission = mysqli_fetch_assoc($result_check_sub_permission_query);
                $subPermission['hasPermission'] = $has_subPermission['count'] > 0;

                if ($subPermission['short_name'] === 'Show') {
                    $sub_order_query = "SELECT `order_by` FROM role_has_permissions
                                                      WHERE permission_id = $subPermission_id AND role_id = $role_id";
                    $result_sub_order_query = mysqli_query($con, $sub_order_query);
                    if (!$result_sub_order_query) {
                        die("Query failed : " . mysqli_error($con));
                    }
                    $sub_order = mysqli_fetch_assoc($result_sub_order_query);
                    $order_id2 = $sub_order ? $sub_order['order_by'] : $order_id2;
                    $no2 = "$order_id2$key2";
                }
            }
            $submenu['permissions'] = $subPermissions;
            $returnSub["$no2"] = $submenu;
        }

        $i = 0;
        $return["$no$i"] = $menu;

        if (!empty($returnSub)) {
            ksort($returnSub);
            foreach ($returnSub as $k => $v) {
                $i++;
                $return["$no$i"] = $v;
            }
        }
    }

    ksort($return);
    return $return;
}

function firstWhere($data=[], $name="", $val="")
{
  if (empty($data) || $name=="" || $val=="") { return []; }
  $get = array_filter($data, function($item) use ($name, $val) {
      return isset($item[$name]) && $item[$name] === $val;
  });
  return reset($get);
}

function hasPermissionTo($roleId, $permissionName) {
    global $con; // Gunakan koneksi global

    // Query untuk mendapatkan ID permission berdasarkan nama permission
    $queryPermission = "SELECT id FROM permissions WHERE name = '$permissionName'";
    $result_queryPermission = mysqli_query($con, $queryPermission);
    if (!$result_queryPermission) {
        die("Query failed : " . mysqli_error($con));
    }
    $getPermission = mysqli_fetch_assoc($result_queryPermission);
    if (empty($getPermission)) {
        return false; // Permission tidak ditemukan
    }
    $permissionId = $getPermission['id'];

    // Query untuk mengecek apakah role memiliki permission
    $queryRolePermission = "SELECT role_id FROM role_has_permissions WHERE role_id = $roleId AND permission_id = $permissionId";
    $result_queryRolePermission = mysqli_query($con, $queryRolePermission);
    if (!$result_queryRolePermission) {
        die("Query failed : " . mysqli_error($con));
    }
    $getRolePermission = mysqli_fetch_assoc($result_queryRolePermission);
    return (!empty($getRolePermission)) ? true : false;
}

function isAjax()
{
  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true:false;
}

function checkPermission($permissionName='', $return='')
{
  $isDataTables = (!empty($_GET['draw'])) ? true:false;
  $role_id = get_session('role_id');
  if (empty($role_id)) {
    if ($isDataTables) {
      echo "Session Expired!"; exit;
    }else if (isAjax()) {
      ResponseFailed('Session Expired!');
    }else {
      redirect('');
    }
  }
  $hasPermissionTo = hasPermissionTo($role_id, $permissionName);
  if ($hasPermissionTo) {
    return true;
  }else{
    if ($isDataTables) {
      echo "Permission Denied!"; exit;
    }else if (isAjax()) {
      ResponseFailed('Permission Denied!');
    }else {
      if ($return=='true') {
        return false;
      }else {
        redirect('404');
      }
    }
  }
}

function getMenuIndex($role_id='', $position='left', $parent_id=NULL, $active=1)
{
  global $con;
  $returnSub=[]; $i=0; $no=1;
  $parent_id_condition = is_null($parent_id) ? 'parent_id IS NULL' : "parent_id = $parent_id";
  // Ambil submenu
  $submenus_query = "SELECT * FROM menus WHERE position='$position' AND $parent_id_condition AND is_active=$active ORDER BY `order_by` ASC";
  $result_submenus_query = mysqli_query($con, $submenus_query);
  if (!$result_submenus_query) {
      die("Query failed : " . mysqli_error($con));
  }
  $subMenus = mysqli_fetch_all($result_submenus_query, MYSQLI_ASSOC);
  foreach ($subMenus as $key2 => $submenu) {
      $submenu_id = $submenu['id'];
      $no2 = '';
      // Ambil permissions untuk submenu
      $sub_permissions_query = "SELECT * FROM permissions
                                WHERE permissions.id_menu = $submenu_id AND short_name='Show' ORDER BY id ASC";
      $result_sub_permissions_query = mysqli_query($con, $sub_permissions_query);
      if (!$result_sub_permissions_query) {
          die("Query failed : " . mysqli_error($con));
      }
      $subPermissions = mysqli_fetch_all($result_sub_permissions_query, MYSQLI_ASSOC);

      foreach ($subPermissions as &$subPermission) {
          $subPermission_id = $subPermission['id'];
          // Cek apakah role memiliki permission
          $check_sub_permission_query = "SELECT COUNT(*) as count FROM role_has_permissions
                                         WHERE role_id = $role_id AND permission_id = $subPermission_id";
          $result_check_sub_permission_query = mysqli_query($con, $check_sub_permission_query);
          if (!$result_check_sub_permission_query) {
             die("Query failed : " . mysqli_error($con));
          }
          $has_subPermission = mysqli_fetch_assoc($result_check_sub_permission_query);
          $submenu['hasPermission'] = $has_subPermission['count'] > 0;
          // if ($submenu['hasPermission']) {
            $sub_order_query = "SELECT `order_by` FROM role_has_permissions
            WHERE permission_id = $subPermission_id AND role_id = $role_id";
            $result_sub_order_query = mysqli_query($con, $sub_order_query);
            if (!$result_sub_order_query) {
              die("Query failed : " . mysqli_error($con));
            }
            $sub_order = mysqli_fetch_assoc($result_sub_order_query);
            $order_id2 = $sub_order ? $sub_order['order_by'] : $order_id2;
            $no2 = "$order_id2$key2";
          // }
      }
      $returnSub["$no2"] = $submenu;
  }

  if (!empty($returnSub)) {
      ksort($returnSub);
  }
  return $returnSub;
}


function generateNumber($name='', $action='')
{
  if (empty($name)) { return ''; }
  $no_urut=1;
  if ($action=='x') {
    $value = $name;
  }else{
    $sql = getData('setup_running_numbers', "name='$name'");
    $value = mysqli_fetch_assoc($sql);
    if (empty($value)) { return ''; }
  }
  $random_allow = $value['random_allow'];
  if (empty($random_allow)) {
    $random_allow = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  }
  $type = $value['type'];
  $inisial = $value['inisial'];
  $length = $value['length'];
  $nomor = $inisial;
  if ($type==1) { // jika nomor urut
    if ($action!='x') {
      $tbl = str_replace('-', '_', $name);
      $sql = getData($tbl, "nomor LIKE '$nomor%' ORDER BY nomor DESC", "nomor");
      $getNomor = mysqli_fetch_assoc($sql);
      if (!empty($getNomor)) {
        $no_urut = (int) khususAngka($getNomor['nomor']) + 1;
      }
    }
    $nomor .= str_pad($no_urut, $length, '0', STR_PAD_LEFT);
  }else {
    $charactersLength = strlen($random_allow);
    for ($i = 0; $i < $length; $i++) {
        $nomor .= $random_allow[random_int(0, $charactersLength - 1)];
    }
  }
  return $nomor;
}

function getGenerateNumber($permissionName="", $tbl='', $field='') {
  if (empty($permissionName)) { ResponseFailed("Permission invalid!"); }
  if (empty($tbl)) { ResponseFailed("Table invalid!"); }
  if (empty($field)) { ResponseFailed("Field invalid!"); }
  checkPermission($permissionName);
  verifyCSRFToken();
  $name = @$_GET['name'];
  $nomor = generateNumber($name);
  $failed=false;
  for ($i=0; $i < 10; $i++) {
    $sql = getData($tbl, "$field='$nomor'", $field);
    $getNomor = mysqli_fetch_assoc($sql);
    if (!empty($getNomor)) {
      $nomor = generateNumber($name);
      $failed=true;
    }else{
      $failed=false;
      break;
    }
    sleep(1);
  }
  if ($failed) {
    ResponseFailed(ucwords($field)." Sudah ada, dan tidak bisa digunakan lagi!");
  }
  ResponseSuccess($nomor);
}

function formatFromMB($megabytes, $precision = 0) {
    $units = ['KB', 'MB', 'GB', 'TB'];

    if ($megabytes < 1) {
        // Convert ke KB dan bulatkan ke bawah
        $kb = floor($megabytes * 1024);
        return $kb . ' KB';
    }

    $bytes = $megabytes * 1024 * 1024;
    $pow = floor(log($bytes) / log(1024));

    // Mulai dari MB (index 1)
    $pow = max(1, $pow); 
    $pow = min($pow, count($units) - 1); 

    $size = $bytes / pow(1024, $pow);
    return floor($size) . ' ' . $units[$pow];
}


function getJenisKendaraan($value='')
{
  return ['KENDARAAN RODA 2', 'KENDARAAN RODA 4'];
}
?>

<?php
require_once DIR_PATH . 'init.php';

$getMethod = @$_SERVER['REQUEST_METHOD'];
$getID = @$_GET['id'];
$getProses = @$_GET['proses'];
if (empty($getProses )) { exit; }

$tbl = 'buku_tamu';
$andDeleted = " AND deleted_at is null";
$permissionName = 'report-buku-tamu';

if ($getProses == 'get') {
  checkPermission($permissionName.'-show');
  verifyCSRFToken();
  $tgl_1 = post('from');
  $tgl_2 = post('to');
  if (empty($tgl_1)) { ResponseFailed('<b>Dari Tanggal</b> wajib diisi'); }
  if (empty($tgl_2)) { ResponseFailed('<b>Sampai Tanggal</b> wajib diisi'); }
  $tgl_1 = tgl_format($tgl_1, 'Y-m-d');
  $tgl_2 = tgl_format($tgl_2, 'Y-m-d');

  $nama_pengunjung = post('nama_pengunjung');

  $select = "nomor, tgl_kunjungan, nama_tamu, no_telp_tamu, jenis_identitas, nomor_kendaraan";
  $where = "tgl_kunjungan BETWEEN '$tgl_1' AND '$tgl_2'";
  if (!empty($nama_pengunjung)) {
    $where .= " AND nama_tamu = '$nama_pengunjung'";
  }
  $sqlData = getData($tbl, $where, $select);
  $getData = mysqli_fetch_all($sqlData, MYSQLI_ASSOC);
  if (empty($getData)) {
    ResponseFailed("Data <b>Buku Tamu</b> tidak ditemukan!");
  }

  ResponseSuccess($getData);
}

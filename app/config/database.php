<?php
require_once DIR_PATH . 'init.php';

include "config.php";
$con = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE, PORT) or die ('Koneksi Gagal');
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die;
}
?>

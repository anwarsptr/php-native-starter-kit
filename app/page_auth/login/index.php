<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}
$title = "Login";

include $dir_auth."/includes/header.php";
// Start Content
include "form.php";
// End Content
include $dir_auth."/includes/footer.php";
?>

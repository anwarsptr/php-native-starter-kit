<?php
require_once DIR_PATH . 'init.php';

date_default_timezone_set('Asia/Jakarta');
session_start();

$expired_time = 3600 * 12; // 3600 detik = 1 jam | 0 Unlimited

// QUERY
include "query.php";

// ENCRYPT or DECRYPT
include "cryptography.php";

// DATATABLES
include "datatables.php";

// HELPER
include "helper.php";
?>

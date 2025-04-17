<?php
require_once DIR_PATH . 'init.php';

clearSession();
$redirect = (!empty($_GET['redirect'])) ? strtolower($_GET['redirect']):'';
redirect($redirect);
?>

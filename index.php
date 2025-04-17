<?php

if (!empty($_ENV['PHP_ENVIRONMENT']) && $_ENV['PHP_ENVIRONMENT']=='production') {
	error_reporting(0);
}

if (!is_dir(__DIR__ . '/vendor') || !file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("❌ Kamu belum menjalankan <code>composer install</code>. Silakan jalankan perintah tersebut terlebih dahulu.");
}

defined('DIR_PATH')  || define('DIR_PATH', __DIR__ . DIRECTORY_SEPARATOR.'/app/');
defined('APP_PATH')  || define('APP_PATH', __DIR__ . DIRECTORY_SEPARATOR.'/');

// CONFIG APP
include "app/config/app.php";

// ROUTE url or page
include "app/routes.php";
?>

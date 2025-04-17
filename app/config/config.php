<?php
require_once DIR_PATH . 'init.php';
require_once APP_PATH . 'vendor/autoload.php';

// URL ROOT
if (!empty($_SERVER['HTTP_HOST'])) {
  $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
  $hostName = "://".$_SERVER['HTTP_HOST'];
  $dirName = preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME']));
  $dirName = preg_replace("/\\\/", '', $dirName);
  $base_url = $protocol.$hostName.$dirName.'/';
  defined('BASEURL') || define('BASEURL', $base_url);
}else{
  $base_url = '/';
  define('BASEURL', $base_url);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../../');
$dotenv->load();

// DATABASE
$hostname = @$_ENV['DB_HOST'];
$port     = @$_ENV['DB_PORT'];
$username = @$_ENV['DB_USERNAME'];
$password = @$_ENV['DB_PASSWORD'];
$database = @$_ENV['DB_DATABASE'];

// DEFINE
defined('BASEURL')  || define('BASEURL', $base_url);
defined('HOSTNAME') || define('HOSTNAME', $hostname);
defined('PORT')     || define('PORT', $port);
defined('USERNAME') || define('USERNAME', $username);
defined('PASSWORD') || define('PASSWORD', $password);
defined('DATABASE') || define('DATABASE', $database);
?>

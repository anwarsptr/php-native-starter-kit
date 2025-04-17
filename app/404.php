<?php
require_once DIR_PATH . 'init.php';

$web_app = app();
$base_url = base_url();
$getUri = getUri();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="author" content="anwarsptr.com">
    <meta name="description" content="<?= $web_app['description'] ?>" />
    <meta name="keywords" content="<?= $web_app['description'] ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="HandheldFriendly" content="True">
    <title>404 - <?= @$web_app['title']; ?></title>
    <base href="<?= $base_url ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= @$web_app['favicon'] ?>" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/style.css">
  </head>
  <body class="hold-transition login-page">
        <br>
        <center>
          <img src="<?= $web_app['logo'] ?>" style="max-height:150px;"/><br>
          <b style="font-size:80px">4 0 4</b>
          <div style="font-size:16px;">
            Halaman <?php if ($getUri != '/404') { ?><code class="url"><?= $getUri ?></code><?php } ?> tidak ditemukan.
          </div>
          <br>
          <br>
          <a href="<?= $base_url ?>" class="btn btn-primary border-radius"><i class="fa fa-home"></i> Halaman Utama</a>
        </center>
  </body>
</html>

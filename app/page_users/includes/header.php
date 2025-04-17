<?php
require_once DIR_PATH . 'init.php';

if (empty($_SESSION['id_user'])) { redirect('login'); }
$user_avatar = getAvatar();
$user_name = $_SESSION['name'];
$app_name = @$web_app['app_name'];
$app_name_3 = get3Initials($app_name);
?>
<!DOCTYPE html>
<!-- beautify ignore:start -->
<html>
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
    <meta name="csrf-token" content="<?= generateCSRFToken() ?>">

    <title><?= $title ?> - <?= @$web_app['title']; ?></title>
    <base href="<?= $base_url ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= @$web_app['favicon'] ?>" />
    <?php include "style.php" ?>
    <script>
      var BASEURL = document.querySelector('base').href;
    </script>
  </head>

  <body class="fixed sidebar-mini sidebar-mini-expand-feature layout-boxed skin-blue-light">
    <!-- Layout wrapper -->
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="<?= $base_url ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><?= $app_name_3 ?></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><?= $app_name; ?></span>
        </a>
        <?php include "navbar.php" ?>
      </header>
      <?php include "menu.php" ?>
      <div class="content-wrapper">

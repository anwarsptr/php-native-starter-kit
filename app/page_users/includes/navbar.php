<?php
require_once DIR_PATH . 'init.php';
?>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>

  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="<?= $user_avatar ?>" class="user-image" alt="User Image" style="height:25px;">
          <span class="hidden-xs"><?= $user_name ?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?= $user_avatar ?>" class="img-circle" alt="User Image">
            <p>
              <?= $user_name ?> - @<?= $_SESSION['username'] ?>
              <?php if (!empty($_SESSION['created_at'])): ?>
                <small>Member since <?= waktu($_SESSION['created_at']) ?></small>
              <?php endif; ?>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="account/profile" class="btn btn-success border-radius" data-placement="bottom" data-toggle="tooltip" title="Profile"> <i class="fa fa-user"></i> </a> &nbsp;
              <a href="account/change-password" class="btn btn-primary border-radius" data-placement="bottom" data-toggle="tooltip" title="Change Password"> <i class="fa fa-lock"></i> </a>
            </div>
            <div class="pull-right">
              <a class="btn btn-danger border-radius" href="javascript:onLogout()">
                <i class="fa fa-power-off"></i> Sign out
              </a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

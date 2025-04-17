<?php
require_once DIR_PATH . 'init.php';
?>

<div class="login-box">
  <div class="login-logo">
    <img src="<?= $web_app['logo'] ?>" style="max-height:100px;"/><br>
    <a href=""><b style="font-size: 20px;"><?= $web_app['app_name'] ?></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body border-radius shadow">
    <p class="login-box-msg">Sign in to start your session</p>

    <form id="formAuthentication" method="POST" action="javascript:postSubmit('formAuthentication', '<?= "$base_url?proses=login" ?>', '<?= $base_url.'dashboard' ?>')" data-parsley-validate="true">
      <div class="form-group has-feedback">
        <input type="text" name="username" id="username" class="form-control border-radius" placeholder="Username" required maxlength="100" onkeyup="onValidUsername()" onkeypress="onValidUsername()" autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" id="password" class="form-control border-radius" placeholder="Password" required>
        <span class="glyphicon glyphicon-eye-open form-control-feedback iconPwd btnShowPwd" onclick="showPwd()"></span>
      </div>
      <button type="submit" class="btn btn-primary btn-block btn-flat border-radius shadow"><span class="glyphicon glyphicon-lock"></span> Sign In</button>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>

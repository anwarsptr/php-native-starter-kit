<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showPermission = checkPermission('account-change-password-edit', true);

$title = "Change Password";

include $dir_users."/includes/header.php";
?>
<section class="content-header">
  <h1> <i class="fa fa-lock"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li class="active"> <i class="fa fa-lock"></i> <?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="box box-primary border-radius">
        <div class="box-body box-profile" style="margin:10px 0px;">
          <?php if ($showPermission) { ?>
          <form id="changePassword" action="javascript:postSubmit('changePassword', 'account/change-password?proses=save-change-password')" method="post" data-parsley-validate="true">
            <div class="form-group has-feedback">
              <label for="password0">Old password <span class="text-danger">*</span></label>
              <input type="password" name="password0" id="password0" class="form-control border-radius" placeholder="Old password" required data-parsley-trigger="keyup" data-parsley-minlength="5">
              <span class="glyphicon glyphicon-eye-open form-control-feedback iconPwd0 btnShowPwd0" onclick="showPwd(0)"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="password1">New password <span class="text-danger">*</span></label>
              <input type="password" name="password1" id="password1" class="form-control border-radius" placeholder="New password" required data-parsley-trigger="keyup" data-parsley-minlength="5">
              <span class="glyphicon glyphicon-eye-open form-control-feedback iconPwd1 btnShowPwd1" onclick="showPwd(1)"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="password2">Confirm new password <span class="text-danger">*</span></label>
              <input type="password" name="password2" id="password2" class="form-control border-radius" placeholder="Confirm new password" required data-parsley-trigger="keyup" data-parsley-minlength="5">
              <span class="glyphicon glyphicon-eye-open form-control-feedback iconPwd2 btnShowPwd2" onclick="showPwd(2)"></span>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
          </form>
        <?php }else{ ?>
          <div class="text-center text-danger">
            No access to edit
          </div>
          <br>
        <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
include $dir_users."/includes/footer.php";
?>

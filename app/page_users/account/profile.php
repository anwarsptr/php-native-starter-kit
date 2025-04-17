<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showPermission = checkPermission('account-profile-edit', true);

$title = "Profile";

include $dir_users."/includes/header.php";

$id = get_session('id_user');
$sql = getData('users', "id=$id");
$get = mysqli_fetch_assoc($sql);
?>
<section class="content-header">
  <h1> <i class="fa fa-user"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li class="active"> <i class="fa fa-user"></i> <?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="box box-primary border-radius">
        <div class="box-body box-profile" style="margin:10px 0px;">
          <form id="profile" action="javascript:postSubmit('profile', 'account/profile?proses=save-profile')" method="post" data-parsley-validate="true">
            <div class="form-group has-feedback">
              <label for="name" class="control-label">Full Name : <span class="text-danger">*</span></label>
              <input type="text" class="form-control border-radius" name="name" id="name" placeholder="Enter full name" value="<?= get_session('name') ?>" oninput="validateAbjad(this)" maxlength="100" required <?= $showPermission ? '':'readonly' ?>>
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="username" class="control-label">Username : <span class="text-danger">*</span></label>
              <input type="text" class="form-control border-radius lowercase" name="username" id="username" placeholder="Enter Username" value="<?= get_session('username') ?>" oninput="onValidUsername('username')" maxlength="100" required <?= $showPermission ? '':'readonly' ?>>
              <span class="form-control-feedback">@</span>
            </div>
            <?php if ($showPermission): ?>
              <div class="form-group">
                <label for="avatar" class="control-label"><i class="fa fa-image"></i> Avatar :</label>
                <input type="file" class="form-control border-radius uploadFoto" name="avatar" id="avatar" accept=".jpeg, .jpg, image/png">
                <small class="text-danger">* Ekstensi : <b>jpeg, jpg, png</b> | Maks.Upload : <b><?= formatFromMB(maxUploadFile('profile')) ?></b></small>
              </div>
            <?php endif; ?>
            <?php if ($showPermission): ?>
              <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
            <?php endif; ?>
          </form>
          <hr style="margin:10px 0px;">
          <div class="text-center">
            <b>Register :</b> <?= waktu($get['created_at']) ?>
            <?php if (!empty($get['updated_at'])): ?>
              <br>
              <b>Last Update :</b> <?= waktu($get['updated_at']) ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
include $dir_users."/includes/footer.php";
?>

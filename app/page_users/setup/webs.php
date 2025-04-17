<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showPermission = checkPermission('setup-website-edit', true);

$title = "Setup Webs";

include $dir_users."/includes/header.php";

$sql = getData('setup_webs', "id=1");
$get = mysqli_fetch_assoc($sql);
?>
<section class="content-header">
  <h1> <i class="fa fa-globe"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li><a href="setup"> <i class="fa fa-gears"></i> Setup </a></li>
    <li class="active"> <i class="fa fa-globe"></i> Webs</li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="box box-primary border-radius">
        <div class="box-body box-profile" style="margin:10px 0px;">
          <form id="profile" action="javascript:postSubmit('profile', 'setup/webs?proses=save-setup-webs')" method="post" data-parsley-validate="true">
            <div class="form-group has-feedback">
              <label for="app_name" class="control-label">App Name : <span class="text-danger">*</span></label>
              <input type="text" class="form-control border-radius" name="app_name" id="app_name" placeholder="Enter app name" value="<?= $get['app_name'] ?>" maxlength="100" required <?= $showPermission ? '':'readonly' ?>>
              <span class="fa fa-bookmark form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="title" class="control-label">Title : <span class="text-danger">*</span></label>
              <input type="text" class="form-control border-radius" name="title" id="title" placeholder="Enter title" value="<?= $get['title'] ?>" maxlength="100" required <?= $showPermission ? '':'readonly' ?>>
              <span class="fa fa-tag form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="descriptions" class="control-label">Description : </label>
              <textarea class="form-control border-radius custom-textarea" name="descriptions" id="descriptions" placeholder="Enter description" rows="1" <?= $showPermission ? '':'readonly' ?>><?= $get['description'] ?></textarea>
              <span class="fa fa-file-text form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <label for="website" class="control-label">Website : </label>
              <input type="text" class="form-control border-radius" name="website" id="website" placeholder="domain.com" value="<?= $get['website'] ?>" style="text-transform:lowercase" <?= $showPermission ? '':'readonly' ?>>
              <span class="form-control-feedback fa fa-globe"></span>
            </div>
            <?php if ($showPermission): ?>
              <div class="form-group">
                <label for="favicon" class="control-label"><i class="fa fa-image"></i> Favicon :</label>
                <input type="file" class="form-control border-radius uploadFoto" name="favicon" id="favicon" accept=".jpeg, .jpg, image/png">
                <small class="text-danger">* Ekstensi : <b>jpeg, jpg, png</b> | Maks.Upload : <b>500 KB</b></small>
              </div>
              <div class="form-group">
                <label for="logo" class="control-label"><i class="fa fa-image"></i> Logo :</label>
                <input type="file" class="form-control border-radius uploadFoto" name="logo" id="logo" accept=".jpeg, .jpg, image/png">
                <small class="text-danger">* Ekstensi : <b>jpeg, jpg, png</b> | Maks.Upload : <b>500 KB</b></small>
              </div>
              <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
            <?php endif; ?>
          </form>
          <?php if (!empty($get['updated_at'])): ?>
          <hr style="margin:10px 0px;">
          <div class="text-center">
              <b>Last Update :</b> <?= waktu($get['updated_at']) ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
include $dir_users."/includes/footer.php";
?>

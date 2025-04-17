<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$role_id = get_session('role_id');
$position = 'left';
$parent_id = NULL;
$active = 1;
$result = getMenuIndex($role_id, $position, $parent_id, $active);

$title = "Dashboard";

include $dir_users."/includes/header.php";

$warna_bg = ['danger','warning','success','info'];
$random_bg = $warna_bg[rand(0,3)];
?>

<section class="content">
  <div class="callout callout-<?= $random_bg ?> alert alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>Selamat Datang <b style="font-size: 22px"><?= $user_name ?></b> 🎉</h4>
    <p>Selamat beraktivitas!</p>
  </div>

  <div class="row">
    <?php foreach ($result as $key => $value):
      if (!$value['hasPermission']) { continue; }
      $menu_name = $value['menu_name'];
      if (strtolower($menu_name)=='dashboard') { continue; }
      $bg_color = (!empty($value['bg_color'])) ? $value['bg_color']:'white';
    ?>
      <?php if ($value['is_separator']==1) { ?>
      <div class="col-lg-12 col-xs-12">
        <h3 class="text-muted"><?= $menu_name ?></h3>
      </div>
      <?php }else{ ?>
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-<?= $bg_color ?>">
          <div class="inner">
            <h4 style="padding-top:10px !important;padding-bottom:10px !important"><b><?= $menu_name ?></b></h4>
          </div>
          <div class="icon">
            <i class="fa <?= $value['menu_icon'] ?>"></i>
          </div>
          <a href="<?= $value['route_name'] ?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <?php } ?>
    <?php endforeach; ?>
  </div>

</section>

<?php
include $dir_users."/includes/footer.php";
?>

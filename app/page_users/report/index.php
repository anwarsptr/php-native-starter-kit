<?php
require_once DIR_PATH . 'init.php';

$role_id = get_session('role_id');
$position = 'left';
$parent_id = 5;
$active = 1;
$result = getMenuIndex($role_id, $position, $parent_id, $active);

$title = "Report";

include $dir_users."/includes/header.php";
?>
<section class="content-header">
  <h1> <i class="fa fa-print"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li class="active"> <i class="fa fa-print"></i> <?= $title ?></li>
  </ol>
</section>
<section class="content">

  <div class="row">
    <?php foreach ($result as $key => $value):
    $bg_color = (!empty($value['bg_color'])) ? $value['bg_color']:'white';
    ?>
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-<?= $bg_color ?>">
          <div class="inner">
            <h4 style="padding-top:10px !important;padding-bottom:10px !important"><b><?= $value['menu_name'] ?></b></h4>
          </div>
          <div class="icon">
            <i class="fa <?= $value['menu_icon'] ?>"></i>
          </div>
          <a href="<?= $value['route_name'] ?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</section>

<?php
include $dir_users."/includes/footer.php";
?>

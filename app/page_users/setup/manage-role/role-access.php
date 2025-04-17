<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$id_ori = @$_GET['id'];
if (empty($id_ori)) { redirect('404'); }
$id = decrypt($id_ori);

$showAccess = checkPermission('setup-manage-role-edit-access', true);
$showSort = checkPermission('setup-manage-role-edit-sort', true);

// $isSave = true;
$isSave = ($id==1) ? false:$showAccess;

$sql = getData('roles', "id=$id");
$roles = mysqli_fetch_assoc($sql);
if (empty($roles)) { redirect('404'); }

$list_menu_role_left = getMenuWithPermissionsByRoleName($id, 'left', 1);
// log_r($list_menu_role_left);

$title = "Setup Role Access";

include $dir_users."/includes/header.php";

$urlnya = "setup/manage-role";
$url_proses = "$urlnya?proses=";
?>
<section class="content-header">
  <h1> <i class="fa fa-key"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li><a href="setup"> <i class="fa fa-gears"></i> Setup </a></li>
    <li><a href="<?= $urlnya ?>"> <i class="fa fa-lock"></i> Manage Role </a></li>
    <li class="active"> <i class="fa fa-key"></i> Role Access</li>
  </ol>
</section>
<section class="content">

<form id="dataForm"  method="POST">
  <input type="hidden" name="id" value="<?= $id_ori ?>">
  <div class="box box-primary border-radius">
    <div class="box-header with-border">
      <h3 class="box-title">Role : <b><?= $roles['name'] ?></b></h3>
      <div class="box-tools pull-right">
        <a href="<?= $urlnya ?>" class="btn btn-box-tool" data-toggle="tooltip" title="Back"><i class="fa fa-times"></i></a>
      </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover" width="100%">
            <thead class="bg-primary">
                <th width="50%" style="vertical-align:middle">Menu&nbsp;Left&nbsp;<?php if ($showSort) { ?><button type="button" class="btn btn-xs btn-warning pull-right hidden-xs" data-toggle="tooltip" title="Setting Sortable Menu Left" onclick="showModalSortable('left')"><i class="fa fa-list"></i> Sort Menu</button><?php } ?></th>
                <th width="50%" style="vertical-align:middle">Permissions</th>
            </thead>
            <tbody>
              <?php
                $arr_short = ['show'=>'blue', 'add'=>'green', 'edit'=>'yellow', 'delete'=>'red', 'detail'=>'grey', 'print'=>'pink', 'block'=>'orange', 'active'=>'orange', 'edit role sorted'=>'orange', 'export pdf'=>'red', 'export excel'=>'green'];
              ?>
              <?php foreach ($list_menu_role_left as $menu) : ?>
                    <tr>
                        <td class="<?= (empty($menu['parent_id'])) ? '':'submenunya' ?>"><i class="fa <?= $menu['menu_icon'] ?>"></i> <?= $menu['menu_name'] ?></td>
                        <td>
                            <div class="d-flex">
                                <?php foreach ($menu['permissions'] as $permission) :
                                    $short_name = $permission['short_name'];
                                    $checkboxColor = empty($arr_short[strtolower($short_name)]) ? 'grey':$arr_short[strtolower($short_name)];
                                ?>
                                  <label for="checked_<?= $menu['id'] . '_' . $permission['id'] ?>">
                                      <input class="flat-<?= $checkboxColor ?>" type="checkbox"
                                          id="checked_<?= $menu['id'] . '_' . $permission['id'] ?>"
                                          <?= $permission['hasPermission'] ? 'checked' : '' ?>
                                          name="<?= $menu['id'] . '_' . $permission['id'] ?>" <?= ($isSave) ? '':'disabled' ?>>
                                      <?= $short_name ?>
                                  </label> &nbsp; &nbsp;
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($isSave): ?>
    <!-- /.box-body -->
    <div class="box-footer">
      <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
    </div>
    <!-- /.box-footer-->
    <?php endif; ?>
  </div>
</form>


<form id="sortableForm" method="post">
<div class="modal modal-default fade" id="sort-menu" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content border-radius">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" arial-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"> Setting Sortable <b id="setMenuSort">Menu Left</b></h3>
      </div>
      <div class="modal-body">
        <ol id="sortable-left">
          <?php $i=0;
          foreach ($list_menu_role_left as $menu) :
              $showMenu=false;
              $permissions = $menu['permissions'];
              $showPermission = firstWhere($permissions, "short_name", "Show");
              if ($showPermission) {
                  if (hasPermissionTo($roles['id'], $showPermission['name'])) {
                      $showMenu=true; $i++;
                  }
              }
          ?>
            <?php if ($showMenu) :
                if (empty($menu['parent_id'])) { ?>
                  <li class="ui-widget-content" style="margin-left: 0px;" id="item-<?= $menu['id'] ?>">
                    <div class="callout callout-success"><i class="fa <?= $menu['menu_icon'] ?>"></i> <?= $menu['menu_name'] ?> </div>
                  </li>
            <?php }else{ ?>
                  <li class="ui-widget-content" style="margin-left: 50px;" id="item-<?= $menu['id'] ?>">
                    <div class="callout callout-info"><i class="fa <?= $menu['menu_icon'] ?>"></i> <?= $menu['menu_name'] ?> </div>
                  </li>
            <?php } ?>
            <?php endif;
          endforeach;
          if ($i==0) : ?>
          <center>
            <div class="text-danger">
              Tidak ada menu yang di checklist <b>Show</b><br />(Pastikan sudah <b>Save Data</b>)
            </div>
          </center>
        <?php endif; ?>
        </ol>
        <textarea name="dataSortable" id="dataSortable" rows="8" cols="80" hidden></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left border-radius" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <?php if ($showSort): ?>
          <button type="submit" class="btn btn-primary pull-right border-radius"><i class="fa fa-check"></i> Save</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</form>
</section>

<?php
include $dir_users."/includes/footer.php";

include $dir_users."/includes/components/iCheck.php";
?>

<script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
$( "#sortable-left, #sortable-top" ).sortable({
  cursorAt : { left: 0 },
  placeholder: "ui-box",
  cursor: "move",
  update: function() {
    var data = $(this).sortable('serialize');
    $('#dataSortable').val(data);
  }
});

$('input[type="checkbox"].flat-blue').iCheck({
  checkboxClass: 'icheckbox_flat-blue'
});
$('input[type="checkbox"].flat-green').iCheck({
  checkboxClass: 'icheckbox_flat-green'
});
$('input[type="checkbox"].flat-red').iCheck({
  checkboxClass: 'icheckbox_flat-red'
});
$('input[type="checkbox"].flat-yellow').iCheck({
  checkboxClass: 'icheckbox_flat-yellow'
});
$('input[type="checkbox"].flat-orange').iCheck({
  checkboxClass: 'icheckbox_flat-orange'
});
$('input[type="checkbox"].flat-grey').iCheck({
  checkboxClass: 'icheckbox_flat-grey'
});
$('input[type="checkbox"].flat-square').iCheck({
  checkboxClass: 'icheckbox_flat-square'
});
$('input[type="checkbox"].flat-aero').iCheck({
  checkboxClass: 'icheckbox_flat-aero'
});
$('input[type="checkbox"].flat-pink').iCheck({
  checkboxClass: 'icheckbox_flat-pink'
});
$('input[type="checkbox"].flat-purple').iCheck({
  checkboxClass: 'icheckbox_flat-purple'
});
// $('.flat-checkbox').on('ifChanged', function(event){
//     $(this).val($(this).prop('checked') ? 1:0);
// });

$('#dataForm').submit(async function (e) {
    e.preventDefault();
    var fd = new FormData($('#dataForm')[0]);
    await prosesSubmit({
      csrf: true, method:"POST", form:'dataForm', url:"<?= $url_proses ?>save-role", fd:fd,
      callbackSuccess: function(data) {
        swalResponse('success', data.message, false, 'x');
        setTimeout(function() {
          window.location.reload();
        }, 2500);
      }
    });
});

function showModalSortable(menu='left')
{
    $('#dataSortable').val('');
    $('#sortable-left').hide();
    $('#sortable-top').hide();
    $('#sortable-'+menu).show();
    if (menu=='top') {
      $('#setMenuSort').html(`Menu Top`);
    }else {
      $('#setMenuSort').html(`Menu Left`);
    }
    $('#sort-menu').modal('show');
}

$('#sortableForm').submit(async function (e) {
    e.preventDefault();
    var stringData = $('#dataSortable').val();
    if (stringData=='') {
      $('#sort-menu').modal('hide');
      return false;
    }
    var fd = new FormData();
    stringData.split('&').forEach(function(item) {
      var keyValue = item.split('=');
      fd.append(keyValue[0], keyValue[1]);
    });
    fd.append('id', '<?= $id_ori ?>');
    await prosesSubmit({
      csrf: true, method:"POST", url:"<?= $url_proses ?>save-sort", fd:fd,
      callbackSuccess: function(data) {
        swalResponse('success', data.message, false, 'x');
        setTimeout(function() {
          window.location.reload();
        }, 2500);
      }
    });
});
</script>

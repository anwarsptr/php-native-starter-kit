<?php
require_once DIR_PATH . 'init.php';

function listSideBarMenuByRoleLogin($position='left')
{
  global $con;
  $id = get_session('id_user');
  $sql_user = getData('users', "id=$id", "role_id");
  $role_id = mysqli_fetch_assoc($sql_user)['role_id'];

  $sql = getData('menus', "position='$position' AND is_active=1 ORDER BY `order_by` ASC");
  $menus = mysqli_fetch_all($sql, MYSQLI_ASSOC);

  $permissionShortName = 'Show';
  $return = [];
  foreach ($menus as $menu) {
    $menu_id = $menu['id'];
    // Ambil izin menu berdasarkan short_name
    $query_permissions = "
        SELECT p.id, p.name
        FROM permissions p
        JOIN role_has_permissions rhp ON p.id = rhp.permission_id
        WHERE p.id_menu = $menu_id AND p.short_name = '$permissionShortName' AND rhp.role_id=$role_id
    ";
    $result_permissions = mysqli_query($con, $query_permissions);
    if (!$result_permissions) {
        die("Query failed : " . mysqli_error($con));
    }
    $showPermission = mysqli_fetch_assoc($result_permissions);

    if ($showPermission) {
        $permission_id = $showPermission['id'];
        // Periksa apakah role memiliki izin
        $query_role_permissions = "
            SELECT `order_by`
            FROM role_has_permissions
            WHERE role_id = $role_id AND permission_id = $permission_id
        ";
        $result_role_permissions = mysqli_query($con, $query_role_permissions);
        if (!$result_role_permissions) {
            die("Query failed : " . mysqli_error($con));
        }
        $order = mysqli_fetch_assoc($result_role_permissions);
        $order_id = ($order) ? $order['order_by'] : 0;

        $keynya = $order_id;
        $return["$keynya"][] = $menu;
    }
  }
  // Urutkan berdasarkan key
  ksort($return);
  // Flatten array hasil
  $return_new = [];
  foreach ($return as $key => $value) {
      foreach ($value as $menu) {
          $return_new[] = $menu;
      }
  }
  return $return_new;
}

function listSideBarMenuTree($items, $parentId=0, $position='left')
{
    $result = [];
    foreach ($items as $item) {
        if ($item['parent_id'] == $parentId) {
            $children = listSideBarMenuTree($items, $item['id'], $position);
            if ($children) {
                $item['children'] = $children;
            }
            $result[] = $item;
        }
    }
    return $result;
}

function buildMenuHTML($position='left', $menus=[])
{
    $func = "buildMenu".$position."HTML";
    return $func($menus=[], $position);
}

function buildMenuLeftHTML($menus=[], $position='left')
{
  if (count($menus) == 0) {
      $items = listSideBarMenuByRoleLogin($position);
      $menus = listSideBarMenuTree($items, 0, $position);
      $html = '<ul class="sidebar-menu" data-widget="tree">';
  } else {
      $html = '<ul class="treeview-menu">';
  }
  foreach ($menus as $mn) {
      if ($mn['is_separator'] == 1) {
          $html .= '<li class="header">' . $mn['menu_name'] . '</li>';
      } else {
          if (!empty($mn['children'])) {
              $html .= '<li class="treeview">
              <a href="javascript:void(0);" class="menu-link menu-toggle">';
              if ($mn['menu_icon'] != '') {
                  $html .= '<i class="fa ' . $mn['menu_icon'] . '"></i>';
              }
              $html .= '
                <span>' . $mn['menu_name'] . '</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>';
                $html .= buildMenuLeftHTML($mn['children'], $position);
              $html .= '</li>';
          } else {
              $route_name = (!empty($mn['route_name'])) ? $mn['route_name']:'';
              $html .= '<li>
                  <a href="' . $route_name . '">';
                  if ($mn['menu_icon'] != '-') {
                      $html .= '<i class="fa ' . $mn['menu_icon'] . '"></i>';
                  }
              $html .= '<span>' . $mn['menu_name'] . '</span></a>
              </li>';
          }
      }
  }
  $html .= '</ul>';
  return $html;
}
?>

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?= $user_avatar ?>" class="img-circle" alt="User Image" style="max-height:45px;">
      </div>
      <div class="pull-left info">
        <p><?= $user_name ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <form action="javascript:void(0);" method="get" class="sidebar-form hidden-xs" style="margin-top:0px;">
      <div class="input-group">
        <input type="text" name="q" class="form-control" id="sidebar-filter" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>
    <hr class="hidden-lg" style="margin:0px;">
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <?= buildMenuHTML() ?>
  </section>
</aside>

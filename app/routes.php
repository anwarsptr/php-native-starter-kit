<?php
require_once DIR_PATH . 'init.php';

$web_app  = app();
$base_url = base_url();

$dir_app  = DIR_PATH;
$dir_app  = preg_replace("#/+#", "/", $dir_app);

$dir_auth  = $dir_app.'page_auth';
$dir_users = $dir_app.'page_users';

$requestURI = getUri();
switch (true) {
    case $requestURI === '/':
        if (!empty($_SESSION['id_user'])) {
          checkPermission('dashboard-show');
          include $dir_users."/dashboard/index.php";
        }else{
          include $dir_auth."/login/index.php";
        }
        break;

    case in_array($requestURI, ['/auth','/login']):
        include $dir_auth."/login/index.php";
        break;
    case $requestURI === '/logout':
        checkPermission('account-sign-out-show');
        include $dir_app."logout.php";
        break;

    case $requestURI === '/dashboard':
        checkPermission('dashboard-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/account/profile':
        checkPermission('account-profile-show');
        include $dir_users."$requestURI.php";
        break;
    case $requestURI === '/account/change-password':
        checkPermission('account-change-password-show');
        include $dir_users."$requestURI.php";
        break;
    case $requestURI === '/buku-tamu':
        checkPermission('buku-tamu-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/setup':
        checkPermission('setup-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/setup/webs':
        checkPermission('setup-website-show');
        include $dir_users."$requestURI.php";
        break;
    case $requestURI === '/setup/running-numbers':
        checkPermission('setup-running-numbers-show');
        include $dir_users."$requestURI.php";
        break;
    case $requestURI === '/setup/user-role':
        checkPermission('setup-user-role-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/setup/manage-role':
        checkPermission('setup-manage-role-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/setup/manage-role/role-access':
        checkPermission('setup-manage-role-show');
        include $dir_users."$requestURI.php";
        break;
    case $requestURI === '/master-data':
        checkPermission('master-data-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/master-data/jenis-identitas':
        checkPermission('master-data-jenis-identitas-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/report':
        checkPermission('report-show');
        include $dir_users."$requestURI/index.php";
        break;
    case $requestURI === '/report/buku-tamu':
        checkPermission('report-buku-tamu-show');
        include $dir_users."$requestURI/index.php";
        break;
    default:
        include "404.php";
        break;
}
exit;
?>

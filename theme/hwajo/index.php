<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

Header("Location:/page.php?id=structure");
// include_once(G5_THEME_PATH.'/dashboard.php');

?>

<?php
// include_once(G5_THEME_PATH.'/tail.php');
?>
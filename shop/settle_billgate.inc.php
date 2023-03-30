<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once G5_SHOP_PATH."/billgate/php/config.php";

$today=mktime(); 
$today_time = date('YmdHis', $today);

//parameter
$orderDate = $today_time ; //(YYYYMMDDHHMMSS)
$returnUrl = "http://".$_SERVER['HTTP_HOST']."/shop/billgate/return.php";
?>
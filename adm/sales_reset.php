<?php
include_once('/home/sdevftv/html/common.php');
$now = date("Y-m-d");
$sql = "update g5_member set sales_day = '$now', mb_my_sales=0,  habu_day_sales=0";
sql_query($sql);
?>

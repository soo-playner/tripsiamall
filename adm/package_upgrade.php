<?php
include('./_common.php');

check_admin_token();

$mb_id = $_POST['mb_id'] ? sql_escape($_POST['mb_id']) : false;
$it_id = $_POST['it_id'] ? sql_escape($_POST['it_id']) : false;

if(!$mb_id || !$it_id){
 echo json_encode(array("code"=>0001));
 return false;
}

$sql = "select od_id, count(*) as cnt from g5_shop_order where mb_id = '{$mb_id}' and od_tno = {$it_id} order by od_date asc limit 0,1";
$row = sql_fetch($sql);

if($row['cnt'] <= 0){
    echo json_encode(array("code"=>0001));
    return false;
}

$_POST['od_id'] = $row['od_id'];
$_POST['mb_id'] = $mb_id;

include_once("../util/package_upgrade.php");
?>
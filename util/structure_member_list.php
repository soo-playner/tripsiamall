<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');
include_once(G5_PATH.'/util/recommend.php');


if($_REQUEST['type'] == 'name'){
	$query = "select mb_no from g5_member where mb_id = '{$_REQUEST['mb_no']}'";
	$srow = sql_fetch($query);
	$mb_no = $srow['mb_no'];
}else{
	$mb_no = $_REQUEST['mb_no'];
}

if($mb_no < 2){
	$depth_limit = 3;
}else{
	$depth_limit = 2;
}

$result  = return_down_manager($mb_no,$depth_limit);
echo json_encode($result);
?>
<?php
include_once('./_common.php');
$start_id = $_POST['start_id'];

if($start_id!=$member['mb_id']){
	$get_up = "Select mb_brecommend from g5_member where mb_id='".$start_id."'";
	$rst = sql_fetch($get_up);
	$one_level_up = $rst['mb_brecommend'];
	echo (json_encode(array("result" => $one_level_up,  "code" => "0000")));
}
else{
	$one_level_up ="";
	echo (json_encode(array("result" => $one_level_up,  "code" => "0001")));
}
?>
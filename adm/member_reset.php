<?php
$sub_menu = "600600";
include_once('./_common.php');
include_once('./inc.member.class.php');
auth_check($auth[$sub_menu], 'r');

$g5['body_script'] = ' oncontextmenu="return false"';


if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}


$token = get_token();




	$sql = "delete from g5_member_class where mb_id='".$member['mb_id']."'";
	sql_query($sql);

	get_recommend_down($member['mb_id'],$member['mb_id'],'11');





?>
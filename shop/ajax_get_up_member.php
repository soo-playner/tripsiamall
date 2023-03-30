<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('../adm/inc.member.class.php');

if ($gubun=="B"){
	get_brecommend2_up($go_id,trim($member['mb_id']));
}else{
	get_recommend2_up($go_id,trim($member['mb_id']));
}
?>

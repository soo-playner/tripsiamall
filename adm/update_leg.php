<?php
include_once('./_common.php');

$brcom_id = $_POST['mb_id'];
$left_leg = $_POST[left];
$right_leg = $_POST[right];


if($left_leg){
	$get_mb = "select mb_id from g5_member where mb_id='$left_leg'";
	$ret = sql_fetch($get_mb);
	if($ret){
		$cond = " where mb_id = '".$left_leg."'";
		$sql = "Update g5_member SET ";
		$sql = $sql."mb_brecommend = '".$brcom_id."', ";			//발생자
		$sql = $sql."mb_lr = 1";
		$sql = $sql."mb_brecommend_type = 'L'";
		$sql = $sql.$cond;
		echo $sql;
	}
	//sql_query($sql);
	else {
		alert('해당 아이디가 존재 하지 않습니다');
	}
}


if($right_leg){
	$get_mb = "select mb_id from g5_member where mb_id='$right_leg'";
	$ret = sql_fetch($get_mb);
	$cond = " where mb_id = '".$right_leg."'";
	$sql = "Update g5_member SET ";
	$sql = $sql."mb_brecommend = '".$brcom_id."', ";			//발생자
	$sql = $sql."mb_lr = 2";
	$sql = $sql."mb_brecommend_type = 'R'";
	$sql = $sql.$cond;
	echo $sql;

}




?>
	
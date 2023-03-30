<?php
include_once('./_common.php');

$receiver_id = $_POST[iwol_receiver];
$sender_id = $_POST[iwol_sender];
$iwol_kind = $_POST[iwol_kind];
$iwol_pv = $_POST[iwol_pv];
$iwol_note = $_POST[iwol_note];

$today = date("Y-m-d");

$sql = "INSERT iwol SET ";
$sql = $sql."mb_id = '".$sender_id."', ";			//발생자
$sql = $sql."kind = '".$iwol_kind."', ";				//종류
$sql = $sql."pv = ".$iwol_pv.", ";					//PV수치
$sql = $sql."note = '".$iwol_note."', ";				//발생 원인
$sql = $sql."iwolday = '".$today."', ";				//넣는 당일 
$sql = $sql."mb_brecommend = '".$receiver_id."' ";	//받는 사람 아이디
echo $sql;

sql_query($sql);



?>
	
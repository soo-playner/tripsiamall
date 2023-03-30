<?php
//쓰지않는 소스

$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$get_cond = "select * from eos_daily_paid";
$list = sql_query($get_cond);

$pay_percent = array();

while($row = sql_fetch_array($list)){
	$pay_percent[$row['eos_grade']] = $row['eos_per'];
	echo $pay_percent[$row['eos_grade']];
}
$mem_list = "select * from {$g5['member_table']}  where mb_save_point >1" ;//Yellow
$rst_list = sql_query($mem_list);
$day = date('Y-m-d', time());
while($mrow = sql_fetch_array($rst_list)){
	$benefit = round($mrow[mb_save_point] * $pay_percent[$mrow['mb_level']]/100 ,8);
	$allowance_name = "daily payout";
	$rec_adm = "daily payout";
	$rec = "daily payout";
	save_benefit($day,$mrow['mb_id'], $mrow[mb_no],$mrow[mbname], $recom, $allowance_name,   $benefit, $rec_adm, $rec);
}

function save_benefit($day,$mbid, $mbno,$mbname, $recom, $allowance_name,   $benefit, $rec_adm, $rec ){
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.",8)  where mb_id = '".$mbid."';";
	sql_query($balance_up);

	$temp_sql1 = " insert soodang_pay set day='".$day."'";
	$temp_sql1 .= " ,mb_id			= '".$mbid."'";
	$temp_sql1 .= " ,mbno			= ".$mbno;
	$temp_sql1 .= " ,mb_level      = ".$mb_level;
	$temp_sql1 .= " ,mb_name	= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend	= '".$recom."'";
	$temp_sql1 .= " ,allowance_name	= '".$allowance_name."'";
	$temp_sql1 .= " ,benefit		=  ".$benefit;	
	$temp_sql1 .= " ,rec			= '".$rec."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
	sql_query($temp_sql1);
}
?>


<?php
include_once('./_common.php');

/*
$_POST['mb_id'] = 'arcthan4';
$_POST['amount'] = '1,000.000';
$_POST['upstair_acc'] = '1,000.000';
*/

$mb_id = $_POST['mb_id'];
$upstair_acc = (int)str_replace(',', '', $_POST['upstair_acc']);
$amount = (int)str_replace(',', '', $_POST['amount']);

$now_date = date('Y-m-d H:i:s');
$now_day = date('Y-m-d');


$sql = "insert g5_shop_upstair_reset_log set
    mb_id = '".$mb_id."'
	, od_date     = '".$now_date."'
	, acc_num   = '".$amount."'
	, current_deposit         = '$upstair_acc'";
//echo $sql;

$rst = sql_query($sql, false);
//$rst = 1;

if($rst){

	$now_sql = "select * from g5_member where mb_id ='{$mb_id}' ";
	$now_data = sql_fetch($now_sql);
	$now_balance = $now_data['mb_balance'];

	//echo "<br><br>";
	//print_R($now_balance);

	$update_point = "update g5_member set ";
	$update_point .= " mb_deposit_point = '0' ";

	if($now_data['mb_level'] < 5){
		$update_point .= ", mb_level = '0' " ;
	}

	// $update_point .= ", mb_deposit_acc = '".($upstair_acc+$amount)."'";
  $update_point .= ", mb_deposit_acc = mb_deposit_acc+'".$amount."'";
  $update_point .= ", mb_balance = 0";
	$update_point .= ", mb_shift_amt =  (mb_shift_amt + '{$now_balance}') ";
	$update_point .= " where mb_id ='".$mb_id."'";

	sql_query($update_point);

	$reset_sql = " insert soodang_pay set day='".$now_day."'";
	$reset_sql .= " ,mb_no			= ".$now_data['mb_no'];
	$reset_sql .= " ,mb_id			= '".$now_data['mb_id']."'";
	$reset_sql .= " ,mb_level      = ".$now_data['mb_level'];
	$reset_sql .= " ,mb_name		= '".$now_data['mb_id']."'";
	$reset_sql .= " ,mb_recommend	= '".$now_data['mb_recommend']."'";
	$reset_sql .= " ,allowance_name	= '".'reset'."'";
	$reset_sql .= " ,benefit		=  '0' ";
	$reset_sql .= " ,rec			= '".'Bonus reset'."'";
	$reset_sql .= " ,rec_adm		= '".'All Bonus reset'."'";

	//echo "<br>".$update_point;

	sql_query($reset_sql);


	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $save_hist)));
}
else{
	echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
}
?>

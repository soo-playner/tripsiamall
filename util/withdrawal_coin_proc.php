<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');

$user_ip = $_SERVER['REMOTE_ADDR'];

$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

/* 메일인증 - 사용안함 */
//include_once('../lib/otphp/lib/otphp.php');
//include_once(G5_LIB_PATH.'/mailer.lib.php');

// $debug=1;



/* 
	출금시 
		func = 'withdraw'
		select_coin = '$'
		

	마이닝출금시 
		func = 'mining-withdraw'
		select_coin = 'fil'
*/

$func				= trim($_POST['func']);
$mb_id				= trim($_POST['mb_id']);
$wallet_addr		= trim($_POST['wallet_addr']);
$amt				= trim($_POST['amt']); // 입력값
$fee 				= $_POST['fee']; // 수수료
$select_coin 		= $_POST['select_coin']; // 계산코인
$coin_amt 			= $_POST['coin_amt']; // 실제출금계산액
$coin_cost 			= $_POST['cost']; // 코인가격



/* if($debug){
	$mb_id = 'test2';
	$wallet_addr = '1234';
	$func = 'withdraw';
	$amt = 1000.00;
	$fee = 614.00;
	$select_coin = '$';
	$coin_amt = 4.9325;
	$coin_cost = 59.59442845;
} */


if($debug){
	$mb_id = 'arcthan';
	$wallet_addr = '0x3970f8E31b61b44400b8917DA39b4f748649815C';
	$func = 'mining-withdraw';
	$amt = 156.7506;
	$fee = 7.8375;
	$select_coin = 'etc';
	$coin_amt = 148.9131;
	$coin_cost = 2933.03637855;
}


// 출금 설정 
if($func == 'withdraw'){
	$withdrwal_setting = wallet_config('withdrawal');
	$max_fund = $total_withraw;
	$od_type = "출금요청";
	$amt_target = 'mb_shift_amt';

}else if($func == 'mining-withdraw'){
	$withdrwal_setting = wallet_config('withdrawal_mining');
	$max_fund = $mining_total;
	$od_type = "마이닝출금요청";
	$mining_target = $mining_target;
	$amt_target = $mining_amt_target;
}

$fee_rate = $withdrwal_setting['fee'];
$min_limit = $withdrwal_setting['amt_minimum'];
$max_limit = $withdrwal_setting['amt_maximum'];
$day_limit = $withdrwal_setting['day_limit'];

$in_amt = $amt;

if($debug){
	echo "수수료율 : ".$fee_rate;
	echo " / 최소출금 : ".$min_limit;
	echo " / 최대출금 : ".$max_limit;
	echo " / 일제한 : ".$day_limit;
	echo "<br><br>";
}

//출금기록 확인
$today_ready_sql = "SELECT * FROM {$g5['withdrawal']} WHERE mb_id = '{$mb_id}' AND date_format(create_dt,'%Y-%m-%d') = '{$now_date}' AND coin = '{$select_coin}' ";
$today_ready = sql_query($today_ready_sql);
$today_ready_cnt = sql_num_rows($today_ready);

if($debug) echo "<code>일제한: ".$day_limit .' / 오늘 : '.$today_ready_cnt."<br><br>".$today_ready_sql."</code><br><br>";

// 일 요청 제한
if($day_limit != 0 && $today_ready_cnt >= $day_limit){
	echo (json_encode(array("result" => "Failed", "code" => "0010","sql"=>"<span style='font-size:12px'><span style='font-size:13px'>일일 출금 횟수 초과입니다. 하루 $day_limit 회 가능</span>"),JSON_UNESCAPED_UNICODE)); 
	return false;
}
if($debug) echo "<code>최소: ".$min_limit .' / 최대가능금액 : '.$max_fund."  (".$max_limit."%) / 현재출금가능".$max_fund."</code><br><br>";


// 최소금액 제한 확인
if( $min_limit != 0 && $amt < $min_limit ) {
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"올바른 최소 수량 값을 입력해 주세요.")));
	return false;
}

// 최대금액 제한 확인
$max_total = ($max_fund * ($max_limit * 0.01));
if( $max_limit != 0 && $amt > $max_total ) {
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"올바른 최대 수량 값을 입력해 주세요.")));
	return false;
}


// 출금잔고 재확인 
if($max_fund < $amt){
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"계정 잔액이 부족합니다.")));
	return false;
}

$Enc_wallet_addr = Encrypt($wallet_addr,$secret_key,$secret_iv);
$Enc_wallet_addr2 = Encrypt($wallet_addr,$mb_id,'x');


//출금 처리
$proc_receipt = "insert {$g5['withdrawal']} set mb_id ='{$mb_id}', addr = '{$Enc_wallet_addr}', amt = {$amt} - {$fee}, fee = {$fee}, fee_rate = {$withdrwal_setting['fee']}, amt_total = {$in_amt}, coin = '{$select_coin}', status = '0', create_dt = '".$now_datetime."', cost = '{$coin_cost}', account = '{$max_fund}', out_amt = '{$coin_amt}', od_type = '{$od_type}', memo = '', ip =  '{$user_ip}' ";


if($debug){ 
	$rst = 1;
	echo "<br>".$proc_receipt."<br><br>"; 
}else{
	$rst = sql_query($proc_receipt);
}

// 회원정보업데이트
if($rst){
	$amt_query = "UPDATE g5_member set withdraw_wallet =  '{$Enc_wallet_addr2}', {$amt_target}= {$amt_target} + {$in_amt}, otp_key = '' where mb_id = '{$mb_id}' ";
}
 
if($debug){ 
	$amt_result = 1;
	print_R($amt_query); 
}else{ 
	
	$amt_result = sql_query($amt_query);
}


if($rst && $amt_result){
	echo (json_encode(array("result" => "success", "code" => "1000")));
}else{
	echo (json_encode(array("result" => "Failed", "code" => "0001","sql"=>"처리되지 않았습니다. 문제가 지속되면 관리자에게 연락주세요."),JSON_UNESCAPED_UNICODE));
}

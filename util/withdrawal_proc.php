<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

include_once(G5_LIB_PATH.'/Telegram/telegram_api.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');


// 출금처리 PROCESS
$user_ip = $_SERVER['REMOTE_ADDR'];
$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

/* 메일인증 - 사용안함 */
//include_once('../lib/otphp/lib/otphp.php');
//include_once(G5_LIB_PATH.'/mailer.lib.php');
/* 코인출금시 */
/* $wallet_addr	= trim($_POST['wallet_addr']);
$select_coin    = trim($_POST['select_coin']);  */
$func				= trim($_POST['func']);
$mb_id			= trim($_POST['mb_id']);
$total_amt		= trim($_POST['total_amt']);
$select_coin 		= $_POST['select_coin'];
$fixed_amt = $_POST['fixed_amt'];
$fixed_fee = $_POST['fixed_fee'];
$coin = get_coins_price();
if($select_coin == 'hja') {
	$result = sql_fetch("SELECT current_cost, used FROM wallet_coin_price WHERE idx = '1'");
  $market_price = $total_amt * ($result['used'] == '1' ? $result['current_cost'] : 1);
} else if ($select_coin == 'etc') {
	$market_price = shift_auto($coin['usdt_krw'] / $coin['etc_krw']);
} else if ($select_coin == 'usdt') {
	$market_price = 1;
} else {
	$market_price = shift_auto($coin['eth_usdt'] / $coin['eth_krw']);
}
/* $cost = str_replace(',','',shift_auto($_POST['cost'],$curencys[2])); */

/* 원화계좌출금*/
$bank_name = trim($_POST['bank_name']);
$bank_account = trim($_POST['bank_account']);
$account_name = trim($_POST['account_name']);


$mb_name = $member['mb_name'];

// $debug = 1;

// if($debug){
// 	$mb_id = 'arcthan';
// 	$func = 'withdraw';
// 	$total_amt = 100000;
// 	$select_coin = '원';
// 	$bank_name = '농협';
// 	$account_name = '로그컴퍼니';
// 	$bank_account = '123-456789-012';
// }


// 출금 설정 
$withdrwal_setting = wallet_config('withdrawal');
$fee = $withdrwal_setting['fee'];
$min_limit = $withdrwal_setting['amt_minimum'];
$max_limit = $withdrwal_setting['amt_maximum'];
$day_limit = $withdrwal_setting['day_limit'];

// 출금가능금액 검증
$withdrwal_total = $total_withraw;

if($max_limit != 0 && ($total_withraw * $max_limit*0.01) < $withdrwal_total){
  $withdrwal_total = $total_withraw * ($max_limit*0.01);
}	

//출금기록 확인
$today_ready_sql = "SELECT * FROM {$g5['withdrawal']} WHERE mb_id = '{$mb_id}' AND date_format(create_dt,'%Y-%m-%d') = '{$now_date}' ";
$today_ready = sql_query($today_ready_sql);
$today_ready_cnt = sql_num_rows($today_ready);

if($is_debug) echo "<code>일제한: ".$day_limit .' / 오늘 : '.$today_ready_cnt."<br><br>".$today_ready_sql."/ 총 필요금액".$amt_eth_cal."</code><br><br>";

// 일 요청 제한
if($day_limit != 0 && $today_ready_cnt >= $day_limit){
	echo (json_encode(array("result" => "Failed", "code" => "0010","sql"=>"<span style='font-size:12px'>Daily withdrawal count exceeded per day $day_limit time(s)</span><br><span style='font-size:13px'>(일일 출금 횟수 초과입니다. 하루 $day_limit 회 가능)</span>"),JSON_UNESCAPED_UNICODE)); 
	return false;
}
if($is_debug) echo "<code>최소: ".$min_limit .' / 최대가능금액 : '.$withdrwal_total."  (".$max_limit."%) / 현재출금가능".$total_withraw."</code><br><br>";


// 최소금액 제한 확인
if( $min_limit != 0 && $total_amt < $min_limit ) {
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"Input correct Minimum Quantity value")));
	return false;
}

// 최대금액 제한 확인
if( $max_limit != 0 && $total_amt > $withdrwal_total ) {
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"Input correct Maximun Quantity value")));
	return false;
}

// 출금잔고 재확인 
$fund_check_sql = "SELECT sum(mb_balance - mb_shift_amt - mb_fee) as total from g5_member WHERE mb_id = '{$mb_id}' ";
$fund_check_val = sql_fetch($fund_check_sql)['total'];


if($fund_check_val < $total_amt){
	echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"Not sufficient account balance")));
	return false;
}


/* 
if($is_debug) {echo "수수료 ".$calc_fee." /   토탈 :".$total_balance_usd." /  출금요청 : ".$amt_haz_cal."<br>" ;}

$check_total_sql = "SELECT SUM(amt_total) as total_sum FROM wallet_withdrawal_request WHERE mb_id ='{$mb_id}' and coin = '{$select_coin}' AND STATUS = 0 ";
$total_row = sql_fetch($check_total_sql);

if($total_row['total_sum'] != ""){

	if($amt_eth_cal > $total_bal - $total_row['total_sum']){
		echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"Not enough balance of ".strtoupper($select_coin). " because of unconfirmed withdrawal")));
		return false;
	}

}else{

	// 잔고 초과
	if($amt_eth_cal > $total_bal){
		echo (json_encode(array("result" => "Failed", "code" => "0002","sql"=>"Not enough balance of ".strtoupper($select_coin))));
		return false;
	}

} */


// 출금주소 확인
/* if(!$wallet_addr){
	echo (json_encode(array("result" => "Failed", "code" => "0003","sql"=>"Please Input Your Etherium Wallet Address")));
	return false;
} */


$amt_total = $fixed_amt+$fixed_fee;
$Enc_wallet_addr = Encrypt($bank_account,$secret_key,$secret_iv);
//출금 처리
$proc_receipt = "insert {$g5['withdrawal']} set
mb_id ='{$mb_id}'
, addr = '{$Enc_wallet_addr}'
, bank_name = ''
, bank_account = ''
, account_name = ''
, account = '{$fund_check_val}'
, amt ={$fixed_amt}
, fee = {$fixed_fee}
, fee_rate = {$fee}
, amt_total = {$amt_total}
, coin = '{$select_coin}'
, status = '0'
, create_dt = '{$now_datetime}'
, cost = {$market_price}
, out_amt = '{$total_amt}'
, od_type = '출금요청'
, memo = ''
, ip =  '{$user_ip}' ";


if($debug){ 
	$rst = 1;
	echo "<br>".$proc_receipt."<br><br>"; 
}else{
	$rst = sql_query($proc_receipt);
}

// 회원정보업데이트
// 출금시 선차감
if($rst){

	$Enc_wallet_addr2 = Encrypt($bank_account,$mb_id,'x');

	if($select_coin == 'hja') {
		$column = "mb_wallet = '{$Enc_wallet_addr2}'";
	} else if ($select_coin == 'etc') {
		$column = "etc_my_wallet = '{$Enc_wallet_addr2}'";
	} else if ($select_coin == 'usdt') {
		$column = "usdt_my_wallet = '{$Enc_wallet_addr2}'";
	} else {
		$column = "eth_my_wallet = '{$Enc_wallet_addr2}'";
	}

	$amt_query = "UPDATE g5_member set 
	mb_shift_amt = mb_shift_amt + {$total_amt}
	, otp_key = ''
	, {$column}
	where mb_id = '{$mb_id}' ";
}
 
if($debug){ 
	$amt_result = 1;
	print_R($amt_query); 
}else{ 
	$amt_result = sql_query($amt_query);
}

// 출금알림 텔레그램 API
if(TELEGRAM_ALERT_USE){
	curl_tele_sent('[HWAJO][출금요청] '.$mb_id.'('.$mb_name.') 님의 '.shift_auto($fixed_amt, $select_coin). ' ' . $select_coin . ' 출금요청이 있습니다.');
}

if($rst && $amt_result){
	echo (json_encode(array("result" => "success", "code" => "1000")));
}else{
	echo (json_encode(array("result" => "Failed", "code" => "0001","sql"=>"처리되지 않았습니다. 문제가 지속되면 관리자에게 연락주세요."),JSON_UNESCAPED_UNICODE));
}

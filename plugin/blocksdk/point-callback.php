<?php
include_once("./_common.php");

$json_text = get_text(json_encode($_POST));
$sql = "insert into blocksdk_callback_log(`json`) values('{$json_text}')";
sql_query($sql);


$coin      = $_POST["category"];
$api_token = $_POST["api_token"];
$event 	   = $_POST["event"];
$tx_hash   = get_text($_POST["tx_hash"]);
$inaddr    = get_text($_POST["address"]);

if($api_token != Crypto::GetToken()){
	//틀린 토큰값
	echo "token !=";
	exit_response(array(
		"code" => 400,
		"data" => array()
	));
}else if($coin != 'btc' && $coin != 'bch' && $coin != 'ltc' && $coin != 'eth' && $coin != 'dash'){
	//지원하지않는 코인
	echo "coin !=";
	exit_response(array(
		"code" => 400,
		"data" => array()
	));
}else if(empty(Crypto::IsReceiveTx($tx_hash)) == false){
	//이미 처리된 거래
	echo "tx !=";
	exit_response(array(
		"code" => 400,
		"data" => array()
	));
}else if($event != "confirmed"){
	//언컨펌 거래
	echo "confirmed !=";
	exit_response(array(
		"code" => 400,
		"data" => array()
	));
}

sleep(30);

if($coin == 'btc')
	$where_sql = "mb_6='{$inaddr}'";
else if($coin == 'bch')
	$where_sql = "mb_7='{$inaddr}'";
else if($coin == 'ltc')
	$where_sql = "mb_8='{$inaddr}'";
else if($coin == 'eth')
	$where_sql = "mb_9='{$inaddr}'";
else if($coin == 'dash')
	$where_sql = "mb_10='{$inaddr}'";

$sql = "SELECT * FROM {$g5['member_table']} WHERE {$where_sql}";
$member_data = sql_fetch($sql);
if(empty($member_data) == true){
	echo "member !=";
	exit_response(array(
		"code" => 400,
		"data" => array()
	));
}

$blocksdk_conf = Crypto::GetConfig();
$receiving_address = Crypto::GetReceivingAddress();

$client = Crypto::GetClient($coin);

$rawTx = $client->getTransaction([
	"hash" => $tx_hash
]);

if($coin == 'eth'){
	if($rawTx['from'] == $inaddr){
		exit_response(array(
			"code" => 400,
			"data" => array()
		));
	}
	
	$ethinfo = Crypto::GetMemberEthAddress($inaddr);
	$balance = $client->getAddressBalance([
		"address" => $ethinfo['address']
	]);
	
	if($balance['balance'] < 0.01){
		exit_response(array(
			"code" => 400,
			"data" => array()
		));
	}

	$eth = $client->getBlockChain();
	if($eth['high_gwei'] > 100){
		$gwei = 100;
	}else if($eth['high_gwei'] < 30){
		$gwei = 30;
	}else{
		$gwei = $eth['high_gwei'];
	}
	
	$price = $gwei * 0.000000001;
	
	$tx = $client->sendToAddress([
		"from" => $ethinfo['address'],
		"to" => $receiving_address[$coin],
		"amount" => $balance['balance'] - ($price * 21000),
		"private_key" => $ethinfo['private_key'],
		"gwei" => $gwei,
		"gas_limit" => 21000
	]);
	
	// $amount = $rawTx['value'];
	$amount = $balance['balance'];
}

// else{  //토큰부분 부정확해서 일단 주석처리
// 	foreach($rawTx['vin'] as $in){
// 		if($in['addresses'][0] == $inaddr){
// 			exit_response(array(
// 				"code" => 400,
// 				"data" => array()
// 			));
// 		}
// 	}
	
// 	$amount = 0;
// 	foreach($rawTx['vout'] as $out){
// 		if($out['addresses'][0] == $inaddr){
// 			$amount += $out['value'] ;
// 		}
// 	}
	
// 	$tx = $client->sendToAddress([
// 		"wallet_id" => $data[$coin . '_wallet_id'],
// 		"seed_wif" => Crypto::GetSeefWif($coin),
// 		"address" => $receiving_address[$coin],
// 		"amount" => $amount,
// 		"subtractfeefromamount" => 'true'
// 	]);
// }

$point = Crypto::GetCoinToPrice($coin, $amount);
insert_point($member_data['mb_id'], $point, $coin.'-'.$tx_hash, '@passive', 'admin', $member_data['mb_id'].'-'.uniqid(''));

$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

Crypto::InsertReceiveTx([
	"mb_no" => $member_data['mb_no'],
	"tx_hash" => $tx_hash,
	"symbol" => $coin,
	"address" => $inaddr,
	"value" => $amount,
	"create_at" => $now_datetime
]);

$sql = "INSERT INTO wallet_deposit_request(mb_id, txhash, create_dt,create_d,status,coin,in_amt)";

if(!$tx['error']['message']){
	$update_member_asset_sql = "UPDATE g5_member set mb_eth_point = mb_eth_point + {$amount} WHERE mb_id = '{$member_data['mb_id']}' ";
	sql_query($update_member_asset_sql);

	$sql .= " VALUES('{$member_data['mb_id']}','$tx_hash','$now_datetime','$now_date','$event','$coin', '$amount')";	
}else{
	$sql .= " VALUES('{$member_data['mb_id']}','$tx_hash','$now_datetime','$now_date','unconfirmed (수수료 부족)','$coin', '$amount')";	
}

sql_query($sql);
?>
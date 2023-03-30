<?php
header("Content-Type:application/json");

$sub_menu = '100610';
include_once('./_common.php');

include_once(G5_LIB_PATH.'/blocksdk.lib.php');
include_once(G5_LIB_PATH.'/crypto.lib.php');


auth_check($auth[$sub_menu], "w");

if(empty($_POST["blocksdk_token"])){
	echo_json(array(
		"error" => array(
			"message" => "토큰값을 입력해주시길 바랍니다"
		)
	));
}else if(strpos($_POST['blocksdk_token'],'*')){
	echo_json(array(
		"error" => array(
			"message" => "잘못된 토큰입니다"
		)
	));
}

	
$blocksdk_token = $_POST["blocksdk_token"];
$blocksdk_conf  = Crypto::GetConfig();

if(empty($blocksdk_conf['blocksdk_token']) == false){
	$update_set = "
		mb_6 = '',
		mb_7 = '',
		mb_8 = '',
		mb_10 = ''
	";
	if($blocksdk_conf['blocksdk_token'] != Crypto::Encrypt($blocksdk_token)){
		$update_set .= ",mb_9=''";
	}
	$sql = "
		UPDATE
		{$g5['member_table']} SET
		{$update_set}
		WHERE 1
	";
	sql_query($sql);
}

$blockSDK   = new BlockSDK($blocksdk_token);
$dashClient = $blockSDK->createDash();
$btcClient  = $blockSDK->createBitcoin();
$ltcClient  = $blockSDK->createLitecoin();
$bchClient  = $blockSDK->createBitcoinCash();

$btcwallet = $btcClient->createWallet([
		"name" => "master"
]);
$dashwallet = $dashClient->createWallet([
		"name" => "master"
]);
$bchwallet = $bchClient->createWallet([
		"name" => "master"
]);
$ltcwallet = $ltcClient->createWallet([
		"name" => "master"
]);


/* 
	시드 키는 분실되면 절대로 찾을 수 없기 때문에 로그로 남겨놓습니다.
*/
$sql = "
	INSERT INTO
	blocksdk_seed_log(btc_seed_wif,bch_seed_wif,ltc_seed_wif,dash_seed_wif)
	values(
		'" . Crypto::Encrypt($btcwallet['seed_wif']) . "',
		'" . Crypto::Encrypt($bchwallet['seed_wif']) . "',
		'" . Crypto::Encrypt($ltcwallet['seed_wif']) . "',
		'" . Crypto::Encrypt($dashwallet['seed_wif']) . "'
	)
";
sql_query($sql);

$sql = "
	UPDATE 
	blocksdk_conf SET 
	blocksdk_token = '" . Crypto::Encrypt($blocksdk_token) . "',
	btc_wallet_id  = '{$btcwallet['id']}',
	btc_seed_wif   = '" . Crypto::Encrypt($btcwallet['seed_wif']) . "',
	bch_wallet_id  = '{$bchwallet['id']}',
	bch_seed_wif   = '" . Crypto::Encrypt($bchwallet['seed_wif']) . "',
	ltc_wallet_id  = '{$ltcwallet['id']}',
	ltc_seed_wif   = '" . Crypto::Encrypt($ltcwallet['seed_wif']) . "',
	dash_wallet_id = '{$dashwallet['id']}',
	dash_seed_wif  = '" . Crypto::Encrypt($dashwallet['seed_wif']) . "' 
	WHERE 1
";

sql_query($sql);
		

echo_json(array(
	'btc'  => $btcwallet['seed_wif'],
	"bch"  => $bchwallet['seed_wif'],
	"ltc"  => $ltcwallet['seed_wif'],
	"dash" => $dashwallet['seed_wif']
));
?>

<?php
include_once('./_common.php');

include_once(G5_LIB_PATH . '/blocksdk.lib.php');
include_once(G5_LIB_PATH.'/crypto.lib.php');

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

/*
	mb_6 비트코인
	mb_7 비트코인캐시
	mb_8 라이트코인
	mb_9 이더리움
	mb_10 대시
*/

$callback = G5_URL . "/plugin/blocksdk/point-callback.php";
$blocksdk_conf = Crypto::GetConfig();


if(empty($member['mb_6'])==true && $blocksdk_conf['de_btc_use'] == 1){
  $wallet_address = Crypto::GetClient("btc")->createWalletAddress([
    "wallet_id" => $blocksdk_conf['btc_wallet_id'],
    "seed_wif" => Crypto::Decrypt($blocksdk_conf['btc_seed_wif'])
  ]);
  
  Crypto::CreateWebHook($callback,"btc",$wallet_address['address']);
  
  $update_sql = "mb_6='{$wallet_address['address']}'";
  
  $member['mb_6'] = $wallet_address['address'];
}

if(empty($member['mb_7']) == true && $blocksdk_conf['de_bch_use'] == 1){
  $wallet_address = Crypto::GetClient("bch")->createWalletAddress([
    "wallet_id" => $blocksdk_conf['bch_wallet_id'],
    "seed_wif" => Crypto::Decrypt($blocksdk_conf['bch_seed_wif'])
  ]);
  
  Crypto::CreateWebHook($callback,"bch",$wallet_address['address']);
  
  $update_sql .= empty($update_sql) ? "" : ","; 
  $update_sql .= "mb_7='{$wallet_address['address']}'";
  
  $member['mb_7'] = $wallet_address['address'];
}

if(empty($member['mb_8'])==true && $blocksdk_conf['de_ltc_use'] == 1){
  $wallet_address = Crypto::GetClient("ltc")->createWalletAddress([
    "wallet_id" => $blocksdk_conf['ltc_wallet_id'],
    "seed_wif" =>  Crypto::Decrypt($blocksdk_conf['ltc_seed_wif'])
  ]);
  
  Crypto::CreateWebHook($callback,"ltc",$wallet_address['address']);
  
  $update_sql .= empty($update_sql) ? "" : "," ; 
  $update_sql .= "mb_8='{$wallet_address['address']}'";
  $member['mb_8'] = $wallet_address['address'];

}


if(empty($member['mb_9'])==true && $blocksdk_conf['de_eth_use'] == 1){
  echo "이더리움 지갑 생성 ";
  $address = Crypto::GetClient("eth")->createAddress([
    "name" => "member_no_".$member['mb_no']
  ]);
  
  Crypto::CreateWebHook($callback,"eth",$address['address']);
  
  $update_sql .= empty($update_sql) ? "" : ","; 
  $update_sql .= "mb_9='{$address['address']}'";
  $member['mb_9'] = $address['address'];
  
  $sql = "
  insert into 
  blocksdk_member_eth_addresses (id, address, private_key) 
  values ('{$address['id']}', '{$address['address']}','{$address['private_key']}')
  ";
  sql_fetch($sql);
}

if(empty($member['mb_10'])==true && $blocksdk_conf['de_dash_use'] == 1){
  $wallet_address = Crypto::GetClient("dash")->createWalletAddress([
    "wallet_id" => $blocksdk_conf['dash_wallet_id'],
    "seed_wif" => Crypto::Decrypt($blocksdk_conf['dash_seed_wif'])
  ]);
 
  Crypto::CreateWebHook($callback,"dash",$wallet_address['address']);
  
  $update_sql .= empty($update_sql) ? "" : ","; 
  $update_sql .= "mb_10='{$wallet_address['address']}'";
  $member['mb_10'] = $wallet_address['address'];
}

if(empty($update_sql) == false){
	$sql = "UPDATE {$g5['member_table']} SET {$update_sql} WHERE mb_no={$member['mb_no']}";
	sql_query($sql);
}


include_once(G5_PATH.'/head.sub.php');

include_once($member_skin_path.'/point2.skin.php');

include_once(G5_PATH.'/tail.sub.php');
?>
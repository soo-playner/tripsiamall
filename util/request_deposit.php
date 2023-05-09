<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/Telegram/telegram_api.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

// 입금처리 PROCESS
// $debug = 1;

/*현재시간*/
$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

$mb_id = $_POST['mb_id'];
$txhash = $_POST['hash'];
$coin = $_POST['coin'];
$d_price = $_POST['d_price'];

/*기존건 확인*/
$pre_result = sql_fetch("SELECT count(*) as cnt from wallet_deposit_request 
WHERE mb_id ='{$mb_id}' AND create_d = '{$now_date}' AND in_amt = {$d_price} ");

if($pre_result['cnt'] < 1){
  $get_coins_price = get_coins_price();
  if($coin == 'etc') {
    $usdt = $get_coins_price['usdt_etc'];
  } else if ($coin == 'hwajo') {
    $usdt = 1;
  } else if ($coin == 'eth') {
    $usdt = $get_coins_price['usdt_eth'];
  } else {
    $usdt = 1;
  }
  $point = $usdt * $d_price;
  $sql = "INSERT INTO wallet_deposit_request(mb_id, txhash, create_dt,create_d,status,coin,cost,amt,in_amt) 
  VALUES('$mb_id','$txhash','$now_datetime','$now_date',0,'$coin', {$usdt},{$d_price},{$point})";
  
  if($debug){
    print_R($sql);
    $result = 1;
  }else{
    $result = sql_query($sql);
  }

  // 입금알림 텔레그램 API
  if(TELEGRAM_ALERT_USE){
    curl_tele_sent('[HWAJO][입금요청] '.$mb_id.'('.$txhash.') 님의 '.Number_format($d_price).'입금요청이 있습니다.');
  }
  
  if($result){
    echo json_encode(array("response"=>"OK", "data"=>'complete'));
  }else{
    echo json_encode(array("response"=>"FAIL", "data"=>"<p>ERROR<br>Please try later</p>"));
  }
}else{
  echo json_encode(array("response"=>"FAIL", "data"=>"이미 해당 요청이 처리진행중입니다."),JSON_UNESCAPED_UNICODE);
}


?>

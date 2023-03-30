<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/Telegram/telegram_api.php');

// 입금처리 PROCESS
// $debug = 1;

/*현재시간*/
$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

$mb_id = 'admin';
$txhash = '관리자 입금 : zeta';
$coin = $_POST['coin'];
$d_price = '5830000';


  // 입금알림 텔레그램 API
  curl_tele_sent('[ZETABYTE][입금요청] '.$mb_id.'('.$txhash.') 님의 '.Number_format($d_price).'입금요청이 있습니다.');


?>

<?
if (!defined('_GNUBOARD_')) exit;
define('LIVE_MODE',true);
define('CONFIG_TITLE','ZETABYTE');
define('CONFIG_SUB_TITLE','ZETABYTE');

// 메일설정
define('CONFIG_MAIL_ACCOUNT','wizclass.inc');
// define('CONFIG_MAIL_PW','willsoft0780!@');
define('CONFIG_MAIL_PW','izmvwaprbjxgftme');
define('CONFIG_MAIL_ADDR','wizclass.inc@gmail.com');

// 기준통화설정
define('ASSETS_CURENCY','ETH');
define('BALANCE_CURENCY','USDT');
define('WITHDRAW_CURENCY','TMP');
define('KRW_CURENCY','KRW');

define('ASSETS_NUMBER_POINT',8); // 입금 단위
define('BONUS_NUMBER_POINT',8); // 수당계산,정산기준단위
define('COIN_NUMBER_POINT',8); // 코인 단위
define('KRW_NUMBER_POINT',0);

$minings = ['원','eth','usdt','fil'];
$mining_hash = ['mh/s','mh/s'];

$before_mining_coin = 1;
$before_mining_target = 'mb_mining_'.$before_mining_coin;
$before_mining_amt_target = $before_mining_target.'_amt';

$now_mining_coin = 2;
$mining_target = 'mb_mining_'.$now_mining_coin;
$mining_amt_target = $mining_target.'_amt';

$secret_key = "wizclass0780";
$version_date = '2022-09-20';

// 회사지갑 설정
define('ETH_ADDRESS','123123123');

// 텔레그램 설정
define('TELEGRAM_ALERT_USE',true);

$log_ip = '61.74.205.8';
$log_pw = "*CB664B173EFE2124B8A144F5FE88D06D07B1EAB1";


// False 설정시 현금사용
define('USE_WALLET',FALSE);


//영카트 로그인체크 주소
if(strpos($_SERVER['HTTP_HOST'],"localhost") !== false){
    $port_number = "";
    define('SHOP_URL',"http://localhost:{$port_number}/bbs/login_check.php");
}else{
    define('SHOP_URL',"http://khanshop.willsoft.kr/bbs/login_check.php");
}

?>

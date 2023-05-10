<?
if (!defined('_GNUBOARD_')) exit;
define('LIVE_MODE',false);
define('CONFIG_TITLE','Hwajo Global asset');
define('CONFIG_SUB_TITLE','Hwajo Global asset');


// 메일설정
define('CONFIG_MAIL_ACCOUNT','hwajoasset');
define('CONFIG_MAIL_PW','fqendcfluafulcyp');
define('CONFIG_MAIL_ADDR','hwajoasset@gmail.com');


// 이더사용 및 회사지갑 설정
// False 설정시 현금사용
define('USE_WALLET',TRUE);
define('ETH_ADDRESS','0x00000005');

define('Mining_solution', false);

// 기준통화설정
$curencys = ['eth','usdt','krw','hja','etc'];

define('ASSETS_NUMBER_POINT',8); // 입금 단위
define('BONUS_NUMBER_POINT',2); // 수당계산,정산기준단위
define('COIN_NUMBER_POINT',8); // 코인 단위
define('KRW_NUMBER_POINT',0);

$minings = ['원','usdt','usdt','fil'];
$mining_hash = ['usdt'];

$before_mining_coin = 1;
$before_mining_target = 'mb_mining_'.$before_mining_coin;
$before_mining_amt_target = $before_mining_target.'_amt';

$now_mining_coin = 2;
$mining_target = 'mb_mining_'.$now_mining_coin;
$mining_amt_target = $mining_target.'_amt';

$secret_key = "wizclass0780";
$version_date = '2022-09-20';


// 텔레그램 설정
define('TELEGRAM_ALERT_USE',false);

// 휴대폰인증 테스트모드
define('HANDLE_STATES', 'test');

$log_ip = '61.74.205.8';
$log_pw = "*CB664B173EFE2124B8A144F5FE88D06D07B1EAB1";





//영카트 로그인체크 주소
if(strpos($_SERVER['HTTP_HOST'],"localhost") !== false){
    $port_number = "";
    define('SHOP_URL',"http://localhost:{$port_number}/bbs/login_check.php");
}else{
    define('SHOP_URL',"http://khanshop.willsoft.kr/bbs/login_check.php");
}

?>

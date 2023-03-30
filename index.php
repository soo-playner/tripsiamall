<?
include_once("./_common.php");
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.php');


/*서비스점검*/
$sql = " select * from maintenance";
$nw = sql_fetch($sql);

if($nw['nw_use'] == 'Y'){
	$maintenance = 'Y';
}else{
	$maintenance = 'N';
}
/*
if($member['mb_wallet'] == ''){
	include_once(G5_PATH.'/wallet_create.php');
}
*/
$domain = $_SERVER["HTTP_HOST"];
// print_R($domain);


if($is_member){
	if(defined('G5_THEME_PATH')) {
		require_once(G5_THEME_PATH.'/index.php');
	}
}else{
	//Header("Location:./bbs/login_pw.php");
	if($_GET['direct']){
		set_session('bypass', 'ok');
		Header("Location:/bbs/login_pw.php");
	}else{
		require_once(G5_THEME_PATH.'/intro.php');
	}
	//require_once(G5_THEME_PATH.'/intro.php');
}

include_once(G5_PATH.'/tail.php');
?>


<?php
$sub_menu = '100610';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");
// check_admin_token();


$btc    = (int)$_POST["btc"];
$bch    = (int)$_POST["bch"];
$ltc    = (int)$_POST["ltc"];
$eth    = (int)$_POST["eth"];
$dash   = (int)$_POST["dash"];
$monero = (int)$_POST["xmr"];

$sql = "
	UPDATE 
	blocksdk_conf SET 
	de_btc_use  = {$btc},
	de_bch_use  = {$bch},
	de_ltc_use  = {$ltc},
	de_eth_use  = {$eth},
	de_dash_use = {$dash}
	WHERE 1
";
sql_query($sql);

goto_url('/adm/blocksdk/cointx.php');
?>

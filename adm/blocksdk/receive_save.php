<?php
$sub_menu = '100610';
include_once('./_common.php');

include_once(G5_LIB_PATH.'/blocksdk.lib.php');

auth_check($auth[$sub_menu], "w");
// check_admin_token();

$btcaddr  = get_text($_POST["btc-address"]);
$bchaddr  = get_text($_POST["bch-address"]);
$ltcaddr  = get_text($_POST["ltc-address"]);
$ethaddr  = get_text($_POST["eth-address"]);
$dashaddr = get_text($_POST["dash-address"]);
$sql = "
	UPDATE 
	`blocksdk_receiving_address` SET 
	`btc`  = '{$btcaddr}', 
	`bch`  = '{$bchaddr}', 
	`eth`  = '{$ethaddr}', 
	`ltc`  = '{$ltcaddr}', 
	`dash` = '{$dashaddr}'
	WHERE 1
";
sql_query($sql);



goto_url('/adm/blocksdk/cointx.php');
?>

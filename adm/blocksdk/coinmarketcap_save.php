<?php
$sub_menu = '100610';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");
// check_admin_token();

if(empty($_POST["coinmarketcap_token"]) == true){
	goto_url('/adm/blocksdk/cointx.php');
}

$token = get_text($_POST["coinmarketcap_token"]);

$sql = "
	UPDATE 
	blocksdk_conf  SET 
	coinmarketcap_token = '{$token}' 
	WHERE 1
";
sql_fetch($sql);

alert('저장되었습니다');
goto_url('/adm/blocksdk/cointx.php');
?>

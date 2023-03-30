<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

print_R($_POST['data']);

$max_package = "<span class='badge t_white color".max_item_level_array($mb_id,'number')."'>".max_item_level_array($mb_id,'name')."</span>";
// print_R($max_package);
echo (json_encode(array("result" => $max_package)));
?>
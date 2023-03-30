<?php
include_once('./_common.php');


for($i = 0 ; $i < 3; $i ++){

    $idx = $_POST['idx'][$i];
    $manual_use = $_POST['manual_use'][$i];
    $manual_cost = $_POST['manual_cost'][$i];

    $update_coin_set = 
    "update {$g5['coin_price']} set 
    manual_use = '{$manual_use}',
    manual_cost = '{$manual_cost}'
    where idx = $idx ;";

    sql_query($update_coin_set);
}
alert('변경되었습니다.');
goto_url('./config_price.php');
?>
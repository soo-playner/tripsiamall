<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');
$pin = $_POST['pin'];

$check_pin = sql_fetch("SELECT mb_id, otp_key from g5_member WHERE mb_id = '{$member['mb_id']}'");

$debug_mode = LIVE_MODE;

if($debug_mode){
    $input_value = Encrypt($pin);
}else{
    $input_value = $pin;
}

if($input_value == $check_pin['otp_key']){
    echo json_encode(array("result" => "success"));
}else{
    echo json_encode(array("result" => "failed"));
}

?>
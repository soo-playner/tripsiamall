<?php
include_once('./_common.php');

// print_R($_POST);

if($_POST['fcm_api_key']){
    $update_api_key = "UPDATE g5_config set cf_fcm_api_key = '{$_POST['fcm_api_key']}' ";
    $update_result = sql_query($update_api_key);
}

if($update_result){
    echo json_encode(array("code"=>"0000"));
}

?>
<?php
include_once('./_common.php');


$mb_hp = isset($_POST['mb_hp']) ? hyphen_hp_number(str_replace('-','',sql_escape($_POST['mb_hp']))) : false;
$_rand_num = sql_escape($_POST['rand_num']);
$rand_num = isset($_POST['rand_num']) ? base64_decode($_rand_num) : false;

$code = "300";
$msg = "잘못된 접근입니다.";
if($mb_hp && $rand_num){
    
    if(HANDLE_STATES == "real"){
        include_once(G5_THEME_PATH."/_include/sms.php");
    }
    $code = "200";
    $msg = "인증번호를 문자로 전송하였습니다.";
    $_SESSION['auth_num'] = $_rand_num;

}

echo json_encode(array("code"=>$code, "msg"=>$msg));

?>
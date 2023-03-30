<?php
    include_once('./_common.php');

    $member_idx = isset($_POST['member_idx']) ? $_POST['member_idx'] : false;
    $token = isset($_POST['token']) ? $_POST['token'] : false;

    $code = "300";
    $msg = "failed";

    if($member_idx && $token){
        $sql = "update {$g5['member_table']} set fcm_token = '{$token}' where mb_no = {$member_idx}";
        $result = sql_query($sql);

        if($result){
            $code = "200";
            $msg = "success";
        }  
    }
    
    echo json_encode(array("code"=>$code, "msg"=>$msg));
?>
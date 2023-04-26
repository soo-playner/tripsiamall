<?php 
include_once("./_common.php");
include_once(G5_THEME_PATH.'/_include/wallet.php');
include_once(G5_LIB_PATH."/register.lib.php");


$mb_id = trim($_POST['mb_id']);

$sql = "SELECT * FROM g5_member_del WHERE mb_id = '".$mb_id."'";
$target_member = sql_fetch($sql);


if($target_member && $target_member['mb_name'] !== '슈퍼관리자') {

    $move_sql = "INSERT INTO g5_member (select * from g5_member_del where mb_id = '".$mb_id."')";
    sql_query($move_sql);
    
    $date_update = "UPDATE g5_member set mb_leave_date = '' where mb_id = '".$mb_id."'";
    sql_query($date_update);
    
    $del_sql = "DELETE FROM g5_member_del WHERE mb_id = '{$mb_id}'  ";
    sql_query($del_sql);

    echo json_encode(array("result" => "success", "msg" => "해당 회원이 복구되었습니다."));

} else echo json_encode(array("result" => "fail", "msg" => "오류가 발생했습니다."));


?>
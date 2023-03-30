<?php
$sub_menu = "200600";
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '회원추천관계 검사';
$g5['income_table'] = "member_table_fixtest";

include_once('./admin.head.php');
?>

<?
    $all_member = sql_query("SELECT mb_id from g5_member ");
    while($row  = sql_fetch_array($all_member)){

        $mb_id = $row['mb_id'];
        $sponsor = return_up_manager(trim($row['mb_id']));
        echo "<br>".$mb_id.' :: sponsor - '.$sponsor;
        
        $update_sponsor = "UPDATE g5_member set mb_sponsor = '{$sponsor}' WHERE mb_id =  '{$mb_id}' ";
        $update_result = sql_query($update_sponsor);
    }
?>


<?php

include_once('./admin.tail.php');

/* if($update_result){
    alert('회원 스폰서 정보가 갱신되었습니다.','./member_list.php');
} */

?>

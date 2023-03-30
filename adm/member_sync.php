<?php
include_once('./_common.php');

//WHERE mb_id = 'ksy2490'
//select A.mb_no, B.mb_no as recommend_no, depth+1 as mb_depth from (SELECT mb_no, mb_recommend FROM g5_member WHERE mb_id = 'ksy2490') A, g5_member B where A.mb_recommend = B.mb_id ORDER By mb_no ASC

$mb_recommend = $_GET['mb_recommend'];

$pre_sql = "SELECT mb_no, depth+1 as mb_depth FROM g5_member WHERE mb_id ='".$mb_recommend."'";
$result  = sql_fetch($pre_sql);

print_r($result['mb_depth']);
?>



<?
include_once("./_common.php");
include_once(G5_PATH."/util/limit_reset.php");

$now_date_time = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

$reset_sql = "UPDATE g5_member set mb_index = 0";
$result = sql_query($reset_sql);

?>
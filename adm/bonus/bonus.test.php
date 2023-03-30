<?
include_once('./_common.php');

$today = date('Y-m-d');
$bonus_day = $_REQUEST['to_date'];

/*
$sql = "select count(*) as count from soodang_pay";
$count = sql_fetch($sql);
$auto_count = $count['count'];
*/
$file_ext = explode(".",basename($_SERVER['PHP_SELF']));
echo $file_ext[1];


?>

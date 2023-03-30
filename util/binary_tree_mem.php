<?php
include_once('./_common.php');

header('Content-Type: application/json');

// $sql = "select * from g5_member as m where mb_recommend='{$_GET['mb_id']}' and mb_level >= 2 and mb_brecommend='' order by mb_datetime desc";
// $sql = "select * from g5_member as m where mb_recommend='{$_POST['mb_id']}' and grade > 0 and mb_brecommend='' order by mb_datetime desc";
$sql = "select * from g5_member as m where mb_recommend='{$_POST['mb_id']}' and mb_brecommend='' AND mb_level > 0 order by mb_datetime desc";

$sth = sql_query($sql);

$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
	$rows[] = $r;
}
print json_encode($rows);

?>

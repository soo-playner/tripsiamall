<?php
include_once('./_common.php');

/*
$sql_count_reset = "ALTER TABLE `{$g5['member_table']}` AUTO_INCREMENT=1;"; 
$sql_count_reset .= "SET @COUNT = 0 ;";
$sql_count_reset .= "UPDATE `{$g5['member_table']}` SET no = @COUNT:=@COUNT+1;";
sql_query($sql_count_reset);
*/

$count_sql = "select mb_no from `{$g5['member_table']}` order by mb_no desc";
$count = sql_fetch($count_sql);
$auto_count = $count['mb_no'];

	echo "AUTO_COUNT :: ".$auto_count;
	echo "<br>";

$sql_auto_count = "ALTER TABLE `{$g5['member_table']}` AUTO_INCREMENT={$auto_count};"; 
sql_query($sql_auto_count);


$i = 1;
do {
	$depth_sql = "select A.mb_no, A.mb_id, B.mb_no as recommend_no, B.depth+1 as mb_depth 
	from (SELECT mb_no,mb_id, mb_recommend, depth FROM g5_member WHERE mb_no = $i ) A,
	 g5_member B where A.mb_recommend = B.mb_id";
	$depth_result = sql_fetch($depth_sql);
	print_r($depth_result['mb_no']."   _   ".$depth_result['mb_id']." ==  ".$depth_result['recommend_no']."/".$depth_result['mb_depth']."<br>");
	
	$sql_update = "update g5_member set depth = '".$depth_result['mb_depth']."', mb_recommend_no = {$depth_result['recommend_no']} where mb_id ='".$depth_result['mb_id']."'";
	sql_query($sql_update);

	echo $sql_update;
	echo "<br><br>";

	$i++;
} while ($i < $auto_count+1 );

goto_url('/adm/member_tree.php');
?>



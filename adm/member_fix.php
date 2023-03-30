<?php
include_once('./_common.php');

$sql = "SELECT A.mb_id, B.mb_id as recommend_id, B.mb_no FROM ( SELECT mb_no, mb_id, mb_recommend FROM g5_member) A, g5_member B  WHERE B.mb_id = A.mb_recommend ORDER BY mb_no ASC";
$result  = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
	
	//print_r($row['mb_id']." / ".$row['mb_no']."<br>");
	
	$sql_update = "update g5_member set mb_recommend_no = '".$row['mb_no']."' where mb_id ='".$row['mb_id']."'";
	
	$result_update  = sql_query($sql_update);
	//print_r($sql_update);
	
}

?>



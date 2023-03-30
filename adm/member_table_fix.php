<?php
include_once('./_common.php');


$this_id = $_GET['mb_no'];

if($this_id){
	
	/*recomend_no*/
	$sql = "SELECT A.mb_id, B.mb_id as recommend_id, B.mb_no FROM ( SELECT mb_no, mb_id, mb_recommend FROM g5_member WHERE mb_no ='".$this_id."') A, g5_member B  WHERE B.mb_id = A.mb_recommend";
	$result  = sql_query($sql);


	for ($i=0; $row=sql_fetch_array($result); $i++) {
	
	//print_r($row['mb_id']." / ".$row['mb_no']."<br>");
	
	$sql_update = "update g5_member set mb_recommend_no = '".$row['mb_no']."' where mb_no ='".$this_id."'";
	
	$result_update  = sql_query($sql_update);
	}


	/*depth*/
	$sql = "select A.mb_no,A.mb_id, B.mb_no as recommend_no, depth+1 as mb_depth from (SELECT mb_no,mb_id, mb_recommend FROM g5_member WHERE mb_no ='".$this_id."' ) A, g5_member B where A.mb_recommend = B.mb_id ORDER By mb_no ASC";
	
	$result  = sql_query($sql);

	for ($i=0; $row=sql_fetch_array($result); $i++) {
		
			$sql_update = "update g5_member set depth = '".$row['mb_depth']."' where mb_no ='".$this_id."'";
		
		$result_update  = sql_query($sql_update);
		

	print_r($sql_update);
	
	}
}else{
	echo "추천인 관계 갱신 ";

	$sql = "SELECT A.mb_id, B.mb_id as recommend_id, B.mb_no FROM ( SELECT mb_no, mb_id, mb_recommend FROM g5_member) A, g5_member B  WHERE B.mb_id = A.mb_recommend ORDER BY mb_no ASC";
	$result  = sql_query($sql);

	for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		print_r($row['mb_id']." / ".$row['mb_no']."<br><br>");
		
		$sql_update = "update g5_member set mb_recommend_no = '".$row['mb_no']."' where mb_id ='".$row['mb_id']."'";
		
		$result_update  = sql_query($sql_update);
		print_r($sql_update);
		
	}
}
?>



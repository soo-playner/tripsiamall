<?php
include_once('./_common.php');

//WHERE mb_id = 'ksy2490'
//select A.mb_no, B.mb_no as recommend_no, depth+1 as mb_depth from (SELECT mb_no, mb_recommend FROM g5_member WHERE mb_id = 'ksy2490') A, g5_member B where A.mb_recommend = B.mb_id ORDER By mb_no ASC

$this_id = $_GET['mb_no'];

if($this_id){
		$sql = "select A.mb_no,A.mb_id, B.mb_no as recommend_no, depth+1 as mb_depth from (SELECT mb_no,mb_id, mb_recommend FROM g5_member_copy WHERE mb_no ='".$this_id."' ) A, g5_member_copy B where A.mb_recommend = B.mb_id ORDER By mb_no ASC";

	print_R($sql."<br>");

	$result  = sql_query($sql);

	for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		print_r($row['mb_id']." / ".$row['mb_depth']."<br>");
		
		
			$sql_update = "update g5_member_copy set depth = '".$row['mb_depth']."' where mb_no ='".$this_id."'";
		
		$result_update  = sql_query($sql_update);
		print_r($sql_update);
		
	}

}else{
	
	$sql = "select A.mb_no,A.mb_id,A.mb_recommend, B.mb_no as recommend_no, depth+1 as mb_depth,(SELECT COUNT(mb_id) FROM g5_member WHERE mb_recommend = A.mb_id) AS direct_cnt from (SELECT mb_no,mb_id, mb_recommend FROM g5_member ) A, g5_member B where A.mb_recommend = B.mb_id ORDER By mb_no ASC";

	// print_R($sql."<br>");

	$result  = sql_query($sql);

	for ($i=0; $row=sql_fetch_array($result); $i++) {

			// print_r("<br> <strong>".$row['mb_id']."</strong> / 추천인 : ".$row['mb_recommend']." / 거리 :".$row['mb_depth']."<br>");
			
		$sql_update = "update g5_member set depth = {$row['mb_depth']}, mb_habu_sum = {$row['direct_cnt']} where mb_id ='{$row['mb_id']}'";
		$result_update  = sql_query($sql_update);

		// print_r($sql_update);
		// echo "<br>";
	}
	alert('회원 추천관계/직추천 정보가 갱신되었습니다.');
	goto_url("/adm/member_list.php");
}
?>



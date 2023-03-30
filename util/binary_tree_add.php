<?php
include_once('./_common.php');

header('Content-Type: application/json');

function get_brecommend_down($mb_id, $m_id, $ca_id) 
{ 
	$max_num    = 800;
	$brd_num  = 0;

	//$class_name     = "g5_member_bclass";
	//$recommend_name = "mb_brecommend";

	if ($mb_id==$m_id){
		$sql = "insert into g5_member_bclass set mb_id='".$mb_id."',c_id='".$m_id."',c_class='".$ca_id."'";
		sql_query($sql);
	}

	$result = sql_query(" select * from g5_member 
		where mb_brecommend ='{$m_id}' and length(mb_id) > 0 and mb_leave_date = '' 
		order by mb_lr asc, mb_brecommend_type asc, mb_datetime desc
	");
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$brd_num++;
		$len = strlen($ca_id);
		//if ($brd_num>$max_num)  break;
		if ($row['mb_id']=="admin") break;
		if ($len == 200)	break;
		$len2  = $len + 1;
		$subid = base_convert(($i+1), 36, 10);
		$subid += 36;
		if ($subid >= 36 * 36)
		{
			$subid = "  ";
		}
		$subid = base_convert($subid, 10, 36);
		$subid = substr("00" . $subid, -2);
		$subid = $ca_id . $subid;

		if ($i==0 && !$row['mb_lr']){
			$sql = "update g5_member set mb_lr=1, mb_brecommend_type='L' where mb_id='".$row['mb_id']."'";
			sql_query($sql);
		}else if ($i==1 && !$row['mb_lr']){
			$sql = "update g5_member set mb_lr=2,mb_brecommend_type='R' where mb_id='".$row['mb_id']."'";
			sql_query($sql);
		}

//		echo $brd_num.".".$subid." = ".$row['mb_id']."<br>\n";
		$sql = "insert into g5_member_bclass set mb_id='".$mb_id."',c_id='".$row['mb_id']."',c_class='".$subid."'";
		sql_query($sql);
		//echo $sql."<br>\n";

		$sql  = "select count(mb_no) as cnt from g5_member where mb_brecommend = '".$row['mb_id']."' and length(mb_id)>0 and mb_leave_date = ''";	
		$row2 = sql_fetch($sql); 

		if ($row2['cnt']){
			get_brecommend_down($mb_id,$row['mb_id'],$subid);
		}
	}
}

$now_date = date("Y-m-d H:i:s",time()); 

// binary_parent - 바이너리 트리에서 직속 부모 
// selected - 선택한 사용자. 
$binary_parent= $_POST['set_id'];
$set_type= $_POST['set_type'];
$selected= $_POST['recommend_id'];

if("" == trim($binary_parent) || "" == trim($set_type) || "" == trim($selected) ){
	print json_encode(array("set_id"=>$binary_parent, "set_type"=>$set_type, "recommend_id"=>$selected));
	exit();
}

if ($set_type=="R"){
	$mb_lr=2;
}else{
	$mb_lr=1;
}

sql_query("update g5_shop_order set od_receipt_time='".$now_date."' where mb_id = '".$selected."' and od_status ='입금'");

sql_query("update g5_member set mb_brecommend='".$binary_parent."',mb_lr={$mb_lr}, mb_brecommend_type='".$set_type."',mb_bre_time = '".$now_date."' where mb_id='".$selected."'");

// calc_sale($selected, date('Y-m-d'));

$ret = sql_fetch("select * from g5_member_bclass where mb_id='{$member['mb_id']}' and c_id = '{$binary_parent}'"); 
if($ret){
	$c_class = $ret['c_class'].'1'.$mb_lr;
	sql_query("insert into g5_member_bclass set mb_id='{$member['mb_id']}', c_id='{$selected}', c_class='{$c_class}' ");
}else{
	// 트리 데이터를 root 부터 지우고 다시 생성 - 오래걸림
	sql_query("delete from g5_member_bclass where mb_id='".$member['mb_id']."'"); 
	get_brecommend_down($member['mb_id'],$member['mb_id'],'11'); 
}

// 자식의 갯수를 업데이트하는 로직
// $result = sql_query("select * from g5_member_bclass where mb_id='{$member['mb_id']}' order by c_class asc");
// for ($i=0; $row=sql_fetch_array($result); $i++) { 
// 	$row2 = sql_fetch("select count(c_class) as cnt from g5_member_bclass where  mb_id='".$member['mb_id']."' and c_class like '".$row['c_class']."%'");
// 	$sql = "update g5_member set mb_b_child='".$row2['cnt']."' where mb_id='".$row['c_id']."'";
// 	sql_query($sql);
// } // 오래걸림. 

$findId = $selected;
while(true){
	$srow = sql_fetch("select mb_id, mb_brecommend from g5_member where mb_id = '{$findId}' ");
	if($srow){
		sql_query("update g5_member set mb_b_child = mb_b_child + 1 where mb_id = '{$srow['mb_id']}'");
		$findId = $srow['mb_brecommend'];
	}else{
		break;
	}
}

print json_encode(array("result"=>"success"));

?>



<?php
$sub_menu = "600300";
include_once('./_common.php');


if(!isset($member['mb_class'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_class` varchar(60) NOT NULL DEFAULT '' AFTER `mb_10` ", false);
}

$max_num = 800;
$get_id  = "admin";
$rd_num  = 0;
$ru_num  = 0;
$ca_id   = "11";

$sql = "update {$g5['member_table']} set mb_class=''";
sql_query($sql);

$sql = "update {$g5['member_table']} set mb_class='11' where mb_id='admin'";
sql_query($sql);

get_recommend_down($get_id,$ca_id);
/*
$get_id  = "0010000339";
get_recommend_up($get_id);
*/

function get_recommend_down($mb_id, $ca_id) 
{ 
	global $g5,$max_num,$rd_num; 
	$sql  = " select * from {$g5['member_table']} where mb_recommend='{$mb_id}' and length(mb_id)>0 order by mb_datetime desc";	

	echo $ca_id."=>".$sql."<br>\n";

	$result = sql_query($sql);
	for ($i=0; $row=mysql_fetch_array($result); $i++) { 
		$rd_num++;
		if ($rd_num>$max_num)  break;
		if ($row['mb_id']=="admin") break;
		$len = strlen($ca_id);
		if ($len == 30){
			echo "END";
			exit;
		}
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

		echo $rd_num.".".$subid." = ".$row['mb_id']."<br>\n";
		$sql = "update {$g5['member_table']} set mb_class='".$subid."' where mb_id='".$row['mb_id']."'";
		echo $sql."<br>\n";
		sql_query($sql);

		$sql  = "select count(mb_no) as cnt from {$g5['member_table']} where mb_recommend='".$row['mb_id']."' and length(mb_id)>0";	
		$row2 = sql_fetch($sql); 

		if ($row2['cnt']){
			get_recommend_down($row['mb_id'],$subid);
		}
	}
} 


function get_recommend_up($mb_id) 
{ 
	global $g5,$max_num,$ru_num; 
	$ru_num++;
	$sql  = " select mb_recommend from {$g5['member_table']} where mb_id='{$mb_id}'";
	$row  = sql_fetch($sql);
	echo $mb_id." -> ".$row['mb_recommend']."<br>\n";

	if ($ru_num>$max_num){
		//END
	}else{
		if ($row['mb_recommend']!="admin"){
			get_recommend_up($row['mb_recommend']);
		}
	}
}
?>
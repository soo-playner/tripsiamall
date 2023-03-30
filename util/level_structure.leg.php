<?php
include_once('./_common.php');
header('Content-Type: application/json');

/*
$_GET['lastParent'] = 'copy5285m';
$_GET['findId']= 'new';
*/

function getParentId($lastParent, $findId){

	global $rows;

	if($lastParent == $findId) {
		array_push($rows, $lastParent);
		return;
	}

	$sql = "select mb_id, mb_recommend from g5_member where mb_id = '{$findId}' ";
	 //echo $sql;

	$srow = sql_fetch($sql);
	if($srow){
		array_push($rows, $srow['mb_id']);
		getParentId( $lastParent, $srow['mb_recommend'] );
	}
}

$rows = array();

getParentId($_GET['lastParent'], $_GET['findId'] );
//getParentId( 'copy5285m', 'new' );

print json_encode($rows);
?>

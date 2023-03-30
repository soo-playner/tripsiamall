<?php
include_once('./_common.php');

$wr_id = intval($_GET['no']);

$table_name = "g5_write_notice";
$ss_name = 'ss_view_'.$bo_table.'_'.$wr_id;
//print_r(get_session($ss_name));

if(!get_session($ss_name)){
    sql_query("update {$table_name} set wr_hit = wr_hit + 1  where wr_id = {$wr_id}");
}

set_session($ss_name, TRUE);



$sql = "select * from {$write_table} A ";
$sql .= " WHERE wr_id=".$_GET['no'];
$record = sql_fetch($sql);

$obj = new stdClass();
$obj->writing = conv_content($record['wr_content'],2);

$sql = " select wr_id, bf_source, bf_no, bf_file, bf_type from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = {$wr_id} ";
// $obj->sql = $sql;
$file_list = sql_query($sql,true);
$obj->file_list = array();
for ($i=0; $row=sql_fetch_array($file_list); $i++) {
	$obj2 = new stdClass();
	$obj2->filename = $row['bf_source'];
	$obj2->wr_id = $row['wr_id'];
	$obj2->bf_no = $row['bf_no'];
	$obj2->bf_file = $row['bf_file'];
	$obj2->bf_type = $row['bf_type'];
	array_push($obj->file_list,$obj2);
}

sql_query("INSERT INTO read_notice (mb_no, wr_id)
SELECT * FROM (SELECT '{$member['mb_no']}', {$wr_id}) AS tmp
WHERE NOT EXISTS (
	SELECT mb_no FROM read_notice WHERE mb_no = '{$member['mb_no']}'
		and wr_id = {$wr_id}
) LIMIT 1");

$obj->not_read_cnt = sql_fetch("select count(*) as cnt from {$table_name} where wr_id not in (select wr_id from read_notice where mb_no = {$member['mb_no']})")['cnt'];

echo json_encode($obj);
?>

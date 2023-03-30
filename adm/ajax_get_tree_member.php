<?php
$sub_menu = "600300";
include_once('./_common.php');


if ($gubun=="B"){
	$sql = "select * from {$g5['member_table']} where  mb_leave_date = '' and mb_id in (select c_id from g5_member_bclass where mb_id='".$member['mb_id']."')  ";
}else{
	$sql = "select * from {$g5['member_table']} where  mb_leave_date = '' and mb_id in (select c_id from g5_member_class where mb_id='".$member['mb_id']."')  ";
}
if ($stx) {
    $sql .= " and {$sfl} like '{$stx}%' ";
}
$sql .= " order by ".$sfl;
$result = sql_query($sql);

?>

		<table style="width:100%">
			<tr>
				<td bgcolor="#f9f9f9" height="30" style="padding-left:10px"><b>RESULT</b></td>
			</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
			<tr>
				<td bgcolor="#f9f9f9"  style="padding:10px 0px 10px 10px">
				<span style="cursor:pointer" onclick="go_member('<?=$row['mb_id']?>')"><?=$row['mb_id']?></span>
				</td>
			
<?
 }
    if ($i == 0)
        echo "<tr><td height=30 align=center>일치하는 데이터가 없습니다.</td></tr>";
?>
		</table>
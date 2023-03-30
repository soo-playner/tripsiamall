<?php
$sub_menu = "600910";
include_once('./_common.php');

$g5['title'] = "MP 승인";

include_once(G5_ADMIN_PATH.'/admin.head.php');

if($_GET[status]){
	$sql_condition .= " and A.status = '".$_GET[status]."'";
	$qstr .= "&status=".$_GET[status];
}
if($_GET[id]){
	$sql_condition .= " and A.writer like '%".$_GET[id]."%'";
	$qstr .= "&id=".$_GET[id];
}
if($_GET[first_name]){
	$sql_condition .= " and M.first_name like '%".$_GET[first_name]."%'";
	$qstr .= "&first_name=".$_GET[first_name];
}
if($_GET[last_name]){
	$sql_condition .= " and M.last_name like '%".$_GET[last_name]."%'";
	$qstr .= "&last_name=".$_GET[last_name];
}
if($_GET[create_dt]){
	$sql_condition .= " and DATE_FORMAT(A.create_dt, '%Y-%m-%d') = '".$_GET[create_dt]."'";
	$qstr .= "&create_dt=".$_GET[create_dt];
}
if($_GET[update_dt]){
	$sql_condition .= " and DATE_FORMAT(A.update_dt, '%Y-%m-%d') = '".$_GET[update_dt]."'";
	$qstr .= "&update_dt=".$_GET[update_dt];
}


$sql = " select count(*) as cnt from marketer A inner join g5_member M on A.writer = M.mb_id WHERE 1=1 ";
$sql .= $sql_condition;
$row = sql_fetch($sql,true);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * from marketer A inner join g5_member M on A.writer = M.mb_id WHERE 1=1 ";
$sql .= $sql_condition;

$sql .= " order by create_dt desc ";
$sql .= " limit {$from_record}, {$rows} ";

$list = sql_query($sql);

?>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
	$(function(){
		$('.regTb [name=status]').on('change',function(e){
			var comment_str = $('.regTb tbody [name=comment][idx=' + $(this).attr('idx') + ']').val();
			$.ajax({
				type: "POST",
				url: "/adm/approve_marketer.u.php",
				data:  {
					idx : $(this).attr('idx'),
					status : $(this).val(),
					comment : comment_str
				},
				async: false,
				success: function(data) {
					alert('상태 변경 완료');
					location.reload();
				},
				dataType: 'json'
			});
		});

		$.datepicker.regional["ko"] = {
			closeText: "close",
			prevText: "이전달",
			nextText: "다음달",
			currentText: "오늘",
			monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
			monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
			dayNames: ["일","월","화","수","목","금","토"],
			dayNamesShort: ["일","월","화","수","목","금","토"],
			dayNamesMin: ["일","월","화","수","목","금","토"],
			weekHeader: "Wk",
			dateFormat: "yymmdd",
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: ""
		};
		$.datepicker.setDefaults($.datepicker.regional["ko"]);

		$("#create_dt, #update_dt").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	});

</script>
<style type="text/css">
	xmp {font-family: 'Noto Sans KR', sans-serif;font-size:12px;}
	input[type="radio"] {}
	input[type="radio"] + label{color:#999;}
	input[type="radio"]:checked + label {color:#e50000;font-weight:bold;font-size:14px;}
	table.regTb {width:100%;table-layout:fixed;border-collapse:collapse;}
	table.regTb {border-top:solid 1px #777;}
	table.regTb th,
	table.regTb td {padding:4px 0;border-bottom:solid 1px #ddd;line-height:28px;font-size:12px;}
	table.regTb th {font-weight:normal;font-family:"nngdb";font-size:12px;color:#444;background-color:#f5f5f5;}
	table.regTb td {padding-left:10px;}
	table.regTb input[type="text"],
	table.regTb input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb textarea {padding:0;padding-left:8px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb label {cursor:pointer;}
	table.regTb input[type="radio"] {}
	table.regTb input[type="radio"] + label{color:#999;}
	table.regTb input[type="radio"]:checked + label {color:#e50000;font-weight:bold;}
	span.help {font-size:11px;font-weight:normal;color:rgba(38,103,184,1);}

	.btn_confirm {position:fixed;width:80px;right:10px;top:50%;z-index:9999;}
	.btn_confirm input[type="submit"] {display:block;width:100%;height:45px;line-height:45px;background-color:rgba(230,0,68,0.6);cursor:pointer;border:none;border-radius:5px;}
	.btn_confirm input[type="submit"]:hover {background-color:rgba(230,0,68,1);}
	#status{height:24px;}
	.adminWrp{padding: 0 20px;}
</style>
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" action="./approve_marketer.php" method="GET">
	상태 : 
	<select name="status" id="status">
		<option value="">전체</option>
		<option <?=$_GET[status] == 'R' ? 'selected':'';?> value="R">신청</option>
		<option <?=$_GET[status] == 'Y'? 'selected':'';?> value="Y">승인</option>
		<option <?=$_GET[status] == 'S'? 'selected':'';?> value="S">추가자료요청</option>
		<option <?=$_GET[status] == 'N'? 'selected':'';?> value="N">부결</option>
	</select>
	<input type="text" name="writer" placeholder="writer" class="frm_input" value="<?=$_GET[writer]?>" />
	<input type="text" name="first_name" placeholder="first_name" class="frm_input" value="<?=$_GET[first_name]?>" />
	<input type="text" name="last_name" placeholder="last_name" class="frm_input" value="<?=$_GET[last_name]?>" />
	<input type="text" name="create_dt" id="create_dt" placeholder="요청일시" class="frm_input" value="<?=$_GET[create_dt]?>" />
	<input type="text" name="update_dt" id="update_dt" placeholder="승인일시" class="frm_input" value="<?=$_GET[update_dt]?>" />

	<input type="submit" class="btn_submit" value="검색" />
</form>
<br><br>
<form name="site" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
<div class="adminWrp">
	Total : <?=$total_count?>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
            <col style="width:50px;"/>
            <col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:auto;"/>
			<col style="width:300px;"/>
			<col style="width:160px;"/>
			<col style="width:auto;"/>
			<col style="width:120px;"/>
			<col style="width:160px;"/>
        </colgroup>
		<thead>
			<th>No</th>
			<th>아이디</th>
			<th>신청자명</th>
			<th>신청내용</th>
			<th>파일</th>
			<th>신청일시</th>
			<th>답변</th>
			<th>처리상태</th>
			<th>승인일시</th>
		</thead>
        <tbody>
		<?for ($i=0; $row=sql_fetch_array($list); $i++) {
			$sql = " select wr_id, bf_source, bf_no from {$g5['board_file_table']} where bo_table = 'marketer' and wr_id = '".$row[idx]."' ";
			$file_list = sql_query($sql);
			// $obj->filename = $file['bf_source'];
			// $obj->wr_id = $file['wr_id'];
		?>
			<tr idx="<?=$row[idx]?>" >
				<td><?=$row[idx]?></td>
				<td><?=$row[writer]?></td>
				<td><?=$row[first_name].' '.$row[last_name]?></td>
				<td><?=conv_content($row[content],1)?></td>
				<td>
				<?php 
					for ($j=0; $row1=sql_fetch_array($file_list); $j++) {
						if($row1['bf_source'] == '') continue;
						echo "<a href='";
						echo G5_URL.'/bbs/download.php?bo_table=marketer&wr_id='.$row1['wr_id'].'&no='.$row1['bf_no'];
						echo "' >".$row1['bf_source']."</a><br>";
					}
				?>
				</td>
				<td><?=$row[create_dt]?></td>
				<td>
					<?php if($row[status] == 'Y'){ // 승인?> 
						<?=conv_content($row[comment],1)?>
					<?php } else { // 미승인?>
						<textarea name="comment" idx="<?=$row[idx]?>" class="comment" maxlength="65536" style="width:88%;height:80px;"><?=conv_content($row[comment],1)?></textarea>
					<?php }?>
				</td>
				<td>
					<?php if($row[status] == 'Y'){ // 승인?> 
						승인
					<?php } else { // 미승인?>
						<select name="status" idx="<?=$row[idx]?>">
							<option <?=$row[status] == 'R' ? 'selected':'';?> value="R">신청</option>
							<option <?=$row[status] == 'Y'? 'selected':'';?> value="Y">승인</option>
							<option <?=$row[status] == 'S'? 'selected':'';?> value="S">추가자료요청</option>
							<option <?=$row[status] == 'N'? 'selected':'';?> value="N">부결</option>
						</select>
					<?php }?>
				</td>
				<td><?=$row[update_dt]?></td>
			</tr>
		<?}?>
        </tbody>
    </table>
</div>
</form>
<!-- // adminWrp // -->
<?php
$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
if ($pagelist) {
	echo $pagelist;
}
?>

<?
include_once ('./admin.tail.php');
?>


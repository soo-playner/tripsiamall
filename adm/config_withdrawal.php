<?php
$sub_menu = "700300";
include_once('./_common.php');

$g5['title'] = "BTC 출금 요청내역";

include_once(G5_ADMIN_PATH.'/admin.head.php');

if($_GET['status']){
	$sql_condition .= " and A.status = '".$_GET['status']."'";
	$qstr .= "&status=".$_GET['status'];
}
if($_GET['id']){
	$sql_condition .= " and A.mb_id like '%".$_GET['id']."%'";
	$qstr .= "&id=".$_GET['id'];
}
if($_GET['first_name']){
	$sql_condition .= " and M.first_name like '%".$_GET['first_name']."%'";
	$qstr .= "&first_name=".$_GET['first_name'];
}
if($_GET['last_name']){
	$sql_condition .= " and M.last_name like '%".$_GET['last_name']."%'";
	$qstr .= "&last_name=".$_GET['last_name'];
}
if($_GET['mb_hp']){
	$sql_condition .= " and M.mb_hp like '%".$_GET['mb_hp']."%'";
	$qstr .= "&mb_hp=".$_GET['mb_hp'];
}
if($_GET['create_dt']){
	$sql_condition .= " and DATE_FORMAT(A.create_dt, '%Y-%m-%d') = '".$_GET['create_dt']."'";
	$qstr .= "&create_dt=".$_GET['create_dt'];
}
if($_GET['update_dt']){
	$sql_condition .= " and DATE_FORMAT(A.update_dt, '%Y-%m-%d') = '".$_GET['update_dt']."'";
	$qstr .= "&update_dt=".$_GET['update_dt'];
}

if($_GET['ord']!=null && $_GET['ord_word']!=null){
	$sql_ord = "order by ".$_GET['ord_word']." ".$_GET['ord'];
}

$sql = " select count(*) as cnt from withdrawal_request A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1 ";
$sql .= $sql_condition;
$sql .= $sql_ord;
$row = sql_fetch($sql,true);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * from withdrawal_request A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1 ";
$sql .= $sql_condition;
if($sql_ord){
	$sql .= $sql_ord;
}else{
$sql .= " order by create_dt desc ";
}
$sql .= " limit {$from_record}, {$rows} ";

$list = sql_query($sql);
print_r($list );
?>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
	$(function(){
		$('.regTb [name=status]').on('change',function(e){
			$.post( "/adm/config_withdrawal.u.php", {
				uid : $(this).attr('uid'),
				status : $(this).val()
			}, function(data) {
				alert(data.result);
			},'json');
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
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" action="./config_withdrawal.php" method="GET">
	상태 : 
	<select name="status" id="status">
		<option value="">전체</option>
		<option <?=$_GET['status'] == 'R' ? 'selected':'';?> value="R">요청</option>
		<option <?=$_GET['status'] == 'Y'? 'selected':'';?> value="Y">승인</option>
		<option <?=$_GET['status'] == 'S'? 'selected':'';?> value="S">대기</option>
		<option <?=$_GET['status'] == 'N'? 'selected':'';?> value="N">불가</option>
	</select>
	<input type="text" name="id" placeholder="id" class="frm_input" value="<?=$_GET['id']?>" />
	<input type="text" name="create_dt" id="create_dt" placeholder="요청일시" class="frm_input" value="<?=$_GET['create_dt']?>" />
	<input type="text" name="update_dt" id="update_dt" placeholder="승인일시" class="frm_input" value="<?=$_GET['update_dt']?>" />

	<input type="submit" class="btn_submit" value="검색" />
</form>
<br><br>

<?php
$ord_array = array('desc','asc'); // 정렬 방법 (내림차순, 오름차순)
$ord_arrow = array('▼','▲'); // 정렬 구분용
$ord = isset($_REQUEST['ord']) && in_array($_REQUEST['ord'],$ord_array) ? $_REQUEST['ord'] : $ord_array[0]; // 지정된 정렬이면 그 값, 아니면 기본 정렬(내림차순)
$ord_key = array_search($ord,$ord_array); // 해당 키 찾기 (0, 1)
$ord_rev = $ord_array[($ord_key+1)%2]; // 내림차순→오름차순, 오름차순→내림차순
?><a href="?ord=<?php echo $ord_rev; ?>">등록일<?php echo $ord_arrow[$ord_key]; ?></a>

<form name="site" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
<div class="adminWrp">
	Total : <?=$total_count?>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
			<col style="width:30px;"/>
            <col style="width:50px;"/>
            <col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:140px;"/>
			<col style="width:160px;"/>
			<col style="width:100px;"/>
			<col style="width:auto;"/>
        </colgroup>
		<thead>
			<th>선택</th>
			<th><a href="?ord=<?php echo $ord_rev; ?>&ord_word=uid">No <?php echo $ord_arrow[$ord_key]; ?></a></th>
			<th>아이디 </th>
			<th>지갑주소</th>
			<th>메모주소</th>
			<th>현재 총 EOS잔고</th>
			<th>현재 EOS 수당 잔고</th>
			
			<th>출금요청금액</th>
			<th>요청일시</th>
			<th>승인여부</th>
			<th>승인일시</th>
		</thead>
        <tbody>
		<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
			<tr>
				<td><input type="checkbox" name="paid_eos[]" value="<?=$row[uid]?>" class="pay_check">  </td>
				<td><?=$row['uid']?></td>
				<td><?=$row['mb_id']?></td>
				<!--<td><a href="#" onclick="window.open('https://blockchain.info/address/<?=$row['addr']?>','width=800, height=500');"><?=$row['addr']?></a></td>-->
				<td><a href="https://bloks.io/account/<?=$row['addr']?>" target="_blank"><?=$row['addr']?></a></td>
				<td><?=$row['addrmemo']?></a></td> <!--지갑주소-->
				<td><?= $row['mb_balance'] + $row['mb_save_point'] ?></td>
				<?if($row['mb_balance']<0){?><td style="color:red"><?=$row['mb_balance']?></td><?}else{?><td><?=$row['mb_balance']?></td><?}?>
				
				<td><?=$row['amt']?></td>
				<td><?=$row['create_dt']?></td>
				<td>
					<!-- <?=$row['status']?> -->
					<select name="status" uid="<?=$row[uid]?>">
						<option <?=$row['status'] == 'R' ? 'selected':'';?> value="R">요청</option>
						<option <?=$row['status'] == 'Y'? 'selected':'';?> value="Y">승인</option>
						<option <?=$row['status'] == 'S'? 'selected':'';?> value="S">대기</option>
						<option <?=$row['status'] == 'N'? 'selected':'';?> value="N">불가</option>
					</select>
				</td>
				<td><?=$row['update_dt']?></td>
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


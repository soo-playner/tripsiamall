<?php
$sub_menu = "600920";
include_once('./_common.php');

$g5['title'] = "mp(마케팅 포인트) 정산";

include_once(G5_ADMIN_PATH.'/admin.head.php');

if($_GET['mb_id']){
	$sql_condition .= " and mb_id like '%".$_GET['mb_id']."%'";
	$qstr .= "&mb_id=".$_GET['mb_id'];
}
if($_GET[mp_id]){
	$sql_condition .= " and mb_mprecommend like '%".$_GET[mp_id]."%'";
	$qstr .= "&mp_id=".$_GET[mp_id];
}
if($_GET[start_dt]){
	$sql_condition .= " and DATE_FORMAT(create_dt, '%Y-%m-%d') >= '".$_GET[start_dt]."'";
	$qstr .= "&start_dt=".$_GET[start_dt];
}
if($_GET[end_dt]){
	$sql_condition .= " and DATE_FORMAT(create_dt, '%Y-%m-%d') <= '".$_GET[end_dt]."'";
	$qstr .= "&end_dt=".$_GET[end_dt];
}

$sql = " select count(*) as cnt from mp_soodang  WHERE 1=1 ";
$sql .= $sql_condition;
$row = sql_fetch($sql,true);
$total_count = $row['cnt'];


$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * from mp_soodang WHERE 1=1 ";
$sql .= $sql_condition;

$sql .= " order by create_dt desc ";
$sql .= " limit {$from_record}, {$rows} ";

$list = sql_query($sql);

?>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
	$(function(){


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

		$("#start_dt, #end_dt").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	});

	function excel(){
		$('#fsearch').attr('action','mp_soodang_excel.php');
		$('#fsearch').submit();
		$('#fsearch').attr('action','mp_soodang.php');
	}

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
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" action="./mp_soodang.php" method="GET">

	<input type="text" name="mb_id" placeholder="가입자" class="frm_input" value="<?=$_GET['mb_id']?>" />
	<input type="text" name="mp_id" placeholder="mp Id" class="frm_input" value="<?=$_GET[mp_id]?>" />

	가입일 : <input type="text" name="start_dt" id="start_dt" placeholder="시작일" class="frm_input" value="<?=$_GET[start_dt]?>" /> ~ <input type="text" name="end_dt" id="end_dt" placeholder="마지막일" class="frm_input" value="<?=$_GET[end_dt]?>" />

	<input type="submit" class="btn_submit" value="검색" />
	<input type="button" class="btn_submit" value="엑셀" onclick="excel();" />
</form>
<br><br>
<form name="site" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
<div class="adminWrp">
	Total : <?=$total_count?>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
            <col style="width:50px;"/>
            <col style="width:200px;"/>
			<col style="width:auto;"/>
			<col style="width:auto;"/>
			<col style="width:160px;"/>
			<col style="width:160px;"/>
        </colgroup>
		<thead>
			<th>No</th>
			<th>가입일</th>
			<th>가입자</th>
			<th>MP</th>
			<th>커미션($)</th>
			<th>usd/btc</th>
		</thead>
        <tbody>
		<?for ($i=0; $row=sql_fetch_array($list); $i++) {
			$a = "select mb_level from g5_member where mb_id='".$row['mb_id']."'";
			$b = sql_fetch($a);			
			if( $b['mb_level']>=2){
				$commission = $row[commission];
				$exchage = $row['usdbtc'];
			}else{
				$commission = 0;
				$exchage = 0;
			}
		?>
			<tr>
			
				<td><?=$row[idx]?></td>
				<td><?=$row[create_dt]?></td>
				<td><?=$row['mb_id']?></td>
				<td><?=$row[mb_mprecommend]?></td>
				<td><?=$commission?></td>
				<td><?=$exchage?></td>

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


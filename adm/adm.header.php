<?
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date)) $fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-7 day"));
if (empty($to_date)) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;to_id=".$fr_id;
$query_string = $qstr ? '?'.$qstr : '';
?>

<style>
    .local_sch02 div.sch_last{display:inline-block;margin-left:15px;}
    .local_sch02 strong{width:inherit;padding-right:5px;}
	.btn_submit{width:100px;margin-left:20px;}
    .black_btn{background:#333 !important; border:1px solid black !important; color:white;}
    .font_blue{color:#4556ff !important;} 
    .font_red{color:#ed2424 !important;}

    .sel_0{}
	.sel_1{background:palegreen;}
	.sel_2{background:azure;}
	.sel_3{background:antiquewhite;}
	.sel_4{background:palevioletred;}
</style>

<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=G5_THEME_URL?>/_common/js/common.js"></script>


<form name="fvisit" id="fvisit" class="local_sch02 local_sch" method="get">

<div class="sch_last" style="margin-left:20px">
    <strong>멤버아이디</strong>
    <input type="text" name="fr_id" value="<?php echo $fr_id ?>" id="fr_id" class="frm_input" style="width:140px" maxlength="30">
    <label for="fr_id" class="sound_only">회원아이디</label>
</div>

<div class="sch_last">
    <strong> 기간별검색</strong>
    <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="15" style="width:120px" maxlength="10">
    <label for="fr_date" class="sound_only">시작일</label>
    ~
    <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="15" style="width:120px" maxlength="10">
    <label for="to_date" class="sound_only">종료일</label>
</div>

<div class="sch_last">
    <strong> 승인일시 :</strong>
    <input type="text" name="update_dt" id="update_dt" placeholder="승인일시" class="frm_input" value="<?=$_GET['update_dt']?>" />
</div>

<div class="sch_last">
    <strong> 상태값 :</strong>
	<select name="status" id="status" style="width:100px;">
		<option value="">전체</option>
		<option <?=$_GET['status'] == '0' ? 'selected':'';?> value="0">요청</option>
		<option <?=$_GET['status'] == '1'? 'selected':'';?> value="1">승인</option>
		<option <?=$_GET['status'] == '2'? 'selected':'';?> value="2">대기</option>
		<option <?=$_GET['status'] == '3'? 'selected':'';?> value="3">불가</option>
		<option <?=$_GET['status'] == '4'? 'selected':'';?> value="4">취소</option>
	</select>
</div>

<input type="submit" value="검색" class="btn_submit">
<? if($g5['title'] == "입금 요청 내역"){ ?>
     <input type="button" class="btn_submit excel" value="엑셀 다운로드" onclick="window.location.href='../excel/deposit_request_excel_down.php?fr_date=<?=$_GET['fr_date']?>&to_date=<?=$_GET['to_date']?>&fr_id=<?=$_GET['fr_id']?>&update_dt=<?=$_GET['update_dt']?>&status=<?=$_GET['status']?>'" />	  
<? } ?>
</form>



<!--
<ul class="anchor">
    <li><a href="./adm.income.php<?php echo $query_string ?>">입금 항목 보기</a></li>
    <li><a href="./adm.eios.ncom.php<?php echo $query_string ?>">전체 입금 항목</a></li>
</ul>
-->
<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>

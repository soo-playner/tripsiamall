<?php
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_MSHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = '마이페이지';
?><!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
if (G5_IS_MOBILE) {
    echo '<meta name="viewport" content="width=device-width,initial-scale=0.5,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
} else {
    echo '<meta http-equiv="imagetoolbar" content="no">'.PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">'.PHP_EOL;
}

if($config['cf_add_meta'])
    echo $config['cf_add_meta'].PHP_EOL;
?>
<title><?php echo $g5_head_title; ?></title>
<link rel="stylesheet" href="/theme/basic/css/mobile_shop.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--[if lte IE 8]>
<script src="/js/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
var g5_bbs_url   = "<?php echo G5_BBS_URL ?>";
var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_bo_table  = "<?php echo isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_editor    = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
</script>
<script src="/js/jquery-1.8.3.min.js"></script>
<script src="/js/jquery.carouFredSel-5.5.0-packed.js"></script>
<script src="/js/common.js"></script>
<script src="/js/wrest.js"></script>
<script src="/js/modernizr.custom.70111.js"></script>
</head>
<body>
<div id="container">
<style type="text/css">
.shop_path {padding:0 20px;height:40px;line-height:40px;font-family:"nngdb";font-size:12px;color:#fff;background-color:#485461;}
.shop_path a {color:#fff;}

.shop_path i {font-size:20px;vertical-align:-3px;color:#fff;margin:0 5px;}
.shop_path select {border:solid 1px #ccc;padding:5px;color:#666;}
.shop_pageTitle {height:60px;line-height:60px;text-align:center;border-bottom:solid 2px #50585e;}
.shop_pageTitle h2 {font-weight:normal;font-size:30px;color:#222;font-family:"nngdb";}
.m_contents {padding:10px;}

</style>
<?
if ($_GET[go]=="Y"){
	goto_url("mypage_tree.php#org_start");
	exit;
}
?>
<p class="blk" style="height:10px;"></p>
<div class="pk_page">
<style type="text/css">
.pk_page {font-size:14px;}
span.btn,
a.btn {display:inline-block;*display:inline;*zoom:1;height:33px;line-height:33px;padding:0 15px;border-radius:3px;background-color:#1DC2BB;color:#fff;}
.infoBx {border:solid 2px rgba(39,48,62,0.4);border-radius:8px;margin-bottom:30px;}
.infoBx h3 {line-height:40px;font-size:15px;padding-left:20px;border-bottom:solid 1px rgba(0,0,0,0.1);background-color:rgba(39,48,62,0.05);}
.infoBx ul {margin:15px;}
.infoBx ul li {display:inline-block;*display:inline;*zoom:1;width:33%;line-height:40px;font-size:14px;color:#777;border-bottom:solid 1px #fff;}
.infoBx ul li.prc {color:rgba(59,105,178,1);}
.infoBx ul li span {display:inline-block;*display:inline;*zoom:1;color:#000;padding-left:20px;width:100px;background-color:rgba(39,48,62,0.05);margin-right:20px;}
</style>
	
<?
// ************************

include_once('../adm/inc.member.class.php');

$sql  = "select count(*) as cnt from g5_member";
$mrow = sql_fetch($sql);

$sql = "select * from g5_member_class_chk where mb_id='".$member['mb_id']."' and  cc_date='".date("Y-m-d",time())."' order by cc_no desc";
$row = sql_fetch($sql);

if ($mrow['cnt']>$row['cc_usr'] || !$row['cc_no'] || $_GET["reset"]){
	$sql = "delete from g5_member_class where mb_id='".$member['mb_id']."'";
	sql_query($sql);

	get_recommend_down($member['mb_id'],$member['mb_id'],'11');

	$sql  = " select * from g5_member_class where mb_id='{$member['mb_id']}' order by c_class asc";	
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$row2 = sql_fetch("select count(c_class) as cnt from g5_member_class where  mb_id='".$member['mb_id']."' and c_class like '".$row['c_class']."%'");
		$sql = "update g5_member set mb_child='".$row2['cnt']."' where mb_id='".$row['c_id']."'";
		sql_query($sql);
	}

	$sql = "insert into g5_member_class_chk set mb_id='".$member['mb_id']."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow['cnt']."'";
	sql_query($sql);

	if ($_GET["reset"]){
		goto_url("mypage_tree.php?sfl=".$sfl."&stx=".$stx);
		exit;		
	}
}
?>
<style type="text/css">
	.btn_menu {padding:5px;border:1px solid #ced9de;background:rgb(246,249,250);cursor:pointer}
</style>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/js/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
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

</script>
<div style="padding:0px 0px 0px 10px;">
	<a name="org_start"></a>
	<div style="float:left">
	<input type="button" class="btn_menu" value="검색메뉴닫기" onclick="btn_menu2()">
	<input type="button" class="btn_menu" value="전체 조직도" onclick="location.href='mypage_tree.php?go=Y'">
	<input type="button" class="btn_menu" value="조직도 재구성" onclick="btn_org()">
	</div>
	<div style="float:right;padding-right:10px">
	<input type="button" class="btn_menu" style="background:#fadfca" value="창닫기" onclick="self.close()">
	</div>
</div>
<div style="padding-top:10px;clear:both"></div>
<div id="div_left" style="display:none;width:100%;border:">
<?
if (!$fr_date) $fr_date = Date("Y-m-d", time()-60*60*24*365);
if (!$to_date) $to_date = Date("Y-m-d", time());
?>
	<div style="margin-left:10px;padding:5px 5px 5px 5px;border:1px solid #d9d9d9;">
		<form name="sForm2" id="sForm2" method="get" action="mypage_tree.php">
		<table style="width:100%">
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>주문기간</b></td>

				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px" align=center>
				<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" style="width:80px" size="10" maxlength="10"> ~
				<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" style="width:80px" size="10" maxlength="10">

				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" colspan=2 height="30" align="center">
				<input type="submit"  class="btn_submit" style="padding:5px" value="적 용">
				</td>
			</tr>
		</table>
		</form>

		<form name="sForm" id="sForm" method="post" style="padding-top:0px" onsubmit="return false;">
		<table style="width:100%">
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>회원검색</b></td>

				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px">
				
				<select name="sfl" id="sfl">
					<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
					<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
				</select>

				<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="required frm_input" style="width:80px;" onkeypress="event.keyCode==13?btn_search():''">

				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" colspan="2" height="30" align="center">
				<input type="button" onclick="btn_search();" class="btn_submit" style="padding:5px" value="검 색">
				</td>
			</tr>
		</table>
		</form>

		<div id="div_result" style="margin-top:5px;overflow-y: auto;">

		</div>
	</div>
</div>
<div id="div_right" style="margin-top:10px;width:100%;">
		<div class="zTreeDemoBackground left" style="margin:0px 10px 0px 10px;border:1px solid #d9d9d9;">
			<ul id="treeDemo" class="ztree"></ul>
		</div>
		<SCRIPT type="text/javascript">
			<!--
			var setting = {
				view: {
					nameIsHTML: true
				},
				data: {
					simpleData: {
						enable: true
					}
				}
			};
			var zNodes =[
		<?
		$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select mb_child from g5_member where mb_id=c.c_id) as c_child,(select count(mb_no) from g5_member where mb_recommend=c.c_id and mb_leave_date = '') as m_child from g5_member m join g5_member_class c on m.mb_id=c.mb_id where c.mb_id='{$member['mb_id']}' order by c.c_class";
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			if (strlen($row['c_class'])==2){
				$parent_id = 0;
			}else{
				$parent_id = substr($row['c_class'],0,strlen($row['c_class'])-2);
			}
			$sql  = "select sum(od_receipt_price) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$row['c_id']."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row2 = sql_fetch($sql);

			$sql  = "select sum(od_receipt_price) as tprice,sum(pv) as tpv from g5_shop_order where mb_id in (select c_id from g5_member_class where mb_id='".$member['mb_id']."' and c_id<>'".$row['c_id']."' and c_class like '".$row['c_class']."%') and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row3 = sql_fetch($sql);
		?>
				{ id:"<?=$row['c_class']?>", pId:"<?=$parent_id?>", name:"[<?=get_member_label($row['mb_level'])?>-<?=(strlen($row['c_class'])/2)-1?>-<?=($row['m_child'])?>-<?=($row['c_child']-1)?>] <?=$row['c_name']?> (<?=$row['c_id']?>) <img src='/adm/img/dot.gif'> 자기매출 <?=number_format($row2[tprice]/1000)?>/<?=number_format($row2['tpv']/1000)?> <img src='/adm/img/dot.gif'> 하부매출 <?=number_format($row3[tprice]/1000)?>/<?=number_format($row3['tpv']/1000)?> ", open:true, click:false},
		<?
		}
		?>
			];

			$(document).ready(function(){
				$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			});

			//-->
		</SCRIPT>
</div>

<style type="text/css">

.ztree li a:hover {text-decoration:none; background-color: #FAD7E0;}

</style>
<script type="text/javascript">
<!--

$(document).ready(function(){
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	<?if ($stx && $sfl){?>
		btn_search();
	<?}?>
});
function btn_print(){

	var html = $('#treeDemo');

	var strHtml = '<!doctype html><html lang="ko"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="imagetoolbar" content="no" /><title></title><link rel="stylesheet" type="text/css" media="all" href="/js/zTreeStyle.css"></';
	strHtml += 'head><body style="padding:0px;margin:0px;"><div class="zTreeDemoBackground left"><ul id="treeDemo" class="ztree"><!--body--></ul></div></body></html>';
	var strContent = html.html();
	var objWindow = window.open('', 'print', 'width=640, height=800, resizable=yes, scrollbars=yes, left=0, top=0');
	if(objWindow)
	{
		 var strSource = strHtml;
		 strSource  = strSource.replace(/\<\!\-\-body\-\-\>/gi, strContent);

		 objWindow.document.open();
		 objWindow.document.write(strSource);
		 objWindow.document.close();

		 setTimeout(function(){ objWindow.print(); }, 500);
	}

}
function btn_menu2(){
	if($("#div_left").css("display") == "none"){ 
		$("#div_left").show();

	} else { 
		$("#div_left").hide(); 

	} 
}
function btn_search(){
	if($("#stx").val() == ""){ 
		//alert("검색어를 입력해주세요.");
		$("#stx").focus();
	}else{
		$.post("ajax_get_tree_member.php", $("#sForm").serialize(),function(data){
			$("#div_result").html(data);
		});
	}
}
function go_member(go_id){
	$.get("ajax_get_tree_load.php?fr_date=<?=$fr_date?>&to_date=<?=$to_date?>&go_id="+go_id, function (data) {
		$('#div_right').html(data);
	});
}
function btn_org(){
	if (confirm("조직도를 재구성 하시겠습니까?")){
		location.href="mypage_org.php?reset=1&sfl=<?=$sfl?>&stx=<?=$stx?>";
	}
}
//-->
</script>
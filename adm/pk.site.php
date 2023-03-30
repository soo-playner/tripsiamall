<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");
/*## set ################################################*/
//$token = get_token();
$unique = "site";
$set_table =  "g5_site";
/*@@End. set #####*/

$mode = "";
if($mode=="re"):
	sql_query(" DROP TABLE `$set_table` ");
endif;
/*## alter ################################################*/
/*
	sql_query(" ALTER TABLE  `g5_site` ADD  `gnb1` VARCHAR( 200 ) NOT NULL AFTER  `slogun` ");
	sql_query(" ALTER TABLE  `g5_site` ADD  `gnb2` VARCHAR( 200 ) NOT NULL AFTER  `gnb1` ");
	sql_query(" ALTER TABLE  `g5_site` ADD  `gnb3` VARCHAR( 200 ) NOT NULL AFTER  `gnb2` ");
	sql_query(" ALTER TABLE  `g5_site` ADD  `gnb4` VARCHAR( 200 ) NOT NULL AFTER  `gnb3` ");
	sql_query(" ALTER TABLE  `g5_site` ADD  `copy_co` VARCHAR( 100 ) NOT NULL AFTER  `gnb4` ");

	sql_query(" ALTER TABLE  `g5_site` ADD  `cpr_em` VARCHAR( 255 ) NOT NULL AFTER  `sms_em` ");
	sql_query(" ALTER TABLE  `g5_site` ADD  `cpr_email` VARCHAR( 255 ) NOT NULL AFTER  `cpr_em` ");
*/
/*@@End.  #####*/
$th = array(
	array("홈페이지 제목","title","varchar","100"),
	array("관리자명","admin","varchar","100"),
	array("고객센터","tel","varchar","100"),

	array("이메일","email","varchar","200"),
	array("대표번호","tel2","varchar","100"),
	array("fax","fax","varchar","100"),

	array("계좌은행","bank","varchar","100"),
	array("계좌번호","bank_number","varchar","100"),
	array("예금주","bank_name","varchar","100"),

	array("운영시간","time","text","","wide"),
	array("Copyright","copyright","text","","wide"),
	array("모바일 Copyright","copyright_m","text","","wide"),
);
## 쿼리생성기
for ($i=0;$i<count($th);$i++){
	$comma = (count($th) == ($i + 1))?"":",";
	if ($th[$i][2] == "varchar") {
		$create_qry .= "`".$th[$i][1]."` ".$th[$i][2]."(".$th[$i][3].") NOT NULL".$comma." ";
	} else if ($th[$i][2] == "text") {
		$create_qry .= "`".$th[$i][1]."` ".$th[$i][2]." NOT NULL".$comma." ";
	}
}
if(!sql_query(" DESC `$set_table` ", false)) {
	$create = " CREATE TABLE `$set_table` (
					  $create_qry
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ; ";
	sql_query($create, false);
}



/*@@End.  #####*/

$g5['title'] = '홈페이지 설정';
include_once ('./admin.head.php');
?>

<!--▼▼▼ datepicker ▼▼▼-->
<!-- // <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script>
$(function() {
  $("#cf_peak_sdate, #cf_peak_edate").datepicker({
    dateFormat: '0000-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    yearSuffix: '년',
    beforeShow: function() {
        setTimeout(function(){
            $('.ui-datepicker').css('z-index', 99999999999999);
        }, 0);
    }
  });
});
</script> // -->
<!--▲▲▲ datepicker ▲▲▲-->
<!-- // <script>
$(function(){
	$('#color1, #color2, #color3, #color4, #color5').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {

			$(el).val(hex);
			$(el).ColorPickerHide();
			// 부모에게 색 먹이기
			$(el).css({"border":"solid 3px #"+hex});
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
});
</script> // -->
<style type="text/css">
xmp {font-family: 'Noto Sans KR', sans-serif;font-size:12px;}
input[type="radio"] {}
input[type="radio"] + label{color:#999;}
input[type="radio"]:checked + label {color:#e50000;font-weight:bold;font-size:14px;}
</style>
<?//=pkicolor("c924c9")?>
<?//=pkbnr("top_l")?>
<style type="text/css">
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


</style>
 
<div class="adminWrp">
<form name="site" method="post" action="./pk.<?=$unique?>.u.php" enctype="multipart/form-data" style="margin:0px;">
<input type="hidden" name="mode" value="<?=$unique?>"/>
<input type="hidden" name="token" value="">
<input type="hidden" name="rtn" value="<?=$_SERVER['REQUEST_URI']?>" />

	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
	<colgroup>
		<col width="140px"/><col/><col width="140px"/><col/><col width="140px"/><col/>
	</colgroup>
	<?
		for ($i=0;$i<9;$i++){
			echo ($i%3==0)?"<tr>":"";
	?>
		<th><?=$th[$i][0]?></th>
		<td><input type="text" name="<?=$th[$i][1]?>" value="<?=$pk[$th[$i][1]]?>" style="width:80%;"/></td>	
	<?
			echo ($i%3==2)?"</tr>":"";
		}
	?>
	<tr>
		<th>운영시간</th>
		<td colspan="5"><textarea name="time" rows="" cols="" style="width:95%;height:60px;"><?=$pk['time']?></textarea></td>
	</tr>
	<tr>
		<th>사이트 하단 정보</th>
		<td colspan="5"><textarea name="copyright" rows="" cols="" style="width:95%;height:60px;"><?=$pk['copyright']?></textarea></td>
	</tr>
	<tr>
		<th>모바일 사이트 하단 정보</th>
		<td colspan="5"><textarea name="copyright_m" rows="" cols="" style="width:95%;height:60px;"><?=$pk['copyright_m']?></textarea></td>
	</tr>
	</table>
	<xmp><span>내용</span> PV컬러 글씨</xmp>
	{타이틀} 은 자동으로 홈페이지 제목으로 바뀌어 노출됩니다.
	<p class="blk" style="height:20px;"></p>

	<h3>약관 / 개인정보취급방침</h3>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
	<colgroup>
		<col width="200px"/><col/><col width="200px"/><col/>
	</colgroup>
	<tr>
		<th>회원가입약관</th>
		<td colspan="3"><textarea name="cf_stipulation" id="cf_stipulation" rows="10"><?php echo $config['cf_stipulation'] ?></textarea></td>
	</tr>
	<tr>
		<th>개인정보처리방침</th>
		<td colspan="3"><textarea id="cf_privacy" name="cf_privacy" rows="10"><?php echo $config['cf_privacy'] ?></textarea></td>
	</tr>
	</table>
	<p class="blk" style="height:20px;"></p>
	<div class="btn_confirm">
		<input type="submit" name="submit" class="confirm" value="저장하기" />
	</div><!-- // btn_confirm // -->
</form>
</div><!-- // adminWrp // -->
<?
include_once ('./admin.tail.php');
?>

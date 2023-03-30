<?php
include_once('./_common.php');

/*## 프레임 아이디 찾기 ################################################*/
if ($_GET['mbid'] && $member['mb_level'] > 8) {
include_once(G5_THEME_PATH.'/head.sub.php');
?>
<div class="id_search">
<style type="text/css">
.id_search {padding:30px;}
.id_search li {float:left;width:32.3%;padding:0.5%;}
.id_search li:nth-child(3n+0) {width:32.4%;}
.id_search li span {display:block;padding:5px;line-height:18px;font-size:14px;border:solid 1px #ddd;cursor:pointer;}
.id_search li span:hover {background-color:#777;color:#fff;}
.infoBx {border:solid 2px rgba(39,48,62,0.4);border-radius:8px;margin-bottom:30px;}
.infoBx h3 {line-height:40px;font-size:15px;padding-left:20px;border-bottom:solid 1px rgba(0,0,0,0.1);background-color:rgba(39,48,62,0.05);}
</style>
<script>
$(function(){
	$('span[id^="id_"]').click(function () {
		var $id = $(this).attr("id").replace("id_","");
		$("#set_id_sel",parent.document.body).val($id);
		$("#insert_id",parent.document.body).show();
		$("#set_id_sel",parent.document.body).focus();
		$("#framer",parent.document.body).attr("src","");
		$("#framewrp",parent.document.body).hide();
	});
});
</script>
<div class="infoBx">
	<h3>주문 아이디 검색</h3>
	<ul>
	<?
		$i = 0;
		$qry = sql_query(" select mb_id, mb_name from g5_member where mb_leave_date = '' and (mb_id like '%{$_GET[mbid]}%' or mb_name like '%{$_GET[mbid]}%') and mb_level > 2 order by mb_id ");
		while ($res = sql_fetch_array($qry)) {
			if ($res['mb_id']) {
	?>
		<li><span id="id_<?=$res['mb_id']?>"><?=$res['mb_id']?><p>(<?=$res['mb_email']?>)</p></span></li>
	<?
				$i++;
			}
		}
		if ($i == 0) {
	?>
		<li>No result found</li>
	<?
		}
	?>
	</ul>
	<p class="clr"></p>
</div><!-- // infoBx -->

</div><!-- // id_search -->
<?
include_once(G5_THEME_PATH.'/tail.sub.php');
/*## 추천 아이디 찾기 ################################################*/
} else if ($_GET['rcm']) {
include_once(G5_THEME_PATH.'/head.sub.php');
?>
<div class="id_search">
<style type="text/css">
.id_search {padding:30px;}
.id_search li {float:left;width:32.3%;padding:0.5%;}
.id_search li:nth-child(3n+0) {width:32.4%;}
.id_search li span {display:block;padding:5px;line-height:18px;font-size:14px;border:solid 1px #ddd;cursor:pointer;}
.id_search li span:hover {background-color:#777;color:#fff;}
.infoBx {border:solid 2px rgba(39,48,62,0.4);border-radius:8px;margin-bottom:30px;}
.infoBx h3 {line-height:40px;font-size:15px;padding-left:20px;border-bottom:solid 1px rgba(0,0,0,0.1);background-color:rgba(39,48,62,0.05);}
</style>
<script>
$(function(){
	$('span[id^="id_"]').click(function () {
		var $id = $(this).attr("id").replace("id_","");
		$("#mb_recommend, #reg_mb_recommend",parent.document.body).val($id);
		$("#reg_mb_recommend",parent.document.body).focus();
		$("#framer",parent.document.body).attr("src","");
		$("#framewrp",parent.document.body).hide();
	});
});
</script>
<div class="infoBx">
	<h3>Referrer username research result</h3>
	<ul>
	<?
		$i = 0;
		$sql = " select mb_id, mb_email from g5_member where mb_leave_date = '' and mb_id != '{$_GET['mb_id']}' and (mb_id like '%{$_GET['rcm']}%' or mb_name like '%{$_GET['rcm']}%')  order by mb_id ";
		$qry = sql_query($sql);
		while ($res = sql_fetch_array($qry)) {
			if ($res['mb_id']) {
	?>
		<li><span id="id_<?=$res['mb_id']?>"><?=$res['mb_id']?><p>(<?=$res['mb_email']?>)</p></span></li>
	<?
				$i++;
			}
		}
		if ($i == 0) {
	?>
		<li>No result found</li>
	<?
		}
	?>
	</ul>
	<p class="clr"></p>
</div><!-- // infoBx -->
<script>
function close_ajax(){
	$("#reg_mb_recommend",parent.document.body).focus();
	$("#framer",parent.document.body).attr("src","");
	$("#framewrp",parent.document.body).hide();
}
</script>
		<div align="center" style="padding-top:30px">
		<input type="button" onclick="close_ajax()" value=" close ">
		</div>
</div><!-- // id_search -->
<?
include_once(G5_THEME_PATH.'/tail.sub.php');
/*## ajax 회원정보입력 ################################################*/

} else if ($_POST['mb_id']) {
	$mb_info = sql_fetch(" select mb_name, mb_tel, mb_hp, mb_zip1, mb_zip2, mb_addr1, mb_addr2, mb_addr3, mb_email from g5_member where mb_leave_date = '' and mb_id = '{$_POST['mb_id']}' ");
?>
<?=$mb_info['mb_name']?>^<?=$mb_info['mb_tel']?>^<?=$mb_info['mb_hp']?>^<?=$mb_info['mb_zip1']?><?=$mb_info['mb_zip2']?>^<?=$mb_info['mb_addr1']?>^<?=$mb_info['mb_addr2']?>^<?=$mb_info['mb_addr3']?>^<?=$mb_info['mb_email']?>
<?
} else if ($_POST['rcm_id']) {
	$rcm_id = trim($_POST['rcm_id']);
	$mb_info = sql_fetch(" select mb_id, mb_name from g5_member where mb_id = '{$rcm_id}' ");
	if ($mb_info) {
		echo "ok";
	} else {
		echo "break";
	}
}else{
/*@@End.  #####*/
include_once(G5_THEME_PATH.'/head.sub.php');
?>
<script>
function close_ajax(){
	$("#reg_mb_recommend",parent.document.body).focus();
	$("#framer",parent.document.body).attr("src","");
	$("#framewrp",parent.document.body).hide();
}
</script>
		<div align="center" style="padding-top:30px">
		<input type="button" onclick="close_ajax()" value=" close ">
		</div>
<?}?>

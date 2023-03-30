<?php
include_once('./_common.php');
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
		$("#mb_recommend, #reg_mb_mprecommend",parent.document.body).val($id);
		$("#reg_mb_mprecommend",parent.document.body).focus();
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
		$qry = sql_query(" select mb_id, mb_name from g5_member where mb_leave_date = '' and mb_id != '{$_GET['mb_id']}' and (mb_id like '%{$_GET[marketer]}%' or mb_name like '%{$_GET[marketer]}%') and is_marketer = 'Y' order by mb_id ");
		while ($res = sql_fetch_array($qry)) {
			if ($res['mb_id']) {
	?>
		<li><span id="id_<?=$res['mb_id']?>"><?=$res['mb_id']?><p>(<?=$res['mb_name']?>)</p></span></li>	
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
		$("#reg_mb_mprecommend",parent.document.body).focus();
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
?>

<?php
$sub_menu = "200100";
include_once('./_common.php');

$g5['title'] = "바이너리 포인트 추가 및 레그 수정";

include_once(G5_ADMIN_PATH.'/admin.head.php');
$mid = $mb_id;
$gb = "select mb_id, mb_lr, mb_brecommend_type from g5_member where mb_brecommend='$mid'";
$ret = sql_query($gb);


for ($i=0; $r=sql_fetch_array($ret); $i++) {	
	if($r['mb_lr']==1){
		$left_mb = $r['mb_id'];
		$left_mlr = 1;
		$left_mblegtype = $r['mb_brecommend_type'];
	}
	else{
		$right_mb = $r['mb_id'];
		$right_mlr = 2;
		$right_mblegtype = $r['mb_brecommend_type'];
	}
}

?>

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

</style>
 
<div class="adminWrp">
바이너리 레그 수정
<form name="site" method="post" action="./update_leg.php" enctype="multipart/form-data" style="margin:0px;">
	<input type="hidden"  name="mb_id" value="<?=$mid?>" />
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
            <col style="width:140px;"/>
            <col style="width:140px;"/>
        </colgroup>
        <tbody>
			<tr>
				<th> <?=$mb_id?>의 left 회원 아이디</th>
				<td><input type="text" name="left" value="<? echo $left_mb;?>" style="width:80%;"/></td>	
			</tr>
			<tr>
				<th><?=$mb_id?>의 right 회원 </th>
				<td><input type="text" name="right" value="<? echo $right_mb;?>" style="width:80%;"/></td>	
			</tr>
			<tr height='30'>
				<td align='center' colspan="2"><input type="submit" name="mod_leg" value="레그 수정" style="width:50%;"/></td>	
			</tr>
        </tbody>
    </table>
<!--	<div class="btn_confirm">
		<input type="submit" name="submit" class="confirm" value="저장하기" />
	</div> // btn_confirm // -->
</form>
<br>
바이너리 포인트 Iwol 데이터 추가
<form name="site" method="post" action="./iwol_insert.php" enctype="multipart/form-data" style="margin:0px;">
<input type="hidden"  name="iwol_receiver" value="<? echo $mid;?>" />
<input type="hidden"  name="iwol_kind" value="200" />
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
            <col style="width:140px;"/>
            <col style="width:140px;"/>
        </colgroup>
        <tbody>
			<tr>
				<th> 이월 포인트 수신자 </th>
				<td><input type="text" disabled value="<? echo $mid;?>" style="width:80%;"/></td>	
			</tr>
			<tr>
				<th> KIND (200고정) </th>
				<td><input type="text" disabled value="200" style="width:80%;"/></td>	
			</tr>
			<tr>
				<th> 추가할 하위 아이디입력 (Left or Right) </th>
				<td><input type="text" name="iwol_sender" value="" style="width:80%;"/></td>	
			</tr>
			<tr>
				<th> 추가할 PV (마이너스 입력 가능)</th>
				<td><input type="text" name="iwol_pv" value="" style="width:80%;"/></td>	
			</tr>
			<tr>
				<th> 추가할 Note </th>
				<td><input type="text" name="iwol_note" value="" style="width:80%;"/></td>	
			</tr>
			<tr height='30'>
				<td align='center' colspan="2"><input type="submit" name="iwol_insert" value="바이너리 데이터 입력" style="width:50%;"/></td>	
			</tr>

        </tbody>
    </table>

</form>
</div><!-- // adminWrp // -->
<?
include_once ('./admin.tail.php');
?>

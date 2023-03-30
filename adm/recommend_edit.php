<?php
$sub_menu = '100100';
include_once('./_common.php');

$g5['title'] = "추천인 변경";
include_once('../shop/head_popup.php');


$sql = "select * from g5_member where mb_id='".$edit_id."'";
$usr=sql_fetch($sql);

if ($w=="S"){
	include_once('../adm/inc.member.class.php');

	if ($gubun){
		$sql = "update g5_member set mb_brecommend='".$recommend_id."' where mb_id='".$edit_id."'";
	}else{
		$sql = "update g5_member set mb_recommend='".$recommend_id."' where mb_id='".$edit_id."'";
	}

	sql_query($sql);


	if ($gubun=="B"){

		$sql = "delete from g5_member_bclass where mb_id='".$member['mb_id']."'";
		sql_query($sql);

		get_brecommend_down($member['mb_id'],$member['mb_id'],'11');

		$sql  = " select * from g5_member_bclass where mb_id='{$member['mb_id']}' order by c_class asc";	
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$row2 = sql_fetch("select count(c_class) as cnt from g5_member_bclass where  mb_id='".$member['mb_id']."' and c_class like '".$row['c_class']."%'");
			$sql = "update g5_member set mb_b_child='".$row2['cnt']."' where mb_id='".$row['c_id']."'";
			sql_query($sql);
		}
	}else{

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
	}
?>
<script type="text/javascript">
<!--
	opener.document.sForm2.submit();
	self.close();
//-->
</script>
<?php
	exit;
}
?>
<script type="text/javascript">
<!--
function set_recommend(mb_id){
	document.sForm.recommend_id.value = mb_id;
	document.sForm.submit();
}
function del_recommend(mb_id){
	document.sForm.del_id.value = mb_id;
	document.sForm.submit();
}
//-->
</script>
<style type="text/css">
	th {font-size:12px !important}
	td {font-size:12px}
</style>
<div style="padding:20px 10px 20px 10px">
   
   
		<div style="border:2px solid #d7d7d7;padding:10px;margin-bottom:10px;width:88%;margin-left:17px">
		변경하실 회원 아이디 : <b><?=$edit_id?> (<?=$usr['mb_name']?>)</b>
		</div>

		<form name="tForm" id="tForm" action="recommend_edit.php" method="post">
		<input type="hidden" name="gubun" value="<?php echo $gubun?>">
		<input type="hidden" name="edit_id" value="<?php echo $edit_id?>">
		<input type="hidden" name="set_type" value="<?php echo $set_type?>">
		<input type="hidden" name="now_id" value="<?php echo $now_id?>">
			<div style="padding-left:20px;padding-bottom:5px">
			<select name="sfl" id="sfl">
			        <option value="mb_id"<?php echo get_selected($_REQUEST['sfl'], "mb_id"); ?>>회원아이디</option>
					<!-- <option value="mb_name"<?php echo get_selected($_REQUEST['sfl'], "mb_name"); ?>>이름</option> -->
					
				</select>

				<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class=" frm_input" style="width:100px" >
				<input type="submit" class="btn_submit" style="padding:5px" value="검 색">

			</div>
		</form>


<?if ($stx){?>
		<form name="sForm" id="sForm" action="recommend_edit.php" method="post">
		<input type="hidden" name="w" value="S">
		<input type="hidden" name="gubun" value="<?php echo $gubun?>">
		<input type="hidden" name="edit_id" value="<?php echo $edit_id?>">
		<input type="hidden" name="set_type" value="<?php echo $set_type?>">
		<input type="hidden" name="now_id" value="<?php echo $now_id?>">
		<input type="hidden" name="recommend_id" value="">
		<input type="hidden" name="del_id" value="">
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>신규가입회원</caption>
        <thead>
        <tr>
            <th width="30%" scope="col">회원아이디</th>
            <th width="20%" scope="col">지갑주소</th>
            <th width="25%" scope="col"><?if ($gubun=="B"){?>바이너리레그<?}else{?>추천인<?}?></th>
            <th width="25%" scope="col">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<?

		$sql = "select * from {$g5['member_table']} where  mb_leave_date = '' ";
		if ($stx) {
			$sql .= " and {$sfl} like '{$stx}%' ";
		}
		$sql .= " order by mb_name";


		$result = sql_query($sql);
		$group_id = "";
		$c_cnt    = 0;
		for ($j=0; $row=sql_fetch_array($result); $j++) {
?>
            <tr>
            <td style="text-align:center"><?=$row['mb_id']?></td>
            <td style="text-align:center"><?=$row['mb_name']?></td>
            <td style="text-align:center"><?if ($gubun=="B"){?><?=$row['mb_brecommend']?><?}else{?><?=$row['mb_recommend']?><?}?></td>
            <td style="text-align:center">
			<input type="button" onclick="set_recommend('<?=$row['mb_id']?>')" style="padding:4px 8px 4px 8px;border:0px;background:#364fa0;color:#ffffff;cursor:pointer" value="선택">
			
			</td>
			</tr>
<?
		}
?>
		</tbody>
		</table>
	</div>

<?}?>


		<div align=center style="padding:30px 0px 30px 0px">
		 <input type="button" value="close" onclick="self.close();" style="display:inline-block;padding:3px 7px 3px 7px;border:1px solid #3b3c3f;background:#4b545e;color:#ffffff;text-decoration:none;vertical-align:middle;cursor:pointer">
	
		</div>

	</form>
</div>


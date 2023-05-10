<?php
$sub_menu = "700020";
include_once('./_common.php');

$g5['title'] = "자사코인 설정";

include_once(G5_ADMIN_PATH.'/admin.head.php');

$update_sql = "update wallet_coin_price set ";
$update_column = "";
$update_value = "";
$update_where = "";

if($_POST['w'] == 'u'){

		for($i = 0; $i < count($_POST['idx']); $i++){

			$used = $_POST['used'][$_POST['idx'][$i]] ? 1 : 0;

			$update_sql = 
			"update wallet_coin_price set 
			name = '{$_POST['name'][$i]}',
			symbol = '{$_POST['symbol'][$i]}',
			current_cost = {$_POST['current_cost'][$i]},
			used = {$used}
			where idx = {$_POST['idx'][$i]} ";

			sql_query($update_sql);
		}

}


$coin_price_sql = "select * from wallet_coin_price order by idx asc";
$res = sql_query($coin_price_sql);
?>

<link rel="stylesheet" href="/adm/css/switch.css">
<link href="<?=G5_ADMIN_URL?>/css/scss/bonus/wallet.config.css" rel="stylesheet">

<style type="text/css">
	/* xmp {font-family: 'Noto Sans KR', sans-serif; font-size:12px;} */
	
</style>
<div class="local_desc01 local_desc">
	<p>
		- 자사 코인 사용유무에 체크를 하지 않으면 자동 시세가 적용됩니다.
	</p>
</div>

<div class="adminWrp mb50">

<form name="site" method="post" action="" onsubmit="return frmnewwin_check(this);" style="margin:0px;">
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
		<thead>
        <colgroup>
			<th class='no'>IDX</th>
			<th>코인명</th>
			<th>심볼명</th>
			<th>코인시세(달러)</th>
			<th>사용유무</th>
        </colgroup>
		</thead>

        <tbody>
			<? while($row = sql_fetch_array($res)){ ?>
				<tr>
					<input type='hidden' name='w' value='u'/>
					<td class='no'><input type='hidden' name='idx[]' value="<?=$row['idx']?>"/><?=$row['idx']?></td>
					<td ><input type='text' name='name[]' value="<?=$row['name']?>"/></td>
					<td ><input type='text' name='symbol[]' value="<?=$row['symbol']?>"/></td>
					<td ><input type='text' name='current_cost[]' value="<?=$row['current_cost']?>"/></td>
					<td style="display: flex;justify-content: center;">
						<input type='checkbox' id="<?=$row['idx']?>" name='used[<?=$row['idx']?>]' value="1" <?=$row['used'] ? "checked" : "" ?>/>
						<label for="<?=$row['idx']?>"></label>
					</td>
				</tr>
			<?}?>
		</tbody>
	</table>

	<div class='btn_ly '>
		<input type="submit" name="submit" class="btn wd btn_submit" value="저장하기" />
	</div>
</form>

</div><!-- // adminWrp // -->

<script>

$(document).ready(function(){

	
});


function frmnewwin_check(f)
{
	alert('변경되었습니다.');
	return true;
}

</script>



<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>



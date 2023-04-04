<?php
$sub_menu = "700050";
include_once('./_common.php');

$g5['title'] = "입/출금 설정";

include_once(G5_ADMIN_PATH.'/admin.head.php');

/* $has_wallet_addr = sql_query("SELECT wallet_addr from {$g5['wallet_config']} WHERE no =3");

if(!$has_wallet_addr){ 
	$add_wallet_addr = "ALTER TABLE {$g5['wallet_config']} ADD `wallet_addr` varchar(255) NOT NULL";
	$add_result = sql_query($add_wallet_addr);
} */

if($_POST['w'] == 'u'){
	
	for($i = 0 ; $i < count($_POST['idx']); $i ++){
		
		$idx = $_POST['idx'][$i];
		$fee = $_POST['fee'][$i];
		$function  = $_POST['function'][$i];

		$amt_minimum = $_POST['amt_minimum'][$i];
		$amt_maximum = $_POST['amt_maximum'][$i];
		$day_limit = $_POST['day_limit'][$i];
		$withdraw_price = $_POST['withdraw_price'][$i] ? $_POST['withdraw_price'][$i] : 0;

		$bank_name = $_POST['bank_name'][0];
		$bank_account = $_POST['bank_account'][0];
		$account_name = $_POST['account_name'][0];

		/* if($function == 'wallet_addr'){
			$blocksdk_update_sql = "UPDATE blocksdk_receiving_address set eth = '{$wallet_addr}' ";
			sql_query($blocksdk_update_sql);

			$wallet_addr = $_POST['wallet_addr'];
			$update_wallet_set = 
			"update {$g5['wallet_config']} set 
			wallet_addr = '{$wallet_addr}'
			where idx = $idx ;";

		}else */ 
		if($idx == 4){
			$update_wallet_set = 
			"update {$g5['wallet_config']} set 
			bank_name = '{$bank_name}',
			bank_account = '{$bank_account}',
			account_name = '{$account_name}'
			where idx = 4 ;";
			
		}else{
			$update_wallet_set = 
			"update {$g5['wallet_config']} set 
			fee = '{$fee}',
			amt_minimum = '{$amt_minimum}',
			amt_maximum = '{$amt_maximum}',
			day_limit = '{$day_limit}',
			withdraw_price = {$withdraw_price}
			where idx = $idx ;";
		}
		
		// print_R($update_wallet_set);
		$result = sql_query($update_wallet_set);
		
	}
	
}


$coin_price_sql = "select * from {$g5['wallet_config']} WHERE used > 0 order by no asc";
$res = sql_query($coin_price_sql);
?>

<link rel="stylesheet" href="/adm/css/switch.css">
<link href="<?=G5_ADMIN_URL?>/css/scss/bonus/wallet.config.css" rel="stylesheet">

<style type="text/css">
	/* xmp {font-family: 'Noto Sans KR', sans-serif; font-size:12px;} */
	
</style>
<div class="local_desc01 local_desc">
	<p>
		- 값을 0으로 설정하면 제한없음<br>
		<!-- - 입금지갑주소(회사)변경시 즉시 연동되어 회원개인지갑으로 입금시 해당 주소로 집금처리됩니다.<br> -->
		- 입금 계좌번호 변경시 즉시 연동되어 회원입금페이지에 반영됩니다.
	</p>
</div>

<div class="adminWrp mb50">

<form name="site" method="post" action="" onsubmit="return frmnewwin_check(this);" style="margin:0px;">
	<!-- <span class='help text-right'>[ 0 이면 제한없음 ]</span> -->
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
		<thead>
        <colgroup>
			<th class='no'>IDX</th>
			<th>구분</th>
			<th>CODE</th>
			<th>수수료 (%)</th>
			<th>최소값 제한 (고정값)</th>
			<th>최대값 제한 (%)</th>
			<th>출금회수제한(일)</th>
			<th>코인시세(원)</th>
        </colgroup>
		</thead>

        <tbody>
			<? while($row = sql_fetch_array($res)){ 
					$type = "hidden";
					$value = 0;
				?>
				<?if($row['idx'] != 4){?>
				<tr id='<?=$row['function']?>'>
					<input type='hidden' name='w' value='u'/>
					<td class='no'><input type='hidden' name='idx[]' value='<?=$row['idx']?>'><?=$row['idx']?></td>
					<td ><?=$row['function_kor']?></td>
					<td ><?=$row['function']?></td>

					<?if($row['idx'] == 3){?>
						<td colspan=4><input type='text' id='wallet_addr' name="wallet_addr" value='<?=$row['wallet_addr']?>'></td>
					<?}else{?>
						<td ><input type='text' name='fee[]' value="<?=$row['fee']?>"/></td>
						<td ><input type='text' name='amt_minimum[]' value="<?=$row['amt_minimum']?>"/></td>
						<td ><input type='text' name='amt_maximum[]' value="<?=$row['amt_maximum']?>"/></td>
						<td ><input type='text' name='day_limit[]' value="<?=$row['day_limit']?>"/></td>
						<?php if($row['function'] == 'withdrawal') {
								$type="text";
								$value = $row['withdraw_price'];
							}	
						?>
						<td ><input type='<?=$type?>' name='withdraw_price[]' value="<?=$value?>"/></td>
					<?}?>
				</tr>
				<?}?>
			<?}?>
		</tbody>
	</table>

	<!-- 은행계좌사용 -->
	<?
		$bank_used = sql_fetch("SELECT used from {$g5['wallet_config']} WHERE function = 'bank_account' ");
		if($bank_used['used'] > 0){
	?>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb" style='margin-top:30px;'>
		<thead>
        <colgroup>
			<th class='no'>IDX</th>
			<th>구분</th>
			<th>CODE</th>
			<th>은행명</th>
			<th colspan="2">계좌번호 </th>
			<th>예금주</th>
        </colgroup>
		</thead>

        <tbody>
			<? 
			$coin_price_sql = "select * from {$g5['wallet_config']} WHERE used = 1 AND no = 4";
			$res_bank = sql_query($coin_price_sql);

			while($row = sql_fetch_array($res_bank)){ ?>
				<tr id='<?=$row['function']?>'>
				<input type='hidden' name='w' value='u'/>
				<input type='hidden' name='function' value='bank_account'/>
					<td class='no'><input type='hidden' name='idx[]' value='<?=$row['idx']?>'><?=$row['idx']?></td>
					<td ><?=$row['function_kor']?></td>
					<td ><?=$row['function']?></td>

					<td><input type='text' name='bank_name[]' value="<?=$row['bank_name']?>"/></td>
					<td colspan="2"><input type='text' name='bank_account[]' value="<?=$row['bank_account']?>"/></td>
					<td><input type='text' name='account_name[]' value="<?=$row['account_name']?>"/></td>

				</tr>
			<?}?>
		</tbody>
	</table>
	<?}?>

	
	<div class='btn_ly '>
		<!-- <button type='button' class="btn btn_wd btn_double blue" onclick="go_to_URL('/coin_price_curl.php?url=/adm/bonus/config_price.php');"> 코인 시세 수동 갱신</button> -->
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



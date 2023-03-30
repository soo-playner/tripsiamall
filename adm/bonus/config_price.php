<?php
$sub_menu = "700080";
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

$g5['title'] = "통화 시세 설정";

include_once(G5_ADMIN_PATH.'/admin.head.php');

$coin_price_sql = "select * from {$g5['coin_price']} WHERE used = 1";
$res = sql_query($coin_price_sql);
?>

<link rel="stylesheet" href="/adm/css/switch.css">
<link href="<?=G5_ADMIN_URL?>/css/scss/bonus/config_price.css" rel="stylesheet">

<div class="adminWrp mb50">

<form name="site" method="post" action="./config_price.proc.php" onsubmit="return frmnewwin_check(this);" style="margin:0px;">

	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
		<thead>
			<th style="width:80px;">아이콘</th>
			<th style="width:100px;">통화</th>
			<th style="width:100px;">기준</th>
			<th class="th_currency">마켓 시세(12h/day)</th>
			<th class="th_currency">적용 시세(-2%)</th>
			<th>자동 시세 반영시간(서버시간)</th>
			<th>증감율표시</th>
			<th>증감율차트</th>
			<th>수동 설정 사용유무</th>
			<th>수동 설정 비율값(%)</th>
		</thead>

        <tbody>
			<? while($row = sql_fetch_array($res)){ ?>
				<tr>
					<input type='hidden' name='idx[]' value='<?=$row['idx']?>'>
					<td class='icon'><img src="<?=$row['icon']?>"/></td>
					<td class='name'><?=$row['name']?></td>
					<td class='text-center'>1 <?=strtoupper($row['symbol'])?> / $</td>
					<td class='currency'>$ <span style='color:crimson'><?=shift_coin($row['current_cost'])?></span> </td>	
					<td class='currency'>$ <span style='color:crimson'><?=shift_coin($row['current_cost'])?></span> </td>	
					<td><?=$row['update_time'];?> </td>
					<td><input type="text" name='changepricedaily[]' value="<?=$row['changepricedaily']?>"/> </td>
					<td class='chart'><img src="<?=$row['chart']?>"/> </td>
					<td> 
						<p style="padding:0;">
							<input type="checkbox" id="<?=$row['symbol']?>_use" name="manual_use[]" class="nw_with" value='<?=$row['manual_use']?>' <?if($row['manual_use'] == 1) {echo "checked";}?>/>
							<label for="<?=$row['symbol']?>_use"><span class="ui"></span><span class="nw_with_txt">사용 설정</span></label>
						</p>
					</td>

					<td><input type="text" name="manual_cost[]" value="<? echo $row['manual_cost'];?>" style="width:80%;"/></td>
				</tr>
			<?}?>
		</tbody>
	</table>
	
	<div class='btn_ly '>
		
		<button type='button' class="btn btn_wd btn_double blue" onclick="go_to_URL('<?=G5_URL?>/m3cron/index_coin_rate.php?url=/adm/bonus/config_price.php');"> 코인 시세 수동 갱신</button>
		
		<?php 
			$btn_double = "btn_double";
			 ?>
		<input type="submit" name="submit" class="btn btn_wd btn_submit <?=$btn_double?>" value="저장하기" />
	</div>
	
</form>
</div><!-- // adminWrp // -->


<script>

$(document).ready(function(){

	$('.nw_with').on('click',function(){

		if($(this).is(":checked")){
			//$(this).attr("checked", true);
			$(this).val('1');
			$(this).parent().find('.nw_with_txt').html('사용함');
		}else{
			//$(this).attr("checked",false);
			$(this).val('2');
			$(this).parent().find('.nw_with_txt').html('사용안함');
		}
	});
});


function frmnewwin_check(f)
{
	
	$('input[type=checkbox]').each(function() {
		if(this.value == "2"){ //값 비교
			this.checked = true; //checked 처리
			//console.log(this.value);
		}
	});

	return true;

}

</script>



<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>



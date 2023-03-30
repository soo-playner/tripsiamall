<?php
$sub_menu = '100610';

include_once('./_common.php');
include_once(G5_LIB_PATH.'/crypto.lib.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = "코인결제 설정";
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/sub.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/blocksdk_admin.css">', 0);

include_once(G5_ADMIN_PATH.'/admin.head.php');

$block_config = Crypto::GetConfig();
$receiving_address = Crypto::GetReceivingAddress();

?>

<div class="pop_bg"></div>

<!-- 여기 아래부터 모든 HTML 요소 구성 시작 -->
<div class="first-container">
	<div class="submit_pop" id="popup">
		<div class="pop_setting">
			<div class="popup_head">
				<a href="javascript:;" class="pop_close"></a>
				<h3>BlcokSDK</h3>
				<p>시드키는 한번만 생성됩니다. 기억해두십시오.</p>
			</div>
			<div class="popup_content">
				<div class="pop_container">
					<div class="cdkey_btn">
						<img src="/adm/img/pop_bitcoin.png" width=30 height=30>
						<input id="seed_btc" readonly onclick="this.select(); document.execCommand('copy'); alert('비트코인의 시드키가 복사되었습니다.')"></input>
					</div>
					<div class="cdkey_btn">
						<img src="/adm/img/pop_bitcoin_cash.png" width=35 height=35 class="bitcoincash_icon">
						<input id="seed_bch" readonly onclick="this.select(); document.execCommand('copy'); alert('비트코인캐시의 시드키가 복사되었습니다.')" ></input>
					</div>
					<div class="cdkey_btn">
						<img src="/adm/img/pop_litecoin.png" width=30 height=30>
						<input id="seed_ltc" readonly onclick="this.select(); document.execCommand('copy'); alert('라이트코인의 시드키가 복사되었습니다.')" ></input>
					</div>
					<div class="cdkey_btn">
						<img src="/adm/img/pop_dash.png" width=30 height=30>
						<input id="seed_dash" readonly onclick="this.select(); document.execCommand('copy'); alert('대시코인의 시드키가 복사되었습니다.')" ></input>
					</div>
				</div>
				<p>클릭하면 복사됩니다.</p>
			</div>
		</div>
	</div>
	

	<div class="tbl_head01 tbl_wrap">
		<table>
			<thead>
				<tr class="bg0">
					<th scope="col">그룹</th>
					<th scope="col">주소</th>
					<th scope="col">관리</th>
				</tr>
			</thead>
			<tbody>
				<tr class="bg0">
					<td class="td_left" style="width: 400px;">BlockSDK 토큰값</td>
					<td><input class="tbl_input" name="blocksdk_token" type="text" required="" value="<?php echo substr_replace(get_sanitize_input(Crypto::Decrypt($block_config['blocksdk_token'])),'**********',10); ?>" placeholder="Please Blocksdk API Token Input."></td>
					<td class="td_mng td_mng_s"><button type="submit" name="button" class="btn_03 btn" onclick="blocksdk_save();">저장</button></td>
				</tr>
			</tbody>
		</table>
		<div class="subrow">
			<a href="https://blocksdk.com/" target="_blank"><u class="sdklink">https://blocksdk.com/</u> API 토큰 값을 받으세요!</a>
		</div>
	</div>

	<form name="transaction_token" action="<?php echo G5_URL?>/adm/blocksdk/coinmarketcap_save.php" method="post">
		<div class="tbl_head01 tbl_wrap">
			<table>
				<thead>
					<tr class="bg0">
						<th scope="col">그룹</th>
						<th scope="col">주소</th>
						<th scope="col">관리</th>
					</tr>
				</thead>
				<tbody>
					<tr class="bg0">
						<td class="td_left" style="width: 400px;">coinmarketcap 토큰값</td>
						<td><input class="tbl_input" name="coinmarketcap_token" type="text" required="" value="<?php echo get_sanitize_input($block_config['coinmarketcap_token']); ?>" placeholder="Please transaction API Token Input."></td>
						<td class="td_mng td_mng_s"><button type="submit" name="button" class="btn_03 btn" >저장</button></td>
					</tr>
				</tbody>
			</table>
			<div class="subrow">
				<a href="https://coinmarketcap.com/api/" target="_blank"><u class="sdklink">https://coinmarketcap.com/api/</u> API 토큰 값을 받으세요!</a>
			</div>
		</div>
	</form>
	<form action="<?php echo G5_URL?>/adm/blocksdk/payment_save.php" method="post" >
		<div class="transaction-management">
			<h2 class="h2_frm">결제 관리</h2>
			<div class="activebox">
			</div>
			<div class="tbl_head01 tbl_wrap">
				<table>
					<thead>
						<tr>
							<th scope="col">
								<input type="checkbox" id="th_checkAll" onchange="checkAll2();"/>
							</th>
							<th scope="col">결제 수단</th>
							<th scope="col">활성화</th>
						</tr>
					</thead>
					<tbody>
						<tr class="bg0">
							<td><input type="checkbox" name="btc" id="bitcoin" value="1" <?php if(empty($block_config['de_btc_use'])==false){echo("checked");} ?>/></td>
							<td class="td_left"><img src="/adm/img/bitcoin.png" width=40 height=40 class="coinimg"><label for="bitcoin">비트코인</label></td>
							<td rowspan="6" style="width: 60px;"><button type="submit" name="button" class="btn_03 btn">저장</button></td>
						</tr>
						<tr class="bg0">
							<td><input type="checkbox" name="bch" id="bitcoincash" value="1" <?php if(empty($block_config['de_bch_use'])==false){echo("checked");} ?> /></td>
							<td class="td_left"><img src="/adm/img/bitcoin_cash.png" width=40 height=40 class="coinimg"><label for="bitcoincash">비트코인캐시</label></td>
						</tr>
						<tr class="bg0">
							<td><input type="checkbox" name="ltc" id="litecoin" value="1" <?php if(empty($block_config['de_ltc_use'])==false){echo("checked");} ?> /></td>
							<td class="td_left"><img src="/adm/img/litecoin.png" width=40 height=40 class="coinimg"><label for="litecoin">라이트코인</label></td>
						</tr>
						<tr class="bg0">
							<td><input type="checkbox" name="eth" id="ethereum" value="1" <?php if(empty($block_config['de_eth_use'])==false){echo("checked");} ?> /></td>
							<td class="td_left"><img src="/adm/img/ethereum.png" width=40 height=40 class="coinimg"><label for="ethereum">이더리움</label></td>
						</tr>
						<tr class="bg0">
							<td><input type="checkbox" name="dash" id="dash" value="1" <?php if(empty($block_config['de_dash_use'])==false){echo("checked");} ?> /></td>
							<td class="td_left"><img src="/adm/img/dash.png" width=40 height=40 class="coinimg"><label for="dash">대시</label></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<form action="<?php echo G5_URL?>/adm/blocksdk/receive_save.php" method="post" >
		<div class="tbl_head01 tbl_wrap">
			<h2 class="h2_frm">입금 받을 코인 주소</h2>
				<table>
					<thead>
						<tr class="bg0">
							<th scope="col">코인</th>
							<th scope="col">주소(Address)</th>
							<th scope="col">관리</th>
						</tr>
					</thead>
					<tbody>
						<tr class="bg0">
							<td class="td_left"><span>비트 코인</span><span class="tooltip" data-tooltip-text="이 암호화폐를 건너뛰려면 비워 두십시오"></span></td>
							<td><input type="text" class="tbl_input" name="btc-address" id="btc"  size="64px;" value="<?php echo get_sanitize_input($receiving_address['btc']); ?>" placeholder="예시) 1GC3q7JYPuQPTSBfJbmTViaVDv5pcZF2Va"></td>
							<td rowspan="6" style="width: 60px;" class="td_mng td_mng_s"><button type="submit" name="button" class="btn_03 btn" >저장</button></td>
						</tr>
						<tr class="bg0">
							<td class="td_left">
								<span>비트 코인 캐시</span><span class="tooltip" data-tooltip-text="이 암호화폐를 건너뛰려면 비워 두십시오"></td>
							<td><input type="text" class="tbl_input" name="bch-address" id="bch"  size="64px;" value="<?php echo get_sanitize_input($receiving_address['bch']); ?>" placeholder="예시) qphxdtaaftx4qepjhqmyyd4vj7sy0dz7t53vj4dd6f"></td>
						</tr>
						<tr class="bg0">
							<td class="td_left">
								<span>라이트 코인</span><span class="tooltip" data-tooltip-text="이 암호화폐를 건너뛰려면 비워 두십시오">
							</td>
							<td><input type="text" class="tbl_input" name="ltc-address" id="ltc" size="64px;" value="<?php echo get_sanitize_input($receiving_address['ltc']); ?>" placeholder="예시) Ld11GMM6dP5QLNTYqSYazvWEwhy8rC2Bnm"></td>
						</tr>
						<tr class="bg0">
							<td class="td_left">
								<span>이더리움 코인</span><span class="tooltip" data-tooltip-text="이 암호화폐를 건너뛰려면 비워 두십시오">
							</td>
							<td><input type="text" class="tbl_input" name="eth-address" id="eth" size="64px;"  value="<?php echo get_sanitize_input($receiving_address['eth']); ?>" placeholder="예시) 0xa50595b2ef5c1a010a1781be32f35dffb3e5bc70"></td>
						</tr>
						<tr class="bg0">
							<td class="td_left">
								<span>대시 코인</span><span class="tooltip" data-tooltip-text="이 암호화폐를 건너뛰려면 비워 두십시오">
							</td>
							<td><input type="text" class="tbl_input" name="dash-address" id="dash" size="64px;" value="<?php echo get_sanitize_input($receiving_address['dash']); ?>" placeholder="예시) XyoUviM9BVd913PhmL4UysswsRWp5nna5c"></td>
						</tr>
					</tbody>
				</table>
				<div class="subrow">
					<a href="https://blocksdk.com/" target="_blank">도움이 필요하거나 제안 사항이 있으면 <u class="sdklink">웹 사이트</u> 의 라이브 채팅을 통해 문의하십시오.</a>
				</div>
			</div>
		</form>
</div>
<!-- 여기 아래부터 모든 HTML 요소 구성 끝 -->
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>

<script>
function checkAll2(){
	  if( $("#th_checkAll").is(':checked') ){
		$("input[name=btc]").prop("checked", true);
		$("input[name=bch]").prop("checked", true);
		$("input[name=ltc]").prop("checked", true);
		$("input[name=eth]").prop("checked", true);
		$("input[name=dash]").prop("checked", true);
		$("input[name=xmr]").prop("checked", true);
	  }else{
		$("input[name=btc]").prop("checked", false);
		$("input[name=bch]").prop("checked", false);
		$("input[name=ltc]").prop("checked", false);
		$("input[name=eth]").prop("checked", false);
		$("input[name=dash]").prop("checked", false);
		$("input[name=xmr]").prop("checked", false);
	  }
}


function blocksdk_save(){
	if(!confirm("토큰값 재설정시 기존에 발급된 주소는 초기화되며 사용할수없습니다. 그래도 진행하시겠습니까?")){
		return;
	}
	
	$.ajax({
		type : 'POST',
		url : '/adm/blocksdk/blocksdk_save.php',
		data : {
			blocksdk_token : $('.tbl_input').val()
		},
		dataType : 'json',
		success : function(data){
			if(data['error']){
				alert(data['error']['message']);
				return;
			}
			
			$("#seed_btc").val(data['btc']);
			$("#seed_bch").val(data['bch']);
			$("#seed_ltc").val(data['ltc']);
			$("#seed_dash").val(data['dash']);
			$(".submit_pop").css("display","block");
			$(".pop_bg").css("display","block");
		},
		error: function(error){
			alert('error');
		}
	});
}
$('.pop_close,.pop_bg').click(function(){
	location.reload(true);
});
</script>

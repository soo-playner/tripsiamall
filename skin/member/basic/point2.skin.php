<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="//cdn.jsdelivr.net/gh/moonspam/NanumSquare@1.0/nanumsquare.css">', 0);
add_stylesheet('<link rel="stylesheet" href="/css/blocksdk_point.css">', 0);
?>


<!-- <?
$ethClient = Crypto::GetClient("eth");
$address = $ethClient->createAddress([
    "name" => "test address"
]);

?> -->

<a href="https://blocksdk.com/ko" target="_blank">
<img class="logo" src="https://blocksdk.com/image/logo_black.png"/>
</a>

<div class="w-container">
    <div class="cover">
        <div class="form">
            <div class="pay-block">

				<?php if($blocksdk_conf['de_btc_use'] == 1){?>
					<div class="row">
						<div class="sc2-label">비트코인</div>
						<input  class="address" type="text" data-address="<?php echo($member['mb_6']); ?>" value="<?php echo($member['mb_6']); ?>"/>
					</div>
				<?php } ?>
				
				<?php if($blocksdk_conf['de_bch_use'] == 1){?>
					<div class="row">
						<div class="sc2-label">비트코인 캐시</div>
						<input  class="address" type="text" data-address="<?php echo($member['mb_7']); ?>" value="<?php echo($member['mb_7']); ?>"/>
					</div>
				<?php } ?>

				<?php if($blocksdk_conf['de_ltc_use'] == 1){?>
					<div class="row">
						<div class="sc2-label">라이트코인</div>
						<input  class="address" type="text" data-address="<?php echo($member['mb_8']); ?>" value="<?php echo($member['mb_8']); ?>"/>
					</div>
				<?php } ?>

				<?php if($blocksdk_conf['de_eth_use'] == 1){?>
					<div class="row">
						<div class="sc2-label">이더리움</div>
						<input  class="address" type="text" data-address="<?php echo($member['mb_9']); ?>" value="<?php echo($member['mb_9']); ?>"/>
					</div>
				<?php } ?>

				<?php if($blocksdk_conf['de_dash_use'] == 1){?>
					<div class="row">
						<div class="sc2-label">대시</div>
						<input  class="address" type="text" data-address="<?php echo($member['mb_10']); ?>" value="<?php echo($member['mb_10']); ?>"/>
					</div>
				<?php } ?>
            
            </div>  
        </div>
		
		<p class="p0">주소에 암호화폐를 전송하면 포인트가 충전됩니다</p>
		<ul class="exts">
			<li>
				<p class="p1">무엇을 해야 하나요?</p>
				<p class="p2">암호화폐 거래소 또는 지갑을 이용하여 위에 표시된 암호화폐 주소에 입금된 금액에 맞게 자동환율 계산이되어 포인트충전이 자동으로 처리됩니다</p>
			</li>
			
			<li>
				<p class="p1">완료될 때까지 기다려야 하나요?</p>
				<p class="p2">컨펌이 날때까지 기다리지 않으셔도 됩니다. 이창을 닫아도 코인을 보내셧다면 자동으로 완료처리가 됩니다</p>
			</li>			
			
			<li>
				<p class="p1">다른주소로 전송하면 어떻게되나요?</p>
				<p class="p2">암호화폐 특성상 다른주소에 전송하셧다면 저희가 복구를 진행해드릴 방법이 전혀 없습니다.</p>
			</li>				
		</ul>
    </div>
</div>


<script>
$(function(){
	$("input[type=text].address").click(function(e){
		var coin_address = $(this)[0];
		coin_address.select();
		document.execCommand('copy');
		alert("주소가 복사 되었습니다")
	});	
	
	$("#point .point_all .full_li button").click(function(e){
		var t = $(this);
		var symbol = t.data("coin");
		
		$.ajax({
			url : '/plugin/blocksdk/ajax.create-coin-address.php',
			type: 'post',
			data: {
				coin : symbol
			},
			dataType: 'json',
			success: function(data){
				if(data.success == true){
					this.closest("span").html(data.address);
				}
				alert(data.message);
			}.bind(t)
		});
	});
	$(".exts li .p1").click(function(e){
		var t = $(this);
		if(t.data("active") != "on"){
			t.closest("li").find(".p2").css({
				"max-height":"1000px",
				"padding":"12px"
			});
			t.data("active","on");
		}else{
			t.closest("li").find(".p2").css({
				"max-height":"0",
				"padding":"0 12px"
			});
			t.data("active","off");
		}
	});	
});
</script>
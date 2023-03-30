<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>
<div id="visWrp">
<style type="text/css">
#visWrp {color:#fff;text-align:center;height:300px;line-height:300px;background:url(/img/bg.jpg) no-repeat center;background-color:#121F37;}
#visWrp h1 {font-size:20px;font-weight:normal;}



</style>
	<h1>국내 최초 가상화폐 거래소</h1>
</div><!-- // visWrp -->

<?
/*## status ################################################*/
?>
<div class="idx_status">
<style type="text/css">
.idx_status {color:#fff;padding:20px;background-color:#1D252E;}
.status_Grp {border-bottom:solid 1px #ddd;}

ul.status_ul {margin-bottom:10px;}
ul.right {}
ul.status_ul li {height:30px;line-height:30px;}
ul.status_ul li dt {display:inline-block;*display:inline;*zoom:1;width:30%;}
ul.status_ul li dd {display:inline-block;*display:inline;*zoom:1;width:30%;text-align:right;}
ul.status_ul li dd:last-child {float:right;}
ul.status_ul li dd.up {color:#ec5457;}
ul.status_ul li dd.dn {color:#3a87ad;}
ul.status_ul li.th {border-bottom:solid 1px #ddd;}
ul.status_ul li:hover {background:rgba(255,255,255,0.1);}
.total_status {line-height:30px;font-size:13px;text-align:center;}
.total_status span {display:inline-block;*display:inline;*zoom:1;font-size:16px;}


/*
ec5457
3a87ad
*/
</style>
	<div class="Grp">

		<div class="status_Grp">
			<ul class="status_ul">
				<li class="th">
					<dl>
						<dt>통화</dt>
						<dd>현재가 (￦)</dd>
						<dd>변동 (24시간)</dd>
					</dl>
				</li>
				<?
					for ($i=0;$i<6;$i++){
				?>
				<li>
					<dl>
						<dt>리플</dt>
						<dd><?=number_format(2746)?></dd>
						<dd class="<?=($i%2==0)?"up":"dn"?>">1.24 % <?=($i%2==0)?"<i class='fa fa-caret-up' aria-hidden='true'></i>":"<i class='fa fa-caret-down' aria-hidden='true'></i>"?></dd>
					</dl>
				</li>
				<?
					}
				?>
			</ul>

			<ul class="status_ul right">
				<li class="th">
					<dl>
						<dt>통화</dt>
						<dd>현재가 (￦)</dd>
						<dd>변동 (24시간)</dd>
					</dl>
				</li>
				<?
					for ($i=0;$i<6;$i++){
				?>
				<li>
					<dl>
						<dt>리플</dt>
						<dd><?=number_format(2746)?></dd>
						<dd class="<?=($i%2==0)?"up":"dn"?>">1.24 % <?=($i%2==0)?"<i class='fa fa-caret-up' aria-hidden='true'></i>":"<i class='fa fa-caret-down' aria-hidden='true'></i>"?></dd>
					</dl>
				</li>
				<?
					}
				?>
			</ul>
			<p class="clr"></p>

		</div><!-- // status_Grp -->

		<div class="total_status">
			24시간 거래량 (￦)
			<span><?=number_format(1564651311231561)?></span>
		</div><!-- // total_status -->		

	</div><!-- // Grp -->
</div><!-- // idx_status -->
<?
/*@@End. status #####*/
?>





<?
/*## idx_icons ################################################*/
?>
<div class="idx_icons">
<style type="text/css">
.idx_icons {padding:30px 0;}
ul.twice_ul > li {text-align:center;margin-bottom:40px;line-height:24px;}
ul.twice_ul > li:nth-child(2n+0) {float:right;}
ul.twice_ul > li img {margin-bottom:20px;}
ul.twice_ul > li h3 {font-size:22px;line-height:40px;}
ul.twice_ul > li div {width:80%;margin:0 auto;word-break:keep-all;}
</style>
	<div class="Grp">
		<ul class="twice_ul">
			<li>
				<p><img src="/img/i01.png" alt="" /></p>
				<h3>다양한 디지털 자산 거래</h3>
				<div>
					코빗은 한국에 최초로 설립된 가상화폐 거래소 입니다. 비트코인, 이더리움 뿐만 아니라 라이트코인, 모네로, 지캐시, 어거, 대시 와 같은 알트코인 거래도 가능합니다.
				</div>
			</li>
			<li>
				<p><img src="/img/i02.png" alt="" /></p>
				<h3>안전한 자산관리 시스템</h3>
				<div>
					고객님들이 코빗에 예치하신 자산의 대부분은 외부 공격이 불가능한 오프라인 저장소, ‘콜드웰렛’ 에 보관되어 있습니다. 해킹으로부터 고객님들의 소중한 자산을 안전하게 보호하고 있습니다.
				</div>
			</li>
			<li>
				<p><img src="/img/i03.png" alt="" /></p>
				<h3>믿을 수 있는 기술력</h3>
				<div>
					코빗은 한국 최초 거래소로 동종업계 중 가장 오랜 거래소 운영 경험이 있습니다. 수년에 걸친 노하우로 안정적인 서버와 최고 수준의 보안시스템을 제공합니다.
				</div>
			</li>
			<li>
				<p><img src="/img/i04.png" alt="" /></p>
				<h3>최고의 고객센터</h3>
				<div>
					90%이상의 서비스레벨을 기록하는 고객센터를 운영하고 있습니다. 이메일문의는 1 영업일 기준으로 답변을 제공합니다.신속하고 전문적인 상담으로 높은 고객 신뢰와 만족을 얻기 위해 노력합니다.
				</div>
			</li>
		</ul>	
		<p class="clr"></p>
	</div><!-- // Grp -->

</div><!-- // idx_icons -->
<?
/*@@End. .idx_icons  #####*/
?>

<style type="text/css">
h3.wide_title {text-align:center;line-height:30px;}
h3.wide_title span {display:inline-block;*display:inline;*zoom:1;padding:0 30px;background-color:#fff;font-size:26px;font-weight:normal;color:#333;}
h3.wide_title:after {display:block;position:absolute;border-bottom:solid 1px #ddd;content:" ";width:100%;margin-top:-15px;z-index:-1;}
</style>
<h3 class="wide_title">
	<span>Our Investors</span>
</h3><!-- // wide_title -->

<p class="blk" style="height:30px;"></p>

<div class="Grp">
<style type="text/css">
ul.fourth_ul_padding {}
ul.fourth_ul_padding li {float:left;width:40%;margin:0 5%;text-align:center;margin-bottom:20px;}
ul.fourth_ul_padding li img {max-width:100%;}

</style>
	<ul class="fourth_ul_padding">
		<li><a href="#"><img src="https://d3esrl798jsx2v.cloudfront.net/landing_page/investors/softbank.png" alt="" /></a></li>
		<li><a href="#"><img src="https://d3esrl798jsx2v.cloudfront.net/landing_page/investors/softbank.png" alt="" /></a></li>
		<li><a href="#"><img src="https://d3esrl798jsx2v.cloudfront.net/landing_page/investors/softbank.png" alt="" /></a></li>
		<li><a href="#"><img src="https://d3esrl798jsx2v.cloudfront.net/landing_page/investors/softbank.png" alt="" /></a></li>
	</ul>
	<p class="clr"></p>
</div><!-- // Grp -->

<p class="blk" style="height:80px;"></p>

<?
/*## product ################################################*/
?>
<div class="idx_product">
<style type="text/css">
.idx_product {padding:30px 0;background-color:#F5F8FB;color:#333;}
.idx_product .Grp {text-align:center;}
.idx_product img {width:250px;}
.idx_product h3 {font-weight:normal;font-size:22px;font-weight:700;line-height:50px;}
.idx_product p {padding:0 20px;}
.idx_product a {display:inline-block;*display:inline;*zoom:1;padding:0 20px;height:40px;line-height:40px;color:#90D9E3;border:solid 1px #90D9E3;border-radius:5px;}
.idx_product a:hover {background-color:#90D9E3;color:#fff;}
</style>
	<div class="Grp">
		<img src="/img/icon.jpg" alt="" />
		<p class="blk" style="height:40px;"></p>
		<h3>세계 최초 원화 거래소</h3>
		<p>세계 최초 원화/비트코인 거래소 코빗은 고객 중심 서비스와 앞선 기술로 비트코인 트레이딩 마켓을 선도합니다.</p>
		<p class="blk" style="height:30px;"></p>
		<a href="#">지금 시작히기</a>
	</div><!-- // Grp -->
</div><!-- // idx_product -->
<?
/*@@End. product #####*/
?>



<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>
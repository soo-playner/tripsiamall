<script>
$(function(){
	prdslide();
});
$(window).resize(function() {
	prdslide();
});
function prdslide() {
	var $dw = 220;
	var $dh = 220;
	var $dmt = parseInt($dh / $dw * 100) / 100;
	var $w = ($(window).width() - 20) / 2; // 20은 padding:10px;
	var $h = parseInt($w * $dmt);
	$('#prd_slide').width($w*2);
	$('.prdBx, div[class^="prdBx_thumb"]').width($w);
	$('#prd_slide').height($w + 84);
	$('.prd_Wrp, .prdBx').height($w + 54);

	$('div[class^="prdBx_thumb"]').css({"height":$w+"px","line-height":$w+"px"});

	$('#prd_slide .prd_Wrp').carouFredSel({
		items: { visible:2,minimum:null,start:0},
		circular: true,
		scroll: {
			items: 1,
			fx: "scroll",
			easing: "swing"
	    },
//		scroll:{ pauseOnHover : true},
		auto : { pauseDuration : 1200000 },
		padding : null,
//		prev : {  button  : "#prd_slide .prev" },
//		next : {  button  : "#prd_slide .next" },
		pagination  : "#prd_slide .pagination"
	});
}
</script>
<style type="text/css">
/* prd_slide common */
#prd_slide {position:relative;padding:0;width:1512px;height:312px;margin:0 auto;overflow:hidden;}
#prd_slide div.prd_Wrp {height:312px;overflow:hidden;}
#prd_slide div.prdBx {float:left;position:relative;width:252px;height:312px;margin:0;padding:0;}
#prd_slide div.prev {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:-846px;margin-top:214px;}
#prd_slide div.prev img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#prd_slide div.prev img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#prd_slide div.next {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:560px;margin-top:214px;}
#prd_slide div.next img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#prd_slide div.next img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#prd_slide span.pagination {position:absolute;left:50%;margin-left:-150px;width:300px;height:40px;line-height:40px;text-align:center;}
#prd_slide span.pagination a {display:inline-block;*display:inline;*zoom:1;width:15px;height:15px;margin:0 5px;background-color:#bfbfbf;border-radius:50%;}
#prd_slide span.pagination a.selected {background-color:#bc8554;}
#prd_slide span.pagination a span { display:none; }
#prd_slide div.hide { top:-10000px!important; }

#prd_slide div.prdBx {}
#prd_slide div.prdBx .prdBx_thumb {padding:0;width:252px;height:252px;line-height:252px;text-align:center;}
#prd_slide div.prdBx .prdBx_thumb img {width:100%;height:auto;}

#prd_slide div.prdBx .sbj {line-height:18px;height:36px;overflow:hidden;}
#prd_slide div.prdBx .sbj a {color:#222;font-size:12px;font-family:"nngdb";}
#prd_slide div.prdBx .price {text-align:center;color:#bc8554;font-family:"nngdb";font-size:12px;}

#prd_slide div.prdBx .tag {position:absolute;width:30px;height:30px;font-family:"nngdb";font-size:11px;color:#fff;text-align:center;}
#prd_slide div.prdBx .tag p {padding-top:2px;font-size:8px;font-family:"nngd";line-height:10px;}

</style>
<div class="taC">
	<h3 class="shopTitle">BEST PRODUCT<br/><span><?=$config['cf_title']?>의 가장 인기 있는 상품입니다.</span></h3>
</div><!-- // taC -->

<p class="blk" style="height:20px;"></p>

<div id="prd_slide">
<!--▼ prd_slide ▼-->
	<!-- // <div class="prev"><img src="/img/vrv.png" alt="" /></div>
	<div class="next"><img src="/img/vxt.png" alt="" /></div> // -->
	<div class="prd_Wrp">

	<?
		$qry = sql_query(" select * from g5_shop_item where it_use = '1' and it_type1 = '1' order by it_time desc ");
		for ($i=0;$prd[$i]=sql_fetch_array($qry);$i++){
	?>
		<div class="prdBx">
			<div class="tag" style="background-color:#<?=($i<4)?"bc8554":"5f5f5f"?>"><p>BEST</p><?=$i+1?></div>
			<div class="prdBx_thumb">
				<a href="/shop/item.php?it_id=<?=$prd[$i]['it_id']?>"><?=get_it_image($prd[$i]['it_id'], 220, 220, '', '', stripslashes($prd[$i]['it_name']))."\n";?>	</a>		
			</div><!-- // thumb -->
			<p class="sbj"><a href="/shop/item.php?it_id=<?=$prd[$i]['it_id']?>"><?=stripslashes($prd[$i]['it_name'])?></a></p>
			<p class="price"><?=display_price(get_price($prd[$i]), $prd[$i]['it_tel_inq'])?></p>
		</div><!-- // prdBx -->	
	<?
		}
	?>
	</div><!-- //prd_Wrp -->
	<span class="pagination"></span>
<!--▲ prd_slide ▲-->
</div><!-- //prd_slide -->
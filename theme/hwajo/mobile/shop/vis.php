
<script>
$(function(){
	vsSlide();
});
$(window).resize(function() {
	vsSlide();
});
function vsSlide() {
	var $dw = 295;
	var $dh = 330;
	var $dmt = parseInt($dh / $dw * 100) / 100;
	var $w = parseInt(($(window).width() - 20 - 10) / 2); // 20은 padding:10px;
	var $h = parseInt($w * $dmt);
	$('#vis_slide, .vis_bx, img[class^="vis_space"], .m_vis_right').width($w);
	$('#vis_slide, .vis_wrp, .vis_bx, .m_vis_right').height($h);

	$('#m_vis_wrps').height($h - 5);


	$('#vis_slide .vis_wrp').carouFredSel({
		items: { visible:1,minimum:null,start:0},
		circular: true,
		scroll: {
			fx: "scroll",
			easing: "swing"
	    },
//		scroll:{ pauseOnHover : true},
		auto : { pauseDuration : 4000 },
		padding : null,
		prev : {  button  : "#vis_slide .prev" },
		next : {  button  : "#vis_slide .next" },
		pagination  : "#vis_slide .pagination"
	});
}
</script>
<style type="text/css">
/* vis_slide common */
#m_vis_wrps {height:3000px;}

#vis_slide {position:relative;float:left;padding:0 !important;width:50%;height:574px;}
#vis_slide div.vis_wrp {height:574px;overflow:hidden;}
#vis_slide div.vis_bx {float:left;position:relative;width:960px;height:574px;margin:0;padding:0;}
#vis_slide div.prev {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:-846px;margin-top:214px;}
#vis_slide div.prev img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#vis_slide div.prev img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#vis_slide div.next {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:560px;margin-top:214px;}
#vis_slide div.next img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#vis_slide div.next img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
*#vis_slide span.pagination {position:absolute;margin-top:-40px;left:50%;margin-left:-150px;width:300px;height:40px;line-height:40px;text-align:center;}
#vis_slide span.pagination a {display:inline-block;*display:inline;*zoom:1;width:12px;height:12px;margin:0 5px;background-color:rgba(0,0,0,0.4);border-radius:50%;}
#vis_slide span.pagination a.selected {background-color:#bc8554;}
#vis_slide span.pagination a span {display:none;}
#vis_slide div.hide {top:-10000px!important;}

.m_vis_right {float:right;overflow:hidden;}
.m_vis_wide2 {padding-top:10px;}

</style>
<div><?=pkbnr("pc_wide")?></div>

<p class="blk" style="height:10px;"></p>

<div id="m_vis_wrps">
	<div id="vis_slide">
	<!--▼ vis_slide ▼-->

		<!-- // <div class="prev"><img src="/img/vrv.png" alt="" /></div>
		<div class="next"><img src="/img/vxt.png" alt="" /></div> // -->
		<div class="vis_wrp">
		<?
			$qry = sql_query(" select * from g5_shop_banner where bn_position = 'mb_bn3' order by bn_order asc ");
			while ($vis_slide = sql_fetch_array($qry)) {
		?>
			<div class="vis_bx" <?=($vis_slide['bn_url'] && $vis_slide['bn_url']!="htpp://")?"onclick=\"location.href='{$vis_slide[bn_url]}'\"":""?> style="background:url(/data/banner/<?=$vis_slide['bn_id']?>) no-repeat center;background-size:cover;">
				<img src="/adm/img/space.png" class="vis_space" />
			</div><!-- // vis_bx -->	
		<?
			}
		?>

		</div><!-- //vis_wrp -->
		<span class="pagination"></span>
	<!--▲ vis_slide ▲-->
	</div><!-- //vis_slide -->

	<div class="m_vis_right">
		<?=pkbnr("pc_ev1")?>
	</div><!-- // m_vis_right -->
	<p class="clr"></p>
</div><!-- // m_vis_wrps // -->

<p class="blk" style="height:3px;"></p>
<!-- // <div class="m_vis_wide2"><p class="blk" style="height:10px;"></p><?=pkbnr("pc_join")?></div> // -->
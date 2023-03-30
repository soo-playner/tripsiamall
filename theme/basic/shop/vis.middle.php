
<script>
$(function(){
	$('#mdl_slide .mdl_Wrp').carouFredSel({
		items: { visible:1,minimum:null,start:0},
		circular: true,
		scroll: {
			fx: "scroll",
			easing: "swing"
	    },
//		scroll:{ pauseOnHover : true},
		auto : { pauseDuration : 4000 },
		padding : null,
//		prev : {  button  : "#mdl_slide .prev" },
//		next : {  button  : "#mdl_slide .next" },
		pagination  : "#mdl_slide .pagination"
	});
});
</script>
<style type="text/css">
/* mdl_slide common */
#mdl_slide {position:relative;padding:0 !important;width:1200px;height:295px;margin:0 auto;}
#mdl_slide div.mdl_Wrp {height:295px;overflow:hidden;}
#mdl_slide div.mdlBx {float:left;position:relative;width:1200px;height:295px;margin:0;padding:0;}
#mdl_slide div.prev {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:-846px;margin-top:214px;}
#mdl_slide div.prev img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#mdl_slide div.prev img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#mdl_slide div.next {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:560px;margin-top:214px;}
#mdl_slide div.next img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#mdl_slide div.next img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#mdl_slide span.pagination {position:absolute;left:50%;margin-left:300px;width:300px;height:40px;line-height:40px;text-align:right;}
#mdl_slide span.pagination a {display:inline-block;*display:inline;*zoom:1;width:18px;height:18px;margin:0 5px;background-color:#bfbfbf;border-radius:50%;}
#mdl_slide span.pagination a.selected {background-color:#bc8554;}
#mdl_slide span.pagination a span { display:none; }
#mdl_slide div.hide { top:-10000px!important; }
</style>
<div id="mdl_slide">
<!--▼ mdl_slide ▼-->
	<!-- // <div class="prev"><img src="/img/vrv.png" alt="" /></div>
	<div class="next"><img src="/img/vxt.png" alt="" /></div> // -->
	<div class="mdl_Wrp">
	<?
		$qry = sql_query(" select * from g5_shop_banner where bn_position = 'pc_middle' order by bn_order asc ");
		while ($mdl = sql_fetch_array($qry)) {
	?>
		<div class="mdlBx">
			<?=($mdl['bn_url'] && $mdl['bn_url'] != "http://")?"<a href='".$mdl['bn_url']."'>":""?>
			<img src="/data/banner/<?=$mdl['bn_id']?>" alt="<?=$mdl['bn_alt']?>" />
			<?=($mdl['bn_url'] && $mdl['bn_url'] != "http://")?"</a>":""?>
		</div><!-- // mdlBx -->
	<?
		}
	?>
	</div><!-- //mdl_Wrp -->
	<span class="pagination"></span>
<!--▲ mdl_slide ▲-->
</div><!-- //mdl_slide -->
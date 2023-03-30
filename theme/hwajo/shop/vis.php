
<script>
$(function(){
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
});
</script>
<style type="text/css">
/* vis_slide common */
#vis_slide {position:relative;padding:0 !important;width:960px;height:574px;margin:0 auto;}
#vis_slide div.vis_wrp {height:574px;overflow:hidden;}
#vis_slide div.vis_bx {float:left;position:relative;width:960px;height:574px;margin:0;padding:0;}
#vis_slide div.prev {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:-846px;margin-top:214px;}
#vis_slide div.prev img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#vis_slide div.prev img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
#vis_slide div.next {position:absolute;width:46px;height:136px;z-index:100;left:50%;margin-left:560px;margin-top:214px;}
#vis_slide div.next img {cursor:pointer;opacity:0.3;filter:alpha(opacity=30);}
#vis_slide div.next img:hover {cursor:pointer;opacity:1;filter:alpha(opacity=100);}
/*#vis_slide span.pagination {position:absolute;margin-top:-110px;left:50%;margin-left:-150px;width:300px;height:40px;line-height:40px;text-align:center;}
#vis_slide span.pagination a {display:inline-block;*display:inline;*zoom:1;width:12px;height:12px;margin:0 5px;background-color:rgba(0,0,0,0.4);border-radius:50%;}
#vis_slide span.pagination a.selected {background-color:#bd4f5e;}
#vis_slide span.pagination a span { display:none; }*/
#vis_slide div.hide { top:-10000px!important; }

#vis_slide div.vis_bx .wide {width:955px;height:345px;overflow:hidden;}
#vis_slide div.vis_bx .bnr3 {height:210px;}
#vis_slide div.vis_bx .bnr3 li {float:left;margin-right:12px;box-shadow:1px 1px 1px rgba(0,0,0,0.2);}
#vis_slide div.vis_bx .bnr3 li:nth-child(3n+0) {margin-right:0;}
#vis_slide div.vis_bx .bnr3set {float:left;width:632px;overflow:hidden;}
#vis_slide div.vis_bx .bnr3set .fl {float:left;}
#vis_slide div.vis_bx .bnr3set .fr {float:right;box-shadow:1px 1px 1px rgba(0,0,0,0.2);}
#vis_slide div.vis_bx .bnr3set .center {clear:both;padding-top:10px;box-shadow:1px 1px 1px rgba(0,0,0,0.2);}

#vis_slide div.vis_bx .knt_product {float:right;padding:30px;width:250px;height:504px;background-color:#fff;box-shadow:1px 1px 1px rgba(0,0,0,0.2);}
#vis_slide div.vis_bx .knt_product h4 {text-align:center;font-weight:normal;font-size:24px;font-family:"nngdb";color:#333;}

@import url(https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css);
.vSlogun {position:absolute;margin-left:560px;margin-top:340px;font-family:"NanumSquare";width:380px;height:200px;text-align:right;font-size:30px;line-height:50px;font-weight:700;color:rgba(0,0,0,0.6);}
.vSlogun .llogun {font-size:40px;}
.vSlogun .tlogun {font-size:34px;}

</style>
			<div id="vis_slide">
			<!--▼ vis_slide ▼-->

				<div class="prev"><img src="/img/vrv.png" alt="" /></div>
				<div class="next"><img src="/img/vxt.png" alt="" /></div>
				<div class="vis_wrp">

					<div class="vis_bx">
						<div class="wide"><?=pkbnr("pc_wide")?></div>
						<p class="blk" style="height:10px;"></p>
						<div class="bnr3"><?=pkbnr("pc_bn3",3)?></div>
						<p class="clr"></p>
					</div><!-- // vis_bx -->
					<?
						$qry = sql_query(" select * from g5_shop_banner where bn_position = 'pc_main' order by bn_order asc ");
						while ($mvis = sql_fetch_array($qry)) {
					?>
						<div class="vis_bx" style="background:url(/data/banner/<?=$mvis['bn_id']?>) no-repeat center;background-size:cover;" <?=($mvis['bn_url'] && $mvis['bn_url'] != "http://")?"onclick=\"location.href='".$mvis['bn_url']."'\"":""?>>
							<div class="vSlogun">
								<p class="slogun">신속한 출고! 합리적인 가격!</p>
								<p class="llogun"><?=$config['cf_title']?></p>
								<p class="tlogun"><?=$default['de_admin_company_tel']?></p>							
							</div><!-- // vSlogun -->
						</div><!-- // vis_bx -->
					<?
						}
					?>

				</div><!-- //vis_wrp -->
				<!-- // <span class="pagination"></span> // -->
			<!--▲ vis_slide ▲-->
			</div><!-- //vis_slide -->
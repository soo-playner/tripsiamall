<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$content_skin_url.'/style.css">', 0);
?>
<style type="text/css">
#ctt {margin-bottom:40px;}
</style>
<article id="ctt" class="ctt_<?php echo $co_id; ?>">
<? if ($co_id == "tape") { ?>
<script>
$(function(){
	$('span[id^="tab_"]').click(function () {
		$('span[id^="tab_"]').parent("li").removeClass("on");
		$('div[id^="tabCont_"]').hide();
		$(this).parent().addClass("on");
		var $idx = $(this).attr("id").replace("tab_","tabCont_");
		$('#'+$idx).show();

	});
});
</script>
<style type="text/css">
.tab_design {padding:9px 10px;border-bottom:solid 1px #ddd;}
.tab_design li {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;width:140px;}
.tab_design span {display:block;font-family:"nngdb";height:44px;line-height:44px;text-align:center;font-size:14px;color:#333;background:url(/adm/img/gap_m.png) no-repeat right center;cursor:pointer;}
.tab_design span:hover,
.tab_design li.on span {color:#fff;background:none;background-color:#da3030;}
.tab_design li.on {background:url(/img/tab_on.png) no-repeat center bottom;}
.tab_design li:last-child span {background-image:none;}
</style>
	<div class="tab_design">
		<ul>
			<li class="on"><span id="tab_1">동판인쇄테이프</span></li>
			<li><span id="tab_2">무동판인쇄테이프</span></li>
			<li><span id="tab_3">동판VS테이프</span></li>
		</ul>
		<p class="clr"></p>
	</div><!-- // tab_design // -->
	
	<p class="blk" style="height:50px;"></p>
	
	<div id="tabCont_1" class="tabCont_design" style="">
		<div class="taC">
			<img src="/img/tape_01.png" alt="" />
		</div><!-- // taC -->
	</div><!-- // tabCont_design // -->
	
	<div id="tabCont_2" class="tabCont_design" style="display:none;">
		<div class="taC">
			<img src="/img/tape_02.png" alt="" />
		</div><!-- // taC -->
	</div><!-- // tabCont_design // -->

	<div id="tabCont_3" class="tabCont_design" style="display:none;">
		<div class="taC">
			<img src="/img/tape_03.png" alt="" />
		</div><!-- // taC -->
	</div><!-- // tabCont_design // -->
<? } else { ?>
    <header>
        <h1><?php echo $g5['title']; ?></h1>
    </header>

    <div id="ctt_con">
        <?php echo $str; ?>
    </div>
<? } ?>


</article>
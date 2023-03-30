<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
if($ca_id)
    $sct_sort_href .= 'ca_id='.$ca_id;
else if($ev_id)
    $sct_sort_href .= 'ev_id='.$ev_id;
if($skin)
    $sct_sort_href .= '&amp;skin='.$skin;
$sct_sort_href .= '&amp;sort=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<style type="text/css">
.list_sort_skin {padding:0;height:50px;line-height:50px;border-bottom:solid 1px #ddd;}
.list_sort_skin select {padding:5px;border:solid 1px #ddd;}
</style>
<script>
$(function(){
	$('#mobile_sorter').on("change", function () {
		var url = $(this).val();
		//alert(url);
		location.replace(url);
	});
});
</script>
<div class="list_sort_skin">
	<select id="mobile_sorter">
		<option value="<?php echo $sct_sort_href; ?>it_sum_qty&amp;sortodr=desc">판매많은순</option>
		<option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=asc">낮은가격순</option>
		<option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=desc">높은가격순</option>
		<option value="<?php echo $sct_sort_href; ?>it_use_avg&amp;sortodr=desc">평점높은순</option>
		<option value="<?php echo $sct_sort_href; ?>it_use_cnt&amp;sortodr=desc">후기많은순</option>
		<option value="<?php echo $sct_sort_href; ?>it_update_time&amp;sortodr=desc">최근등록순</option>
	</select>
</div><!-- // list_sort_skin -->

<p class="blk" style="height:20px;"></p>
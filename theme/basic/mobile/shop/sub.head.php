<style type="text/css">
.shop_path {padding:0 20px;height:40px;line-height:40px;font-family:"nngdb";font-size:12px;color:#fff;background-color:#485461;}
.shop_path a {color:#fff;}

.shop_path i {font-size:20px;vertical-align:-3px;color:#fff;margin:0 5px;}
.shop_path select {border:solid 1px #ccc;padding:5px;color:#666;}
.shop_pageTitle {height:60px;line-height:60px;text-align:center;border-bottom:solid 2px #50585e;}
.shop_pageTitle h2 {font-weight:normal;font-size:30px;color:#222;font-family:"nngdb";}
.m_contents {padding:10px;}

</style>
<script>
$(function(){
	$('#last_category').on("change", function () {
		var url = "/shop/list.php?ca_id=" + $(this).val();
		location.replace(url);
	});
});
</script>
<div class="shop_path">

	<a href="<?php echo G5_SHOP_URL; ?>/">홈</a>
<?
if (!$ca_id) {
	$ca_id = $it['ca_id'];
}
if ($ca_id) {
    $navigation = "";
	$bars = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
    $len = strlen($ca_id) / 2;
    for ($i=1; $i<=$len; $i++) {
        $code = substr($ca_id,0,$i*2);

        $sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$code' ";
        $row = sql_fetch($sql);

        $sct_here = '';
		/*if ($ca_id == $code) {// 현재 분류와 일치하면
            $on = 'sct_here';
		}*/

        if ($i != $len) {// 현재 위치의 마지막 단계가 아니라면
	        $navigation .= $bars.' <a href="./list.php?ca_id='.$code.'" class="'.$sct_here.'">'.$row['ca_name'].'</a>';
        } else {
	        $navigation .= $bars.' ';
			/*##  ################################################*/
			$shopPageTitle = $row['ca_name'];
			$ca_len = strlen($ca_id);
			$parent_caid = substr($ca_id,0,-2);
			$qry = sql_query(" select ca_id, ca_name from g5_shop_category where length(ca_id) = '$ca_len' and ca_id like '{$parent_caid}%' and ca_use = '1' ");
			$navigation .= "<select id='last_category'>";
			while ($res = sql_fetch_array($qry)) {
				$on = ($res['ca_id']==$ca_id)?"selected":"";
				$navigation .= "<option value='".$res['ca_id']."' ".$on.">".$res['ca_name']."</option>";
			}
			$navigation .= "</select>";
			/*@@End.  #####*/
		}


		
    }
} else {
    $navigation = $g5['title'];
}

//if ($it_id) $navigation .= " > $it[it_name]";

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>
	<?php echo $navigation; ?>
</div><!-- // shop_path -->
<div class="m_contents">
	<? if ($ca_id && !$it_id) { ?>
	<div class="shop_pageTitle">
		<h2><?=$shopPageTitle?></h2>
	</div><!-- // shop_pageTitle -->
	<? } ?>
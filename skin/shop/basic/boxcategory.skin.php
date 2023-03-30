<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<style type="text/css">
/* aside:gnb */
#shop_category {width:210px;padding:10px 0;background-color:#fff;}
#shop_category h2 {position:absolute;font-size:0;line-height:0;overflow:hidden}
#left_category {}
#left_category > li {height:36px;line-height:36px;}
#left_category > li > a {font-weight:normal;font-family:"nngdb";padding-left:20px;display:block;font-size:14px;color:#404040;}
#left_category > li.on > a,
#left_category > li > a:hover {color:#da3130;}
.casnb {position:absolute;margin-left:210px;top:133px;padding:30px;padding-top:20px;width:290px;border:solid 2px #b71b1c;background-color:#fff;box-shadow:3px 3px 3px rgba(0,0,0,0.3);z-index:9999;display:none;}
.casnb .menu_on {position:absolute;margin-left:-40px;}
.casnb .title {border-bottom:solid 1px #ddd;font-family:"nngdb";font-size:26px;color:#222;padding-bottom:20px;margin-bottom:10px;}
.casnb > ul > li {float:left;width:50%;line-height:30px;}
.casnb > ul > li > a {display:block;font-family:"nngdb";font-size:14px;color:#404040;}
.casnb > ul > li > a:hover {color:#da3130;}
</style>
<?
if (!defined('_INDEX_')) {
?>
<style type="text/css">
#shop_category {margin-left:-20px;box-shadow:1px 3px 3px rgba(0,0,0,0.3);}
.casnb {top:0;margin-top:-52px;}
</style>
<?
}
?>
<script>
$(function(){
	$('a[id^="canb_"]').on("hover",function () {
		var $idx = $(this).attr("id").replace("canb_","casnb_");
		$('div[id^="casnb_"]').each(function () {
			if ($(this).attr("id") == $idx) {
				if ($(this).css("display") == "none") {
					$(this).slideDown(200);
				}
			} else {
				$(this).fadeOut(40);
			}
		});
	});
	$('#shop_category').on("mouseleave",function () {
		$('div[id^="casnb_"]').fadeOut(40);
	});
});
</script>
<nav id="shop_category">
    <h2>쇼핑몰 카테고리</h2>
    <ul id="left_category">
        <?php
        // 1단계 분류 판매 가능한 것만
        $hsql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '2' and ca_use = '1' order by ca_order, ca_id ";
        $hresult = sql_query($hsql);
        $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
        for ($i=0; $row=sql_fetch_array($hresult); $i++)
        {
            $gnb_zindex -= 1; // html 구조에서 앞선 gnb_1dli 에 더 높은 z-index 값 부여
            // 2단계 분류 판매 가능한 것만
            $sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where LENGTH(ca_id) = '4' and SUBSTRING(ca_id,1,2) = '{$row['ca_id']}' and ca_use = '1' order by ca_order, ca_id ";
            $result2 = sql_query($sql2);
            $count = sql_num_rows($result2);
        ?>
        <li>
            <a href="<?php echo G5_SHOP_URL.'/list.php?ca_id='.$row['ca_id']; ?>" id="canb_<?=$i?>"><?php echo $row['ca_name']; ?></a>
            <?php
            for ($j=0; $row2=sql_fetch_array($result2); $j++) {
				if ($j==0) {
					// menu on
					$mt = ($i+1) * 36 +2;
			?>
				<div id="casnb_<?=$i?>" class="casnb">
					<div class="menu_on" style="margin-top:<?=$mt?>px;"><img src="/img/menu_on.png" alt="" /></div>
					<div class="title"><?php echo $row['ca_name']; ?></div>
					<ul>
			<?
				}
            ?>
                <li><a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo $row2['ca_id']; ?>" class="gnb_2da"><?php echo $row2['ca_name']; ?></a></li>
            <?php }
            if ($j>0) {
			?>
					</ul>
					<p class="clr"></p>
				</div><!-- // casnb_ // -->			
			<?
			}
            ?>
        </li>
        <?php } ?>
    </ul>
</nav>
<!-- } 쇼핑몰 카테고리 끝 -->
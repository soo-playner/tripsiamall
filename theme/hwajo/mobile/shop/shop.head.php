<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

?>
<script>
$(function(){
	$('img[class^="ms"]').each(function () {
		var $dmt = parseInt($(window).width() / 640 * 100) / 100;
			alert(parseInt($(this).width() * $dmt));
	});
});
</script>
<style type="text/css">
#m_header {height:56px;line-height:56px;background-color:#0f1e33;}
.m_logo_Bx {text-align:left;padding-left:10px;}
.m_logo_Bx img {width:auto;max-height:48px;}
#m_bar {position:absolute;right:0;width:56px;height:56px;line-height:56px;text-align:center;}
#m_bar span {display:block;cursor:pointer;color:#478a94;font-size:20px;}

#m_tel {position:absolute;right:0;width:56px;height:56px;line-height:56px;text-align:center;display:none;}
#m_tel a {display:block;cursor:pointer;background-color:#404040;}
img.mtel {width:18px;height:auto;}

#hd_ct {}


/*카테고리*/
#category{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0, 0, 0,0.8);z-index:99999;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;-webkit-backface-visibility: hidden;}
#category .ct_wr{width:250px;height:100%;overflow-y:auto;background:#47A8B4;border-right:solid 1px rgba(0,0,0,0.1);}
#category ul.cate_tab{width:100%;}
#category ul.cate_tab:after{display:block;visibility:hidden;clear:both;content:""}
#category ul.cate_tab li{float:left;width:33.3%;font-size:0.92em;}
#category ul.cate_tab li a{display:block;height:30px;line-height:30px;color:#222;background:rgba(0,0,0,0.05);border:1px solid rgba(0,0,0,0.1);border-left:none}
#category ul.cate_tab li a.ct_tab_sl{background:#47A8B4;border-bottom-color:rgba(0,0,0,0.1);color:#545454}
#category ul.cate{background:#47A8B4;;width:100%;text-align:left;text-indent:10px;}
#category ul.cate>li{line-height:35px;border-bottom:1px solid rgba(0,0,0,0.1);}
#category ul.cate li{position:relative;font-weight:bold;}
#category ul.cate li a{color:#545454;display:block;  text-overflow: ellipsis;  overflow: hidden;  white-space: nowrap;padding-right:30px;}
#category ul.cate li .ct_op{display:inline-block;text-indent:-999px;background:url(../mobile/shop/img/cate_op.gif) no-repeat 10px 50% ;height:35px; width:30px;position:absolute;top:0;right:0;border:none}
#category ul.cate li a:hover{color:#000}
#category ul.cate li .ct_cl{background-position:-12px 50%}
#category ul.sub_cate{display:none}
#category ul.sub_cate1 li{text-indent:14px;background:#47848c;border-top:1px solid rgba(0,0,0,0.1);font-weight:normal}
#category ul.sub_cate1 li a {color:#fff;}
#category ul.sub_cate2 li{text-indent:25px;background:#47848c;}
#category ul.sub_cate2 li a {color:#fff;}

#category ul.sub_cate3 li{text-indent:40px;background:#47848c;}
#category ul.sub_cate4 li{text-indent:55px;background:#47848c;}
#category .pop_close{position:absolute;top:10px;left:260px;width:30px;height:30px; background:url(../mobile/shop/img/close.png) no-repeat 50% 50%;;border:none;text-indent:-99999px}

#container img {max-width:100%;height:auto;}

/* ## common */
/* ## common */
ul.ul-3p > li {float:left;width:32%;margin-right:2%;}
ul.ul-3p > li:nth-child(3n+0) {margin-right:0;}

h3.shopTitle {font-weight:bold;font-size:20px;color:#222;}
h3.shopTitle span {font-size:12px;color:#666;font-weight:normal;}
</style>
<?php if(defined('_INDEX_')) { // index에서만 실행
	include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
} ?>
<div id="m_header">
	
	<div id="m_bar">
		<?php include_once(G5_THEME_MSHOP_PATH.'/category.php'); // 분류 ?>

	</div><!-- // m_bar -->

	<div id="m_tel">
		<span><a href="tel:<?=$default['de_admin_company_tel']?>"><img src="/img/m_tel.png" class="mtel" /></a></span>
	</div><!-- // m_tel -->
	<div class="m_logo_Bx">
		<a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img" alt="<?php echo $config['cf_title']; ?> 메인"></a>
	</div><!-- // m_logo_Bx -->


</div><!-- // m_header -->

<div id="container">
<?
if (!defined('_INDEX_')) {
	@include_once(G5_THEME_MSHOP_PATH."/sub.head.php");
}
?>
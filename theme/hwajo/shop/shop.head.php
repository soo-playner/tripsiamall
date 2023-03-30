<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}
// 헤드
include_once(G5_THEME_PATH.'/head.sub.php');

// 라이브러리
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<style type="text/css">
/* .Grp {width:1200px;margin:0 auto;} */
.Grp {width:1200px;margin: 100px auto 10px;}
</style>

<?php if(defined('_INDEX_')) {  // index에서만 실행
	include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
} ?>
<!--xx
<header>
	<div id="<?=(defined('_INDEX_'))?"idx":"sub"?>_header" class="inner after">
		<div class="logo_section flot-left">
			<div class="mobie_menu" id="mobie_menu">
				<span class="line"></span>
				<span class="line"></span>
				<span class="line"></span>
			</div>
			<a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_THEME_URL; ?>/img/main_logo.png" alt="" /></a>
		</div>
		<div class="menu flot-right">
			<ul>
				<li class="menu-kind"><a href="<?php echo G5_URL; ?>/">HOME</a></li>
				
				<? if ($is_admin) { ?>
					<li class="menu-kind"><a href="/adm" target="_blank">ADMIN</a></li>
				<? } ?>
				<? if ($is_member) { ?>
					
					<!--li class="menu-kind"><a href="<?php echo G5_SHOP_URL; ?>/cart.php">CART</a></li--><!--
					<li class="menu-kind"><a href="<?php echo G5_URL; ?>/shop/compensation.php">BONUS PLAN</a></li>
					<!--li class="menu-kind"><a href="<?php echo G5_SHOP_URL; ?>/mypage.php">MY OFFICE</a></li--><!--
					<!--li class="menu-kind"><a href="<?php echo G5_URL; ?>/bbs/member_confirm.php?url=register_form.php">MY OFFICE</a></li--><!--
					<li class="menu-kind"><a href="<?php echo G5_URL; ?>/new/dashboard.php">MY OFFICE</a></li>
					<!--li class="menu-kind"><a href="<?php echo G5_URL; ?>/new/binary_tree.php?gubun=B">MY OFFICE</a></li--><!--
					<li class="menu-kind"><a href="http://pinnaclemining.us-east-2.elasticbeanstalk.com" target="_blank" >OLD PINNACLE</a></li>
					<li class="m-menu"><a href="<?php echo G5_BBS_URL; ?>/logout.php" class="menu_btn login">LOGOUT</a></li>
				<? } else { ?>
					<li class="m-menu"><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="menu_btn login">LOGIN</a></li>
					<li class="m-menu"><a href="<?php echo G5_BBS_URL; ?>/register_form.php" class="menu_btn signup">SIGN UP</a></li>
				<? } ?>
			</ul> 
		</div>
	</div>-->
	 <!--div id="google_translate_element"></div>
	 <script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
		}
	</script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>--><!--
</header>
-->
<!-- 상단 시작 { -->

<!-- // <a href="<?php echo G5_SHOP_URL; ?>/cart.php"><img src="<?php echo G5_SHOP_URL; ?>/img/hd_nb_cart.gif" alt="장바구니"></a>
<a href="<?php echo G5_SHOP_URL; ?>/wishlist.php"><img src="<?php echo G5_SHOP_URL; ?>/img/hd_nb_wish.gif" alt="위시리스트"></a>
<a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php"><img src="<?php echo G5_SHOP_URL; ?>/img/hd_nb_deli.gif" alt="주문/배송조회"></a> // -->


<?//php include(G5_SHOP_SKIN_PATH.'/boxtodayview.skin.php'); // 오늘 본 상품 ?>
<?//php echo outlogin('theme/shop_basic'); // 아웃로그인 ?>
<?//php include_once(G5_SHOP_SKIN_PATH.'/boxcart.skin.php'); // 장바구니 ?>
<?//php include_once(G5_SHOP_SKIN_PATH.'/boxwish.skin.php'); // 위시리스트 ?>
<?//php include_once(G5_SHOP_SKIN_PATH.'/boxevent.skin.php'); // 이벤트 ?>
<?//php include_once(G5_SHOP_SKIN_PATH.'/boxcommunity.skin.php'); // 커뮤니티 ?>
<!--
<?
if (!defined('_INDEX_') && !defined('_SUB_')) {
	@include_once(G5_THEME_PATH."/shop/sub.head.php");
}
?>
-->


<style type="text/css">
/* ## 게시판 타이틀 죽이기 */
h2#container_title {display:none;}
</style>

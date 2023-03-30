<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
    return;
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
<?
if (!defined('_INDEX_') && !defined('_SUB_')) {
	@include_once(G5_THEME_PATH."/shop/sub.tail.php");
}
?>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>

<footer style="display:none;"> <!-- display:none;-->
	<? if (!$is_member) { ?>
	<section class="csection csec09">
		<div class="inner">
			<h2>START YOUR BUSSINESS WITH PINNACLE MINING</h2>
			
			<hr />
			<?if (defined('_INDEX_') ) { ?>
			<!-- <a href="<?php echo G5_BBS_URL; ?>/register_form.php">SIGN UP</a> -->
			<?}?>
		</div>
	</section>
	<? } ?>
	<div class="inner footerIn after">
		<div class="logo_section flot-left">
			<img src="<?php echo G5_THEME_URL; ?>/img/footer_logo.png" alt="" />
		</div>
		<div class="sns_section flot-left">
			<div class="innering">
				<p>FOLLOW</p>
				<div class="sns_icon">
					<a href=""><img src="<?php echo G5_THEME_URL; ?>/img/icon_sns_twitter.png" alt="" /></a>
					<a href=""><img src="<?php echo G5_THEME_URL; ?>/img/icon_sns_facebook.png" alt="" / class="FB"></a>
					<a href=""><img src="<?php echo G5_THEME_URL; ?>/img/icon_sns_insta.png" alt="" /></a>
					<a href=""><img src="<?php echo G5_THEME_URL; ?>/img/icon_sns_youtube.png" alt="" / class="YT"></a>
					<a href="" class="last"><img src="<?php echo G5_THEME_URL; ?>/img/icon_sns_telegram.png" alt="" / ></a>
				</div>
			</div>
		</div>
		<? if (!$is_member) { ?>
		<div class="signup flot-right">
			<p>SIGN UP</p>
			<a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="menu_btn login">LOGIN</a>
			<!-- <a href="<?php echo G5_BBS_URL; ?>/register_form.php" class="menu_btn signup">SIGNUP</a> -->
			
		</div>
		<? } ?>
		<div class="flot-left" style="margin-top:20px;">Pinnacle Mining Corporate address: 89 Boulevard des Entreprises, Boisbriand, QC J7G 2T1, Canada Question about Pinnacle Mining? Send request here</div>
	</div>
	
</footer>

<div class="backtop" id="backtop">
	
	<img src="<?php echo G5_THEME_URL; ?>/img/backtop_img.png" alt="" />
</div>
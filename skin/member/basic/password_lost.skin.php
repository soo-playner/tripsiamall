<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Member information search 시작 { -->
<div id="find_info" class="new_win mbskin">
	<h1 id="win_title">Member information search</h1>

	<form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
	<fieldset >
		<div style="margin:0 0 0 20px">
			<p>
				Enter the email address on your account to request username and new password
			</p>
			<p>
				<label for="mb_email">Email <strong class="sound_only">필수</strong></label>
				<input type="text" name="mb_email" id="mb_email" required class="required frm_input email" size="30">
			</p>
			<script src='https://www.google.com/recaptcha/api.js'></script>
			<?php
				$site_key = '';
				if($_SERVER['HTTP_HOST'] == 'localhost') {
					$site_key = '6LcpVl4UAAAAAKvAzFdw_Kp11m2eUus8BI8OolpS';
				} else if($_SERVER['HTTP_HOST'] == '211.238.13.142'){ // 개발
					$site_key = '6LfhqXoUAAAAAPhPCGzrhOEiuiNScrBQPhNF7e-a';
				} else { // 운영
					$site_key = '6Lf2hV4UAAAAAH04ceK6wZDYJ9iTL6A4HO58lLxz';
				}
			?>
			<div class="g-recaptcha" data-sitekey="<?=$site_key?>"></div>
		</div>
	</fieldset>
	<?php //echo captcha_html();  ?>
	<div class="win_btn">
		<input type="submit" value="Send" class="btn_submit">
		<button type="button" onclick="window.close();">Cancel</button>
	</div>
	</form>
</div>

<script>
function fpasswordlost_submit(f)
{
	<?php echo chk_captcha_js();  ?>

	return true;
}

$(function() {
	var sw = screen.width;
	var sh = screen.height;
	var cw = document.body.clientWidth;
	var ch = document.body.clientHeight;
	var top  = sh / 2 - ch / 2 - 100;
	var left = sw / 2 - cw / 2;
	moveTo(left, top);
});
</script>
<!-- } Member information search 끝 -->